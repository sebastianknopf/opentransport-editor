<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Shapes Model
 *
 * @property \App\Model\Table\TripsTable|\Cake\ORM\Association\HasMany $Trips
 *
 * @method \App\Model\Entity\Shape get($primaryKey, $options = [])
 * @method \App\Model\Entity\Shape newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Shape[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Shape|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shape saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shape patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Shape[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Shape findOrCreate($search, callable $callback = null, $options = [])
 */
class ShapesTable extends Table
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

        $this->setTable('shapes');
        $this->setDisplayField('shape_name');
        $this->setPrimaryKey('shape_id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Duplicatable.Duplicatable', [
            'finder' => 'all',
            'remove' => ['created'],
            'append' => ['shape_name' => '_Copy']
        ]);

        // search behaviour
        $this->addBehavior('Search.Search');
        $this->searchManager()
            ->value('shape_id')                     // for filter
            ->add('shape_name', 'Search.Like', [    // for filter
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['shape_name']
            ]);

        $this->hasMany('StopTimes', [
            'foreignKey' => 'stop_id'
        ]);

        $this->hasMany('Trips', [
            'foreignKey' => 'shape_id'
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
            ->integer('shape_id')
            ->allowEmptyString('shape_id', 'create');

        $validator
            ->scalar('shape_name')
            ->requirePresence('shape_name', 'create')
            ->allowEmptyString('shape_name', false);

        $validator
            ->scalar('shape_polyline')
            ->requirePresence('shape_polyline', 'create', __('You must specify at least two shape points!'))
            ->allowEmptyString('shape_polyline', false, __('You must specify at least two shape points!'));

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
        $rules->add($rules->isUnique(['shape_name']));

        return $rules;
    }
}
