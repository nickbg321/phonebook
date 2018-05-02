<?php

use yii\db\Migration;

/**
 * Class m180428_055732_contact_ddl
 */
class m180428_055732_contact_ddl extends Migration
{
    public function up()
    {
        $this->createTable('contact', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(35)->notNull(),
            'last_name' => $this->string(35)->notNull(),
            'phone_number' => $this->string(15)->notNull(),
            'note' => $this->text(),
        ]);

        $this->createIndex('idx_contact', 'contact', ['first_name', 'last_name', 'phone_number'], true);
    }

    public function down()
    {
        $this->dropTable('contact');
    }
}
