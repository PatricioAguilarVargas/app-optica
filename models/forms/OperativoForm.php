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
class OperativoForm extends Model {

    public $dia;
    public $hora;
    public $doctor;
    public $tipo;
    public $obser;
    public $pacientes;

    public function rules() {
        return [
            [['obser'], function () {
                return is_null($this->obser)? "" : $this->obser;
            }],
            [['dia'], 'required', 'message' => 'El día debe estar ingresado'],
            [['hora'], 'required', 'message' => 'La hora debe estar ingresada'],
            [['tipo'], 'required', 'message' => 'La seleccionar el tipo de operativo'],
            [['doctor'], 'required', 'message' => 'Debe seleccionar un doctor de la lista'],
        ];
    }

}
