<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "fund_ref".
 *
 * @property int $id
 * @property string|null $nama
 * @property string|null $keterangan
 * @property int|null $status
 */
class FundRef extends \yii\db\ActiveRecord
{

    const SPONSOR = 1;
    const REWARD = 2;
    const WITHDRAW = 3;
    const ROI = 4;
    const LEVEL = 5;
    const CASHBACK = 6;
    const DEPOSIT = 7;
    const TOPUP = 8;
    const RIPS = 9;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fund_ref';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['nama', 'keterangan'], 'string', 'max' => 255],
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
            'keterangan' => 'Keterangan',
            'status' => 'Status',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'nama');
    }

}
