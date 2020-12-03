<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "file_upload".
 *
 * @property int $id
 * @property int|null $id_mata_kuliah_tayang
 * @property string|null $jenis
 * @property string|null $base_name
 * @property string|null $file_name
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_user
 * @property string|null $updated_user
 *
 * @property MataKuliahTayang $mataKuliahTayang
 */
class FileUpload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_upload';
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
            [['id_mata_kuliah_tayang', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['jenis'], 'string', 'max' => 64],
            [['base_name', 'file_name', 'created_user', 'updated_user'], 'string', 'max' => 255],
            [['id_mata_kuliah_tayang'], 'exist', 'skipOnError' => true, 'targetClass' => MataKuliahTayang::className(), 'targetAttribute' => ['id_mata_kuliah_tayang' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_mata_kuliah_tayang' => 'Id Mata Kuliah Tayang',
            'jenis' => 'Jenis',
            'base_name' => 'Base Name',
            'file_name' => 'File Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * Gets query for [[MataKuliahTayang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMataKuliahTayang()
    {
        return $this->hasOne(MataKuliahTayang::className(), ['id' => 'id_mata_kuliah_tayang']);
    }
}
