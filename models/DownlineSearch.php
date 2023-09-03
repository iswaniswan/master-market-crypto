<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Downline;

/**
 * DownlineSearch represents the model behind the search form of `app\models\Downline`.
 */
class DownlineSearch extends Downline
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_member', 'id_sponsor', 'posisi', 'kiri', 'kanan', 'id_upline0'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
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
        $query = Downline::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_member' => $this->id_member,
            'id_sponsor' => $this->id_sponsor,
            'posisi' => $this->posisi,
            'kiri' => $this->kiri,
            'kanan' => $this->kanan,
            'id_upline0' => $this->id_upline0,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
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
