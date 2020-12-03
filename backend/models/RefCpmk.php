<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "ref_cpmk".
 *
 * @property int $id
 * @property int|null $id_ref_mata_kuliah
 * @property string|null $kode
 * @property string|null $isi
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_user
 * @property string|null $updated_user
 *
 * @property CapaianMahasiswa[] $capaianMahasiswas
 * @property RefMataKuliah $refMataKuliah
 * @property RefCpl $refCpl
 * @property RelasiCpmkCpl[] $relasiCpmkCpls
 */
class RefCpmk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_cpmk';
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
            [['id_ref_mata_kuliah', 'status'], 'integer'],
            [['id_ref_mata_kuliah', 'isi', 'kode'], 'required', 'message' => '{attribute} tidak boleh kosong'],
            [['isi'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 8],
            [['created_user', 'updated_user'], 'string', 'max' => 255],
            [['id_ref_mata_kuliah'], 'exist', 'skipOnError' => true, 'targetClass' => RefMataKuliah::className(), 'targetAttribute' => ['id_ref_mata_kuliah' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_ref_mata_kuliah' => Yii::t('app', 'Mata Kuliah'),
            'kode' => 'Kode CPMK',
            'isi' => 'Isi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * Gets query for [[CapaianMahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCapaianMahasiswas()
    {
        return $this->hasMany(CapaianMahasiswa::className(), ['id_ref_cpmk' => 'id']);
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
     * Gets query for [[RelasiCpmkCpls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelasiCpmkCpls()
    {
        return $this->hasMany(RelasiCpmkCpl::className(), ['id_ref_cpmk' => 'id']);
    }

    /**
     * Gets query for [[RefCpl]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefCpl()
    {
        return $this->hasOne(RefCpl::className(), [
            'id' => 'id_ref_cpl'
        ])
            ->viaTable('relasi_cpmk_cpl', [
                'id_ref_cpmk' => 'id'
            ]);
    }

    public function getCapaianMahasiswa()
    {
        return $this->hasOne(CapaianMahasiswa::className(), ['id_ref_cpmk' => 'id']);
    }
}
