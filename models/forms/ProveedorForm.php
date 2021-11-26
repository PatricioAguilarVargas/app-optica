<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\utilities\Utils;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ProveedorForm extends Model
{
    public $codigo;
	public $nombreEmpresa;
	public $contacto;
	public $direccion;
	public $ciudad;
	public $mail;
	public $telefono;

    public function rules()
    {
        return [
            [['codigo'], 'required', 'message'=> 'El Rut debe estar ingresado'],
			[['nombreEmpresa'], 'required', 'message'=> 'Debe ingresar el nombre de la empresa'],
			[['contacto'], 'required', 'message'=> 'Debe ingresar el contacto'],
			[['direccion'], 'required', 'message'=> 'Debe ingresar la dirección'],
			[['ciudad'], 'required', 'message'=> 'Debe ingresar la cuidad'],
			[['mail'], 'required', 'message'=> 'Debe ingresar el E-Mail'],
			[['telefono'], 'required', 'message'=> 'Debe ingresar el teléfono'],
			['codigo', 'validaRut'],
		];
    }

	public function validaRut($attribute, $params){
		if (!$this->hasErrors()) {
            $utils = new Utils;
			if($this->codigo != ""){
				if($utils->valida_rut($this->codigo) == "NOK"){
					$this->addError($attribute, 'El Rut no es valido.');
				}
			}
        }
	}
}
