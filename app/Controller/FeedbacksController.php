<?php
App::uses('AppController', 'Controller');
set_time_limit ( 600 );
/**
 * Feedbacks Controller
 *
 * @property Feedback $Feedback
 */
class FeedbacksController extends AppController {
    
    private $houseIds = array();
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $houseList = $this->Feedback->Survey->House->house_list($this->request->data);//('list', array('conditions' => $this->_set_conditions()));

        if( isset($this->request->data['House']['id']) && !empty($this->request->data['House']['id']) ){
            $this->houseIds[] = $this->request->data['House']['id'];
        }else{
            $this->houseIds = $this->Feedback->Survey->House->id_from_list($houseList);                
        }
        if( !empty($this->current_campaign_detail) ){            
            $this->set('achievements',$this->Feedback->Survey->Campaign->achievements_by_house(
                $this->houseIds, $this->current_campaign_detail['Campaign']['id'],
                $this->total_camp_days, $this->day_passed));
        }
    }
    
    public function report(){
        
    }
    
    public function caller_panel(){
        //pr($this->request->data);exit;
        
        if( !empty($this->request->data['Feedback']) && isset($this->request->data['Feedback']['save']) ){               
            $this->request->data['Feedback']['user_id'] = $this->Auth->user('id');
            unset($this->request->data['Feedback']['save']);
            
            if( $this->Feedback->save($this->data['Feedback']) ){
                $this->Feedback->Survey->id = $this->data['Feedback']['survey_id'];
                $this->Feedback->Survey->saveField('feedback_taken', 1);
                $this->Session->setFlash('Feedback saved successfully');
            }
        }   
        
        
        
            /**
             * 1. Check already target fulfilled if yes then request him to select another house 
             * 2. If not then 
             */
        $conditions = array(
            'Survey.feedback_taken' => 0, 
            'Survey.campaign_id' => $this->current_campaign_detail['Campaign']['id'],
            'Survey.house_id' => $this->houseIds,
            'Survey.feedback_skipped' => 0,
            'DATE(Survey.created)' => $this->request->data['Survey']['created'],
        );
        if( isset($this->request->data['Feedback']['skip']) ){
            $this->Feedback->Survey->id = $this->data['Feedback']['survey_id'];
            $this->Feedback->Survey->saveField('feedback_skipped',1);
            $conditions['Survey.id !='] = $this->request->data['Feedback']['survey_id'];
        }
        
        
        //$this->Feedback->Survey->Behaviors->load('Containable');     
        
        $this->set('survey', $this->Feedback->Survey->find('first',array(            
            'conditions' => $conditions, 'recursive' => 0)));
    }
    
    /**
     * Import House Feedback Target
     */
    public function import_feedback_target(){
        if( $this->request->is('post') ){
            if( !empty($this->request->data['Feedback']['xls_file']) ){
                if( $this->request->data['Feedback']['xls_file']['error']==0){
                    $renamed_f_name = time().$this->request->data['Feedback']['xls_file']['name'];
                    if( move_uploaded_file($this->request->data['Feedback']['xls_file']['tmp_name'], WWW_ROOT.$renamed_f_name) ){

                        $failedRows = $this->_import($renamed_f_name);
                        
                        if( empty($failedRows) ){
                            $this->Session->setFlash(__('Data import successful.'));
                        }else{
                            $this->Session->setFlash(__('Following rows import failed! Maybe region/area/house data is invalid.'));
                            pr($failedRows);
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
    * For Import House feedback target
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
        
        $this->loadModel('Region');
        //$this->loadModel('CampaignDetail');

        //pr($totalRow);
        
        $failedRows = array();

        for($i=3; $i<=$totalRow; $i++){                
            $successful_update = false;
            
            $region = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
            $regId = $this->Region->get_id_by_field('title', trim($region));
            
            if( $regId ){
                $area = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
                $area = trim($area);
                $areaD = $this->Region->Area->find('first',array('fields' => array('id'),
                    'conditions' => array('Area.region_id' => $regId, 'Area.title' => $area),
                    'recursive' => -1));
                
                if( !empty($areaD) ){
                    $areaId = $areaD['Area']['id'];
                    $house = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
                    $house = trim($house);
                    $houseD = $this->Region->Area->House->find('first',array('fields' => array(
                            'House.id'),'conditions' => array('House.area_id' => $areaId),
                            'recursive' => -1));
                    
                    if( !empty($houseD) ){
                        $houseId = $houseD['House']['id'];
                        $target = round($objWorksheet->getCellByColumnAndRow(8,$i)->getValue());
                        //$this->CampaignDetail->
                        $this->Feedback->query('UPDATE campaign_details SET campaign_details.house_feedback_target='.
                                $target.' WHERE campaign_id='.$this->current_campaign_detail['Campaign']['id'].
                                ' AND house_id='.$houseId);
                        $successful_update = true;
                    }
                }
            }            
            if( !$successful_update ){
                $failedRows[] = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue().'--> '.
                        $objWorksheet->getCellByColumnAndRow(1,$i)->getValue().'--> '.
                        $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
            }
        }
        return $failedRows;
    }
    
    
    /**
     * Import already taken feedback
     */
    public function import_feedback(){
        if( $this->request->is('post') ){
            if( !empty($this->request->data['Feedback']['xls_file']) ){
                if( $this->request->data['Feedback']['xls_file']['error']==0){
                    $renamed_f_name = time().$this->request->data['Feedback']['xls_file']['name'];
                    if( move_uploaded_file($this->request->data['Feedback']['xls_file']['tmp_name'], WWW_ROOT.$renamed_f_name) ){

                        $failedRows = $this->_import_feedback($renamed_f_name);
                        
                        if( empty($failedRows) ){
                            $this->Session->setFlash(__('Data import successful.'));
                        }else{
                            $this->Session->setFlash(__('Following rows import failed! Maybe region/area/house data is invalid.'));
                            pr($failedRows);
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
    protected function _import_feedback( $xlName ){
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
        
        $failedRows = array();

        for($i=19; $i<=$totalRow; $i++){                
            $successful_insert = false;
            
            $ccEmail = $objWorksheet->getCellByColumnAndRow(15,$i)->getValue();
            $ccEmail = trim($ccEmail);
            
            $mobile = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
            $mobile = trim($mobile);
            
            //adding leading 0
            $sub = substr($mobile, 0, 1);
            if( $sub!=0 ){
                $mobile = '0'.$mobile;
            }
            $surveyDetail = $this->Feedback->Survey->find('first',array('fields' => array('id'),
                'conditions' => array('campaign_id' => $this->current_campaign_detail['Campaign']['id'],
                    'phone' => $mobile),'recursive' => -1));
            
            $ccDetail = $this->Feedback->User->find('first',array('fields' => array('id'),
                'conditions' => array('User.email' => $ccEmail, 'User.is_cc' => 1),
                'recursive' => -1));
            
            if( !empty($surveyDetail) && !empty($ccDetail) ){
                $feedback['Feedback']['survey_id'] = $surveyDetail['Survey']['id'];
                $feedback['Feedback']['user_id'] = $ccDetail['User']['id'];
                $feedback['Feedback']['is_right_name'] = trim($objWorksheet->getCellByColumnAndRow(5,$i)->getValue());
                $feedback['Feedback']['is_right_age'] = trim($objWorksheet->getCellByColumnAndRow(7,$i)->getValue());
                $feedback['Feedback']['is_right_occupation'] = trim($objWorksheet->getCellByColumnAndRow(9,$i)->getValue());
                $feedback['Feedback']['current_brand'] = trim($objWorksheet->getCellByColumnAndRow(10,$i)->getValue());
                $feedback['Feedback']['new_pack'] = trim($objWorksheet->getCellByColumnAndRow(11,$i)->getValue());
                $feedback['Feedback']['tobacco_quality'] = trim($objWorksheet->getCellByColumnAndRow(12,$i)->getValue());
                $feedback['Feedback']['br_toolkit'] = trim($objWorksheet->getCellByColumnAndRow(13,$i)->getValue());
                $feedback['Feedback']['got_ptr'] = trim($objWorksheet->getCellByColumnAndRow(14,$i)->getValue());
                
                $time = trim($objWorksheet->getCellByColumnAndRow(15,$i)->getValue());
                $time = date('Y-m-d H:i:s',strtotime($time));
                $feedback['Feedback']['created'] = $time;
                
                $this->Feedback->create();
                if( $this->Feedback->save($feedback) ){
                    $this->Feedback->Survey->id = $surveyDetail['Survey']['id'];
                    $this->Feedback->Survey->saveField('feedback_taken',1);
                    $successful_insert = true;
                }
            }            
            if( !$successful_insert ){
                $failedRows[] = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
            }
        }
        return $failedRows;
    }
    
    
    


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Feedback->recursive = 0;
		$this->set('feedbacks', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Feedback->id = $id;
		if (!$this->Feedback->exists()) {
			throw new NotFoundException(__('Invalid feedback'));
		}
		$this->set('feedback', $this->Feedback->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Feedback->create();
			if ($this->Feedback->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feedback could not be saved. Please, try again.'));
			}
		}
		$surveys = $this->Feedback->Survey->find('list');
		$this->set(compact('surveys'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Feedback->id = $id;
		if (!$this->Feedback->exists()) {
			throw new NotFoundException(__('Invalid feedback'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Feedback->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feedback could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Feedback->read(null, $id);
		}
		$surveys = $this->Feedback->Survey->find('list');
		$this->set(compact('surveys'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Feedback->id = $id;
		if (!$this->Feedback->exists()) {
			throw new NotFoundException(__('Invalid feedback'));
		}
		if ($this->Feedback->delete()) {
			$this->Session->setFlash(__('Feedback deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Feedback was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
