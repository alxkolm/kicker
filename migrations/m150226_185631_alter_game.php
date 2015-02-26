<?php

use yii\db\Schema;
use yii\db\Migration;

class m150226_185631_alter_game extends Migration
{
    public function up()
    {
        $this->dropForeignKey('teamA_playerA_user', '{{%game}}');
        $this->dropForeignKey('teamA_playerB_user', '{{%game}}');
        $this->dropForeignKey('teamB_playerC_user', '{{%game}}');
        $this->dropForeignKey('teamB_playerD_user', '{{%game}}');
        $this->dropColumn('{{%game}}', 'teamA_playerA');
        $this->dropColumn('{{%game}}', 'playerA_role');
        $this->dropColumn('{{%game}}', 'teamA_playerB');
        $this->dropColumn('{{%game}}', 'playerB_role');
        $this->dropColumn('{{%game}}', 'teamB_playerC');
        $this->dropColumn('{{%game}}', 'playerC_role');
        $this->dropColumn('{{%game}}', 'teamB_playerD');
        $this->dropColumn('{{%game}}', 'playerD_role');
    }

    public function down()
    {

    }
}
