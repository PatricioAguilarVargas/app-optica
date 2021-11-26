<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ConveniosWebForm extends Model {

    public $id;
    public $titulo;
    public $descripcion;
    public $foto;
    public $vigencia;

    public function rules() {
        return [
            [['titulo'], 'required', 'message' => 'Debe ingresar el titulo'],
            [['descripcion'], 'required', 'message' => 'Debe ingresar la descripción'],
            [['foto'], 'required', 'message' => 'Debe ingresar una imagen'],
            [['foto'], 'file','extensions'=>'jpg'],
            [['vigencia'], 'string'],
            [['id'], 'integer'],
        ];
    }

}
