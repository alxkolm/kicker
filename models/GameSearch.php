<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Game;

/**
 * GameSearch represents the model behind the search form about `app\models\Game`.
 */
class GameSearch extends Game
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'teamA_playerA', 'teamA_playerB', 'teamB_playerC', 'teamB_playerD'], 'integer'],
            [['date', 'modified', 'created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Game::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'teamA_playerA' => $this->teamA_playerA,
            'teamA_playerB' => $this->teamA_playerB,
            'teamB_playerC' => $this->teamB_playerC,
            'teamB_playerD' => $this->teamB_playerD,
            'modified' => $this->modified,
            'created' => $this->created,
        ]);

        return $dataProvider;
    }
}
