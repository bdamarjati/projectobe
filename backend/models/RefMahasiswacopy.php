<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ref_mahasiswa".
 *
 * @property int $id
 * @property string|null $nim
 * @property string|null $nama
 * @property int|null $angkatan
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_user
 * @property string|null $updated_user
 *
 * @property CapaianMahasiswa[] $capaianMahasiswas
 * @property RelasiCpmkCpl[] $relasiCpmkCpls
 */
class RefMahasiswa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_mahasiswa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['angkatan', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nim'], 'string', 'max' => 16],
            [['nama'], 'string', 'max' => 128],
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
            'nim' => 'Nim',
            'nama' => 'Nama',
            'angkatan' => 'Angkatan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapaianMahasiswas()
    {
        return $this->hasMany(CapaianMahasiswa::className(), ['id_ref_mahasiswa' => 'id']);
    }

    public function getRelasiCpmkCpls()
    {
        return $this->hasMany(RelasiCpmkCpl::className(), ['id_ref_cpmk' => 'id_ref_cpmk'])
        ->viaTable(RefCpmk::tableName(), ['id' => 'id_ref_cpmk'])
        ->viaTable(CapaianMahasiswa::tableName(), ['nim_ref_mahasiswa' => 'nim']);
    }
}
