<?php

use yii\db\Schema;
use yii\db\Migration;

class m141019_080118_create_user extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id'        => Schema::TYPE_PK,
            'firstname' => Schema::TYPE_STRING . ' NOT NULL',
            'lastname'  => Schema::TYPE_STRING . ' NOT NULL',
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('name', '{{%user}}', 'firstname, lastname', true);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
