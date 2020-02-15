<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\Date;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Trips Model
 *
 * @property \App\Model\Table\ClientsTable|\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\RoutesTable|\Cake\ORM\Association\BelongsTo $Routes
 * @property \App\Model\Table\CalendarTable|\Cake\ORM\Association\BelongsTo $Calendar
 * @property \App\Model\Table\ShapesTable|\Cake\ORM\Association\BelongsTo $Shapes
 * @property \App\Model\Table\StopTimesTable|\Cake\ORM\Association\HasMany $StopTimes
 *
 * @method \App\Model\Entity\Trip get($primaryKey, $options = [])
 * @method \App\Model\Entity\Trip newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Trip[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Trip|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Trip saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Trip patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Trip[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Trip findOrCreate($search, callable $callback = null, $options = [])
 */
class TripsTable extends Table
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

        $this->setTable('trips');
        $this->setDisplayField('id');
        $this->setPrimaryKey('trip_id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Duplicatable.Duplicatable', [
            'finder' => 'all',
            'contain' => [
                'StopTimes',
                'Frequencies'
            ],
            'remove' => ['created']
        ]);

        // search behaviour
        $this->addBehavior('Search.Search');
        $this->searchManager()
            ->value('route_variation_id')                   // for inline route view
            ->value('direction_id')                         // for inline route view and list-index
            ->value('service_id')                           // for inline route view and list-index
            ->value('trip_id')                              // for filter in list-index
            ->value('route_id')                             // for filter in list-index
            ->add('trip_short_name', 'Search.Like', [       // for filter in list-index
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['trip_short_name']
            ])
            ->add('trip_headsign', 'Search.Like', [         // for filter in list-index
                'before' => true,
                'after' => true,
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'field' => ['trip_headsign']
            ]);

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Routes', [
            'foreignKey' => 'route_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Services', [
            'foreignKey' => 'service_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Shapes', [
            'foreignKey' => 'shape_id'
        ]);

        $this->hasMany('StopTimes', [
            'dependent' => true,
            'foreignKey' => 'trip_id',
            'order' => 'StopTimes.stop_sequence'
        ]);

        $this->hasMany('Frequencies', [
            'dependent' => true,
            'foreignKey' => 'trip_id'
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
            ->scalar('trip_headsign')
            ->maxLength('trip_headsign', 255)
            ->requirePresence('trip_headsign', 'create')
            ->allowEmptyString('trip_headsign', false);

        $validator
            ->scalar('trip_short_name')
            ->maxLength('trip_short_name', 255)
            ->requirePresence('trip_short_name', 'create')
            ->allowEmptyString('trip_short_name');

        $validator
            ->scalar('wheelchair_accessible')
            ->allowEmptyString('wheelchair_accessible', false);

        $validator
            ->scalar('bikes_allowed')
            ->allowEmptyString('bikes_allowed', false);

        $validator
            ->integer('direction_id')
            ->allowEmptyString('direction_id', false, __('Please select a valid direction id!'));

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
        $rules->add($rules->existsIn(['route_id'], 'Routes'));
        $rules->add($rules->existsIn(['service_id'], 'Services'));
        $rules->add($rules->existsIn(['shape_id'], 'Shapes'));

        return $rules;
    }

    /**
     * Before save method.
     * Apply stop_sequence for each StopTime here.
     *
     * @param $event
     * @param $entity The entity to be modified.
     * @param $options
     */
    public function beforeSave($event, $entity, $options)
    {
        // set stop sequence
        for ($s = 0; $s < count($entity->stop_times); $s++) {
            $entity->stop_times[$s]->stop_sequence = ($s + 1);
        }

        if (isset($entity->stop_times) && count($entity->stop_times) > 0) {
            // start & end time for sorting purposes
            $entity->start_time = $entity->stop_times[0]->departure_time;
            $entity->end_time = $entity->stop_times[count($entity->stop_times) - 1]->arrival_time;

            // calculate route variation id
            $stop_succession = [];
            foreach ($entity->stop_times as $stop_time) {
                array_push($stop_succession, $stop_time->stop_id);
            }

            $entity->route_variation_id = md5(implode('#', $stop_succession));
        }
    }

    /**
     * BeforeFind method - Set a default order when no order is specified.
     *
     * @param $event The event
     * @param $query The current query object
     * @param $options The current options
     * @param $primary Unknown
     */
    public function beforeFind ($event, $query, $options, $primary)
    {
        $order = $query->clause('order');
        if ($order === null || !count($order)) {
            $query->order(['Trips.start_time']);
        }
    }

    /**
     * Custom finder to find a trip with all detail information by its tripId.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByTripId(Query $query, array $options)
    {
        $trip_id = isset($options['query']['tripId']) ? $options['query']['tripId'] : null;

        if ($trip_id == null) {
            throw new BadRequestException('invalid or missing parameter tripId');
        }

        return $query->where(['Trips.trip_id' => $trip_id])->contain([
            'Services' => [
                'ServiceExceptions'
            ],
            'Routes' => [
                'Agencies'
            ],
            'StopTimes' => [
                'Stops'
            ],
            'Frequencies',
            'Shapes'
        ]);
    }

    /**
     * Custom finder to find all trips with basic information by their routeId.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByRouteId(Query $query, array $options)
    {
        $route_id = isset($options['query']['routeId']) ? $options['query']['routeId'] : null;

        if($route_id == null) {
            throw new BadRequestException('invalid or missing parameter routeId');
        }

        $query = $this->applyDateFilter($query, $options);
        $query = $this->applyLorryFilter($query, $options);
        return $query->where(['Trips.route_id' => $route_id])->contain([
            'Routes' => [
                'Agencies'
            ],
            'StopTimes' => [
                'Stops'
            ],
            'Frequencies'
        ]);
    }

    /**
     * Custom finder to find all trips with basic information by their serviceId.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByServiceId(Query $query, array $options)
    {
        $service_id = isset($options['query']['serviceId']) ? $options['query']['serviceId'] : null;

        if ($service_id == null) {
            throw new BadRequestException('invalid or missing parameter serviceId');
        }

        $query = $this->applyDateFilter($query, $options);
        $query = $this->applyLorryFilter($query, $options);

        return $query->where(['Trips.service_id' => $service_id])->contain([
            'Routes' => [
                'Agencies'
            ],
            'StopTimes' => [
                'Stops'
            ],
            'Frequencies'
        ]);
    }

    /**
     * Custom finder to find all trips departing/arriving at a certain stop with basic information.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    public function findByStopId(Query $query, array $options)
    {
        $stop_id = isset($options['query']['stopId']) ? $options['query']['stopId'] : null;
        $arrivals = isset($options['query']['arrivals']) ? $options['query']['arrivals'] : '1';
        $departures = isset($options['query']['departures']) ? $options['query']['departures'] : '1';

        if ($stop_id == null) {
            throw new BadRequestException('invalid or missing parameter stopId');
        }

        // expand from *STATION* id to all *STOP* ids
        // difference here: Normally a trip is not scheduled at a station, but scheduled at
        // a single stop of the station. So if $stop_id is a station-id, it must be expanded
        // to all stop-ids to match every trip departing here.
        $stops_table = TableRegistry::getTableLocator()->get('Stops');
        $stops = $stops_table->find('all')->where([
            'Stops.parent_station' => $stop_id
        ])->select([
            'Stops.stop_id'
        ]);

        // extract only stop ids as array and merge with the existing stop_id
        $stop_ids = Hash::extract($stops->toArray(), '{n}.stop_id');
        $stop_ids = array_merge($stop_ids, [$stop_id]);

        // select trips matching the desired stop ids
        // also take a look whether only arrivals or departures should be selected
        $query = $query->matching('StopTimes', function (Query $query) use ($stop_ids, $arrivals, $departures) {
            $query = $query->where(['StopTimes.stop_id' => $stop_ids], ['StopTimes.stop_id' => 'integer[]']);

            if ($arrivals == '0') {
                $query = $query->where(['StopTimes.pickup_type IS NOT' => 1]);
            }

            if ($departures == '0') {
                $query = $query->where(['StopTimes.drop_off_type IS NOT' => 1]);
            }

            return $query;
        });

        $query = $this->applyDateFilter($query, $options);
        $query = $this->applyLorryFilter($query, $options);

        return $query->contain([
            'Routes' => [
                'Agencies'
            ],
            'StopTimes' => [
                'Stops'
            ],
            'Frequencies'
        ]);
    }

    /**
     * Apply a filter on wheelchair_accessible and bikes_allowed if there's a corresponding parameter set.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    private function applyDateFilter(Query $query, array $options)
    {
        if(!isset($options['query']['date'])) {
            return $query;
        }

        $date = Date::createFromFormat('Y-m-d', $options['query']['date']);
        if ($date == null) {
            throw new BadRequestException('invalid parameter date');
        }

        $servicesTable = TableRegistry::getTableLocator()->get('Services');
        $serviceIds = $servicesTable->find('all')->where([
            'Services.start_date <=' => $date,
            'Services.end_date >=' => $date,
            'Services.' . strtolower($date->format('l')) => '1'
        ])->combine('{n}', 'service_id');

        $exceptionsTable = TableRegistry::getTableLocator()->get('ServiceExceptions');
        $additionalIds = $exceptionsTable->find()->where([
            'ServiceExceptions.date' => $date,
            'ServiceExceptions.exception_type' => 1
        ])->combine('{n}', 'service_id');

        $exceptionalIds = $exceptionsTable->find()->where([
            'ServiceExceptions.date' => $date,
            'ServiceExceptions.exception_type' => 2
        ])->combine('{n}', 'service_id');

        $serviceIds = $serviceIds->toArray();

        $serviceIds = array_merge($serviceIds, $additionalIds->toArray());
        $serviceIds = array_diff($serviceIds, $exceptionalIds->toArray());

        if (!count($serviceIds)) {
            $serviceIds = [0];
        }

        return $query->where(['Trips.service_id' => $serviceIds], ['Trips.service_id' => 'integer[]']);
    }

    /**
     * Apply a filter on wheelchair_accessible and bikes_allowed if there's a corresponding parameter set.
     *
     * @param Query $query The query to modify.
     * @param array $options The passed parameters.
     * @return Query Query modified based on passed parameters.
     */
    private function applyLorryFilter(Query $query, array $options)
    {
        $wheelchair_enabled = isset($options['query']['wheelchairEnabled']) ? $options['query']['wheelchairEnabled'] : null;
        $bikes_enabled = isset($options['query']['bikesEnabled']) ? $options['query']['bikesEnabled'] : null;

        if($wheelchair_enabled != null) {
            $query = $query->where([
                'Trips.wheelchair_accessible' => $wheelchair_enabled
            ]);
        }

        if($bikes_enabled != null) {
            $query = $query->where([
                'Trips.bikes_allowed' => $bikes_enabled
            ]);
        }

        return $query;
    }
}
