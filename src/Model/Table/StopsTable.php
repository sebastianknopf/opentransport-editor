<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use mysql_xdevapi\Exception;

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
class StopsTable extends Table
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

        $this->setTable('stops');
        $this->setDisplayField('stop_name');
        $this->setPrimaryKey('stop_id');

        $this->addBehavior('Timestamp');

        // search behaviour
        $this->addBehavior('Search.Search');
        $this->searchManager()
            ->value('stop_id')                      // for filter
            ->add('stop_code', 'Search.Like', [     // for filter
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['stop_code']
            ])
            ->add('stop_name', 'Search.Like', [     // for filter
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['stop_name']
            ])
            ->add('q', 'Search.Like', [             // for AJAX in backend
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['stop_code', 'stop_name']
            ]);

        $this->hasMany('StopTimes', [
            'foreignKey' => 'stop_id'
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
            ->allowEmptyString('wheelchair_boarding', false);

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
     * Before deleting the entire entity, we'll delete all sub entities. Can't do this by built in methods,
     * because we're working on the same table with two different tupels ...
     *
     * @param Event $event System event
     * @param EntityInterface $entity The parent entity to be deleted
     * @param \ArrayObject $options Options
     */
    public function beforeDelete(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        $stops = $this->find()->where(['Stops.parent_station' => $entity->stop_id])->all();

        foreach ($stops as $stop) {
            $this->delete($stop);
        }
    }

    /**
     * Custom finder to find a stop by its stopId.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByStopId(Query $query, array $options)
    {
        $stopId = isset($options['query']['stopId']) ? $options['query']['stopId'] : null;

        if ($stopId == null) {
            throw new BadRequestException('invalid or missing parameter stopId');
        }

        return $query->where(['Stops.stop_id' => $stopId]);
    }

    /**
     * Custom finder to find a stop by its stopCode or stopName. The parameters
     * are matched using the LIKE functionality in SQL - This means that a stop
     * can be found by its stopName, its stopCode or both.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByStopCodeOrStopName(Query $query, array $options)
    {
        $stopCode = isset($options['query']['stopCode']) ? $options['query']['stopCode'] : null;
        $stopName = isset($options['query']['stopName']) ? $options['query']['stopName'] : null;

        if ($stopCode == null && $stopName == null) {
            throw new BadRequestException('missing either parameter stopCode or parameter stopName');
        }

        $query = $this->applyLorryFilter($query, $options);
        return $query->where(function (QueryExpression $expression, Query $query) use ($stopCode, $stopName) {
            $conditions = $expression->or_(function ($or) use ($stopCode, $stopName) {
                if ($stopCode != null) {
                    $or = $or->like('Stops.stop_code', '%' . $stopCode . '%');
                }

                if ($stopName != null) {
                    $or = $or->like('Stops.stop_name', '%' . $stopName . '%');
                }

                return $or;
            });

            return $expression->add($conditions);
        });
    }

    /**
     * Custom finder to find a stop by its latitude and longitude within a certain
     * search radius.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByLatLon(Query $query, array $options)
    {
        $refLat = isset($options['query']['refLat']) ? $options['query']['refLat'] : null;
        $refLon = isset($options['query']['refLon']) ? $options['query']['refLon'] : null;
        $refDistance = isset($options['query']['refDistance']) ? $options['query']['refDistance'] : 10;

        if ($refLat == null || !is_numeric($refLat)) {
            throw new BadRequestException('invalid or missing parameter refLat');
        }

        if ($refLon == null || !is_numeric($refLon)) {
            throw new BadRequestException('invalid or missing parameter refLon');
        }

        $query = $this->applyLorryFilter($query, $options);
        $query = $query->select(['stop_distance' => 'SQRT(POW(69.1 * (Stops.stop_lat - ' . $refLat . '), 2) + POW(69.1 * (Stops.stop_lon - ' . $refLon . ') * COS(Stops.stop_lat / 57.3), 2)) * 1.60934'])->select($this);
        $query = $query->having(['stop_distance <= ' => $refDistance]);
        $query = $query->order(['stop_distance']);

        return $query->where(['Stops.location_type' => 1]);
    }

    /**
     * Apply a filter on wheelchair_boarding if there's a corresponding parameter set.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function applyLorryFilter(Query $query, array $options)
    {
        $wheelchairEnabled = isset($options['query']['wheelchairEnabled']) ? $options['query']['wheelchairEnabled'] : null;

        if ($wheelchairEnabled != null) {
            $query = $query->where([
                'Stops.wheelchair_boarding' => $wheelchairEnabled
            ]);
        }

        return $query;
    }
}
