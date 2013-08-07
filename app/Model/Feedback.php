<?php
App::uses('AppModel', 'Model');
/**
 * Feedback Model
 *
 * @property Survey $Survey
 * @property Survey $Survey
 */
class Feedback extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'survey_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_right_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_right_age' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_right_occupation' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'current_brand' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'new_pack' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'tobacco_quality' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		
		'br_toolkit' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'got_ptr' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed



/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Survey' => array(
			'className' => 'Survey',
			'foreignKey' => 'survey_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
                'User' => array(
                    'className' => 'User',
                    'foreignKey' => 'user_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
                )
	);
        
        /**
         * @desc Used in FeedbacksController.php 
         * @param type $houseIds
         * @param type $survey_date
         * @return int 0 means all the houses target has been achieved;
         */
        public function house_target_not_achieved( $campaignId, $houseIds, $survey_date ){
            if( !is_array($houseIds) ){
                $houseIds[] = $houseIds;
            }
            
            foreach( $houseIds as $hid){
                if( !$this->Survey->is_feedback_achieved($campaignId, $hid, $survey_date) ){
                    return $hid;
                }
            }
            return 0;
        }
        
        /**
         * @desc Used in FeedbacksController.php 
         */
        public function get_contain_array( ){
            
            return array(
                'Survey' => array('fields' => array('Survey.*'),
                    'Representative' => array(
                        'fields' => array('name','superviser_name'),
                        'House' => array(
                                    'fields' => array('title'),
                                    'Area' => array(
                                        'fields' => array('title'),
                                        'Region' => array('fields' => array('title')))),
                    ),
                    'Occupation' => array('title')
                ),
            );
        }
        
        /**
         *
         * @return type 
         */
        public function set_conditions( $surveyIds = null, $data = array() ){
            
            $conditions = array();
            
            if( $surveyIds ){
                $conditions[]['Feedback.survey_id'] = $surveyIds;                
            }
            
            //since Feedback reporting on Feedbacks created date. Not on survey date            
            if( isset($data['start_date']) && !empty($data['start_date']) ){
                $conditions[]['DATE(Feedback.created) >='] = $data['start_date'];
            }
            if( isset($data['end_date']) && !empty($data['end_date']) ){
                $conditions[]['DATE(Feedback.created) <='] = $data['end_date'];
            }
            return $conditions;
        }
        
        
        /**
         * @desc Used in FeedbacksController.php file
         * @param type $feedbacks
         * @return type 
         */
        public function format_for_feedback_export( $feedbacks ){
            $formatted = array();
            $i = 0;
            
            foreach( $feedbacks as $srv ){
                $formatted[$i]['id'] = $srv['Feedback']['id'];
                $formatted[$i]['region'] = $srv['Survey']['Representative']['House']['Area']['Region']['title'];
                $formatted[$i]['area'] = $srv['Survey']['Representative']['House']['Area']['title'];
                $formatted[$i]['house'] = $srv['Survey']['Representative']['House']['title'];
                $formatted[$i]['br_name'] = $srv['Survey']['Representative']['name'];
                $formatted[$i]['sup_name'] = $srv['Survey']['Representative']['superviser_name'];
                
                $formatted[$i]['customer_name'] = $srv['Feedback']['is_right_name'];
                //$formatted[$i]['phone_no'] = $srv['Survey']['phone'];
                $formatted[$i]['age'] = $srv['Feedback']['is_right_age'];                
                $formatted[$i]['occupation'] = $srv['Feedback']['is_right_occupation'];
                $formatted[$i]['current_brand'] = $srv['Feedback']['current_brand'];
                $formatted[$i]['notice_new_pack'] = $srv['Feedback']['new_pack'];                
                $formatted[$i]['tobacco_quality'] = $srv['Feedback']['tobacco_quality'];
                $formatted[$i]['br_toolkit'] = $srv['Feedback']['br_toolkit'];
                $formatted[$i]['ptr_back_check'] = $srv['Feedback']['got_ptr'];
                $formatted[$i]['date'] = date('Y-m-d',strtotime($srv['Feedback']['created']));
                $i++;
            }
            return $formatted;
        }
}
