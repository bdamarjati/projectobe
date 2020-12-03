<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "ref_mata_kuliah".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int|null $sks
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_user
 * @property string|null $updated_user
 *
 * @property MataKuliahTayang[] $mataKuliahTayangs
 * @property RefCpmk[] $refCpmks

 */
class RefMataKuliah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_mata_kuliah';
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'nama', 'sks'], 'required', 'message' => '{attribute} tidak boleh kosong'],
            [['sks', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 16],
            [['nama'], 'string', 'max' => 64],
            [['created_user', 'updated_user'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'nama' => 'Nama',
            'sks' => 'Sks',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * Gets query for [[MataKuliahTayangs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMataKuliahTayangs()
    {
        return $this->hasMany(MataKuliahTayang::className(), ['id_ref_mata_kuliah' => 'id']);
    }

    /**
     * Gets query for [[RefCpmks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefCpmks()
    {
        return $this->hasMany(RefCpmk::className(), ['id_ref_mata_kuliah' => 'id']);
    }
}
