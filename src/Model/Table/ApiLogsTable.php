<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ApiLog Model
 *
 * @method \App\Model\Entity\ApiLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ApiLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ApiLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ApiLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ApiLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ApiLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ApiLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ApiLog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ApiLogsTable extends Table
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

        $this->setTable('api_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('method')
            ->maxLength('method', 10)
            ->requirePresence('method', 'create')
            ->allowEmptyString('method', false);

        $validator
            ->scalar('endpoint')
            ->maxLength('endpoint', 2048)
            ->requirePresence('endpoint', 'create')
            ->allowEmptyString('endpoint', false);

        $validator
            ->scalar('request_data')
            ->maxLength('request_data', 4294967295)
            ->requirePresence('request_data', 'create')
            ->allowEmptyString('request_data', true);

        $validator
            ->integer('response_code')
            ->requirePresence('response_code', 'create')
            ->allowEmptyString('response_code', false);

        $validator
            ->scalar('response_data')
            ->maxLength('response_data', 4294967295)
            ->requirePresence('response_data', 'create')
            ->allowEmptyString('response_data', true);

        $validator
            ->scalar('exception')
            ->maxLength('exception', 4294967295)
            ->requirePresence('exception', 'create')
            ->allowEmptyString('exception', true);

        return $validator;
    }

    /**
     * Find date related number of success requests.
     *
     * @param Query $query The query to apply finder logic.
     * @param array $options Options to be passed by user.
     * @return Query The query modified depending on required settings.
     */
    public function findCountSuccessRequests(Query $query, array $options)
    {
        $groupByDate = isset($options['groupByDate']) ? $options['groupByDate'] : false;

        if($groupByDate) {
            return $query->select([
                'date' => 'CAST(ApiLogs.created AS DATE)',
                'count' => 'COUNT(*)'
            ])->where([
                'ApiLogs.response_code' => 200
            ])->group([
                'CAST(date AS DATE)'
            ])->order([
                'date'
            ]);
        } else {
            return $query->select([
                'count' => 'COUNT(*)'
            ])->where([
                'ApiLogs.response_code' => 200
            ]);
        }
    }

    /**
     * Find date related number of error requests.
     *
     * @param Query $query The query to apply finder logic.
     * @param array $options Options to be passed by user.
     * @return Query The query modified depending on required settings.
     */
    public function findCountErrorRequests(Query $query, array $options)
    {
        $groupByDate = isset($options['groupByDate']) ? $options['groupByDate'] : false;

        if($groupByDate) {
            return $query->select([
                'date' => 'CAST(ApiLogs.created AS DATE)',
                'count' => 'COUNT(*)'
            ])->where([
                'ApiLogs.response_code <>' => 200
            ])->group([
                'CAST(date AS DATE)'
            ])->order([
                'date'
            ]);
        } else {
            return $query->select([
                'count' => 'COUNT(*)'
            ])->where([
                'ApiLogs.response_code <>' => 200
            ]);
        }
    }
}
