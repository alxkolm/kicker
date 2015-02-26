<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_user".
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $user_id
 * @property string $team
 * @property string $position
 * @property integer $flags
 * @property string $modified
 * @property string $created
 *
 * @property User $user
 * @property Game $game
 */
class GameUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'user_id'], 'required'],
            [['game_id', 'user_id', 'flags'], 'integer'],
            [['team', 'position'], 'string'],
            [['game_id', 'user_id'], 'unique', 'targetAttribute' => ['game_id', 'user_id'], 'message' => 'The combination of Game ID and User ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'game_id'  => 'Game ID',
            'user_id'  => 'User ID',
            'team'     => 'Team',
            'position' => 'Position',
            'flags'    => 'Flags',
            'modified' => 'Modified',
            'created'  => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }
}
