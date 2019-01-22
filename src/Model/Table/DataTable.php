<?php
namespace App\Model\Table;

use App\Model\Entity\Data;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Data Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LtiUser
 * @property \Cake\ORM\Association\BelongsTo $LtiUserUsers
 */
class DataTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('data');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Datetime');

        $this->belongsTo('LtiUser', [
            'foreignKey' => ['lti_consumer_key', 'lti_context_id', 'lti_user_id'],
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('lti_consumer_key', 'create')
            ->notEmpty('lti_consumer_key');

        $validator
            ->requirePresence('lti_context_id', 'create')
            ->notEmpty('lti_context_id');

        $validator
            ->requirePresence('lti_user_id', 'create')
            ->notEmpty('lti_user_id');

        $validator
            ->allowEmpty('data');

        $validator
            ->integer('revision_parent')
            ->allowEmpty('revision_parent');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['lti_consumer_key', 'lti_context_id', 'lti_user_id'], 'LtiUser'));
        return $rules;
    }
    
    public function getBasicConditions($tool = null, $model = null) {
        if(!$tool) {
            return [];
        }
        
        $conditions = [
            (($model?($model . '.'):'') . 'lti_consumer_key') => $tool->consumer->getKey(),
            (($model?($model . '.'):'') . 'lti_context_id') => $tool->context->getId(),
        ];

        return $conditions;
    }
    
    public function getBasicUserConditions($tool = null, $model = null) {
        if(!$tool) {
            return [];
        }
        
        $conditions = $this->getBasicConditions($tool, $model);
        
        $conditions[(($model?($model . '.'):'') . 'lti_user_id')] = $tool->user->getId();

        return $conditions;
    }
    
    public function getCases() {
        $cases = [
            [
                'name' => 'stroke-01-left-mca',
                'label' => '1. Left MCA',
            ],
            [
                'name' => 'stroke-02-right-mca',
                'label' => '2. Right MCA',
            ],
            [
                'name' => 'stroke-03-venous-thrombosis',
                'label' => '3. Venous Thrombosis',
            ],
            [
                'name' => 'stroke-04-right-pica',
                'label' => '4. Right PICA',
            ],
            [
                'name' => 'stroke-05-right-aca',
                'label' => '5. Right ACA',
            ],
            [
                'name' => 'stroke-06-sensory-thalamic-bleed',
                'label' => '6. Sensory Thalamic Bleed',
            ],
            [
                'name' => 'stroke-07-left-occipital',
                'label' => '7. Left Occipital',
            ],
            [
                'name' => 'stroke-08-pons',
                'label' => '8. Pons',
            ],
        ];
        
        return $cases;
    }

    public function getLatestUserData($tool = null) {
        if(!$tool) {
            return [];
        }
        
        $conditions = $this->getBasicUserConditions($tool);
        $conditions['revision_parent'] = 0;

        $dataQuery = $this->find('all', [
            'conditions' => $conditions,
        ]);

        $data = $dataQuery->first();

        return $data;
    }
    
    public function getResults($tool = null) {
        if(!$tool) {
            return [];
        }
        
        $conditions = $this->getBasicConditions($tool, 'Data');
        $conditions['revision_parent'] = 0;
        
        //Find the results 
        //Don't sort here, as need to sort by associated and can easily do it in Javascript
        $dataQuery = $this->find('all', [
            'conditions' => $conditions,
            'contain' => ['LtiUser' => ['LtiUserUsers' => ['Users']]],
            'order' => ['Data.modified' => 'DESC'],
        ]);
		
		$results = $dataQuery->toArray();
        
        $results = $this->processResults($results);
        
        return $results;
    }
    
    public function processResults($results = null) {
        if(!$results) {
            return [];
        }
        
        $writingTimeTaken = array();
		$writingLength = array();
		$joiningTimeTaken = array();
		$joiningLength = array();
		
		$writingTimeTakenMean = array();
		$writingLengthMean = array();
		$joiningTimeTakenMean = array();
		$joiningLengthMean = array();
		
		$writingTimeTakenCounter = 0;
		$writingLengthCounter = 0;
		$joiningTimeTakenCounter = 0;
		$joiningLengthCounter = 0;
						
		foreach($results as &$result) {
			//$result = json_decode($result, true);   //Decode the JSON, with objects as associative arrays
			$result['data'] = json_decode($result['data'], true);   //Decode the JSON, with objects as associative arrays
			//$result['data'] = json_decode($result['data'], true);   //Decode the JSON, with objects as associative arrays
			//get first 4 characters of username
			$collegePrefix = substr($result['lti_user']['lti_user_user']['user']['username'], 0, 4);
			//does that key exist in the array - if not create the key
			if(!array_key_exists($collegePrefix, $writingTimeTaken)){
				$writingTimeTaken[$collegePrefix]=0;
			}
			if(!array_key_exists($collegePrefix, $writingLength)){
				$writingLength[$collegePrefix]=0;
			}
			if(!array_key_exists($collegePrefix, $joiningTimeTaken)){
				$joiningTimeTaken[$collegePrefix]=0;
			}
			if(!array_key_exists($collegePrefix, $joiningLength)){
				$joiningLength[$collegePrefix]=0;
			}
			//add time or length to array
			if($result['data']['writingTimeTaken']) {
				$writingTimeTaken[$collegePrefix] += $result['data']['writingTimeTaken'];
				$writingTimeTakenCounter = $writingTimeTakenCounter + 1;
			}
			if(isset($result['data']['writingLength'])) {
				$writingLength[$collegePrefix] += $result['data']['writingLength'];
				$writingLengthCounter = $writingLengthCounter + 1;
			}
			if(isset($result['data']['joiningTimeTaken'])) {
				$joiningTimeTaken[$collegePrefix] += $result['data']['joiningTimeTaken'];
				$joiningTimeTakenCounter = $joiningTimeTakenCounter + 1;
			}
			if(isset($result['data']['joiningLength'])) {
				$joiningLength[$collegePrefix] += $result['data']['joiningLength'];
				$joiningLengthCounter = $joiningLengthCounter + 1;
			}				
		}
		
		//divide by Counter to populate means
		foreach($writingTimeTaken as $key => $value) {
			$means[$key]['writingTimeTaken']=$value/$writingTimeTakenCounter;
			$means[$key]['writingTimeTakenCounter']=$writingTimeTakenCounter;
		}
		foreach($writingLength as $key => $value) {
			$means[$key]['writingLength']=$value/$writingLengthCounter;
			$means[$key]['writingLengthCounter']=$writingLengthCounter;
		}
		foreach($joiningTimeTaken as $key => $value) {
			$means[$key]['joiningTimeTaken']=$value/$joiningTimeTakenCounter;
			$means[$key]['joiningTimeTakenCounter']=$joiningTimeTakenCounter;
		}
		foreach($joiningLength as $key => $value) {
			$means[$key]['joiningLength']=$value/$joiningLengthCounter;
			$means[$key]['joiningLengthCounter']=$joiningLengthCounter;
		}
		
		$results['means'] = $means;
		//$results['writingLengthMean'] = $writingLengthMean;
		//$results['joiningTimeTakenMean'] = $joiningTimeTakenMean;
		//$results['joiningLengthMean'] = $joiningLengthMean;
		
		//Get and count the cases
        /*$cases = $this->getCases();
        $caseCount = count($cases);
        
        //Process the results
        foreach($results as &$result) {
            $result['data'] = json_decode($result['data'], true);   //Decode the JSON, with objects as associative arrays
            
            $casesComplete = 0;
            $casesAttempted = 0;
            
            //Loop through the cases
            foreach($cases as $case) {
                $result[$case['name']] = null;
                //If score exists for this case, it has been attempted
                if(isset($result['data'][$case['name']])) {
                    $casesAttempted++;
                    //If score is greater than 0, it has been 'completed'
                    if($result['data'][$case['name']] > 0) {
                        $casesComplete++;
                    }
                }
            }
            $result['attempted'] = $casesAttempted;
            $result['completed'] = $casesComplete;
            $result['not_completed'] = $caseCount - $casesComplete;
            
            //Move user data to top level of results array for sorting
            $user = $result->lti_user->lti_user_user->user;
            unset($result['lti_user']);
            
            $userFields = ['username', 'email', 'fullname', 'firstname', 'lastname'];
            foreach($userFields as $field) {
                $result[$field] = $user[$field];
            }
            
            $result['modified'] = $this->formatDatetimeObjectForView($result['modified']);
        }*/

        return $results;
    }
}
