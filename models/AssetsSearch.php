<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Assets;

/**
 * AssetsSearch represents the model behind the search form of `app\models\Assets`.
 */
class AssetsSearch extends Assets
{
    public $top5;
    public $top20;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['asset_id', 'rank', 'symbol', 'name', 'supply', 'max_supply', 'market_cap_usd', 'volume_usd_24_hr', 'price_usd', 'change_percent_24_hr', 'vwap_24_hr', 'explorer'], 'safe'],
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
        $query = Assets::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'asset_id', $this->asset_id])
            ->andFilterWhere(['like', 'rank', $this->rank])
            ->andFilterWhere(['like', 'symbol', $this->symbol])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'supply', $this->supply])
            ->andFilterWhere(['like', 'max_supply', $this->max_supply])
            ->andFilterWhere(['like', 'market_cap_usd', $this->market_cap_usd])
            ->andFilterWhere(['like', 'volume_usd_24_hr', $this->volume_usd_24_hr])
            ->andFilterWhere(['like', 'price_usd', $this->price_usd])
            ->andFilterWhere(['like', 'change_percent_24_hr', $this->change_percent_24_hr])
            ->andFilterWhere(['like', 'vwap_24_hr', $this->vwap_24_hr])
            ->andFilterWhere(['like', 'explorer', $this->explorer]);

        if ($this->top5 != null and $this->top5 == true) {
            $query->andWhere('CONVERT(rank, SIGNED) <= "5"');
        }

        if ($this->top20 != null and $this->top20 == true) {
            $query->andWhere('CONVERT(rank, SIGNED) <= "20"');
        }

//         $command = $query->createCommand()->getRawSql();
//         var_dump($command); die();

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
