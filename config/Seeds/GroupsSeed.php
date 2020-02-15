<?php
use Migrations\AbstractSeed;

/**
 * Groups seed.
 */
class GroupsSeed extends AbstractSeed
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
                'name' => 'DefaultGroup',
                'created' => NULL,
                'modified' => NULL,
            ],
        ];

        $table = $this->table('groups');
        $table->insert($data)->save();
    }
}
