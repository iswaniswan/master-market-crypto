<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fund_active".
 *
 * @property int $id
 * @property int|null $id_member
 * @property int|null $in
 * @property int|null $out
 * @property int|null $id_fund_ref
 * @property string|null $id_trx
 * @property string|null $date_created
 * @property Member|null $member
 * @property FundRef|null $fundRef
 */
class FundActive extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fund_active';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_member', 'credit', 'debet', 'id_fund_ref'], 'integer'],
            [['date_created'], 'safe'],
            [['id_trx'], 'string', 'max' => 255],
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
            'credit' => 'Credit',
            'debet' => 'Debet',
            'id_fund_ref' => 'Id Fund Ref',
            'id_trx' => 'Id Trx',
            'date_created' => 'Date Created',
        ];
    }

    public static function getBalance($id_member)
    {
        $allCredit = static::find()->where([
            'id_member' => $id_member
        ])->sum('credit');

        $allDebet = static::find()->where([
            'id_member' => $id_member
        ])->sum('debet');

        return $allCredit - $allDebet;
    }

    public static function totalWithdraw($id_member)
    {
        return static::find()->where([
            'id_member' => $id_member
        ])->sum('debet');
    }

    public static function getByIdTrx($id_trx)
    {
        return static::findOne([
            'id_trx' => $id_trx
        ]);
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public function getFundRef()
    {
        return $this->hasOne(FundRef::class,['id' => 'id_fund_ref']);
    }

}
