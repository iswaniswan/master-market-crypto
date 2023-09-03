<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Deposit;

/**
 * DepositSearch represents the model behind the search form of `app\models\Deposit`.
 */
class DepositSearch extends Deposit
{
    // public $idGroups = Groups::GROUP_ADMIN;
    public $idGroups;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_member', 'id_paket', 'id_ref_metode_pembayaran', 'total_bayar', 'status'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'id_transaksi'], 'safe'],
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
        $query = Deposit::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_member' => $this->id_member,
            'id_paket' => $this->id_paket,
            'id_ref_metode_pembayaran' => $this->id_ref_metode_pembayaran,
            'total_bayar' => $this->total_bayar,
            'id_transaksi' => $this->id_transaksi,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        if ($this->idGroups != null) {
            $query->innerJoinWith('groups', true)->where([
                'id_group' => $this->idGroups, 'is_admin_group' => 0
            ]);
        }

        $query->orderBy(['created_at' => SORT_DESC]);

        // $command = $query->createCommand()->getRawSql();
        // var_dump($this->idGroups); die();

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
