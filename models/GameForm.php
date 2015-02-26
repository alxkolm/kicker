<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\FormatConverter;


class GameForm extends Game
{
    public $playerA_form;
    public $playerB_form;
    public $playerC_form;
    public $playerD_form;
    public $players_form = [];

    public $dateInput;
    public $dateInputTimestamp;

    public $playerA_role_form = 0;
    public $playerB_role_form = 0;
    public $playerC_role_form = 0;
    public $playerD_role_form = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateInput'], 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'dateInputTimestamp', 'except' => ['track']],
            [['dateInput'], 'required', 'except' => ['track']],
            [['scoreA', 'scoreB'], 'required'],
            [['playerA_role_form', 'playerB_role_form', 'playerC_role_form', 'playerD_role_form'], 'safe'],
            ['players_form', 'validatePlayers'],
            ['players_form', 'validateDistinctPlayers'],
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
            'playerA_role_form' => 'Роль',
            'playerB_role_form' => 'Роль',
            'playerC_role_form' => 'Роль',
            'playerD_role_form' => 'Роль',
            'modified'      => 'Modified',
            'created'       => 'Created',
        ];
    }

    public function afterFind()
    {
        // Преобразуем битовый флаг в массив для формы
        foreach (['A', 'B', 'C', 'D'] as $letter){
            $fieldUser = "player{$letter}";
            $user = $this->$fieldUser;
            if ($user === null){
                continue;
            }
            $rest = (int)$user->flags;
            $result = [];
            $bit = 1;
            while ($rest != 0) {
                if ($rest & $bit){
                    $result[] = $bit;
                }
                $rest = $rest & (~$bit);
                $bit = $bit << 1;
            }
            $this->players_form[$letter] = $result;
        }
        // Преобразуем дату для формы
        $this->dateInput = date('d.m.Y', strtotime($this->date));

        parent::afterFind();
    }

    public function beforeSave($insert)
    {

        if (!empty($this->dateInput)){
            // Применяем дату из формы

            $this->date = Yii::$app->formatter->asDate($this->dateInputTimestamp, 'yyyy-MM-dd');
        }

        // Конвертим выбраные чекбоксы в битовый флаг
//        foreach (['A', 'B', 'C', 'D'] as $letter){
//            $fieldForm  = "player{$letter}_role_form";
//            $fieldModel = "player{$letter}_role";
//            $this->$fieldModel = (is_array($this->$fieldForm)) ? array_reduce($this->$fieldForm, [$this, 'bitwiseOr'], 0) : 0;
//        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $transaction = Yii::$app->db->beginTransaction();
//        try {
            // Обновляем связи
            if ($insert){
                // Создаем связи
                foreach ($this->players_form as $letter => $userId){
                    if (empty($userId)){
                        continue;
                    }
                    $fieldForm  = "player{$letter}_role_form";
                    $model = new GameUser([
                        'team'     => in_array($letter, ['A', 'B']) ? self::TEAM_A : self::TEAM_B,
                        'position' => in_array($letter, ['A', 'C']) ? self::POSITION_ATTACK : self::POSITION_DEFENSE,
                        'flags'    => (is_array($this->$fieldForm)) ? array_reduce($this->$fieldForm, [$this, 'bitwiseOr'], 0) : 0,
                        'game_id'  => $this->id,
                        'user_id'  => $userId,
                    ]);
                    if (!$model->save()){
                        throw new Exception(json_encode($model->errors));
                    }
                }
            } else {
                // TODO обновление связей
                throw new Exception('Not implemented');
            }
            $transaction->commit();
//        } catch (\Exception $e){
//            $transaction->rollBack();
//        }
    }

    public function bitwiseOr($a, $b)
    {
        return $a | $b;
    }

    public static function roles()
    {
        return [
            Game::PLAYER_ROLE_ATTACK       => 'Нападение',
            Game::PLAYER_ROLE_DEFENCE      => 'Защита',
            Game::PLAYER_ROLE_SHASHLICHNIK => 'Шашлычник',
        ];
    }

    /**
     * Проверяем что выбрано хотябы по одному игроку
     * @param $attribute
     * @param $params
     */
    public function validatePlayers($attribute, $params)
    {
        if (empty($this->players_form['A']) || empty($this->players_form['C'])){
            $this->addError('players_form[A]', 'Выберите хотябы по одному игроку в каждой команде.');
        }
    }

    /**
     * Все игроки должны быть разными
     * @param $attribute
     * @param $params
     */
    public function validateDistinctPlayers($attribute, $params)
    {
        $players = $this->players_form;

        // Убираем пустые
        $players = array_filter($players, function ($a) {return !empty($a);});

        if (count(array_unique($players)) != count($players)){
            // TODO Установить ошибку на правиьлное поле с дублирующимся игроком
            $this->addError('players_form[A]', 'Игрок не может играть на нескольких позициях одновременно.');
        }
    }
}
