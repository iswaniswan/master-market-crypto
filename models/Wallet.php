<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wallet".
 *
 * @property int $id
 * @property int|null $id_member
 * @property int|null $id_crypto
 * @property float|null $balance
 * @property string|null $date_created
 * @property string|null $date_updated
 * @property Crypto $crypto
 * @property Member $member
 */
class Wallet extends \yii\db\ActiveRecord
{
    /**@see Assets */
    const USDT = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallet';
    }

    public static function findOrCreate($id_member, $id_crypto)
    {
        $model = static::findOne([
            'id_member' => $id_member,
            'id_crypto' => $id_crypto
        ]);

        if ($model == null) {
            $model = new Wallet();
            $model->id_member = $id_member;
            $model->id_crypto = $id_crypto;
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
            [['id_member', 'id_crypto'], 'integer'],
            [['balance'], 'number'],
            [['date_created', 'date_updated'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_member' => 'Id Member',
            'id_crypto' => 'Id Crypto',
            'balance' => 'Balance',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    public function getCrypto()
    {
        return $this->hasOne(Crypto::class, ['id' => 'id_crypto']);
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public static function getBalance($id_member, $id_crypto)
    {
        return static::find()->where([
            'id_member' => $id_member,
            'id_crypto' => $id_crypto
        ])->sum('balance');
    }
}
