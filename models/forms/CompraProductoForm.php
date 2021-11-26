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
class CompraProductoForm extends Model
{
    public $tipDoc;
	public $numDoc;
	public $proveedor;
	public $cantidad;
	public $producto;
	public $lote;
	public $subTotal;
	public $descuento;
	public $neto;
	public $iva;
	public $total;

    public function rules()
    {
        return [
            [['tipDoc'], 'required', 'message'=> 'Debe elegir un documento'],
			[['numDoc'], 'required', 'message'=> 'Debe ingresar en número de documento'],
			[['proveedor'], 'required', 'message'=> 'Debe elegir al proveedor'],
			[['cantidad'], 'required', 'message'=> 'Debe ingresar la cantidad'],
			[['producto'], 'required', 'message'=> 'Debe elegir el producto'],
			[['lote'], 'required', 'message'=> 'Debe ingresar el lote del producto'],
			[['subTotal'], 'required', 'message'=> 'No hay subtotal'],
			[['descuento'], 'required', 'message'=> 'No hay descuento'],
			[['neto'], 'required', 'message'=> 'No hay neto'],
			[['iva'], 'required', 'message'=> 'No hay iva'],
			[['total'], 'required', 'message'=> 'No hay total'],
        ];
    }

}
