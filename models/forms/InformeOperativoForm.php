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
class InformeOperativoForm extends Model {

    public $doctor;
    public $fecha;
    public $hora;

    public function rules() {
        return [
            [['doctor'], 'required', 'message' => 'Debe seelccinar un doctor.'],
            [['fecha'], 'required', 'message' => 'Debe ingresar una fecha.'],
            [['monto'], 'required', 'message' => 'Debe ingresar una hora.'],
        ];
    }

}