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
    public $dateInput;
    public $dateInputTimestamp;

    public $playerA_role_form;
    public $playerB_role_form;
    public $playerC_role_form;
    public $playerD_role_form;

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
            [['dateInput'], 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'dateInputTimestamp', 'except' => ['track']],
            [['dateInput'], 'required', 'except' => ['track']],
            [['scoreA', 'scoreB', 'teamA_playerA', 'teamB_playerC'], 'required'],
            [['teamA_playerA', 'teamA_playerB', 'teamB_playerC', 'teamB_playerD'], 'integer'],
            [['playerA_role_form', 'playerB_role_form', 'playerC_role_form', 'playerD_role_form'], 'safe']
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

    public function afterFind()
    {
        // Преобразуем битовый флаг в массив для формы
        foreach (['A', 'B', 'C', 'D'] as $letter){
            $fieldForm  = "player{$letter}_role_form";
            $fieldModel = "player{$letter}_role";
            $rest = (int)$this->$fieldModel;
            $result = [];
            $bit = 1;
            while ($rest != 0) {
                if ($rest & $bit){
                    $result[] = $bit;
                }
                $rest = $rest & (~$bit);
                $bit = $bit << 1;
            }
            $this->$fieldForm = $result;
        }
        // Преобразуем дату для формы
        $this->dateInput = date('d.m.Y', strtotime($this->date));
    }

    public function beforeSave($insert)
    {
        if ($insert){
            $this->created = new Expression('NOW()');
        }

        if (!empty($this->dateInput)){
            // Применяем дату из формы

            $this->date = Yii::$app->formatter->asDate($this->dateInputTimestamp, 'yyyy-MM-dd');
        }

        // Конвертим выбраные чекбоксы в битовый флаг
        foreach (['A', 'B', 'C', 'D'] as $letter){
            $fieldForm  = "player{$letter}_role_form";
            $fieldModel = "player{$letter}_role";
            if (is_array($this->$fieldForm)){
                $this->$fieldModel = array_reduce($this->$fieldForm, [$this, 'bitwiseOr'], 0);
            }
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

    public function bitwiseOr($a, $b)
    {
        return $a | $b;
    }
}
