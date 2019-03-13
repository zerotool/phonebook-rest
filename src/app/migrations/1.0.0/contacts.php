<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ContactsMigration_104
 */
class ContactsMigration_100 extends Migration
{
    const TABLE_NAME = 'contacts';

    /**
     * Run the migrations
     */
    public function up()
    {
        $this->morphTable(static::TABLE_NAME, [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'first_name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'last_name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'first_name'
                        ]
                    ),
                    new Column(
                        'phone_number',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 30,
                            'after' => 'last_name'
                        ]
                    ),
                    new Column(
                        'country_code',
                        [
                            'type' => Column::TYPE_CHAR,
                            'size' => 2,
                            'after' => 'phone_number'
                        ]
                    ),
                    new Column(
                        'timezone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 20,
                            'after' => 'country_code'
                        ]
                    ),
                    new Column(
                        'inserted_on',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'default' => 'current_timestamp()',
                            'size' => 1,
                            'after' => 'timezone'
                        ]
                    ),
                    new Column(
                        'updated_on',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'default' => 'current_timestamp()',
                            'size' => 1,
                            'after' => 'inserted_on'
                        ]
                    ),
                    new Column(
                        'deleted',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'size' => 1,
                            'after' => 'updated_on'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('contacts_id_uindex', ['id'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );

        $this->batchInsert(
            static::TABLE_NAME,
            ['first_name', 'last_name', 'phone_number', 'country_code', 'timezone']
        );
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        $this->getConnection()->dropTable(static::TABLE_NAME);
    }
}
