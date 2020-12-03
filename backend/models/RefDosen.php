<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "ref_dosen".
 *
 * @property int $id
 * @property string|null $kode_dosen
 * @property int|null $nip
 * @property string|null $nama_dosen
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_user
 * @property string|null $updated_user
 *
 * @property MataKuliahTayang[] $mataKuliahTayangs
 */
class RefDosen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_dosen';
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
            [['nip', 'status'], 'integer'],
            [['nip', 'kode_dosen', 'nama_dosen', 'status'], 'required', 'message' => '{attribute} tidak boleh kosong'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode_dosen'], 'string', 'max' => 10],
            [['nama_dosen'], 'string', 'max' => 60],
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
            'kode_dosen' => 'Kode Dosen',
            'nip' => 'Nip',
            'nama_dosen' => 'Nama Dosen',
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
        return $this->hasMany(MataKuliahTayang::className(), ['id_ref_dosen' => 'id']);
    }
}
