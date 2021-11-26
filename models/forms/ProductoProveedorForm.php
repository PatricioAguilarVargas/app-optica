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
class ProductoProveedorForm extends Model
{
    public $id_producto;
	public $id_proveedor;
	public $v_compra;

    public function rules()
    {
        return [
            [['id_producto'], 'required', 'message'=> 'Debe seleccionar un producto'],
			[['id_proveedor'], 'required', 'message'=> 'Debe seleccionar el proveedor'],
			[['v_compra'], 'required', 'message'=> 'Debe ingresar el valor de compra del producto'],
		];
    }

}
