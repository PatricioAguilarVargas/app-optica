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
class CajasForm extends Model {

    public $monto;

    public function rules() {
        return [
            [['monto'], 'required', 'message' => 'Debe ingresar el monto de la apertura'],
        ];
    }

}
