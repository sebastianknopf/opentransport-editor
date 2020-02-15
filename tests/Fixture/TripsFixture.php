<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TripsFixture
 */
class TripsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'client_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'route_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'service_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'trip_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'trip_headsign' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'trip_short_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'direction_id' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'block_id' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'shape_id' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'wheelchair_accessible' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => '0', 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'client_id' => ['type' => 'index', 'columns' => ['client_id'], 'length' => []],
            'route_id' => ['type' => 'index', 'columns' => ['route_id'], 'length' => []],
            'service_id' => ['type' => 'index', 'columns' => ['service_id'], 'length' => []],
            'shape_id' => ['type' => 'index', 'columns' => ['shape_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'trip_id' => ['type' => 'unique', 'columns' => ['trip_id'], 'length' => []],
            'trips_ibfk_1' => ['type' => 'foreign', 'columns' => ['client_id'], 'references' => ['clients', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'trips_ibfk_2' => ['type' => 'foreign', 'columns' => ['route_id'], 'references' => ['routes', 'route_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'trips_ibfk_3' => ['type' => 'foreign', 'columns' => ['service_id'], 'references' => ['calendar', 'service_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'trips_ibfk_4' => ['type' => 'foreign', 'columns' => ['shape_id'], 'references' => ['shapes', 'shape_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_german2_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'client_id' => 1,
                'route_id' => 'Lorem ipsum dolor sit amet',
                'service_id' => 'Lorem ipsum dolor sit amet',
                'trip_id' => 'Lorem ipsum dolor sit amet',
                'trip_headsign' => 'Lorem ipsum dolor sit amet',
                'trip_short_name' => 'Lorem ipsum dolor sit amet',
                'direction_id' => 'Lorem ipsum dolor sit amet',
                'block_id' => 'Lorem ipsum dolor sit amet',
                'shape_id' => 'Lorem ipsum dolor sit amet',
                'wheelchair_accessible' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
