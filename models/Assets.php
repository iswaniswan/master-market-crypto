<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assets".
 *
 * @property int $id
 * @property string|null $asset_id
 * @property string|null $rank
 * @property string|null $symbol
 * @property string|null $name
 * @property string|null $supply
 * @property string|null $max_supply
 * @property string|null $market_cap_usd
 * @property string|null $volume_usd_24_hr
 * @property string|null $price_usd
 * @property string|null $change_percent_24_hr
 * @property string|null $vwap_24_hr
 * @property string|null $explorer
 * @property Crypto $crypto
 */
class Assets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assets';
    }

    public static function findOrCreate($asset_id)
    {
        $model = static::findOne([
            'asset_id' => $asset_id
        ]);

        if ($model == null) {
            $model = new Assets();
            $model->asset_id = $asset_id;
            $model->save();
        }

        return $model;
    }

    public static function createOrUpdate()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asset_id', 'rank', 'symbol', 'name', 'supply', 'max_supply', 'market_cap_usd', 'volume_usd_24_hr', 'price_usd', 'change_percent_24_hr', 'vwap_24_hr', 'explorer'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'asset_id' => 'Asset ID',
            'rank' => 'Rank',
            'symbol' => 'Symbol',
            'name' => 'Name',
            'supply' => 'Supply',
            'max_supply' => 'Max Supply',
            'market_cap_usd' => 'Market Cap Usd',
            'volume_usd_24_hr' => 'Volume Usd 24 Hr',
            'price_usd' => 'Price Usd',
            'change_percent_24_hr' => 'Change Percent 24 Hr',
            'vwap_24_hr' => 'Vwap 24 Hr',
            'explorer' => 'Explorer',
        ];
    }

    public function getImageUrl()
    {
        $urlImage = Yii::getAlias('@web').'/images/default-currency.png';

        $filename = strtolower($this->symbol) . "@2x.png";
        $filePath = Yii::getAlias('@webroot') . '/images/' . $filename;

        if (file_exists($filePath)) {
            $urlImage = Yii::getAlias('@web') . '/images/' . $filename;
        }

        return $urlImage;
    }

    public function getDefaultThumbnail()
    {
        $urlImage = Yii::getAlias('@web').'/images/default-currency.png';

        $filename = strtolower($this->symbol) . "@2x.png";
        $filePath = Yii::getAlias('@webroot') . '/images/' . $filename;

        if (file_exists($filePath)) {
            $urlImage = Yii::getAlias('@web') . '/images/' . $filename;
        }

        $html = <<<HTML
            <img src="{$urlImage}" class="rounded-circle border-purple" style="width: 36px;">
        HTML;

        return $html;
    }

    public function getCrypto()
    {
        return $this->hasOne(Crypto::class, ['id_asset_coincap' => 'id']);
    }

}

