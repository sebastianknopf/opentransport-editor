<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\Chronos\Date;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Services Table
 *
 * @package App\Model\Table
 */
class ServicesTable extends Table
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

        $this->setTable('calendar');
        $this->setDisplayField('label');
        $this->setPrimaryKey('service_id');

        $this->addBehavior('Timestamp');

        // search behaviour
        $this->addBehavior('Search.Search');
        $this->searchManager()
            ->value('service_id')                      // for filter
            ->add('service_name', 'Search.Like', [     // for filter
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['service_name']
            ]);

        $this->hasMany('StopTimes', [
            'foreignKey' => 'stop_id'
        ]);

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Trips', [
            'foreignKey' => 'service_id'
        ]);
        $this->hasMany('ServiceExceptions', [
            'dependent' => true,
            'foreignKey' => 'service_id'
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
            ->integer('service_id')
            ->allowEmptyString('service_id', 'create');

        $validator
            ->integer('client_id')
            ->allowEmptyString('client_id', false);

        $validator
            ->scalar('service_name')
            ->maxLength('service_name', 255)
            ->requirePresence('service_name', 'create')
            ->allowEmptyString('service_name', false);

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->allowEmptyDate('start_date', false);

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->allowEmptyDate('end_date', false);

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
        $rules->add($rules->existsIn(['client_id'], 'Clients'));

        return $rules;
    }

    /**
     * Parse text input data as dates before marshaling the objects.
     *
     * @param Event $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        try {
            if(isset($data['start_date'])) {
                $data['start_date'] = Date::parse($data['start_date']);
            }

            if(isset($data['end_date'])) {
                $data['end_date'] = Date::parse($data['end_date']);
            }
        } catch(\Exception $e) {
            // simply do nothing to fire validation error by system
        }
    }
}