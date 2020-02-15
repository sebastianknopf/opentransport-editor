<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StopsFixture
 */
class StopsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'stop_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'stop_code' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'stop_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'stop_desc' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null],
        'stop_lat' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'stop_lon' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'location_type' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => '1', 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'parent_station' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'wheelchair_boarding' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => '0', 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'stop_id' => ['type' => 'unique', 'columns' => ['stop_id'], 'length' => []],
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
                'stop_id' => 'Lorem ipsum dolor sit amet',
                'stop_code' => 'Lorem ipsum dolor sit amet',
                'stop_name' => 'Lorem ipsum dolor sit amet',
                'stop_desc' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'stop_lat' => 1,
                'stop_lon' => 1,
                'location_type' => 'Lorem ipsum dolor sit amet',
                'parent_station' => 'Lorem ipsum dolor sit amet',
                'wheelchair_boarding' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
