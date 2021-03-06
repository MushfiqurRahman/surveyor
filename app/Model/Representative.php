<?php
App::uses('AppModel', 'Model');
/**
 * Representative Model
 *
 * @property House $House
 * @property Sale $Sale
 */
class Representative extends AppModel {

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
		'house_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
//		'mobile_no' => array(
//			'notempty' => array(
//				'rule' => array('notempty'),
//				//'message' => 'Your custom message here',
//				//'allowEmpty' => false,
//				//'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
//		),
		'type' => array(
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
		'House' => array(
			'className' => 'House',
			'foreignKey' => 'house_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Survey' => array(
			'className' => 'Survey',
			'foreignKey' => 'representative_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
//		'Section' => array(
//			'className' => 'Section',
//			'foreignKey' => 'representative_id',
//		),
                'Mobile' => array(
                    'className' => 'Mobile',
                    'foreignKey' => 'representative_id'
                )
	);
        
        /**
         *
         * @param type $houseId
         * @return string 
         */
        public function repList_with_mobile( $houseId, $rep_type = null, $ss_id = 0 ){
            $qry = 'select * from representatives left join mobiles '.
                        'on representatives.id = mobiles.representative_id where representatives.house_id='.
                        $houseId;
            if( $rep_type ){
                $qry .= ' AND representatives.superviser_id=0';
            }
            if( $ss_id ){
                $qry .= ' AND representatives.superviser_id='.$ss_id;
            }
            $res = $this->query($qry);
            
            //pr($res);exit;
                    
            $repList = array();

            foreach( $res as $r ){
                if( isset($repList[ $r['representatives']['id'] ]) ){
                    $repList[ $r['representatives']['id'] ] .= ', '.$r['mobiles']['mobile_no'];
                }else{
                    $repList[ $r['representatives']['id'] ] = $r['representatives']['name'].', '.$r['mobiles']['mobile_no'];
                }
            }
            return $repList;
        }

}
