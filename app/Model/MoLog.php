<?php
App::uses('AppModel', 'Model');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoLog
 *
 * @author Shakil
 */
class MoLog extends AppModel{
    //put your code here
  public function sms_process($sms_temp) {
	
	while(substr_count($sms_temp, '  ')) {
		$sms_temp = str_replace('  ',' ',trim($sms_temp));
	}
	return strtoupper($sms_temp);
}  

    /**
     * Find out representatives type. Either sr/ss/tsa
     * @param type $mob_no
     * @return boolean 
     */
    public function find_rep_type( $mob_no ){
        $res = $this->query('SELECT mobiles.representative_id, representatives.type FROM mobiles INNER JOIN
            representatives ON mobiles.representative_id = representatives.id WHERE mobiles.mobile_no="'.$mob_no.'"');
        
        if( count($res)>0 ){
            return $res[0]['representatives']['type'];
        }
        return false;
    }

     public function check_sr_tlp_mobile($tlp, $mobile, $sr_type = null){
         
         /**** Representative may have multiple mobiles ****/
        
//        $res = $this->query('SELECT mobiles.mobile_no, representatives.id, representatives.type, outlets.id,'.
//                'outlets.title, outlets.code, outlets.section_id, sections.id FROM mobiles INNER JOIN '.
//                'representatives ON mobiles.representative_id = '.
//                'representatives.id INNER JOIN outlets ON representatives.house_id = outlets.house_id'.
//                ' LEFT JOIN sections ON representatives.id=sections.representative_id WHERE mobiles.mobile_no ="'.
//                $mobile.'" AND outlets.code="'.$tlp.'"');
         
         $qry = 'SELECT mobiles.mobile_no, mobiles.representative_id, representatives.id,'.
                ' sections.id, sections.'.$sr_type.'_id, outlets.id, outlets.section_id, '.
                'outlets.code, outlets.title FROM mobiles INNER JOIN '.
                'representatives ON mobiles.representative_id = representatives.id INNER JOIN '.
                'sections ON representatives.id=';
         
         if( $sr_type=='sr' ){
             $qry .= 'sections.sr_id';
         }else if( $sr_type == 'ss' ){
             $qry .= 'sections.ss_id';
         }else if( $sr_type=='tsa' ){
             $qry .= 'sections.tsa_id';
         }
         
         $qry .= ' INNER JOIN outlets ON sections.id = outlets.section_id WHERE mobiles.mobile_no ="'.$mobile.
                '" AND representatives.type="'.$sr_type.'" AND outlets.code="'.$tlp.'"';
         
         $res = $this->query($qry);
        
        //pr($res);exit;
        
        if( count($res)>0 && $res[0]['outlets']['code']==$tlp ){
            return $res;
        }
        return false;
    }
    
    /**
     *
     * @param type $tlp
     * @param type $mobile
     * @return boolean 
     */
    public function check_tlp_mobile( $tlp, $mobile ){
        $res = $this->query('SELECT * FROM representatives LEFT JOIN outlets ON '.
                'representatives.house_id = outlets.house_id WHERE representatives.mobile_no="'.
                $mobile.'" AND outlets.code="'.$tlp.'"');
        
//        pr($res);
        
        if( count($res)>0 && ( isset($res[0]['outlets']['code']) && $res[0]['outlets']['code'] == $tlp ) ){
            return true;
        }
        return false;
    }
    
    /**
     *
     * @param type $outletCode
     * @param type $mobile
     * @return boolean 
     */
    public function get_outlet_id( $outletCode, $mobile ){
        $res = $this->query('SELECT * FROM outlets WHERE outlets.code="'.$outletCode.'" AND outlets.phone_no="'.
                $mobile.'"');
        
        if( count($res)>0 ){
            return $res[0]['outlets']['id'];
        }
        return false;
    }
    
    /**
     *
     * @param type $mobile_number
     * @param type $tlp_code
     * @return boolean 
     */
    public function srTlpId( $mobile_number, $tlp_code ){
        $res = $this->query('SELECT * FROM representatives LEFT JOIN outlets ON representatives.house_id = outlets.house_id 
            WHERE representatives.mobile_no="'.$mobile_number.'" AND outlets.code="'.$tlp_code.'"');
        
        if( count($res)>0 && isset($res[0]['outlets'])){
            $ids['representative_id'] = $res[0]['representatives']['id'];
            $ids['outlet_id'] = $res[0]['outlets']['id'];
            $ids['section_id'] = $res[0]['outlets']['section_id'];
            return $ids;
        }
        return false;
    }
    
    /**
     *
     * @param type $params
     * @return boolean 
     */
    public function numeric_check( $params ){
        if( $params[0] == 'RP' ){
            $total = 4;
        }else if( $params[0] == 'CUP' ){
            $total = 7;
        }else{
            return false;
        }   
        for($i=3;$i<$total;$i++){
            if( $params[$i]===0 ) continue;
            if( !is_numeric($params[$i]) ){
                return false;
            }
        }
        return true;
    }

public function mobile_number_process($mobile_num_temp) {
		
	$mobile_num=str_replace('-','',$mobile_num_temp);
	$mobile_num=trim($mobile_num);
	
	if(strlen($mobile_num) < 13)
		$mobile_num = "88".$mobile_num;
	
	return $mobile_num;
}


    /**
     *
     * @param type $mobile_number
     * @param type $sms
     * @param type $keyword
     * @param type $date
     * @param type $time_int
     * @return type 
     */
    public function save_log( $mobile_number, $sms, $keyword, $date, $time_int){
        $moLog['MoLog']['msisdn'] = $mobile_number;
        $moLog['MoLog']['sms'] = $sms;
        $moLog['MoLog']['keyword'] = $keyword;
        $moLog['MoLog']['datetime'] = $date;
        $moLog['MoLog']['time_int'] = $time_int;        
        $this->save($moLog);
        return $this->id;
    }
    
    
    /**
     *
     * @param type $outletId
     * @return type 
     */
    public function get_total_coupon_point( $outletId ){
        $total_point = $this->query('SELECT SUM(total_score) AS total_point FROM coupons '.
                        'WHERE coupons.outlet_id='.$outletId);

        $tp = isset($total_point[0][0]['total_point']) ? $total_point[0][0]['total_point'] : 0;
        return $tp;
    }
	
public function send_sms_free_of_charge($to, $outlet_id = 0, $msg,$recid,$keyword, $date = '', $time_int = 0){
    
		$this->query("INSERT INTO mt_logs(msisdn, outlet_id, sms,keyword,datetime,time_int) VALUES('$to',".
                        $outlet_id.",'$msg','$keyword','$date',$time_int)");
		
		$date=date('Y-m-d h:i A');
		$ftp = fopen("log.txt",'a+');
        	fwrite($ftp,$to." ".$msg."	".$date."\n");
        	fclose($ftp);
		
		echo $msg; 
}

public function get_telcoID($mobile_num_temp){
		
			$operator = substr($mobile_num_temp,0,5);
			
			if($operator == '88017'){
					$telcoID = '1';
			}else if($operator == '88018'){
					$telcoID = '4';
			}else if($operator == '88015'){
					$telcoID = '5';
			}else if($operator == '88019'){
					$telcoID = '3';
			}else if($operator == '88011'){
					$telcoID = '2';				
			}else if($operator == '88016'){
					$telcoID = '6';				
			}else{ $telcoID = '7'; }
			
			return $telcoID;
	}
}