<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "{{%goal}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $game_id
 * @property integer $autogoal
 * @property string $modified
 * @property string $created
 *
 * @property Game $game
 * @property User $user
 */
class Goal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'game_id'], 'required'],
            [['user_id', 'game_id', 'autogoal'], 'integer'],
            [['modified', 'created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'game_id' => 'Game ID',
            'autogoal' => 'Autogoal',
            'modified' => 'Modified',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeSave($insert)
    {
        if ($insert){
            $this->created = new Expression('NOW()');
        }
        return parent::beforeSave($insert);
    }
}
