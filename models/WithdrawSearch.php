<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Withdraw;

/**
 * WithdrawSearch represents the model behind the search form of `app\models\Withdraw`.
 */
class WithdrawSearch extends Withdraw
{
    public $idGroups;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tipe', 'id_member', 'amount', 'fee', 'nett', 'status'], 'integer'],
            [['id_transaksi', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = Withdraw::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tipe' => $this->tipe,
            'id_member' => $this->id_member,
            'amount' => $this->amount,
            'fee' => $this->fee,
            'nett' => $this->nett,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'id_transaksi', $this->id_transaksi]);

        /** groups */
        if ($this->idGroups != null) {
            $query->innerJoin('groups', 'groups.id_member = withdraw.id_member')->andOnCondition([
                'groups.id_group' => $this->idGroups
            ]);
        }

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
