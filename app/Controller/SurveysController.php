<?php
App::uses('AppController', 'Controller');
/**
 * Surveys Controller
 *
 * @property Survey $Survey
 */
class SurveysController extends AppController {
    
    public $region_list = array();
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->region_list = $this->Survey->House->Area->Region->find('list');
        $this->set('regions', $this->region_list);
    }


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Survey->recursive = 0;
		$this->set('surveys', $this->paginate());
	}
        
        /**
         * 
         */
        public function dashboard(){
            $achievements = array();
            
            $achievements_by_house['total_allocation'] = $this->current_campaign_detail['Campaing']['total_target'];
            
            $achievements['achieved_total'] = $this->Survey->find('count');

            $diff = abs(strtotime($this->current_campaign_detail['Campaign']['start_date']) - strtotime($this->current_campaign_detail['Campaign']['end_date']));
            $camp_date_diff = $diff/(24*3600);

            $day_passed = round(abs(time() - strtotime($this->current_campaign_detail['Campaign']['start_date']))/(24*3600));


            $achievements['achievement_parcentage'] = round($achieved_total*100/$this->current_campaign_detail['Campaign']['total_target']);
            $achievements['required_rate'] = round(($this->current_campaign_detail['Campaign']['total_target'] - $achievements['achieved_total'])/($camp_date_diff-$day_passed));

            $achievements['target_till_date'] = round($this->current_campaign_detail['Campaign']['total_target']*$day_passed/$camp_date_diff);
            
            $regionwise_achievements = $this->Survey->get_region_wise_achievements($this->current_campaign_detail, $this->region_list);

            $this->set('achievements',$achievements);
            $this->set('regionwise_achievements',$regionwise_achievements);
        }
        
        public function report(){
            $this->_set_request_data_from_params();
            //$this->_format_date_fields();

            
            $houseList = $this->Survey->House->house_list($this->request->data);//('list', array('conditions' => $this->_set_conditions()));
                       
            
            if( isset($this->request->data['House']['id']) && !empty($this->request->data['House']['id']) ){
                $houseIds[] = $this->request->data['House']['id'];
            }else{
                $houseIds = $this->Survey->House->id_from_list($houseList);                
            }
            
            
            
            $SurveyIds = $this->Survey->find('list',array('fields' => 'id','conditions' => 
                array('Survey.campaign_id' => $this->current_campaign_detail['Campaign']['id'],
                      'Survey.house_id' => $houseIds)));            

            $this->Survey->Behaviors->load('Containable');
            
            //var_dump($this->Survey->set_conditions($SurveyIds, $this->request->data));exit;

            $this->paginate = array(
                'contain' => $this->Survey->get_contain_array(),
                'conditions' => $this->Survey->set_conditions($SurveyIds, $this->request->data),                                    
                'order' => array('Survey.created' => 'DESC'),
                'limit' => 10,
            );                
            $Surveys = $this->paginate();
            
            $this->set('achievements',$this->Survey->Campaign->achievements_by_house($houseIds, $this->current_campaign_detail['Campaign']['id']));

            //pr($Surveys);exit;           
            
            $this->set('houses', $houseList);
            //$this->set('productsList',$this->Survey->SurveyDetail->Product->find('list',array('fields' => array('id','name'))));
            $this->set('Surveys', $Surveys);
            
        }
        
        protected function _set_conditions(){
            $conditions = array();
            if( $this->request->data['Area']['id'] ){
                $conditions[]['area_id'] = $this->request->data['Area']['id'];
            }else if( $this->request->data['Region']['id'] ){
                $areas = $this->Survey->House->Area->find('list',array('conditions' => array(
                    'Area.region_id' => $this->request->data['Region']['id']
                )));
                
                $areaIds = array();
                foreach($areas as $k => $v){
                    $areaIds[] = $k;
                }
                $conditions[]['area_id'] = $areaIds;
            }
            return $conditions;
        }

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		$this->set('survey', $this->Survey->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
            
		if ($this->request->is('post')) {
			$this->Survey->create();
			if ($this->Survey->save($this->request->data)) {
				$this->Session->setFlash(__('The survey has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
			}
		}
		$campaigns = $this->Survey->Campaign->find('list');
		$representatives = $this->Survey->Representative->find('list');
		$moLogs = $this->Survey->MoLog->find('list');
		//$ages = $this->Survey->Age->find('list');
		$occupations = $this->Survey->Occupation->find('list');
		$houses = $this->Survey->House->find('list');
		$this->set(compact('campaigns', 'representatives', 'moLogs', 'ages', 'occupations', 'houses'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Survey->save($this->request->data)) {
				$this->Session->setFlash(__('The survey has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Survey->read(null, $id);
		}
		$campaigns = $this->Survey->Campaign->find('list');
		$representatives = $this->Survey->Representative->find('list');
		$moLogs = $this->Survey->MoLog->find('list');
		$ages = $this->Survey->Age->find('list');
		$occupations = $this->Survey->Occupation->find('list');
		$houses = $this->Survey->House->find('list');
		$this->set(compact('campaigns', 'representatives', 'moLogs', 'ages', 'occupations', 'houses'));
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
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		if ($this->Survey->delete()) {
			$this->Session->setFlash(__('Survey deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
