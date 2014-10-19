<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 *
 * @property Game[] $games
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'required'],
            [['firstname', 'lastname'], 'string', 'max' => 255],
            [['firstname', 'lastname'], 'unique', 'targetAttribute' => ['firstname', 'lastname'], 'message' => 'The combination of Firstname and Lastname has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::className(), ['teamB_defender' => 'id']);
    }

    public function getFullname()
    {
        $names = [$this->lastname];
        if (!empty($this->firstname)){
            $names[] = $this->firstname;
        }
        return implode(' ', $names);
    }

    public static function listData()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'fullname');
    }
}
