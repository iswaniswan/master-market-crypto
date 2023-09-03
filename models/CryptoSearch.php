<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Crypto;

/**
 * CryptoSearch represents the model behind the search form of `app\models\Crypto`.
 */
class CryptoSearch extends Crypto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_asset_coincap', 'harga', 'harga_jual', 'harga_beli', 'status'], 'integer'],
            [['date_created'], 'safe'],
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
        $query = Crypto::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_asset_coincap' => $this->id_asset_coincap,
            'harga' => $this->harga,
            'harga_jual' => $this->harga_jual,
            'harga_beli' => $this->harga_beli,
            'status' => $this->status,
            'date_created' => $this->date_created,
        ]);

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
