<?php

namespace backend\models\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RefCpmk as RefCpmkModel;
use backend\models\RefMataKuliah;

/**
 * RefCpmk represents the model behind the search form of `backend\models\RefCpmk`.
 */
class RefCpmk extends RefCpmkModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'status'], 'integer'],
            [['id_ref_mata_kuliah', 'nama', 'kode', 'isi', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'safe'],
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
        $query = RefCpmkModel::find()->where([static::tableName() . '.status' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // echo '<pre>';
        // print_r($params);
        // exit;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['refMataKuliah']);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'id_ref_mata_kuliah' => $this->id_ref_mata_kuliah,
            // 'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', RefMataKuliah::tableName() . '.nama', $this->id_ref_mata_kuliah])
            ->andFilterWhere(['like', static::tableName() . '.kode', $this->kode])
            ->andFilterWhere(['like', 'isi', $this->isi])
            ->andFilterWhere(['like', static::tableName() . '.status', $this->status])
            ->andFilterWhere(['like', 'created_user', $this->created_user])
            ->andFilterWhere(['like', 'updated_user', $this->updated_user]);

        return $dataProvider;
    }
}
