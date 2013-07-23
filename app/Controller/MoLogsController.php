<?php
date_default_timezone_set('Asia/Dhaka');
ini_set('max_execution_time', 50000);

App::uses('AppController', 'Controller');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoLogsController
 *
 * @author Shakil
 */
class MoLogsController extends AppController{
    //put your code here    
    
    var $keywords = array('PSTT', 'CUP', 'RP', 'POINT');
    
    public function beforeFilter(){
        $this->Auth->allow(array('add_sale','add_coupon', 'redeem', 'total_point'));
    }
    
    /**
     * 
     */
    public function index(){
        $this->paginate = array('limit' => 100);
        $this->set('mo_logs',$this->paginate());
    }
    
    /**
     *
     * @param type $id 
     */
    public function delete( $id = null ){
        $this->MoLog->id = $id;
        $this->request->onlyAllow('post', 'delete');
        if( $this->MoLog->delete() ){
            $this->loadModel('SaleDetail');
            $this->SaleDetail->deleteAll(array('mo_log_id' => $id));
            $this->loadModel('Coupon');
            $this->Coupon->deleteAll(array('mo_log_id' => $id));
            $this->Session->setFlash(__('Mo Log delete successful!'));
            $this->redirect(array('controller' => 'MoLogs','action' => 'index'));
        }else{
            $this->Session->setFlash(__('Mo Log delete failed!'));
            $this->redirect(array('action' => 'index'));
        }
    }
    
    /**
     *
     * @return array $processed  
     */
    protected function _processing(){
        $mobile_number_temp = $_REQUEST['MSISDN'];
        $sms_text_temp = $_REQUEST['MSG'];
        $sms = $this->MoLog->sms_process($sms_text_temp);
        
        if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){            
            $processed['dates'] = $_REQUEST['DATETIME'];
            $processed['time_int'] = strtotime($processed['dates']);
            $processed['date'] = date("Y-m-d", $processed['time_int']);
        }else{        
            $processed['date'] = date("Y-m-d");
            $processed['dates'] = date("Y-m-d H:i:s");
            $processed['time_int'] = strtotime($processed['dates']);
        }
        
        $processed['mobile_number'] = $this->MoLog->mobile_number_process($mobile_number_temp);

        $sms_slice = explode(' ', $sms);
        $processed['keyword'] = $sms_slice[0];
        
        $processed['lastMoLogId'] = $this->MoLog->save_log($processed['mobile_number'],$sms,$processed['keyword'],$processed['date'],$processed['time_int']);
        
        $processed['params'] = array();
        
        $tok = strtok( $sms, ' ,\t\n');
        $tok = trim($tok);
        for(;1;){
            if(!is_numeric($tok) && $tok==false){
                break;
            }            
            $processed['params'][] = $tok;
            $tok = strtok(' ,\t\n');
            $tok = trim($tok);
        }
        $processed['params'][0] = isset($processed['params'][0]) ? strtoupper($processed['params'][0]) : 'XXX';
        
        return $processed;
    }
    
    /**
     * @desc Save and update Sale and SaleDetail
     * @param type $rep_id
     * @param type $outlet_id
     * @param type $sec_id
     * @param type $sl_counter
     * @param type $processed['date'] 
     * @param int $sale_id if it is present then this method do update otherwise save
     */
    protected function _save_sales($rep_id, $outlet_id, $sec_id=null, $sl_counter=1, $dates, $sale_detail, $sale_id = null){
        
        $data['Sale']['representative_id'] = $rep_id;
        $data['Sale']['outlet_id'] = $outlet_id;
        if( $sec_id ){
            $data['Sale']['section_id'] = $sec_id;
        }
        $data['Sale']['date'] = $dates;
        $data['SaleDetail'] = $sale_detail;
        
        if( $sale_id ){   
            $data['Sale']['id'] = $sale_id;   
            $this->Sale->SaleDetail->deleteAll(array('SaleDetail.sale_id'=>$sale_id,
                'SaleDetail.sale_counter' => $sl_counter));
            if( $this->Sale->saveAll($data) ){
                return true;
            }
        }else{
            if( $this->Sale->saveAssociated($data) ){
                return true;
            }        
        }
        return false;
    }
    
    /**
     * Check the sale message format, product codes validity and also format and array for sale detail
     */
    protected function _format_sale_detail( $params, $sale_id = null, $sale_counter = 1, $moLogId, $saleDetails = array() ){
        $productList = $this->Sale->SaleDetail->Product->find('list', array('fields' => array('id','code')));
                
        $total = count($params);
        $data = array();
        
        for( $j=0, $i=2; $i<$total-1; $i++ ){
            if( !is_numeric($params[$i]) && is_numeric($params[$i+1]) ){                
                
                $flag_found = false;

                foreach( $productList as $k => $v ){
                    if( $v == $params[$i] || strtoupper($params[$i]) == $v){
                        
                        //checking already inserted this product in previous requests
                        if( count($saleDetails)>0 ){
                            foreach($saleDetails as $sd ){
                                if( $sd['sale_details']['product_id']==$k && $sd['sale_details']['sale_counter']!= $sale_counter ){                                
                                    $data['error'] = 'Sorry! You have already sent STT for '.$v.'. Please send your request again.';
                                    return $data;
                                }
                            }
                        }
                        
                        //checking same products code present more than one or not
                        for( $l=$i+1; $l < $total-1; $l++ ){
                            if( $params[$i]==$params[$l] ){
                                $data['error'] = 'Invalid message format. You have entered same product code for twice.';
                                return $data;
                            }
                        }
                        
                        if( $sale_id ){
                            $data['SaleDetail'][$j]['sale_id'] = $sale_id;
                        }
                        $data['SaleDetail'][$j]['mo_log_id'] = $moLogId;
                        $data['SaleDetail'][$j]['sale_counter'] = $sale_counter;
                        $data['SaleDetail'][$j]['product_id'] = $k;
                        $data['SaleDetail'][$j++]['quantity'] = $params[$i+1];           
                        
                        $i++;
                        $flag_found = true;
                        break;
                    }
                }
                if( !$flag_found ){
                    $data['error'] = 'Invalid product code';
                    return $data;
                }
            }else{
                $data['error'] = 'Invalid sms format or product code! Please send sms with valid format.';
            }
        }
        return $data;
    }
    
        
    /**
     * This processes add and update sales request
     */
    public function add_sale(){
        
        $this->layout = $this->autoRender = false;
        $processed = $this->_processing();
        
        $errorFound = true;

        //------------------------------
        $ttl_msg_part = count($processed['params']);

        if( $processed['params'][0]!='PSTT' || !is_numeric($processed['params'][$ttl_msg_part-1]) || 
                $ttl_msg_part % 2 == 0 || $ttl_msg_part < 5) {
            
            $error = "Your SMS format is wrong, plesae try again with right format.";            
        }else{            
            $repType = $this->MoLog->find_rep_type( $processed['mobile_number'] );
        
            if( !$repType || $repType == 'tsa' ){
                $error = 'Invalid Mobile number! Please try again with valid mobile no.';                
            }else{                
                $outletId = $this->MoLog->check_sr_tlp_mobile( $processed['params'][1], $processed['mobile_number'], $repType);

                if( !is_array($outletId) ){
                    $error = 'Invalid Mobile no or Outlet code! Please try again with valid code.';                    
                }else{
                    $this->loadModel('Sale');

                    $res = $this->MoLog->query('SELECT sales.id, sale_details.sale_counter, sale_details.product_id '.
                            'FROM sales LEFT JOIN '.
                            'sale_details ON sales.id=sale_details.sale_id WHERE DATE(date)="'.$processed['date'].
                            '" AND representative_id='.$outletId[0]['representatives']['id'].
                            ' AND outlet_id='.$outletId[0]['outlets']['id']);

                    //pr($res);exit;
                    if(count($res)>0) { 
                        $sale_detail = $this->_format_sale_detail($processed['params'], $res[0]['sales']['id'], $processed['params'][ $ttl_msg_part - 1 ], $processed['lastMoLogId'], $res);

                        if( isset($sale_detail['error']) ){   
                            $error = $sale_detail['error'];
                        }else{
                            $this->_save_sales($outletId[0]['representatives']['id'], $outletId[0]['outlets']['id'],
                                    $outletId[0]['sections']['id'], $processed['params'][$ttl_msg_part-1], $processed['dates'], 
                                    $sale_detail['SaleDetail'], $res[0]['sales']['id']);   

                            $is_update = false;
                            foreach( $res as $rs ){
                                if( $rs['sale_details']['sale_counter'] == $processed['params'][ $ttl_msg_part -1 ] ){
                                    $is_update = true;
                                    break;
                                }
                            }
                            $errorFound = false;
                            $msg = $is_update ? 'Thank you! STT Report for '.$outletId[0]['outlets']['title'].' have been updated.' : 'Thank you! STT Report for '.$outletId[0]['outlets']['title'].' have been received.';
                            
                        }
                    }
                    else {
                        $sale_detail = $this->_format_sale_detail($processed['params'], null, $processed['params'][ $ttl_msg_part - 1 ], $processed['lastMoLogId']);
                        if( isset($sale_detail['error']) ){                    
                            $error = $sale_detail['error'];
                        }else{                            
                            $this->_save_sales($outletId[0]['representatives']['id'], $outletId[0]['outlets']['id'],
                                    $outletId[0]['sections']['id'], $processed['params'][$ttl_msg_part-1], $processed['dates'], $sale_detail['SaleDetail']);//                    
                            
                            $errorFound = false;
                            $msg = 'Thank you! STT Report for '.$outletId[0]['outlets']['title'].' have been received.';
                            
                        }
                    }
                }                
            }
        }
        if( $errorFound ){
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $error.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], 0, $error, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }            
            die();
        }else{
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $msg.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], $outletId[0]['outlets']['id'], $msg, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }            
            die();
        }
    }
    
    /**
     * 
     */
    public function add_coupon(){
        $this->layout = $this->autoRender = false;
        
        $processed = $this->_processing();
        $errorFound = true;
        
        //--------------------------------------
        if( ($processed['params'][0]=='CUP' && count($processed['params'])!=7) || $processed['params'][0]!='CUP'
          || !$this->MoLog->numeric_check($processed['params']) ) {
            $error = "Your SMS format is wrong, plesae try again with right format.";            
        }
        else if( $processed['params'][2] != ($processed['params'][3]+$processed['params'][4]+$processed['params'][5]) ){
            $error = 'Invalid value! Total point is not equal to the sum of activity points';            
        }else{        
            $repType = $this->MoLog->find_rep_type( $processed['mobile_number'] );

            if( !$repType || $repType != 'tsa' ){
                $error = 'Invalid Mobile number! Please try again with valid mobile no.';                
            }else{
                $outletId = $this->MoLog->check_sr_tlp_mobile( $processed['params'][1], $processed['mobile_number'], 'tsa');

                if( !is_array($outletId) ){
                    $error = 'Invalid user or TLP code! Please try again with valid info.';                    
                }else{            
                    $res = $this->MoLog->query('SELECT id FROM coupons WHERE DATE(date)="'.$processed['date'].'" AND representative_id='.
                            $outletId[0]['representatives']['id'].' AND outlet_id='.$outletId[0]['outlets']['id'].
                            ' AND is_redeem=0 AND coupon_counter='.$processed['params'][6]);
        //                pr($res);

                    if( count($res)>0 ) {
                        $update_qry = 'UPDATE coupons SET total_score='.$processed['params'][2].', first_act_score='.
                                $processed['params'][3].', second_act_score='.$processed['params'][4].', third_act_score='.$processed['params'][5].
                                ' WHERE coupons.id='.$res[0]['coupons']['id'];

                        $this->MoLog->query( $update_qry );        

                        $tp = $this->MoLog->get_total_coupon_point($outletId[0]['outlets']['id']);

                        $msg = 'Thank you! '.$processed['params'][2].' coupon points updated for '.
                                $outletId[0]['outlets']['title'].'. Current point is '.$tp.'.';
                        $errorFound = false;
                        
                    } else {
                        $outletId[0]['sections']['id']  = empty($outletId[0]['sections']['id']) ? 0 : $outletId[0]['sections']['id'];
                        $insert_qry = 'INSERT INTO coupons(representative_id, mo_log_id, outlet_id, section_id, coupon_counter,'.
                                'total_score, first_act_score, second_act_score, third_act_score, date) '.
                                'values('.$outletId[0]['representatives']['id'].','.$processed['lastMoLogId'].','.
                                $outletId[0]['outlets']['id'].','.
                                $outletId[0]['sections']['id'].','.$processed['params'][6].','.$processed['params'][2].','.$processed['params'][3].','.$processed['params'][4].
                                ','.$processed['params'][5].',"'.$processed['dates'].'")';                    

                        $this->MoLog->query($insert_qry);
                        $tp = $this->MoLog->get_total_coupon_point($outletId[0]['outlets']['id']);
                        
                        $errorFound = false;
                        $msg = 'Thank you! '.$processed['params'][2].' coupon points added for '.
                                $outletId[0]['outlets']['title'].'. Current point: '.$tp.'.';
                    }
                }
            }
        }
        if( $errorFound ){
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $error.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], 0, $error, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }
            die();
        }else{
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $msg.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], $outletId[0]['outlets']['id'], $msg, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }
            die();
        }
    }
    
    
    /**
     * 
     */
    public function total_point(){
        $this->layout = $this->autoRender = false;
        
        $processed = $this->_processing();
        $errorFound = true;
        
        //-------------------------------------------
        if( ($processed['params'][0]=='POINT' && count($processed['params'])!=2) || 
                $processed['params'][0]!='POINT' ){
            $error = "Your SMS format is wrong, plesae try again with right format.";            
        }else{
            $outletId = $this->MoLog->get_outlet_id( $processed['params'][1], $processed['mobile_number']);
        
            if( !$outletId ){
                $error = 'Invalid Outlet code or mobile no! Please try again with valid info.';                
            }else{
                $tp = $this->MoLog->get_total_coupon_point($outletId);
                $msg = 'Till now your total point is:'.$tp;
                $errorFound = false;
            }
        }
        if( $errorFound ){
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $error.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], 0, $error, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }
            die();
        }else{
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $msg.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], $outletId, $msg, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }
            die();
        }
    }
    
    /**
     * Redeem coupon point
     */
    public function redeem(){
        $this->layout = $this->autoRender = false;
        $processed = $this->_processing();
        $errorFound = true;
        
        //---------------------------

        if( ($processed['params'][0]=='RP' && count($processed['params'])!=4) || $processed['params'][0]!='RP'
           || !$this->MoLog->numeric_check($processed['params']) ){
            $error = "Your SMS format is wrong, plesae try again with right format.";            
        }else{        
            $outletId = $this->MoLog->check_sr_tlp_mobile( $processed['params'][1], $processed['mobile_number'], 'tsa');

            if( !is_array($outletId) ){
                $error = 'Invalid user or TLP code! Please try again with valid info.';                
            }else{            
                $res = $this->MoLog->query('SELECT id, total_score FROM coupons WHERE DATE(date)="'.$processed['date'].'" AND representative_id='.
                        $outletId[0]['representatives']['id'].' AND outlet_id='.$outletId[0]['outlets']['id'].
                        ' AND is_redeem=1 AND coupon_counter='.$processed['params'][3]);
                    //pr($res);

                $totalQp = $this->MoLog->get_total_coupon_point($outletId[0]['outlets']['id']);    

                if( count($res)>0 ){
                    $totalQp += (-$res[0]['coupons']['total_score']);
                }

                //echo $totalQp;

                if( $processed['params'][2] > $totalQp ){
                    $msg = 'Invalid request! Sorry, your redeem point is greater than your total point. You total point is: '.
                            $totalQp;
                    $errorFound = false;
                }else{
                    $processed['params'][2] = -($processed['params'][2]);

                    if( count($res)>0 ) { 

                        $update_qry = 'UPDATE coupons SET total_score='.$processed['params'][2].', first_act_score = 0,'.
                                ' second_act_score=0, third_act_score = 0 WHERE coupons.id='.$res[0]['coupons']['id'];

                        $this->MoLog->query( $update_qry );  

                        $totalQp += $processed['params'][2];

                        $msg = 'Thank you! Redeem points updated for '.
                                $outletId[0]['outlets']['title'].'. Current'.
                                ' point: '.$totalQp.'.';
                        $errorFound = false;
                    }
                    else {
                        $outletId[0]['sections']['id']  = empty($outletId[0]['sections']['id']) ? 0 : $outletId[0]['sections']['id'];
                        $insert_qry = 'INSERT INTO coupons(representative_id, outlet_id, section_id, coupon_counter,'.
                                'is_redeem, total_score, first_act_score, second_act_score, third_act_score, date) '.
                                'values('.$outletId[0]['representatives']['id'].','.$outletId[0]['outlets']['id'].','.
                                $outletId[0]['sections']['id'].','.$processed['params'][3].', 1,'.$processed['params'][2].',0,0,0,'.
                                '"'.$processed['dates'].'")';                    

                        $this->MoLog->query($insert_qry);
                        $totalQp += $processed['params'][2];

                        $msg = 'Thank you!'.(-$processed['params'][2]).' points redeemed from '.
                                $outletId[0]['outlets']['title'].'. Current'.
                                ' point: '.$totalQp.'.';
                        $errorFound = false;
                    }
                }
            }
        }
        if( $errorFound ){
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $error.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], 0, $error, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }            
            die();
        }else{
            if( isset($_REQUEST['DATETIME']) && !empty($_REQUEST['DATETIME']) ){
                echo $msg.'<br />';
            }else{
                $this->MoLog->send_sms_free_of_charge($processed['mobile_number'], $outletId[0]['outlets']['id'], $msg, 796, $processed['keyword'], $processed['date'], $processed['time_int']);
            }
            exit;
        }
    } 
    
    /**
     * @desc Suppose server was off for a day. In that case through this method all the sms stored in a xls
     * file can be restored into database in proper way. 
     */
    public function import_backup(){
        if( $this->request->is('post') ){
            if( !empty($this->request->data['MoLog']['backup_xls']) ){
                if( $this->request->data['MoLog']['backup_xls']['error']==0){
                    $renamed_f_name = time().$this->request->data['MoLog']['backup_xls']['name'];
                    if( move_uploaded_file($this->request->data['MoLog']['backup_xls']['tmp_name'], WWW_ROOT.$renamed_f_name) ){

                        $this->_import($renamed_f_name);

                    }else{
                        $this->Session->setFlash(__('File upload failed! Please try again.'));
                    }
                }else{
                    $this->Session->setFlash(__('Your given file is corrupted! Please try with valid file.'));
                }
            }else{
                $this->Session->setFlash(__('You have not selected any file to upload.'));
            }
        }
    }
    
    /**
     *
     * @param type $fName 
     */
    protected function _import( $xlName ){
        
        App::import('Vendor','PHPExcel',array('file' => 'PHPExcel/Classes/PHPExcel.php'));

        //here i used microsoft excel 2007
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        //set to read only
        $objReader->setReadDataOnly(true);
        //load excel file
        $objPHPExcel = $objReader->load($xlName);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

        $totalRow = $objPHPExcel->getActiveSheet()->getHighestRow();

        //pr($totalRow);exit;
        $ch = curl_init();

        for($i=2; $i<=$totalRow; $i++){ 
            $data['MSISDN'] = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
            $data['MSG'] = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
            $data['DATETIME'] = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
            
            $sms_slice = explode(' ', $data['MSG']);
            
            if( $sms_slice[0]=='PSTT' ){
                $url = Configure::read('base_url').'sms_pstt.php';
            }else if( $sms_slice[0]=='CUP' ){
                $url = Configure::read('base_url').'sms_cup.php';
            }else if( $sms_slice[0]=='RP' ){
                $url = Configure::read('base_url').'sms_rp.php';
            }            
            curl_setopt($ch, CURLOPT_URL,$url);
            //curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            $response = curl_exec($ch);
        }
        curl_close($ch);
    }
}