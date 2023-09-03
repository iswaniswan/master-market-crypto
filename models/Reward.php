<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reward".
 *
 * @property int $id
 * @property int $type rewards
 * @property int|null $terms poin
 * @property string|null $rating
 * @property string|null $description
 * @property string|null $status
 */
class Reward extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type', 'terms'], 'integer'],
            [['rating'], 'string', 'max' => 20],
            [['description', 'status'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'terms' => 'Terms',
            'rating' => 'Rating',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }

    public function getSimpleTerm()
    {
        if ($this->terms >= 1000000000) {
            return round($this->terms / 1000000000, 50) . ' Mil';
        } 

        if ($this->terms >= 1000000) {
            return round($this->terms / 1000000, 1) . ' Juta';
        } 
        
        if ($this->terms >= 1000) {
            return round($this->terms / 1000, 1) . ' Ribu';
        }

        return $this->terms;
    }
}
