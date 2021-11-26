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
class PersonaForm extends Model
{
	public $categoria;
    public $rut;
	public $nombre;
	public $direccion;
	public $telefono;
	public $eMail;
	
    public function rules()
    {
        return [
			[['categoria'], 'required', 'message'=> 'El seleccionar la categoría'],
                        [['rut'], 'required', 'message'=> 'El Rut debe estar ingresado'],
			[['nombre'], 'required', 'message'=> 'Debe ingresar el nombre de la persona'],
			[['direccion'], 'required', 'message'=> 'Debe ingresar la dirección'],
			[['telefono'], 'required', 'message'=> 'Debe ingresar el teléfono'],
			[['eMail'], 'required', 'message'=> 'Debe ingresar el E-Mail'],
                        [['eMail'], 'email', 'message'=> 'Debe ingresar un E-Mail válido'],
			['rut', 'validaRut' ],
		];
    }
	
	public function validaRut($attribute, $params){
		if (!$this->hasErrors()) {
            $utils = new Utils;
			if($this->rut != ""){
				if($utils->valida_rut($this->rut) == "NOK"){
					$this->addError($attribute, 'El Rut no es valido.');
				}
			}
        }
	}

}
