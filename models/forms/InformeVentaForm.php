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
class InformeVentaForm extends Model {

    public $tipo;
    public $fecIni;
    public $fecFin;
    
    public function rules() {
        return [
            [['tipo'], 'required', 'message' => 'Debe seleccionar un tipo de Ingreso.'],
            [['fecIni'], 'required', 'message' => 'Debe ingresar la fecha de inicio.'],
            [['fecFin'], 'required', 'message' => 'Debe ingresar la fecha final.'],
        ];
    }

}