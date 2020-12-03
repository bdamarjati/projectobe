<?php

namespace backend\models\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MataKuliahTayang as MataKuliahTayangModel;
use backend\models\RefMataKuliah;
use backend\models\RefTahunAjaran;
use Yii;

/**
 * MataKuliahTayang represents the model behind the search form of `backend\models\MataKuliahTayang`.
 */
class MataKuliahTayang extends MataKuliahTayangModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_ref_dosen'], 'integer'],
            [['id_tahun_ajaran', 'id_ref_mata_kuliah', 'id_ref_kelas', 'semester', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $dosen   = RefDosen::find()
            ->where(['nip' => Yii::$app->user->identity->nip])
            ->one();

        $query = MataKuliahTayangModel::find()
            ->where([static::tableName() . '.status' => 1])
            ->andWhere([MataKuliahTayang::tableName() . '.id_ref_dosen' => $dosen->id]);

        if (Yii::$app->assign->is(["administrator"]) || Yii::$app->assign->is(["admin dosen"])) {
            $query = MataKuliahTayangModel::find()
                ->where([static::tableName() . '.status' => 1]);
        } 
        // echo '<pre>';
        // print_r($query);
        // exit;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('refKelas');
        $query->joinWith('tahunAjaran');
        $query->joinWith('refMataKuliah');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'id_tahun_ajaran' => $this->id_tahun_ajaran,
            // 'id_ref_mata_kuliah' => $this->id_ref_mata_kuliah,
            // 'id_ref_kelas' => $this->id_ref_kelas,
            'id_ref_dosen' => $this->id_ref_dosen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', RefTahunAjaran::tableName() . '.tahun', $this->id_tahun_ajaran])
            ->andFilterWhere(['like', RefMataKuliah::tableName() . '.nama', $this->id_ref_mata_kuliah])
            ->andFilterWhere(['like', RefKelas::tableName() . '.kelas', $this->id_ref_kelas])
            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'created_user', $this->created_user])
            ->andFilterWhere(['like', 'updated_user', $this->updated_user]);

        return $dataProvider;
    }
}
