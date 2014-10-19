<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "game".
 *
 * @property integer $id
 * @property string $date
 * @property integer $teamA_defender
 * @property integer $teamA_forward
 * @property integer $teamB_defender
 * @property integer $teamB_forward
 * @property string $modified
 * @property string $created
 *
 * @property User $teamBForward
 * @property User $teamADefender
 * @property User $teamAForward
 * @property User $teamBDefender
 */
class Game extends \yii\db\ActiveRecord
{
    public $dateInput;
    public $dateInputTimestamp;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game';
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
            [['dateInput'], 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'dateInputTimestamp'],
            [['dateInput', 'scoreA', 'scoreB', 'teamA_defender', 'teamB_defender'], 'required'],
            [['teamA_defender', 'teamA_forward', 'teamB_defender', 'teamB_forward'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'date'           => 'Дата',
            'teamA_defender' => 'Защитник',
            'teamA_forward'  => 'Нападающий',
            'teamB_defender' => 'Защитник',
            'teamB_forward'  => 'Нападающий',
            'modified'       => 'Modified',
            'created'        => 'Created',
        ];
    }

    public function beforeSave($insert)
    {
        if (!empty($this->dateInput)){
            // Применяем дату из формы
            $this->date = date('Y-m-d', $this->dateInputTimestamp);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamBForward()
    {
        return $this->hasOne(User::className(), ['id' => 'teamB_forward']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamADefender()
    {
        return $this->hasOne(User::className(), ['id' => 'teamA_defender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamAForward()
    {
        return $this->hasOne(User::className(), ['id' => 'teamA_forward']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamBDefender()
    {
        return $this->hasOne(User::className(), ['id' => 'teamB_defender']);
    }
}
