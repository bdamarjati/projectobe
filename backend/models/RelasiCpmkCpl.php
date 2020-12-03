<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "relasi_cpmk_cpl".
 *
 * @property int $id
 * @property int|null $id_ref_cpmk
 * @property int|null $id_ref_cpl
 * @property float|null $bobot
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_user
 * @property string|null $updated_user
 *
 * @property RefCpmk $refCpmk
 * @property RefCpl $refCpl
 */
class RelasiCpmkCpl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relasi_cpmk_cpl';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ref_cpmk', 'id_ref_cpl', 'status'], 'integer'],
            [['id_ref_cpmk', 'id_ref_cpl', 'bobot'], 'required', 'message' => '{attribute} tidak boleh kosong'],
            [['bobot'], 'number', 'max' => 1, 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['created_user', 'updated_user'], 'string', 'max' => 255],
            [['id_ref_cpmk'], 'exist', 'skipOnError' => true, 'targetClass' => RefCpmk::className(), 'targetAttribute' => ['id_ref_cpmk' => 'id']],
            [['id_ref_cpl'], 'exist', 'skipOnError' => true, 'targetClass' => RefCpl::className(), 'targetAttribute' => ['id_ref_cpl' => 'id']],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_ref_cpmk' => 'Id Ref Cpmk',
            'id_ref_cpl' => 'Id Ref Cpl',
            'bobot' => 'Bobot',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * Gets query for [[RefCpmk]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefCpmk()
    {
        return $this->hasOne(RefCpmk::className(), ['id' => 'id_ref_cpmk']);
    }

    /**
     * Gets query for [[RefCpl]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefCpl()
    {
        return $this->hasOne(RefCpl::className(), ['id' => 'id_ref_cpl']);
    }

    public function getRefMataKuliah()
    {
        return $this->hasOne(RefMataKuliah::className(), ['id' => 'id_ref_mata_kuliah'])
            ->viaTable('ref_cpmk', ['id' => 'id_ref_cpmk']);
    }

    public function getDataRelasiCpmkCpl()
    {
        return $this->hasOne(CapaianMahasiswa::className(), [
            'id_ref_cpmk' => 'id'
        ])
            ->viaTable('refCpmk', [
                'id' => 'id_ref_cpmk'
            ]);
    }
}
