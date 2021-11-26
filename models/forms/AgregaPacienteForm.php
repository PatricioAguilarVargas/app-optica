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
class AgregaPacienteForm extends Model {
    public $dia;
    public $hora;
    public $doctor;
    public $obser;
    public $pacientes;

    public function rules() {
        return [
            [['pacientes'], 'required', 'message' => 'Debe seleccionar un paciente de la lista'],
            [['obser'],'string'],
            [['dia'],'string'],
            [['hora'],'string'],
            [['doctor'],'string'],
        ];
    }

}
