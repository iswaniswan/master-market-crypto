<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "roi".
 *
 * @property int $id
 * @property float|null $roi
 * @property string|null $date_created
 */
class Roi extends \yii\db\ActiveRecord
{

    const MAX = 90;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['roi'], 'number'],
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
            'roi' => 'Roi',
            'date_created' => 'Date Created',
        ];
    }

    public static function getCurrentRoi()
    {
        return static::find()->orderBy(['date_created' => SORT_DESC])->one();
    }


}
