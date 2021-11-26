<?php

namespace app\models\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\entity\Usuario;

/**
 * BrcUsuariosSearch represents the model behind the search form about `app\models\BrcUsuarios`.
 */
class UsuariosSearch extends Usuario
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RUT'], 'number'],
            [['DV', 'NOMBRE', 'USUARIO', 'CLAVE'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Usuario::find();

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
            'RUT' => $this->RUT,
        ]);

        $query->andFilterWhere(['like', 'DV', $this->DV])
            ->andFilterWhere(['like', 'NOMBRE', $this->NOMBRE])
            ->andFilterWhere(['like', 'USUARIO', $this->USUARIO])
            ->andFilterWhere(['like', 'CLAVE', $this->CLAVE]);

        return $dataProvider;
    }
}
