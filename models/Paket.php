<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "paket".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $price
 * @property int|null $poin
 * @property string|null $remark
 * @property int|null $is_active
 */
class Paket extends \yii\db\ActiveRecord
{

    const INDIVIDU = 1;
    const PENGECER = 2;
    const AGEN = 3;
    const DISTRIBUTOR = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'poin', 'is_active'], 'integer'],
            [['name', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'poin' => 'Poin',
            'remark' => 'Remark',
            'is_active' => 'Is Active',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->all(),'id', function($self) {
            return ucwords($self->name);
        });
    }
}
