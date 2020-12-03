<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "setup_aplikasi".
 *
 * @property int $id
 * @property string|null $univ_id
 * @property string|null $univ_en
 * @property string|null $fakultas_id
 * @property string|null $fakultas_en
 * @property string|null $prodi_id
 * @property string|null $prodi_en
 * @property string|null $nama_dekan
 * @property int|null $nip_dekan
 * @property string|null $nama_kaprodi
 * @property int|null $nip_kaprodi
 */
class SetupAplikasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setup_aplikasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nip_dekan', 'nip_kaprodi', 'status'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['univ_id', 'univ_en', 'fakultas_id', 'fakultas_en', 'prodi_id', 'prodi_en', 'nama_dekan', 'nama_kaprodi'], 'string', 'max' => 64],
            [['created_user', 'updated_user'], 'string', 'max' => 255],
            [['univ_id', 'univ_en', 'fakultas_id', 'fakultas_en', 'prodi_id', 'prodi_en', 'nama_dekan', 'nama_kaprodi', 'nip_dekan', 'nip_kaprodi'], 'required', 'message' => '{attribute} tidak boleh kosong'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'univ_id' => 'Universitas Id',
            'univ_en' => 'Universitas En',
            'fakultas_id' => 'Fakultas Id',
            'fakultas_en' => 'Fakultas En',
            'prodi_id' => 'Program Studi Id',
            'prodi_en' => 'Program Studi En',
            'nama_dekan' => 'Nama Dekan',
            'nip_dekan' => 'NIP Dekan',
            'nama_kaprodi' => 'Nama Kepala Program Studi',
            'nip_kaprodi' => 'NIP Kaprodi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }
}
