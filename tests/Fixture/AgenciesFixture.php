<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AgenciesFixture
 */
class AgenciesFixture extends TestFixture
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
        'agency_id' => ['type' => 'string', 'length' => 64, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'agency_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'agency_url' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'agency_timezone' => ['type' => 'string', 'length' => 64, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'agency_lang' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'agency_phone' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'agency_fare_url' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_german2_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'client_id' => ['type' => 'index', 'columns' => ['client_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'agency_id' => ['type' => 'unique', 'columns' => ['agency_id'], 'length' => []],
            'agencies_ibfk_1' => ['type' => 'foreign', 'columns' => ['client_id'], 'references' => ['clients', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'agency_name' => 'Lorem ipsum dolor sit amet',
                'agency_url' => 'Lorem ipsum dolor sit amet',
                'agency_timezone' => 'Lorem ipsum dolor sit amet',
                'agency_lang' => 'Lo',
                'agency_phone' => 'Lorem ipsum dolor sit amet',
                'agency_fare_url' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
