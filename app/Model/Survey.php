<?php
App::uses('AppModel', 'Model');
/**
 * Survey Model
 *
 * @property Campaign $Campaign
 * @property Representative $Representative
 * @property MoLog $MoLog
 * @property Age $Age
 * @property Occupation $Occupation
 * @property House $House
 */
class Survey extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'campaign_id' => array(
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
		'representative_id' => array(
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
		'mo_log_id' => array(
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
		'survey_counter' => array(
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
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'phone' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'age' => array(
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
		'adc' => array(
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
		'occupation_id' => array(
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Campaign' => array(
			'className' => 'Campaign',
			'foreignKey' => 'campaign_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Representative' => array(
			'className' => 'Representative',
			'foreignKey' => 'representative_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MoLog' => array(
			'className' => 'MoLog',
			'foreignKey' => 'mo_log_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
//		'Age' => array(
//			'className' => 'Age',
//			'foreignKey' => 'age_id',
//			'conditions' => '',
//			'fields' => '',
//			'order' => ''
//		),
		'Occupation' => array(
			'className' => 'Occupation',
			'foreignKey' => 'occupation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'House' => array(
			'className' => 'House',
			'foreignKey' => 'representative_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        
         /**
        *
        * @return type 
        */
        public function get_contain_array(){

            return array(
                'Representative' => array(
                    'fields' => array('name','superviser_name'),
                    'House' => array(
                                'fields' => array('title'),
                                'Area' => array(
                                    'fields' => array('title'),
                                    'Region' => array('fields' => array('title')))),
                    ),
                'Occupation' => array('title')
            );
        }
        
        /**
         *
         * @return type 
         */
        public function set_conditions( $surveyIds = null, $data = array() ){
        
            //pr($outletIds);exit;
            
            $conditions = array();
            
            if( $surveyIds ){
                $conditions[]['Survey.id'] = $surveyIds;                
            }else{
                $conditions[]['Survey.id'] = 0;
            }
//            if( isset($data['from_date']) && !empty($data['from_date']) ){
//                $conditions[]['DATE(Sale.date) >='] = $data['from_date'];
//            }
//            if( isset($data['till_date']) && !empty($data['till_date']) ){
//                $conditions[]['DATE(Sale.date) <='] = $data['till_date'];
//            }
            return $conditions;
        }
        
        public function get_region_wise_achievements( $current_campaign, $regions ){
            $reg_achievements = array();
            foreach($regions as $k => $rg){
                foreach( $current_campaign['Achievement'] as $ach ){
                    if( $ach['region_id']==$k ){
                        $reg_achievements[$rg]['parcent_achieved'] = round(($ach['region_achieved']*100)/$ach['region_target']);
                        $reg_achievements[$rg]['total_disbursed'] = $ach['region_achieved'];
                        break;
                    }
                }
            }
            return $reg_achievements;            
        }
}
