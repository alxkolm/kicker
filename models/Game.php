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
 * @property integer $scoreA
 * @property integer $scoreB
 * @property integer $teamA_playerA
 * @property integer $teamA_playerB
 * @property integer $teamB_playerC
 * @property integer $teamB_playerD
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
    public $dateInput;
    public $dateInputTimestamp;

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
            [['dateInput'], 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'dateInputTimestamp'],
            [['dateInput', 'scoreA', 'scoreB', 'teamA_playerA', 'teamB_playerC'], 'required'],
            [['teamA_playerA', 'teamA_playerB', 'teamB_playerC', 'teamB_playerD'], 'integer']
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
            'teamA_playerA' => 'Защитник',
            'teamA_playerB'  => 'Нападающий',
            'teamB_playerC' => 'Защитник',
            'teamB_playerD'  => 'Нападающий',
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
}
