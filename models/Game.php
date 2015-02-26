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
 * @property GameUser $gameUserAttackA
 * @property GameUser $gameUserDefenceA
 * @property GameUser $gameUserAttackB
 * @property GameUser $gameUserDefenceB
 */
class Game extends \yii\db\ActiveRecord
{
    // Константы строго степени двойки
    const PLAYER_ROLE_ATTACK       = 1;
    const PLAYER_ROLE_DEFENCE      = 2;
    const PLAYER_ROLE_SHASHLICHNIK = 4;

    const TEAM_A = 'A';
    const TEAM_B = 'B';

    const POSITION_ATTACK  = 'attack';
    const POSITION_DEFENSE = 'defense';
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
            [['scoreA', 'scoreB'], 'required'],
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

    public function getGameUserAttackA(){
        return $this->hasOne(GameUser::className(), ['game_id' => 'id'])
            ->where([
                'team'     => self::TEAM_A,
                'position' => self::POSITION_ATTACK
            ]);
    }

    public function getGameUserDefenceA(){
        return $this->hasOne(GameUser::className(), ['game_id' => 'id'])
            ->where([
                'team'     => self::TEAM_A,
                'position' => self::POSITION_DEFENSE
            ]);
    }

    public function getGameUserAttackB(){
        return $this->hasOne(GameUser::className(), ['game_id' => 'id'])
            ->where([
                'team'     => self::TEAM_B,
                'position' => self::POSITION_ATTACK
            ]);
    }

    public function getGameUserDefenceB(){
        return $this->hasOne(GameUser::className(), ['game_id' => 'id'])
            ->where([
                'team'     => self::TEAM_B,
                'position' => self::POSITION_DEFENSE
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerA()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('gameUserAttackA');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerB()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('gameUserDefenceA');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerC()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('gameUserAttackB');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerD()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('gameUserDefenceB');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGameUsers()
    {
        return $this->hasMany(GameUser::className(), ['game_id' => 'id']);
    }

    /**
     * @return static
     */
    public function getPlayers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->via('gameUsers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoals()
    {
        return $this->hasMany(Goal::className(), ['game_id' => 'id']);
    }

    /**
     * @param $userId
     * @param bool $autogoal
     * @return Goal
     */
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
        return (bool)$this->getGameUsers()->where(['user_id' => $userId, 'team' => 'A'])->count();
    }

    /**
     * @param null $userId
     * @return bool
     */
    public function userInGame($userId = null)
    {
        $userId = $userId === null ? Yii::$app->user->id : $userId;
        return (bool)$this->getGameUsers()->where(['user_id' => $userId])->count();
    }
}
