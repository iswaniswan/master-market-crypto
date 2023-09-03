<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "withdraw".
 *
 * @property int $id
 * @property string|null $id_transaksi
 * @property int|null $tipe
 * @property int|null $id_member
 * @property int|null $amount
 * @property int|null $fee
 * @property int|null $nett
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property Member|null $member
 */
class Withdraw extends \yii\db\ActiveRecord
{

    const ACTIVE = 1;
    const PASSIVE = 2;

    const SUCCESS = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'withdraw';
    }

    public static function getByIdTrx($id_trx)
    {
        return static::findOne([
            'id_transaksi' => $id_trx
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipe', 'id_member', 'amount', 'fee', 'nett', 'status'], 'integer'],
            [['amount', 'fee', 'nett'], 'required'],
            [['created_at', 'updated_at', 'deleted_at', 'id_trx'], 'safe'],
            [['id_transaksi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_transaksi' => 'Id Transaksi',
            'tipe' => 'Tipe',
            'id_member' => 'Member',
            'amount' => 'Amount',
            'fee' => 'Fee',
            'nett' => 'Nett',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public static function getListTipe()
    {
        return [
            static::ACTIVE => 'Active',
            static::PASSIVE => 'Passive',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public function getTipeText()
    {
        if ($this->tipe == static::ACTIVE) {
            return 'Active';
        }
        return 'Passive';
    }

    public function getBadgeStatus()
    {
        if ($this->status == null) {
            return '<span class="badge badge-pill badge-warning" style="padding: 4px 8px;">WAITING</span>';
        }

        return '<span class="badge badge-pill badge-success" style="padding: 4px 8px;">SUCCESS</span>';
    }

    public static function getBalanceDistributor(Member $member)
    {
        $id_group = $member->getGroupAsAdmin()->id_group;

        $query = static::find()->where([
            'status' => 1   
        ]);
        $query->innerJoin('groups', 'groups.id_member = withdraw.id_member')->andOnCondition([
            'groups.id_group' => $id_group
        ]);

        return $query->sum('amount');
    }

}
