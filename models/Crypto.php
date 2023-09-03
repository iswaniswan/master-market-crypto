<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "crypto".
 *
 * @property int $id
 * @property int $id_asset_coincap
 * @property int $harga
 * @property int $harga_jual
 * @property int $harga_beli
 * @property int $status
 * @property string $date_created
 * @property Assets $assets
 */
class Crypto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'crypto';
    }

    public static function findOrCreate(int $id_asset_coincap)
    {
        $model = static::findOne([
            'id_asset_coincap' => $id_asset_coincap
        ]);

        if ($model == null) {
            $model = new Crypto();
            $model->id_asset_coincap = $id_asset_coincap;
            $model->save();
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_asset_coincap', 'harga', 'harga_jual', 'harga_beli', 'status'], 'integer'],
            [['date_created'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_asset_coincap' => 'Id Asset Coincap',
            'harga' => 'Harga',
            'harga_jual' => 'Harga Jual',
            'harga_beli' => 'Harga Beli',
            'status' => 'Status',
            'date_created' => 'Date Created',
        ];
    }

    public function getAssets()
    {
        return $this->hasOne(Assets::class, ['id' => 'id_asset_coincap']);
    }

    public function getDefaultThumbnail()
    {
        $urlImage = Yii::getAlias('@web').'/images/default-currency.png';

        $html = <<<HTML
            <img src="{$urlImage}" class="rounded-circle">
        HTML;

        return $html;
    }

    public static function getCrypto($id)
    {
        return static::findOne(['id' => $id]);
    }
}
