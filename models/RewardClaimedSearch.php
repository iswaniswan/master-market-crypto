<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RewardClaimed;

/**
 * RewardClaimedSearch represents the model behind the search form of `app\models\RewardClaimed`.
 */
class RewardClaimedSearch extends RewardClaimed
{

    public $idGroups;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_reward', 'id_member', 'status'], 'integer'],
            [['date_created', 'id_trx'], 'safe'],
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
        $query = RewardClaimed::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_reward' => $this->id_reward,
            'id_member' => $this->id_member,
            'date_created' => $this->date_created,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'id_trx', $this->id_trx]);

        if ($this->idGroups != null) {
            $query->innerJoin('groups', 'groups.id_member = reward_claimed.id_member')->andOnCondition([
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
