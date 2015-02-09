<?php

use yii\db\Schema;
use yii\db\Migration;

class m150209_184156_create_goal extends Migration
{
    public function up()
    {
        $this->createTable('{{%goal}}', [
            'id'       => Schema::TYPE_BIGPK,
            'user_id'  => Schema::TYPE_INTEGER . ' NOT NULL',
            'game_id'  => Schema::TYPE_INTEGER . ' NOT NULL',
            'autogoal' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'modified' => Schema::TYPE_TIMESTAMP,
            'created'  => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey('goal_user', '{{%goal}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('goal_game', '{{%goal}}', 'game_id', '{{%game}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%goal}}');
    }
}
