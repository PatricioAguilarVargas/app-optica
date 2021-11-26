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
class CierreForm extends Model {

    public $tipo;
    public $fecha;

    public function rules() {
        return [
            [['tipo'], 'required', 'message' => 'Debe seleccionar el estado de la caja'],
            [['fecha'], 'required', 'message' => 'Debe ingresar la fecha.'],
        ];
    }

}