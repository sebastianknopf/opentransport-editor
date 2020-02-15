<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Stops Model
 *
 * @property \App\Model\Table\StopTimesTable|\Cake\ORM\Association\HasMany $StopTimes
 *
 * @method \App\Model\Entity\Stop get($primaryKey, $options = [])
 * @method \App\Model\Entity\Stop newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Stop[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Stop|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Stop saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Stop patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Stop[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Stop findOrCreate($search, callable $callback = null, $options = [])
 */
class StopTimesTable extends Table
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

        $this->setTable('stop_times');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Trips', [
            'foreignKey' => 'trip_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Stops', [
            'foreignKey' => 'stop_id',
            'joinType' => 'INNER'
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
        /*$validator
            ->integer('stop_id')
            ->allowEmptyString('stop_id', 'create');

        $validator
            ->scalar('stop_code')
            ->maxLength('stop_code', 255)
            ->allowEmptyString('stop_code', true);

        $validator
            ->scalar('stop_name')
            ->maxLength('stop_name', 255)
            ->requirePresence('stop_name', 'create')
            ->allowEmptyString('stop_name', false);

        $validator
            ->scalar('stop_desc')
            ->requirePresence('stop_desc', 'create')
            ->allowEmptyString('stop_desc', true);

        $validator
            ->numeric('stop_lat')
            ->requirePresence('stop_lat', 'create')
            ->allowEmptyString('stop_lat', false, __('Please specify a GPS position'));

        $validator
            ->numeric('stop_lat')
            ->requirePresence('stop_lat', 'create')
            ->allowEmptyString('stop_lat', false, __('Please specify a GPS position'));

        $validator
            ->scalar('location_type')
            ->allowEmptyString('location_type', false);

        $validator
            ->scalar('parent_station')
            ->maxLength('parent_station', 255)
            ->requirePresence('parent_station', 'create')
            ->allowEmptyString('parent_station', true);

        $validator
            ->scalar('wheelchair_boarding')
            ->allowEmptyString('wheelchair_boarding', false);*/

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
        return $rules;
    }

    /**
     * Modify the default order for stop times.
     *
     * @param $event
     * @param $query
     * @param $options
     * @param $primary
     */
    public function beforeFind($event, $query, $options, $primary) {
        $order = $query->clause('order');
        if ($order === null || !count($order)) {
            $query->order(['StopTimes.stop_sequence' => 'ASC'] );
        }
    }
}
