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
class ProductoForm extends Model
{
    public $codigo;
	public $descripcion;
	public $stockCritico;
	public $vigencia;
	public $codBarra;
	public $valorVenta;

    public function rules()
    {
        return [
            [['codigo'], 'required', 'message'=> 'El código debe estar ingresado'],
			[['descripcion'], 'required', 'message'=> 'Debe ingresar una descripción del producto'],
			[['stockCritico'], 'required', 'message'=> 'Debe ingrear el stock mínimo del producto'],
			[['vigencia'], 'required', 'message'=> 'Debe definir si el producto esta vigente'],
			[['codBarra'], 'required', 'message'=> 'Debe definir el código de barras'],
			[['valorVenta'], 'required', 'message'=> 'Debe ingresar el valor del venta del producto'],
        ];
    }

}
