<?php
App::uses('AppController', 'Controller');
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
                        //pr($this->request->data);exit;
//            foreach( $this->data['Feedback'] as $key => $v ){
//                if($key !='current_brand' && $key!= 'survey_id' ){
//                    $this->request->data['Feedback'][$key] = ($v=='Right' || $v=='Yes')?1:0;
//                }
//            }
            $this->request->data['Feedback']['user_id'] = $this->Auth->user('id');
            unset($this->request->data['Feedback']['save']);
            if( $this->Feedback->save($this->data['Feedback']) ){
                $this->Feedback->Survey->id = $this->data['Feedback']['survey_id'];
                $this->Feedback->Survey->saveField('feedback_taken', 1);
                $this->Session->setFlash('Feedback saved successfully');
            }
        }   
        
        $conditions = array(
                'Survey.feedback_taken' => 0, 'Survey.campaign_id' => $this->current_campaign_detail['Campaign']['id'],
                'Survey.house_id' => $this->houseIds,
                'Survey.feedback_skipped' => 0,
            );
        
        if( isset($this->request->data['Feedback']['skip']) ){
            $this->Feedback->Survey->id = $this->data['Feedback']['survey_id'];
            $this->Feedback->Survey->saveField('feedback_skipped',1);
            //pr($this->request->data);exit;
            $conditions['Survey.id !='] = $this->request->data['Feedback']['survey_id'];
        }
        //$this->Feedback->Survey->Behaviors->load('Containable');     
        
        $this->set('survey', $this->Feedback->Survey->find('first',array(            
            'conditions' => $conditions, 'recursive' => 0)));
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
