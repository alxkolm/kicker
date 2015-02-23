<?php

use yii\db\Schema;
use yii\db\Migration;

class m150223_185124_create_game_user extends Migration
{
    public function up()
    {
        $this->createTable('{{%game_user}}', [
            'id'       => Schema::TYPE_BIGPK,
            'game_id'  => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id'  => Schema::TYPE_INTEGER . ' NOT NULL',
            'position' => 'ENUM("attack", "defense", "attack-defense")',
            'flags'    => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 COMMENT "Битовый флаг. Различные метки о типе игры, например: шашлычник"',
            'modified' => Schema::TYPE_TIMESTAMP,
            'created'  => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey('gu_game', '{{%game_user}}', 'game_id', '{{%game}}', 'id', 'CASCADE');
        $this->addForeignKey('gu_user', '{{%game_user}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->createIndex('position', '{{%game_user}}', 'position');
        $this->createIndex('game_user', '{{%game_user}}', 'game_id, user_id', true);
        $this->createIndex('created', '{{%game_user}}', 'created', true);
    }

    public function down()
    {
        $this->dropTable('{{%game_user}}');
    }
}
