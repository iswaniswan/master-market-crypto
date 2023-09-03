<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ref_metode_pembayaran".
 *
 * @property int $id
 * @property string|null $nama
 * @property int|null $harga
 * @property string|null $keterangan
 * @property int|null $status
 * @property int $fee_admin
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 */
class RefMetodePembayaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_metode_pembayaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['harga', 'status', 'fee_admin'], 'integer'],
            [['fee_admin'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama'], 'string', 'max' => 50],
            [['keterangan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'harga' => 'Harga',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'fee_admin' => 'Fee Admin',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->all(),'id', function($self) {
            return ucwords($self->nama);
        });
    }

}
