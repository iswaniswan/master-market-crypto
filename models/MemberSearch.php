<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Member;

/**
 * MemberSearch represents the model behind the search form of `app\models\Member`.
 */
class MemberSearch extends Member
{

    public $id_group;
    public $not_distributor;
    public $downline_binary;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'id_paket', 'id_reff_kotakab', 'id_reff_provinsi', 'id_member_sponsor', 'id_member_upline', 'is_verified', 'is_active', 'is_deleted'], 'integer'],
            [['nama', 'no_ktp', 'phone', 'alamat', 'kotakab', 'provinsi', 'kodepos', 'info', 'bank', 'rekening', 'rekening_an', 'referral_code', 'photo', 'date_active', 'date_created'], 'safe'],
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
        $query = Member::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_user' => $this->id_user,
            'id_paket' => $this->id_paket,
            'id_reff_kotakab' => $this->id_reff_kotakab,
            'id_reff_provinsi' => $this->id_reff_provinsi,
            'id_member_sponsor' => $this->id_member_sponsor,
            'id_member_upline' => $this->id_member_upline,
            'is_verified' => $this->is_verified,
            'is_active' => $this->is_active,
            'date_active' => $this->date_active,
            'date_created' => $this->date_created,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'no_ktp', $this->no_ktp])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'kotakab', $this->kotakab])
            ->andFilterWhere(['like', 'provinsi', $this->provinsi])
            ->andFilterWhere(['like', 'kodepos', $this->kodepos])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'rekening', $this->rekening])
            ->andFilterWhere(['like', 'rekening_an', $this->rekening_an])
            ->andFilterWhere(['like', 'referral_code', $this->referral_code])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        /** join group */
        /** admin */
        if (Session::isAdmin()) {
            if ($this->not_distributor == true) {
                $query->innerJoin('groups', 'groups.id_member = member.id')->andOnCondition([
                    'groups.id_group' => Groups::GROUP_ADMIN
                ])->andFilterWhere([
                    'groups.is_admin_group' => 0
                ])->andFilterWhere(['or',
                    ['<>', 'member.id_paket', Paket::DISTRIBUTOR],
                    ['is', 'member.id_paket', new \yii\db\Expression('null')]
                ]);
            } else {
                $query->innerJoin('groups', 'groups.id_member = member.id')->andWhere([
                    'is_admin_group' => 0
                ]);
            }
        } else {
            if ($this->downline_binary == true) {
                $query->innerJoinWith('downline', true)->where([
                    'id_group' => $this->id_group, 'id_sponsor' => $this->id_member_sponsor
                ])->orFilterWhere([
                    'in', 'id_upline0', Downline::getAllDownlineID($this->id_member_sponsor)
                ]);
            } else {
                $query->innerJoinWith('downline', true)->where([
                    'id_group' => $this->id_group
                ]);
            }

        }

        // var_dump($this->id_group); die();

        // if ($this->not_distributor != null and $this->not_distributor == true) {
        //     $query->andFilterWhere([
        //         '<>', 'id_paket', Paket::DISTRIBUTOR
        //     ]);

        //     $query->orFilterWhere([
        //         'is', 'id_paket',  new \yii\db\Expression('null')
        //     ]);
        // }

        // $command = $query->createCommand()->getRawSql();
        // var_dump($command); die();

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
