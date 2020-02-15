<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Frequencies Model
 *
 * @property \App\Model\Table\TripsTable|\Cake\ORM\Association\BelongsTo $Trips
 *
 * @method \App\Model\Entity\Frequency get($primaryKey, $options = [])
 * @method \App\Model\Entity\Frequency newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Frequency[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Frequency|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Frequency saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Frequency patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Frequency[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Frequency findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FrequenciesTable extends Table
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

        $this->setTable('frequencies');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Trips', [
            'foreignKey' => 'trip_id',
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
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->time('start_time')
            ->requirePresence('start_time', 'create')
            ->allowEmptyTime('start_time', false);

        $validator
            ->time('end_time')
            ->requirePresence('end_time', 'create')
            ->allowEmptyTime('end_time', false);

        $validator
            ->integer('headway_min')
            ->requirePresence('headway_min', 'create')
            ->allowEmptyString('headway_min', false);

        $validator
            ->scalar('exact_times');

        $validator
            ->allowEmptyString('flags', false);

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
}
