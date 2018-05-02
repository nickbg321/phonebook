<?php

use yii\db\Migration;

/**
 * Class m180429_133732_user_ddl
 */
class m180429_133732_user_ddl extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
        ]);

        $this->createIndex('idx_user_username', 'user', 'username');
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
