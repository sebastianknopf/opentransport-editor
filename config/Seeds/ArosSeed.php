<?php
use Migrations\AbstractSeed;

/**
 * Aros seed.
 */
class ArosSeed extends AbstractSeed
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
                'model' => 'Groups',
                'foreign_key' => '1',
                'alias' => NULL,
                'lft' => '1',
                'rght' => '2',
            ]
        ];

        $table = $this->table('aros');
        $table->insert($data)->save();
    }
}
