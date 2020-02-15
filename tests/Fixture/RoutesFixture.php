<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoutesFixture
 */
class RoutesFixture extends TestFixture
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
        'agency_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'route_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'route_short_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'route_long_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'route_desc' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null],
        'route_type' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'route_url' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'route_color' => ['type' => 'string', 'length' => 9, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'route_text_color' => ['type' => 'string', 'length' => 9, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'client_id' => ['type' => 'index', 'columns' => ['client_id'], 'length' => []],
            'agency_id' => ['type' => 'index', 'columns' => ['agency_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'route_id' => ['type' => 'unique', 'columns' => ['route_id'], 'length' => []],
            'routes_ibfk_1' => ['type' => 'foreign', 'columns' => ['client_id'], 'references' => ['clients', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'routes_ibfk_2' => ['type' => 'foreign', 'columns' => ['agency_id'], 'references' => ['agencies', 'agency_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'agency_id' => 'Lorem ipsum dolor sit amet',
                'route_id' => 'Lorem ipsum dolor sit amet',
                'route_short_name' => 'Lorem ipsum dolor sit amet',
                'route_long_name' => 'Lorem ipsum dolor sit amet',
                'route_desc' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'route_type' => 'Lorem ipsum dolor sit amet',
                'route_url' => 'Lorem ipsum dolor sit amet',
                'route_color' => 'Lorem i',
                'route_text_color' => 'Lorem i'
            ],
        ];
        parent::init();
    }
}
