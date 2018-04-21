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
