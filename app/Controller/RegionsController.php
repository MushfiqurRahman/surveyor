<?php
App::uses('AppController', 'Controller');
set_time_limit ( 600 );
/**
 * Regions Controller
 *
 * @property Region $Region
 */
class RegionsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	
	public function index() {
            $this->Region->recursive = 0;
            $this->set('regions', $this->paginate());
	}        
        
        /**
         * 
         */
        public function import_data(){
            if( $this->request->is('post') ){
                if( !empty($this->request->data['Region']['xls_file']) ){
                    if( $this->request->data['Region']['xls_file']['error']==0){
                        $renamed_f_name = time().$this->request->data['Region']['xls_file']['name'];
                        if( move_uploaded_file($this->request->data['Region']['xls_file']['tmp_name'], WWW_ROOT.$renamed_f_name) ){
                        	
                            if( $this->_import($renamed_f_name) ){
                            	
                                $this->Session->setFlash(__('Data import successful.'));
                            }else{
                                $this->Session->setFlash(__('Data import failed!'));
                            }
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
            
            //pr($totalRow);
            
            for($i=2; $i<=$totalRow; $i++){                
                $region['Region']['title'] = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
                $regId = $this->_save_region( $region );
                
                $area['Area']['region_id'] = $regId;
                $area['Area']['title'] = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
                $areaId = $this->_save_area( $area );
                
                $house['House']['area_id'] = $areaId;                
                $house['House']['title'] = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
                $houseId = $this->_save_house($house);
                
                $ss['Representative']['house_id'] = $houseId;
                $ss['Representative']['name'] = $objWorksheet->getCellByColumnAndRow(11,$i)->getValue();
                $ss['Representative']['type'] = 'ss';
                $ss['Mobile'] = $this->_get_mobile_nos( $objWorksheet->getCellByColumnAndRow(12,$i)->getValue() );
                $ssId = $this->_save_representative( $ss );
                
                $sr['Representative']['house_id'] = $houseId;
                $sr['Representative']['name'] = $objWorksheet->getCellByColumnAndRow(9,$i)->getValue();
                $sr['Representative']['type'] = 'sr';
                $sr['Representative']['ss_id'] = $ssId;
                $sr['Mobile'] = $this->_get_mobile_nos( $objWorksheet->getCellByColumnAndRow(10,$i)->getValue() );
                $srId = $this->_save_representative( $sr );
                
                $tsa['Representative']['house_id'] = $houseId;
                $tsa['Representative']['name'] = $objWorksheet->getCellByColumnAndRow(13,$i)->getValue();
                $tsa['Representative']['type'] = 'tsa';
                $tsa['Representative']['location'] = $objWorksheet->getCellByColumnAndRow(15,$i)->getValue();
                $tsa['Mobile'] = $this->_get_mobile_nos( $objWorksheet->getCellByColumnAndRow(14,$i)->getValue() );
                $tsaId = $this->_save_representative( $tsa );
                
                $section['Section']['house_id'] = $houseId;
                //$section['Section']['representative_id'] = $srId;
                $section['Section']['sr_id'] = $srId;
                $section['Section']['tsa_id'] = $tsaId;
                $section['Section']['ss_id'] = $ssId;
                $section['Section']['title'] = $objWorksheet->getCellByColumnAndRow(16,$i)->getValue();
                $sectionId = $this->_save_section( $section );
                
                $outlet['Outlet']['section_id'] = $sectionId;
                $outlet['Outlet']['house_id'] = $houseId;
                $outlet['Outlet']['code'] = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
                $outlet['Outlet']['title'] = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
                $outlet['Outlet']['priority'] = $this->request->data['Region']['priority'];
                $outlet['Outlet']['outlet_retailer_name'] = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();                
                $outlet['Outlet']['phone_no'] = $objWorksheet->getCellByColumnAndRow(7,$i)->getValue();                
                $outlet['Outlet']['address'] = $objWorksheet->getCellByColumnAndRow(8,$i)->getValue();
                $outletId = $this->_save_outlet($outlet);
            }
            return true;
        }
        
        /**
         * 
         * Enter description here ...
         * @param unknown_type $mb_nos
         */
        protected function _get_mobile_nos( $mb_nos ){
        	$mobiles = array();
        	$i = 0;
        	$tok = strtok( $mb_nos, " ,\s\t\/");
        	while( $tok !== false ){
        		if( substr($tok, 0, 2) != '88' ){
        			$tok = '88'.$tok;
        		}
        		$mobiles[$i]['mobile_no'] = $tok;        		
        		$tok = strtok(" ,\s\t\/");
        		$i++;
        	}
        	return $mobiles;        	
        } 
        
        
        /**
         * 
         * Enter description here ...
         * @param unknown_type $region
         */
        protected function _save_region( $region ){
            $rgId = $this->Region->field('id',array('title' => $region['Region']['title']));
            if( $rgId ) return $rgId;
            $this->Region->create();
            $this->Region->save($region);
            return $this->Region->id;
        }
        
        /**
         * 
         * Enter description here ...
         * @param unknown_type $area
         */
	protected function _save_area( $area ){
            $arId = $this->Region->Area->field('id',array('Area.region_id' => $area['Area']['region_id'],
                'Area.title' => $area['Area']['title']));
            
            if( $arId ) return $arId;
            
            $this->Region->Area->create();
            $this->Region->Area->save($area);
            return $this->Region->Area->id;        	
        }
        
        /**
         * 
         * Enter description here ...
         */
	protected function _save_house( $house ){
            $hsId = $this->Region->Area->House->field('id',array('House.area_id' => $house['House']['area_id'],
                'House.title' => $house['House']['title']));
            
            if( $hsId ) return $hsId;
            $this->Region->Area->House->create();
            $this->Region->Area->House->save($house);
            return $this->Region->Area->House->id;
        }
        
        /**
         * 
         * Enter description here ...
         */
	protected function _save_section( $section ){            
            $scId = $this->Region->Area->House->Section->field('id',array(
                'Section.title' => $section['Section']['title'],'Section.house_id' => $section['Section']['house_id']));
            
            if( $scId ) return $scId;
			
            $this->Region->Area->House->Section->create();
            $this->Region->Area->House->Section->save($section);
            return $this->Region->Area->House->Section->id;
        }
        
        /**
         * 
         * Enter description here ...
         */
	protected function _save_outlet( $outlet ){
		$otId = $this->Region->Area->House->Outlet->field('id',array(
                    'Outlet.house_id' => $outlet['Outlet']['house_id'],
                    'Outlet.code' => $outlet['Outlet']['code']));
                
                if( $otId ) return $otId;
        	$this->Region->Area->House->Outlet->create();
        	$this->Region->Area->House->Outlet->save($outlet);
        	return $this->Region->Area->House->Outlet->id;
        }
        
        /**
         * 
         * Enter description here ...
         */
	protected function _save_representative( $rep ){ 
            
            $inMob = array();
            foreach( $rep['Mobile'] as $mb ){
                $inMob[] = $mb['mobile_no'];
            }
            
            $rpttvs = $this->Region->Area->House->Representative->Mobile->find('all',array('conditions' =>
                array('Mobile.mobile_no' => $inMob), 'recursive' => -1));
            
            if( count($rpttvs)>0 ){
                return $rpttvs[0]['Mobile']['representative_id'];
            }else{
                $this->Region->Area->House->Representative->create();
                $this->Region->Area->House->Representative->saveAssociated($rep);
                return $this->Region->Area->House->Representative->id;
            }
        }
	


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Region->exists($id)) {
			throw new NotFoundException(__('Invalid region'));
		}
		$options = array('conditions' => array('Region.' . $this->Region->primaryKey => $id));
		$this->set('region', $this->Region->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Region->create();
			if ($this->Region->save($this->request->data)) {
				$this->Session->setFlash(__('The region has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The region could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Region->exists($id)) {
			throw new NotFoundException(__('Invalid region'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Region->save($this->request->data)) {
				$this->Session->setFlash(__('The region has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The region could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Region.' . $this->Region->primaryKey => $id));
			$this->request->data = $this->Region->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Region->id = $id;
		if (!$this->Region->exists()) {
			throw new NotFoundException(__('Invalid region'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Region->delete()) {
			$this->Session->setFlash(__('Region deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Region was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
