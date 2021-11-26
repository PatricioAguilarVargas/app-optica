<?php

namespace app\models\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Perfiles;
use app\models\UsuariosPerfiles;

/**
 * BrcPerfilesSearch represents the model behind the search form about `app\models\BrcPerfiles`.
 */
class PerfilesSearch extends Perfiles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_PADRE', 'ID_HIJO'], 'integer'],
            [['DESCRIPCION', 'IMG', 'RUTA'], 'safe'],
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
        $query = Perfiles::find(array('order'=>'ID_PADRE'));

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
            'ID_PADRE' => $this->ID_PADRE,
            'ID_HIJO' => $this->ID_HIJO,
        ]);

        $query->andFilterWhere(['like', 'DESCRIPCION', $this->DESCRIPCION])
            ->andFilterWhere(['like', 'IMG', $this->IMG])
            ->andFilterWhere(['like', 'RUTA', $this->RUTA]);

        return $dataProvider;
    }
	
	public function searchByUsuario($params,$rut)
    {
        //$query = Perfiles::find()->all();
		$query = new \yii\db\Query;
		$query	->select([
				'brc_perfiles.ID_PADRE',
				'brc_perfiles.ID_HIJO',
				'brc_perfiles.DESCRIPCION', 
				'brc_perfiles.IMG',
				'brc_perfiles.RUTA']
				)  
			->from('brc_usuarios')
			->join('INNER JOIN', 'brc_usuarios_perfiles',
						'brc_usuarios_perfiles.RUT_USUARIO =brc_usuarios.RUT ')		
			->join('INNER JOIN', 'brc_perfiles',
					'brc_perfiles.ID_HIJO = brc_usuarios_perfiles.ID_HIJO and brc_perfiles.ID_PADRE = brc_usuarios_perfiles.ID_PADRE')
			->where(['brc_usuarios.RUT' => $rut]);
			
		
		//$command = $sql->createCommand();
		//$query = $command->queryAll();	

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
            'ID_PADRE' => $this->ID_PADRE,
            'ID_HIJO' => $this->ID_HIJO,
        ]);

        $query->andFilterWhere(['like', 'DESCRIPCION', $this->DESCRIPCION])
            ->andFilterWhere(['like', 'IMG', $this->IMG])
            ->andFilterWhere(['like', 'RUTA', $this->RUTA]);

        return $dataProvider;
    }
	
	public function searchPerfilSinUsuario($params,$rut)
    {
        
			/*
		$query = "SELECT * 
				FROM  `brc_perfiles` 
				where 
				brc_perfiles.ID_PADRE not in (select brc_usuarios_perfiles.ID_PADRE from brc_usuarios_perfiles where brc_usuarios_perfiles.RUT_USUARIO = ".$rut." and brc_usuarios_perfiles.ID_PADRE = brc_perfiles.ID_PADRE and brc_usuarios_perfiles.ID_HIJO = brc_perfiles.ID_HIJO)
				and
				brc_perfiles.ID_HIJO not in (select brc_usuarios_perfiles.ID_HIJO from brc_usuarios_perfiles where brc_usuarios_perfiles.RUT_USUARIO = ".$rut." and brc_usuarios_perfiles.ID_PADRE = brc_perfiles.ID_PADRE and brc_usuarios_perfiles.ID_HIJO = brc_perfiles.ID_HIJO)";
		$command = $sql->createCommand($sql);
		$query = $command->queryAll();	*/

        // add conditions that should always apply here

		$subQuery1 = UsuariosPerfiles::find()
					->select('brc_usuarios_perfiles.ID_PADRE')
					->where(["(brc_usuarios_perfiles.ID_PADRE = brc_perfiles.ID_PADRE) AND (brc_usuarios_perfiles.ID_HIJO = brc_perfiles.ID_HIJO) AND brc_usuarios_perfiles.RUT_USUARIO" => $rut]);

		$subQuery2 = UsuariosPerfiles::find()
					->select('brc_usuarios_perfiles.ID_HIJO')
					->where(["(brc_usuarios_perfiles.ID_PADRE = brc_perfiles.ID_PADRE) AND (brc_usuarios_perfiles.ID_HIJO = brc_perfiles.ID_HIJO) AND brc_usuarios_perfiles.RUT_USUARIO" => $rut]);
		$query = Perfiles::find()
				->where(['not in', 'brc_perfiles.ID_PADRE', $subQuery1])
				->andWhere(['not in', 'brc_perfiles.ID_HIJO', $subQuery2]);
		//$models = $query->all();
			
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
            'ID_PADRE' => $this->ID_PADRE,
            'ID_HIJO' => $this->ID_HIJO,
        ]);

        $query->andFilterWhere(['like', 'DESCRIPCION', $this->DESCRIPCION])
            ->andFilterWhere(['like', 'IMG', $this->IMG])
            ->andFilterWhere(['like', 'RUTA', $this->RUTA]);

        return $dataProvider;
    }
}
