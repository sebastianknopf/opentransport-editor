<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {

        $this->table('acos')
            ->addColumn('parent_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('alias', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('lft', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('rght', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addIndex(
                [
                    'lft',
                    'rght',
                ]
            )
            ->addIndex(
                [
                    'alias',
                ]
            )
            ->create();

        $this->table('agencies', ['id' => false, 'primary_key' => ['agency_id']])
            ->addColumn('agency_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('agency_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('agency_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('agency_timezone', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('agency_lang', 'string', [
                'default' => null,
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('agency_phone', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('agency_fare_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'client_id',
                ]
            )
            ->create();

        $this->table('api_logs')
            ->addColumn('method', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('endpoint', 'string', [
                'default' => null,
                'limit' => 2048,
                'null' => false,
            ])
            ->addColumn('query_params', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => false,
            ])
            ->addColumn('request_data', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => false,
            ])
            ->addColumn('response_code', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
            ])
            ->addColumn('response_data', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => false,
            ])
            ->addColumn('exception', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => false,
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('aros')
            ->addColumn('parent_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('alias', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('lft', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('rght', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addIndex(
                [
                    'lft',
                    'rght',
                ]
            )
            ->addIndex(
                [
                    'alias',
                ]
            )
            ->create();

        $this->table('aros_acos')
            ->addColumn('aro_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('aco_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('_create', 'string', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('_read', 'string', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('_update', 'string', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('_delete', 'string', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addIndex(
                [
                    'aro_id',
                    'aco_id',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'aco_id',
                ]
            )
            ->create();

        $this->table('calendar', ['id' => false, 'primary_key' => ['service_id']])
            ->addColumn('service_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('service_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('start_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('end_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('monday', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('tuesday', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('wednesday', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('thursday', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('friday', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('saturday', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('sunday', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('calendar_dates')
            ->addColumn('service_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('exception_type', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'service_id',
                ]
            )
            ->create();

        $this->table('clients')
            ->addColumn('shortname', 'string', [
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('longname', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'shortname',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('frequencies')
            ->addColumn('trip_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('start_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('end_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('headway_secs', 'integer', [
                'default' => null,
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('exact_times', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'trip_id',
                ]
            )
            ->create();

        $this->table('groups')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('messages')
            ->addColumn('serial', 'string', [
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('level', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('entity_source', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('entity_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('short_message', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('long_message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => null,
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('queue_processes')
            ->addColumn('pid', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('terminate', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('server', 'string', [
                'default' => null,
                'limit' => 90,
                'null' => true,
            ])
            ->addColumn('workerkey', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addIndex(
                [
                    'workerkey',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'pid',
                    'server',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('queued_jobs')
            ->addColumn('job_type', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('job_group', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('reference', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('notbefore', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('fetched', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('completed', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('progress', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('failed', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('failure_message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('workerkey', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('priority', 'integer', [
                'default' => '5',
                'limit' => 3,
                'null' => false,
            ])
            ->create();

        $this->table('routes', ['id' => false, 'primary_key' => ['route_id']])
            ->addColumn('route_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('agency_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('route_short_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('route_long_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('route_desc', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('route_type', 'integer', [
                'default' => null,
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('route_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('route_color', 'string', [
                'default' => null,
                'limit' => 9,
                'null' => true,
            ])
            ->addColumn('route_text_color', 'string', [
                'default' => null,
                'limit' => 9,
                'null' => true,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'agency_id',
                ]
            )
            ->addIndex(
                [
                    'client_id',
                ]
            )
            ->create();

        $this->table('shapes', ['id' => false, 'primary_key' => ['shape_id']])
            ->addColumn('shape_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('shape_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('shape_polyline', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'shape_id',
                ]
            )
            ->create();

        $this->table('stop_times')
            ->addColumn('trip_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('stop_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('arrival_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('departure_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('stop_sequence', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
            ])
            ->addColumn('pickup_type', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('drop_off_type', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'stop_id',
                ]
            )
            ->addIndex(
                [
                    'trip_id',
                ]
            )
            ->create();

        $this->table('stops', ['id' => false, 'primary_key' => ['stop_id']])
            ->addColumn('stop_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('stop_code', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('stop_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('stop_desc', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('stop_lat', 'float', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('stop_lon', 'float', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('location_type', 'string', [
                'default' => '1',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('parent_station', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('platform_code', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('wheelchair_boarding', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'stop_id',
                ]
            )
            ->create();

        $this->table('trips', ['id' => false, 'primary_key' => ['trip_id']])
            ->addColumn('trip_id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('route_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('route_variation_id', 'string', [
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('start_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('end_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('service_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('trip_headsign', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('trip_short_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('direction_id', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('block_id', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('shape_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('wheelchair_accessible', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('bikes_allowed', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('flags', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'client_id',
                ]
            )
            ->addIndex(
                [
                    'route_id',
                ]
            )
            ->addIndex(
                [
                    'service_id',
                ]
            )
            ->addIndex(
                [
                    'shape_id',
                ]
            )
            ->create();

        $this->table('user_settings')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('value', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('users')
            ->addColumn('superuser', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('activated', 'string', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('group_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('client_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'username',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'client_id',
                ]
            )
            ->addIndex(
                [
                    'group_id',
                ]
            )
            ->create();

        $this->table('agencies')
            ->addForeignKey(
                'client_id',
                'clients',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('calendar_dates')
            ->addForeignKey(
                'service_id',
                'calendar',
                'service_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('frequencies')
            ->addForeignKey(
                'trip_id',
                'trips',
                'trip_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('routes')
            ->addForeignKey(
                'agency_id',
                'agencies',
                'agency_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'client_id',
                'clients',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('stop_times')
            ->addForeignKey(
                'stop_id',
                'stops',
                'stop_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'trip_id',
                'trips',
                'trip_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('trips')
            ->addForeignKey(
                'client_id',
                'clients',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'route_id',
                'routes',
                'route_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'service_id',
                'calendar',
                'service_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'shape_id',
                'shapes',
                'shape_id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('user_settings')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('users')
            ->addForeignKey(
                'client_id',
                'clients',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'group_id',
                'groups',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('agencies')
            ->dropForeignKey(
                'client_id'
            )->save();

        $this->table('calendar_dates')
            ->dropForeignKey(
                'service_id'
            )->save();

        $this->table('frequencies')
            ->dropForeignKey(
                'trip_id'
            )->save();

        $this->table('routes')
            ->dropForeignKey(
                'agency_id'
            )
            ->dropForeignKey(
                'client_id'
            )->save();

        $this->table('stop_times')
            ->dropForeignKey(
                'stop_id'
            )
            ->dropForeignKey(
                'trip_id'
            )->save();

        $this->table('trips')
            ->dropForeignKey(
                'client_id'
            )
            ->dropForeignKey(
                'route_id'
            )
            ->dropForeignKey(
                'service_id'
            )
            ->dropForeignKey(
                'shape_id'
            )->save();

        $this->table('user_settings')
            ->dropForeignKey(
                'user_id'
            )->save();

        $this->table('users')
            ->dropForeignKey(
                'client_id'
            )
            ->dropForeignKey(
                'group_id'
            )->save();

        $this->table('acos')->drop()->save();
        $this->table('agencies')->drop()->save();
        $this->table('api_logs')->drop()->save();
        $this->table('aros')->drop()->save();
        $this->table('aros_acos')->drop()->save();
        $this->table('calendar')->drop()->save();
        $this->table('calendar_dates')->drop()->save();
        $this->table('clients')->drop()->save();
        $this->table('frequencies')->drop()->save();
        $this->table('groups')->drop()->save();
        $this->table('messages')->drop()->save();
        $this->table('queue_processes')->drop()->save();
        $this->table('queued_jobs')->drop()->save();
        $this->table('routes')->drop()->save();
        $this->table('shapes')->drop()->save();
        $this->table('stop_times')->drop()->save();
        $this->table('stops')->drop()->save();
        $this->table('trips')->drop()->save();
        $this->table('user_settings')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
