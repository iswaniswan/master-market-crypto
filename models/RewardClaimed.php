<?php

namespace app\models;

use app\components\Helper;
use Yii;

/**
 * This is the model class for table "reward_claimed".
 *
 * @property int $id
 * @property int|null $id_reward
 * @property int|null $id_member
 * @property string|null $date_created
 * @property int|null $status
 * @property string|null $id_trx
 * @property Reward|null $reward
 * @property Member|null $member
 */

class RewardClaimed extends \yii\db\ActiveRecord
{

    const SUCCESS = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward_claimed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reward', 'id_member', 'status'], 'integer'],
            [['date_created'], 'safe'],
            [['id_trx'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_reward' => 'Id Reward',
            'id_member' => 'Id Member',
            'date_created' => 'Date Created',
            'status' => 'Status',
            'id_trx' => 'Id Trx',
        ];
    }

    public function getReward()
    {
        return $this->hasOne(Reward::class, ['id' => 'id_reward']);
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public function getBadgeStatus()
    {
        if ($this->status != static::SUCCESS) {
            return '<span class="badge badge-pill badge-warning" style="padding: 4px 8px;">WAITING</span>';
        }

        return '<span class="badge badge-pill badge-success" style="padding: 4px 8px;">SUCCESS</span>';
    }

    public static function isRewardClaimd($id_reward, $id_member)
    {
        $query = static::findOne([
            'id_reward' => $id_reward,
            'id_member' => $id_member
        ]);

        return $query != null;
    }

    public static function isEligible($id_reward, $id_member)
    {
        $reward = Reward::findOne(['id' => $id_reward]);
        if ($reward == null) {
            return false;
        }

        $creditReward = FundPassive::find()->where([
            'id_member' => $id_member,
            'id_fund_ref' => FundRef::REWARD 
        ])->sum('credit') ?? 0;

        $terms = $reward->terms;
        
        $omzet = Downline::getDownlineOmzetCount(['id_member' => $id_member]);

        /** hitung omzet kiri */
        $isOmzetLeftEligible = false;
        if (($omzet['left'] - $creditReward) >= $terms) {
            $isOmzetLeftEligible = true;
        }

        /** hitung omzet kanan */
        $isOmzetRightEligible = false;
        if (($omzet['right'] - $creditReward) >= $terms) {
            $isOmzetRightEligible = true;
        }

        return $isOmzetLeftEligible and $isOmzetRightEligible;
    }

    public function getIdTrx()
    {
        if ($this->id_trx == null) {
            $this->id_trx = Helper::generateNomorTransaksi();
            $this->save();
        }

        return $this->id_trx;
    }

}
