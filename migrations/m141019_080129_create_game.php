<?php

use yii\db\Schema;
use yii\db\Migration;

class m141019_080129_create_game extends Migration
{
    public function up()
    {
        $this->createTable('{{%game}}', [
            'id'             => Schema::TYPE_PK,
            'date'           => Schema::TYPE_DATE . ' NOT NULL',
            'scoreA'         => Schema::TYPE_SMALLINT . ' NOT NULL',
            'scoreB'         => Schema::TYPE_SMALLINT . ' NOT NULL',
            'teamA_defender' => Schema::TYPE_INTEGER . ' NOT NULL',
            'teamA_forward'  => Schema::TYPE_INTEGER . '  NULL DEFAULT NULL',
            'teamB_defender' => Schema::TYPE_INTEGER . ' NOT NULL',
            'teamB_forward'  => Schema::TYPE_INTEGER . '  NULL DEFAULT NULL',
            'modified'       => Schema::TYPE_TIMESTAMP,
            'created'        => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('date', '{{%game}}', 'date');
        $this->addForeignKey('teamA_defender', '{{%game}}', 'teamA_defender', 'user', 'id', 'CASCADE');
        $this->addForeignKey('teamA_forward',  '{{%game}}', 'teamA_forward', 'user', 'id', 'CASCADE');
        $this->addForeignKey('teamB_defender', '{{%game}}', 'teamB_defender', 'user', 'id', 'CASCADE');
        $this->addForeignKey('teamB_forward',  '{{%game}}', 'teamB_forward', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%game}}');
    }
}
