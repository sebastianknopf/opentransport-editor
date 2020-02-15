<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Routes Model
 *
 * @property \App\Model\Table\ClientsTable|\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\AgenciesTable|\Cake\ORM\Association\BelongsTo $Agencies
 * @property \App\Model\Table\TripsTable|\Cake\ORM\Association\HasMany $Trips
 *
 * @method \App\Model\Entity\Route get($primaryKey, $options = [])
 * @method \App\Model\Entity\Route newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Route[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Route|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Route saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Route patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Route[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Route findOrCreate($search, callable $callback = null, $options = [])
 */
class RoutesTable extends Table
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

        $this->setTable('routes');
        $this->setDisplayField('label');
        $this->setPrimaryKey('route_id');

        $this->addBehavior('Timestamp');

        // search behaviour
        $this->addBehavior('Search.Search');
        $this->searchManager()
            ->value('route_id')                             // for filter
            ->value('route_type')                           // for filter
            ->add('route_short_name', 'Search.Like', [      // for filter
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['route_short_name']
            ])
            ->add('route_long_name', 'Search.Like', [       // for filter
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['route_long_name']
            ]);

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Agencies', [
            'foreignKey' => 'agency_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Trips', [
            'dependent' => true,
            'foreignKey' => 'route_id'
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
            ->scalar('route_short_name')
            ->maxLength('route_short_name', 255)
            ->requirePresence('route_short_name', 'create')
            ->allowEmptyString('route_short_name');

        $validator
            ->scalar('route_long_name')
            ->maxLength('route_long_name', 255)
            ->requirePresence('route_long_name', 'create')
            ->allowEmptyString('route_long_name', false);

        $validator
            ->scalar('route_desc')
            ->allowEmptyString('route_desc');

        $validator
            ->scalar('route_type')
            ->requirePresence('route_type', 'create')
            ->allowEmptyString('route_type', false);

        $validator
            ->scalar('route_url')
            ->maxLength('route_url', 255)
            ->allowEmptyString('route_url');

        $validator
            ->scalar('route_color')
            ->maxLength('route_color', 9)
            ->allowEmptyString('route_color');

        $validator
            ->scalar('route_text_color')
            ->maxLength('route_text_color', 9)
            ->allowEmptyString('route_text_color');

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
        $rules->add($rules->existsIn(['agency_id'], 'Agencies'));

        return $rules;
    }

    /**
     * Custom finder to find a route by its routeId.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByRouteId(Query $query, array $options)
    {
        $routeId = isset($options['query']['routeId']) ? $options['query']['routeId'] : null;

        if($routeId == null) {
            throw new BadRequestException('invalid or missing parameter routeId');
        }

        return $query->where(['Routes.route_id' => $routeId]);
    }

    /**
     * Custom finder to find a route by its routeLongName or routeShortName. The parameters
     * are matched using the LIKE functionality in SQL - This means that a route
     * can be found by its routeShortName, its routeLongName or both.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByRouteName(Query $query, array $options) {
        $routeName = isset($options['query']['routeName']) ? $options['query']['routeName'] : null;

        if($routeName == null) {
            throw new BadRequestException('invalid or missing parameter routeName');
        }

        return $query->where(function (QueryExpression $expression, Query $query) use ($routeName) {
            $conditions = $expression->or_(function ($or) use ($routeName) {
                if($routeName != null) {
                    $or = $or->like('Routes.route_short_name', '%' . $routeName . '%');
                }

                if($routeName != null) {
                    $or = $or->like('Routes.route_long_name', '%' . $routeName . '%');
                }

                return $or;
            });

            return $expression->add($conditions);
        });
    }
}
