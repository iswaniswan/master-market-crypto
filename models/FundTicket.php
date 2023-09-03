<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fund_ticket".
 *
 * @property int $id
 * @property int|null $id_member
 * @property int|null $credit
 * @property int|null $debet
 * @property int|null $id_fund_ref
 * @property int|null $id_member_ref
 * @property string|null $id_trx
 * @property string|null $date_created
 * @property Member $member
 * @property Member $memberRef
 */
class FundTicket extends \yii\db\ActiveRecord
{
    public $nama;

    const DEPOSIT = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fund_ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_member', 'credit', 'debet', 'id_fund_ref', 'id_member_ref'], 'integer'],
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
            'id_member' => 'Member',
            'credit' => 'Credit',
            'debet' => 'Debet',
            'id_fund_ref' => 'Fund Ref',
            'id_member_ref' => 'Member Ref',
            'id_trx' => 'Trx. ID',
            'date_created' => 'Date Created',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public function getMemberRef()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member_ref']);
    }

    public static function sumCredit($id_member)
    {
        return static::find()->where([
            'id_member' => $id_member
        ])->sum('credit');
    }

    public static function sumDebet($id_member)
    {
        return static::find()->where([
            'id_member' => $id_member
        ])->sum('debet');
    }

    public static function getBalance($id_member)
    {
        $allCredit = static::sumCredit($id_member);
        $allDebet = static::sumDebet($id_member);

        return $allCredit - $allDebet;
    }

    public static function lastCredit($id_member)
    {
        $query = static::find()->where([
            'id_member' => $id_member,
        ]);

        $query->andFilterWhere(['>', 'credit', 0]);

        return $query->orderBy(['date_created' => SORT_DESC])->one();
    }

    public static function lastDebet($id_member)
    {
        $query = static::find()->where([
            'id_member' => $id_member,
        ]);

        $query->andFilterWhere(['>', 'debet', 0]);
        return $query->orderBy(['date_created' => SORT_DESC])->one();
    }

    public function getBadgeTransaction()
    {
        $transaksi = 'Receive';
        $class = 'success';
        if ($this->debet > 0) {
            $transaksi = 'Deliver';
            $class = 'primary';
        }

        return '<span class="badge badge-pill badge-' . $class . '" style="padding: 4px 16px;">'. $transaksi . '</span>';
    }

}
