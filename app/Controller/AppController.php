<?php
date_default_timezone_set('Asia/Dacca');
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public $total_outlet = 0;
    
    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'pages', 'action' => 'home'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authorize' => array('Controller'),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email','password' => 'password')))));

    public function beforeFilter(){    
        parent::beforeFilter();
    }

    public function isAuthorized($user) {
        if( $this->Auth->user() ){
            return true;
        }
        return false;
    }
    
    
    
    function _day_interval(){
        if( isset($this->request->data['from_date']) && isset($this->request->data['till_date']) ){            
            $total_day = strtotime($this->request->data['till_date']) - strtotime($this->request->data['from_date']);
            $total_day /= (24*3600);
            $total_day = (int)$total_day;
            return ($total_day > 0 ? $total_day : 1);
        }
        return 1;
    }
    
    /**
    * 
    */
    protected function _format_date_fields(){            
        if( isset($this->request->data['from_date']) ){
            $this->request->data['from_date'] = is_numeric($this->request->data['from_date']) ? date('Y-m-d',$this->request->data['from_date']) : $this->request->data['from_date'];
        }
        if( isset($this->request->data['till_date']) ){
            $this->request->data['till_date'] = is_numeric($this->request->data['till_date']) ? date('Y-m-d', $this->request->data['till_date']) : $this->request->data['till_date'];
        }
    }
    
    /**
     * 
     */
    protected function _set_request_data_from_params(){
        
        if( !$this->request->is('post') && !empty($this->request->params['named'])){
            if( isset($this->request->params['named']['region_id']) ){
                $this->request->data['Region']['id'] = $this->request->params['named']['region_id'];
            }
            if( isset($this->request->params['named']['area_id']) ){
                $this->request->data['Area']['id'] = $this->request->params['named']['area_id'];
            }            
            if( isset($this->request->params['named']['house_id']) ){
                $this->request->data['House']['id'] = $this->request->params['named']['house_id'];
            }
            
            if( isset($this->request->params['named']['priority']) ){
                $this->request->data['Outlet']['priority'] = $this->request->params['named']['priority'];
            }
            if( isset($this->request->params['named']['from_date']) ){
                $this->request->data['from_date'] = date('Y-m-d',$this->request->params['named']['from_date']);
            }
            if( isset($this->request->params['named']['till_date']) ){
                $this->request->data['till_date'] = date('Y-m-d',$this->request->params['named']['till_date']);
            }
        } 
    }    
}
