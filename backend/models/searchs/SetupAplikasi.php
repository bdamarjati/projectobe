<?php

namespace backend\models\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SetupAplikasi as SetupAplikasiModel;

/**
 * SetupAplikasi represents the model behind the search form of `backend\models\SetupAplikasi`.
 */
class SetupAplikasi extends SetupAplikasiModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nip_dekan', 'nip_kaprodi', 'status'], 'integer'],
            [['univ_id', 'univ_en', 'fakultas_id', 'fakultas_en', 'prodi_id', 'prodi_en', 'nama_dekan', 'nama_kaprodi', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'safe'],
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
        $query = SetupAplikasiModel::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'nip_dekan' => $this->nip_dekan,
            'nip_kaprodi' => $this->nip_kaprodi,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'univ_id', $this->univ_id])
            ->andFilterWhere(['like', 'univ_en', $this->univ_en])
            ->andFilterWhere(['like', 'fakultas_id', $this->fakultas_id])
            ->andFilterWhere(['like', 'fakultas_en', $this->fakultas_en])
            ->andFilterWhere(['like', 'prodi_id', $this->prodi_id])
            ->andFilterWhere(['like', 'prodi_en', $this->prodi_en])
            ->andFilterWhere(['like', 'nama_dekan', $this->nama_dekan])
            ->andFilterWhere(['like', 'nama_kaprodi', $this->nama_kaprodi])
            ->andFilterWhere(['like', 'created_user', $this->created_user])
            ->andFilterWhere(['like', 'updated_user', $this->updated_user]);

        return $dataProvider;
    }
}
