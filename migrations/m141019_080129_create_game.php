<?php

use yii\db\Schema;
use yii\db\Migration;

class m141019_080129_create_game extends Migration
{
    public function up()
    {
        $this->createTable('{{%game}}', [
            'id'            => Schema::TYPE_PK,
            'date'          => Schema::TYPE_DATE .     ' NOT NULL',
            'scoreA'        => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'scoreB'        => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'teamA_playerA' => Schema::TYPE_INTEGER .  ' NULL DEFAULT NULL',
            'teamA_playerB' => Schema::TYPE_INTEGER .  ' NULL DEFAULT NULL',
            'teamB_playerC' => Schema::TYPE_INTEGER .  ' NULL DEFAULT NULL',
            'teamB_playerD' => Schema::TYPE_INTEGER .  ' NULL DEFAULT NULL',
            'playerA_role'  => Schema::TYPE_SMALLINT . ' NULL DEFAULT NULL',
            'playerB_role'  => Schema::TYPE_SMALLINT . ' NULL DEFAULT NULL',
            'playerC_role'  => Schema::TYPE_SMALLINT . ' NULL DEFAULT NULL',
            'playerD_role'  => Schema::TYPE_SMALLINT . ' NULL DEFAULT NULL',
            'modified'      => Schema::TYPE_TIMESTAMP,
            'created'       => Schema::TYPE_TIMESTAMP .' NOT NULL',
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('date', '{{%game}}', 'date');
        $this->createIndex('playerA_role', '{{%game}}', 'playerA_role');
        $this->createIndex('playerB_role', '{{%game}}', 'playerB_role');
        $this->createIndex('playerC_role', '{{%game}}', 'playerC_role');
        $this->createIndex('playerD_role', '{{%game}}', 'playerD_role');
        $this->addForeignKey('teamA_playerA', '{{%game}}', 'teamA_playerA', 'user', 'id', 'CASCADE');
        $this->addForeignKey('teamA_playerB', '{{%game}}', 'teamA_playerB', 'user', 'id', 'CASCADE');
        $this->addForeignKey('teamB_playerC', '{{%game}}', 'teamB_playerC', 'user', 'id', 'CASCADE');
        $this->addForeignKey('teamB_playerD', '{{%game}}', 'teamB_playerD', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%game}}');
    }
}
