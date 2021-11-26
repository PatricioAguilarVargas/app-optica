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
class UsuarioForm extends Model {

    public $rut;
    public $nombre;
    public $usuario;
    public $clave;
    public $avatar;
    public $vigencia;

    public function rules() {
        return [
            [['rut'], 'required', 'message' => 'El Rut debe estar ingresado'],
            [['nombre'], 'required', 'message' => 'Debe ingresar el nombre del usuario'],
            [['usuario'], 'required', 'message' => 'Debe ingresar el usuario'],
            [['clave'], 'required', 'message' => 'Debe ingresar la clave del usuario'],
            [['avatar'], 'required', 'message' => 'Debe seleccionar una imágen como avatar'],
            [['vigencia'], 'required', 'message' => 'Debe seleccionar la vigencia del usuario'],
        ];
    }

}
