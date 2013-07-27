<?php
App::uses('AppModel', 'Model');
/**
 * Achievement Model
 *
 * @property Campaign $Campaign
 * @property Region $Region
 */
class Achievement extends AppModel {
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
		'region_id' => array(
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
		'region_target' => array(
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
		'region_achieved' => array(
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
		'Region' => array(
			'className' => 'Region',
			'foreignKey' => 'region_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        /**
         *
         * @param type $data
         * @param type $house_id
         * @return type 
         */
        protected function _find_house_target( $data, $house_id ){
            foreach($data as $dt){
                if( $dt['house_id'] == $house_id ){
                    return $dt['house_target'];
                }
            }
        }
        
        /**
         *
         * @param type $data
         * @param type $region_id 
         */
        protected function _get_region_target($data, $region_id){
            $regionDetail = $this->Region->find('first',array('conditions' => array('Region.id' => $region_id),
                    'recursive' =>2));
            
            $regionTarget = 0;

            foreach( $regionDetail['Area'] as $area ){                                
                foreach( $area['House'] as $house ){
                    $regionTarget += $this->_find_house_target($data['CampaignDetail'], $house['id']);
                }
            }
            return $regionTarget;
        }
        
        /**
         *
         * @param type $data
         * @param type $campaign_id 
         */
        public function set_region_target( $data, $campaign_id ){ 
            $regionDetail = $this->Region->find('all',array('recursive' =>2));

            $achievement = array();
            $i = 0;
            foreach( $regionDetail as $rgd ){
                $achievement[$i]['Achievement']['campaign_id'] = $campaign_id;
                $achievement[$i]['Achievement']['region_id'] = $rgd['Region']['id'];
                $achievement[$i]['Achievement']['region_target'] = 0;
                
                foreach( $rgd['Area'] as $area ){
                    foreach( $area['House'] as $house ){
                        $achievement[$i]['Achievement']['region_target'] += $this->_find_house_target($data['CampaignDetail'], $house['id']);
                    }
                }
                $i++;
            }
            $this->saveMany($achievement);
            $this->log(print_r($achievement,true),'error');
        }
        
        /**
         *
         * @param type $data
         * @param type $campaign_id 
         */
        public function update_region_target( $data, $campaign_id ){ 
            $achievements = $this->find('all',array('conditions' => array('campaign_id' => $campaign_id)));
            $updatedData = array();
            foreach( $achievements as $k => $ach ){
                $updatedData[$k]['Achievement'] = $ach['Achievement'];
                $updatedData[$k]['Achievement']['region_target'] = $this->_get_region_target($data, $ach['Achievement']['region_id']);
            }            
            $this->saveMany($updatedData);
            $this->log(print_r($updatedData,true),'error');
        }
        
        
}
