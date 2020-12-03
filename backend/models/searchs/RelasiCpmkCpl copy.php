<?php

namespace backend\models\searchs;

use backend\models\RefCpl;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RelasiCpmkCpl as RelasiCpmkCplModel;

/**
 * RelasiCpmkCpl represents the model behind the search form of `backend\models\RelasiCpmkCpl`.
 */
class RelasiCpmkCpl extends RelasiCpmkCplModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['id_ref_cpl', 'id_ref_cpmk', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'safe'],
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
        $query = RelasiCpmkCplModel::find()->where([static::tableName() . ".status" => 1]);

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
        $query->joinWith('refCpmk.refMataKuliah');
        $query->joinWith('refCpl');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'id_ref_cpmk' => $this->id_ref_cpmk,
            // 'id_ref_cpl' => $this->id_ref_cpl,
            // 'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', RefMataKuliah::tableName() . '.nama', $this->id_ref_cpmk])
            ->andFilterWhere(['like', static::tableName() . '.status', $this->status])
            ->andFilterWhere(['like', RefCpl::tableName() . '.kode', $this->id_ref_cpl])
            ->andFilterWhere(['like', 'created_user', $this->created_user])
            ->andFilterWhere(['like', 'updated_user', $this->updated_user]);

        return $dataProvider;
    }
}
