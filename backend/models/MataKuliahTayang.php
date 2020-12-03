<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "mata_kuliah_tayang".
 *
 * @property int $id
 * @property int|null $id_tahun_ajaran
 * @property string|null $semester
 * @property int|null $id_ref_mata_kuliah
 * @property int|null $id_ref_kelas
 * @property int|null $id_ref_dosen
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_user
 * @property string|null $updated_user
 *
 * @property Krs[] $krs
 * @property RefTahunAjaran $tahunAjaran
 * @property RefMataKuliah $refMataKuliah
 * @property RefKelas $refKelas
 * @property RefDosen $refDosen
 * @property RefCpmks $refCpmks
 * @property CapaianMahasiswa $capaianMahasiswa
 */
class MataKuliahTayang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mata_kuliah_tayang';
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
            [['id_tahun_ajaran', 'id_ref_mata_kuliah', 'id_ref_kelas', 'id_ref_dosen', 'status'], 'integer'],
            [['id_tahun_ajaran', 'id_ref_mata_kuliah', 'id_ref_kelas', 'id_ref_dosen', 'semester'], 'required', 'message' => '{attribute} tidak boleh kosong'],
            [['created_at', 'updated_at'], 'safe'],
            [['semester'], 'string', 'max' => 16],
            [['created_user', 'updated_user'], 'string', 'max' => 255],
            [['id_tahun_ajaran'], 'exist', 'skipOnError' => true, 'targetClass' => RefTahunAjaran::className(), 'targetAttribute' => ['id_tahun_ajaran' => 'id']],
            [['id_ref_mata_kuliah'], 'exist', 'skipOnError' => true, 'targetClass' => RefMataKuliah::className(), 'targetAttribute' => ['id_ref_mata_kuliah' => 'id']],
            [['id_ref_kelas'], 'exist', 'skipOnError' => true, 'targetClass' => RefKelas::className(), 'targetAttribute' => ['id_ref_kelas' => 'id']],
            [['id_ref_dosen'], 'exist', 'skipOnError' => true, 'targetClass' => RefDosen::className(), 'targetAttribute' => ['id_ref_dosen' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tahun_ajaran' => 'Tahun Ajaran',
            'semester' => 'Semester',
            'id_ref_mata_kuliah' => 'Mata Kuliah',
            'id_ref_kelas' => 'Kelas',
            'id_ref_dosen' => 'Dosen',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * Gets query for [[Krs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKrs()
    {
        return $this->hasMany(Krs::className(), ['id_mata_kuliah_tayang' => 'id']);
    }

    /**
     * Gets query for [[TahunAjaran]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTahunAjaran()
    {
        return $this->hasOne(RefTahunAjaran::className(), ['id' => 'id_tahun_ajaran']);
    }

    /**
     * Gets query for [[RefMataKuliah]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefMataKuliah()
    {
        return $this->hasOne(RefMataKuliah::className(), ['id' => 'id_ref_mata_kuliah']);
    }

    /**
     * Gets query for [[RefKelas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefKelas()
    {
        return $this->hasOne(RefKelas::className(), ['id' => 'id_ref_kelas']);
    }

    /**
     * Gets query for [[RefDosen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefDosen()
    {
        return $this->hasOne(RefDosen::className(), ['id' => 'id_ref_dosen']);
    }

    // public function getRelasiCpmkCpls()
    // {
    //     return $this->hasMany(RelasiCpmkCpl::className(), ['id_ref_cpmk' => 'id'])
    //         ->viaTable(RefCpmk::tableName(), ['id' => 'id_ref_cpmk']);
    // }

    public function getRefCpmks()
    {
        return $this->hasMany(RefCpmk::className(), ['id_ref_mata_kuliah' => 'id'])
            ->viaTable(RefMataKuliah::tableName(), ['id' => 'id_ref_mata_kuliah']);
    }
}
