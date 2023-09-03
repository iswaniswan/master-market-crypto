<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assets_history".
 *
 * @property int $id
 * @property int|null $id_crypto
 * @property string|null $price_usd
 * @property string|null $time
 * @property string|null $date
 */
class AssetsHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assets_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_crypto'], 'integer'],
            [['price_usd', 'time', 'date'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_crypto' => 'Id Crypto',
            'price_usd' => 'Price Usd',
            'time' => 'Time',
            'date' => 'Date',
        ];
    }
}
