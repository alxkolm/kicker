<?php

use yii\db\Schema;
use yii\db\Migration;

class m150302_073238_alter_user extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}', 'email', Schema::TYPE_STRING . ' NULL DEFAULT NULL');
        $this->createIndex('email', '{{%user}}', 'email', true);
    }

    public function down()
    {
        $this->dropIndex('email', '{{%user}}');
        $this->alterColumn('{{%user}}', 'email', Schema::TYPE_STRING . ' NOT NULL DEFAULT ""');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
