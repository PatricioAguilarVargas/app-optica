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
class VentaProductoForm extends Model
{
    public $folio;
	public $cliente;
	public $cantidad;
	public $producto;
	public $subTotal;
	public $descuento;
	public $neto;
	public $iva;
	public $total;

    public function rules()
    {
        return [
			[['folio'], 'required', 'message'=> 'Debe ingresar el folio'],
			[['cliente'], 'required', 'message'=> 'Debe elegir al cliente'],
			[['cantidad'], 'required', 'message'=> 'Debe ingresar la cantidad'],
			[['producto'], 'required', 'message'=> 'Debe elegir el producto'],
			[['subTotal'], 'required', 'message'=> 'No hay subtotal'],
			[['descuento'], 'required', 'message'=> 'No hay descuento'],
			[['neto'], 'required', 'message'=> 'No hay neto'],
			[['iva'], 'required', 'message'=> 'No hay iva'],
			[['total'], 'required', 'message'=> 'No hay total'],
        ];
    }

}
