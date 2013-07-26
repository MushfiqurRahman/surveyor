<?php
App::uses('AppController', 'Controller');
/**
 * Surveys Controller
 *
 * @property Survey $Survey
 */
class SurveysController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $achieved_total = $this->Survey->find('count');
        
        $diff = abs(strtotime($this->current_campaign_detail['Campaign']['start_date']) - strtotime($this->current_campaign_detail['Campaign']['end_date']));
        $camp_date_diff = $diff/(24*3600);
        
        $day_passed = ceil(abs(time() - strtotime($this->current_campaign_detail['Campaign']['start_date']))/(24*3600));
        
        
        $ach_percentage = ceil($achieved_total*100/$this->current_campaign_detail['Campaign']['total_target']);
        
        $this->set('achieved_percentage',$ach_percentage);        
        $this->set('achieved_total',$achieved_total);
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
