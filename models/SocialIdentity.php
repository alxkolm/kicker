<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "social_identity".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $network
 * @property string $network_user_id
 */
class SocialIdentity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_identity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['network', 'network_user_id'], 'string', 'max' => 255]
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
            'network' => 'Network',
            'network_user_id' => 'Network User ID',
        ];
    }
}
