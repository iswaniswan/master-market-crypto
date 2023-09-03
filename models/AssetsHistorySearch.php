<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AssetsHistory;

/**
 * AssetsHistorySearch represents the model behind the search form of `app\models\AssetsHistory`.
 */
class AssetsHistorySearch extends AssetsHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_crypto'], 'integer'],
            [['price_usd', 'time', 'date'], 'safe'],
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

    public function getQuerySearch($params)
    {
        $query = AssetsHistory::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_crypto' => $this->id_crypto,
        ]);

        $query->andFilterWhere(['like', 'price_usd', $this->price_usd])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'date', $this->date]);

        return $query;
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
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
