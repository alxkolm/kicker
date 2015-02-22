<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\FormatConverter;

/**
 * This is the model class for table "game".
 *
 * @property integer $id
 * @property string $date
 * @property integer $scoreA
 * @property integer $scoreB
 * @property integer $teamA_playerA
 * @property integer $teamA_playerB
 * @property integer $teamB_playerC
 * @property integer $teamB_playerD
 * @property integer $playerA_role
 * @property integer $playerB_role
 * @property integer $playerC_role
 * @property integer $playerD_role
 * @property integer $playerA_role_form
 * @property integer $playerB_role_form
 * @property integer $playerC_role_form
 * @property integer $playerD_role_form
 * @property string $modified
 * @property string $created
 *
 * @property User $playerA
 * @property User $playerB
 * @property User $playerC
 * @property User $playerD
 */
class Game extends \yii\db\ActiveRecord
{
    const PLAYER_ROLE_ATTACK       = 1;
    const PLAYER_ROLE_DEFENCE      = 2;
    const PLAYER_ROLE_SHASHLICHNIK = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game}}';
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'modified',
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scoreA', 'scoreB', 'teamA_playerA', 'teamB_playerC'], 'required'],
            [['teamA_playerA', 'teamA_playerB', 'teamB_playerC', 'teamB_playerD'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'date'          => 'Дата',
            'teamA_playerA' => 'Игрок A',
            'teamA_playerB' => 'Игрок B',
            'teamB_playerC' => 'Игрок C',
            'teamB_playerD' => 'Игрок D',
            'modified'      => 'Modified',
            'created'       => 'Created',
        ];
    }



    public function beforeSave($insert)
    {
        if ($insert){
            $this->created = new Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerA()
    {
        return $this->hasOne(User::className(), ['id' => 'teamA_playerA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerB()
    {
        return $this->hasOne(User::className(), ['id' => 'teamA_playerB']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerC()
    {
        return $this->hasOne(User::className(), ['id' => 'teamB_playerC']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerD()
    {
        return $this->hasOne(User::className(), ['id' => 'teamB_playerD']);
    }

    public function getGoals()
    {
        return $this->hasMany(Goal::className(), ['game_id' => 'id']);
    }

    public function scoreGoal($userId, $autogoal = false)
    {
        $goal = new Goal();
        $goal->autogoal = $autogoal;
        $goal->user_id = $userId;
        $goal->game_id = $this->id;
        if ($goal->save(false)){
            $isTeamA = $this->isTeamA($userId);
            if (($isTeamA && !$autogoal) || (!$isTeamA && $autogoal)){
                $this->scoreA += 1;
            } else {
                $this->scoreB += 1;
            }
            $this->save();
        }
        return $goal;
    }

    public function isTeamA($userId)
    {
        return $this->teamA_playerA == $userId
            || $this->teamA_playerB == $userId;
    }
}
