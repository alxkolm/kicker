<?php

use yii\db\Schema;
use yii\db\Migration;

class m150228_140312_user_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'auth_key', Schema::TYPE_STRING . '(32) NOT NULL');
        $this->addColumn('{{%user}}', 'password_hash', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('{{%user}}', 'password_reset_token', Schema::TYPE_STRING);
        $this->addColumn('{{%user}}', 'email', Schema::TYPE_STRING . ' NOT NULL DEFAULT ""');
        $this->addColumn('{{%user}}', 'created_at', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn('{{%user}}', 'updated_at', Schema::TYPE_INTEGER . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'auth_key');
        $this->dropColumn('{{%user}}', 'password_hash');
        $this->dropColumn('{{%user}}', 'password_reset_token');
        $this->dropColumn('{{%user}}', 'email');
        $this->dropColumn('{{%user}}', 'created_at');
        $this->dropColumn('{{%user}}', 'updated_at');

        return true;
    }
}
