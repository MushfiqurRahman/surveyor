<?php
App::uses('AppController', 'Controller');
/**
 * Campaigns Controller
 *
 * @property Campaign $Campaign
 */
class CampaignsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {            
		$this->Campaign->recursive = 0;
		$this->set('campaigns', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Campaign->id = $id;
		if (!$this->Campaign->exists()) {
			throw new NotFoundException(__('Invalid campaign'));
		}
		$this->set('campaign', $this->Campaign->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
                    //pr($this->request->data);exit;
                    $this->request->data['Campaign']['start_date'] = $this->request->data['Campaign']['start_date'].' 00:00:00';
                    $this->request->data['Campaign']['end_date'] = $this->request->data['Campaign']['end_date'].' 00:00:00';
                    if($this->Campaign->check_total($this->request->data)){
			$this->Campaign->create();
			if ($this->Campaign->saveAssociated($this->request->data)) {  
                            
                            //setting regionwise target
                            $this->Campaign->Achievement->set_region_target($this->request->data, $this->Campaign->id);
                            
				$this->Session->setFlash(__('The campaign has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The campaign could not be saved. Please, try again.'));
			}
                    }else{
                        $this->Session->setFlash(__('Save Failed! Total target and sum of houses target are not equal.'));
                    }
		}
                $this->set('houses',$this->Campaign->CampaignDetail->House->house_list(null));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Campaign->id = $id;
		if (!$this->Campaign->exists()) {
			throw new NotFoundException(__('Invalid campaign'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                    //pr($this->data);exit;
                    if($this->Campaign->check_total($this->request->data)){
			if ($this->Campaign->saveAssociated($this->request->data)) {
                            
                            $this->Campaign->Achievement->update_region_target($this->request->data, $id);
				$this->Session->setFlash(__('The campaign has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The campaign could not be saved. Please, try again.'));
			}
                    }else{
                        $this->Session->setFlash(__('Save Failed! Total target and sum of houses target are not equal.'));
                    }
		}
                $this->Campaign->Behaviors->load('Containable');
                $this->request->data = $this->Campaign->find('first',array('contain' => array(
                    'CampaignDetail' => array(
                        'fields' => array('id','house_id','house_target'),
                        'House' => array('title')
                    )),
                    'conditions' => array('Campaign.id' => $id)));
                    //pr($this->request->data);
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
		$this->Campaign->id = $id;
		if (!$this->Campaign->exists()) {
			throw new NotFoundException(__('Invalid campaign'));
		}
		if ($this->Campaign->delete()) {
			$this->Session->setFlash(__('Campaign deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Campaign was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
