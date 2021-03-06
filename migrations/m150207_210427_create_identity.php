<?php

use yii\db\Schema;
use yii\db\Migration;

class m150207_210427_create_identity extends Migration
{
    public function up()
    {
        $this->createTable('{{%social_identity}}', [
            'id'              => Schema::TYPE_PK,
            'user_id'         => Schema::TYPE_INTEGER,
            'network'         => Schema::TYPE_STRING,
            'network_user_id' => Schema::TYPE_STRING,
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey('identity_user', '{{%social_identity}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%social_identity}}');
    }
}
