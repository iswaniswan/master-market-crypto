<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Wallet;
use yii\db\Query;

/**
 * WalletSearch represents the model behind the search form of `app\models\Wallet`.
 */
class WalletSearch extends Wallet
{
    public $grouping;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_member', 'id_crypto'], 'integer'],
            [['balance'], 'number'],
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
        $query = Wallet::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_member' => $this->id_member,
            'id_crypto' => $this->id_crypto,
            'balance' => $this->balance,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
        ]);

        if ($this->grouping != null and $this->grouping == true) {
            $query = (new Query())
                ->select(['id', 'id_member', 'id_crypto', 'sum(balance) AS balance', 'date_updated'])
                ->from('wallet')
                ->where(['id_member' => $this->id_member])
                ->groupBy(['id_member', 'id_crypto']);

//             $command = $query->createCommand()->getRawSql();
//             var_dump($command); die();
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
