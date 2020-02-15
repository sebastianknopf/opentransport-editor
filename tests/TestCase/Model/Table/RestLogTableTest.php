<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ApiLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RestLogTable Test Case
 */
class RestLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ApiLogsTable
     */
    public $RestLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Log'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Log') ? [] : ['className' => ApiLogsTable::class];
        $this->RestLog = TableRegistry::getTableLocator()->get('Log', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RestLog);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
