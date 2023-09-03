<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kotakab".
 *
 * @property int $id
 * @property int $id_provinsi
 * @property int $id_rajaongkir
 * @property string $nama
 * @property string|null $keterangan
 * @property string|null $date_created
 * @property string|null $date_updated
 * @property int|null $status_hapus
 */
class Kotakab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kotakab';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_provinsi', 'id_rajaongkir', 'nama'], 'required'],
            [['id_provinsi', 'id_rajaongkir', 'status_hapus'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
            [['nama', 'keterangan'], 'string', 'max' => 255],
            [['id_rajaongkir'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_provinsi' => 'Id Provinsi',
            'id_rajaongkir' => 'Id Rajaongkir',
            'nama' => 'Nama',
            'keterangan' => 'Keterangan',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'status_hapus' => 'Status Hapus',
        ];
    }

    public static function getList($keyword="a")
    {
        $query = static::find()->where(['LIKE', 'nama', $keyword])->all();

        return ArrayHelper::map($query, 'id', function($model) {
            return "$model->nama, $model->keterangan";
        });
    }
}
