<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180421_194107_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('User', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->unique()->notNull(),
            'name' => $this->string(),
            'password' => $this->string(60)->notNull(),
            'access_token' => $this->string(32),
            'token_expires_at' => $this->integer(10),
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
        ]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('User');
        return true;
    }
}
