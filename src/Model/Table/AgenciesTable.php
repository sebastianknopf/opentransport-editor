<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Agencies Model
 *
 * @property \App\Model\Table\ClientsTable|\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\RoutesTable|\Cake\ORM\Association\HasMany $Routes
 *
 * @method \App\Model\Entity\Agency get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agency newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Agency[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agency|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agency saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agency patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agency[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agency findOrCreate($search, callable $callback = null, $options = [])
 */
class AgenciesTable extends Table
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

        $this->setTable('agencies');
        $this->setDisplayField('label');
        $this->setPrimaryKey('agency_id');

        $this->addBehavior('Timestamp');

        // search behaviour
        $this->addBehavior('Search.Search');
        $this->searchManager()
            ->value('agency_id')                      // for filter
            ->add('agency_name', 'Search.Like', [     // for filter
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['agency_name']
            ]);

        $this->hasMany('StopTimes', [
            'foreignKey' => 'stop_id'
        ]);

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Routes', [
            'dependent' => true,
            'foreignKey' => 'agency_id'
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
            ->scalar('agency_name')
            ->maxLength('agency_name', 255)
            ->requirePresence('agency_name', 'create')
            ->allowEmptyString('agency_name', false);

        $validator
            ->scalar('agency_url')
            ->maxLength('agency_url', 255)
            ->requirePresence('agency_url', 'create')
            ->allowEmptyString('agency_url', false);

        $validator
            ->scalar('agency_timezone')
            ->maxLength('agency_timezone', 64)
            ->requirePresence('agency_timezone', 'create')
            ->allowEmptyString('agency_timezone', false);

        $validator
            ->scalar('agency_lang')
            ->maxLength('agency_lang', 2)
            ->requirePresence('agency_lang', 'create')
            ->allowEmptyString('agency_lang', false);

        $validator
            ->scalar('agency_phone')
            ->maxLength('agency_phone', 255)
            ->allowEmptyString('agency_phone');

        $validator
            ->scalar('agency_fare_url')
            ->maxLength('agency_fare_url', 255)
            ->allowEmptyString('agency_fare_url');

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
}
