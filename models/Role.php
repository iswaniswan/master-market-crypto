<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Role extends \yii\db\ActiveRecord
{

    const ADMIN = 1;
    const DISTRIBUTOR = 2;
    const PENGECER = 5;
    const AGEN = 6;
    const MEMBER = 7;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name', 'code'], 'string', 'max' => 255],
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
            'code' => 'Code',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->all(),'id','name');
    }

}
