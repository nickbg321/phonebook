<?php

use yii\db\Migration;

/**
 * Class m180429_134007_user_dml
 */
class m180429_134007_user_dml extends Migration
{
    public function up()
    {
        $this->insert('user', [
            'id' => 1,
            'username' => 'admin',
            'auth_key' => 'ltO9IDpnr9lKXImnIKE_y7jTdhhBUIvi',
            'password_hash' => '$2y$13$t5SJklUWk6Pc5AGUqmb9X.D5FZ8jxV2c4skfA3rLteyUho2.vZhW2',
        ]);
    }

    public function down()
    {
        $this->delete('user', ['id' => 1]);
    }
}
