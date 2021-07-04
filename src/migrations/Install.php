<?php

namespace jorenvanhee\templateguard\migrations;

use Craft;
use craft\db\Migration;
use jorenvanhee\templateguard\records\LoginAttempt;

/**
 * Install migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(LoginAttempt::tableName(), [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'ipAddress' => $this->string(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createIndex(null, LoginAttempt::tableName(), 'key');
        $this->createIndex(null, LoginAttempt::tableName(), 'ipAddress');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists(LoginAttempt::tableName());
    }
}
