<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deposit".
 *
 * @property int $id
 * @property int|null $id_member
 * @property int|null $id_paket
 * @property int|null $id_ref_metode_pembayaran
 * @property string|null $id_transaksi
 * @property int|null $total_bayar
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property Paket $paket
 * @property RefMetodePembayaran $refMetodePembayaran
 * @property User $user
 * @property Member $member
 */
class Deposit extends \yii\db\ActiveRecord
{

    const ACTIVE = 1;

    public $harga_paket;
    public $ref_metode_pembayaran_harga;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_member', 'id_paket', 'id_ref_metode_pembayaran', 'status'], 'integer'],
            [['total_bayar', 'id_transaksi'], 'safe'],
            [['total_bayar', 'id_paket'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_member' => 'Id User',
            'id_paket' => 'Paket',
            'id_ref_metode_pembayaran' => 'Metode Pembayaran',
            'total_bayar' => 'Total Bayar',
            'id_transaksi' => 'ID Transaksi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function generateNomorTransaksi()
    {
        $dateTime = $this->created_at;

        if ($dateTime == null) {
            $dateTime = date('Y-m-d H:i:s');
        }

        $y = date('y', strtotime($dateTime));
        $m = date('m', strtotime($dateTime));
        $m = intval($m) + 64;
        $m = chr($m);
        $d = date('d', strtotime($dateTime));

        $h = date('H', strtotime($dateTime));
        $h = intval($h) + 64;
        $h = chr($h);

        $i = date('i', strtotime($dateTime));
        $s = date('s', strtotime($dateTime));

        return "TRX$y$m$d$h$i$s";
    }

    public function getPaket()
    {
        return $this->hasOne(Paket::class, ['id' => 'id_paket']);
    }

    public function getRefMetodePembayaran()
    {
        return $this->hasOne(RefMetodePembayaran::class, ['id' => 'id_ref_metode_pembayaran']);
    }

    public function getBadgeStatusTransaksi()
    {
        if ($this->status == null) {
            return '<span class="badge badge-pill badge-warning" style="padding: 4px 16px;">WAITING</span>';
        }

        return '<span class="badge badge-pill badge-success" style="padding: 4px 16px;">SUCCESS</span>';
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user'])->via('member');
    }

    public function getGroups()
    {
        return $this->hasOne(Groups::class, ['id_member' => 'id_member']);
    }

    public static function getBalanceDistributor(Member $member)
    {
        $id_group = $member->getGroupAsAdmin()->id_group;

        $query = static::find()->where([
            'status' => 1   
        ]);
        $query->innerJoin('groups', 'groups.id_member = deposit.id_member')->andOnCondition([
            'groups.id_group' => $id_group
        ]);

        return $query->sum('total_bayar');
    }

    public static function getByIdTrx($id_trx)
    {
        return static::findOne([
            'id_transaksi' => $id_trx
        ]);
    }

}
