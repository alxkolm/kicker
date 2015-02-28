<?php

use yii\db\Schema;
use yii\db\Migration;

class m150228_190214_alter_user extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%user}}', 'created_at');
        $this->dropColumn('{{%user}}', 'updated_at');
        $this->addColumn('{{%user}}', 'modified', 'timestamp');
        $this->addColumn('{{%user}}', 'created', 'timestamp NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'modified');
        $this->dropColumn('{{%user}}', 'created');
        $this->addColumn('{{%user}}', 'created_at', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn('{{%user}}', 'updated_at', Schema::TYPE_INTEGER . ' NOT NULL');
    }
}
