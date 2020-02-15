<?php
use Migrations\AbstractSeed;

/**
 * Acos seed.
 */
class AcosSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'parent_id' => NULL,
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Controllers',
                'lft' => '1',
                'rght' => '10',
            ],
            [
                'id' => '3',
                'parent_id' => '1',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Clients',
                'lft' => '2',
                'rght' => '3',
            ],
            [
                'id' => '4',
                'parent_id' => '1',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Groups',
                'lft' => '4',
                'rght' => '5',
            ],
            [
                'id' => '5',
                'parent_id' => '1',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Users',
                'lft' => '6',
                'rght' => '7',
            ],
            [
                'id' => '6',
                'parent_id' => NULL,
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'DataManagement',
                'lft' => '11',
                'rght' => '16',
            ],
            [
                'id' => '7',
                'parent_id' => '6',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Stops',
                'lft' => '12',
                'rght' => '13',
            ],
            [
                'id' => '8',
                'parent_id' => '6',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Shapes',
                'lft' => '14',
                'rght' => '15',
            ],
            [
                'id' => '9',
                'parent_id' => NULL,
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'TripData',
                'lft' => '17',
                'rght' => '26',
            ],
            [
                'id' => '10',
                'parent_id' => '9',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Services',
                'lft' => '18',
                'rght' => '19',
            ],
            [
                'id' => '11',
                'parent_id' => '9',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Agencies',
                'lft' => '20',
                'rght' => '21',
            ],
            [
                'id' => '12',
                'parent_id' => '9',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Routes',
                'lft' => '22',
                'rght' => '23',
            ],
            [
                'id' => '13',
                'parent_id' => '9',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'Trips',
                'lft' => '24',
                'rght' => '25',
            ],
            [
                'id' => '14',
                'parent_id' => NULL,
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'DataExchange',
                'lft' => '27',
                'rght' => '30',
            ],
            [
                'id' => '15',
                'parent_id' => '14',
                'model' => NULL,
                'foreign_key' => NULL,
                'alias' => 'RESTAPI',
                'lft' => '28',
                'rght' => '29',
            ],
        ];

        $table = $this->table('acos');
        $table->insert($data)->save();
    }
}
