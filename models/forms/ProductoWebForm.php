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
class ProductoWebForm extends Model {

    public $codigo;
    public $descripcion;
    public $vigencia;
    public $valor;
    public $tipo;
    public $marca;
    public $modelo;
    public $material;
    public $color;
    public $forma;
    public $foto1;
    public $foto2;

    public function rules() {
        return [
            [['codigo'], 'required', 'message' => 'El código debe estar ingresado'],
            [['vigencia'], 'required', 'message' => 'Debe definir la vigencia del producto'],
            [['marca'], 'required', 'message' => 'Debe ingresar la marca del producto'],
            [['modelo'], 'required', 'message' => 'El modelo debe estar ingresado'],
            [['material'], 'required', 'message' => 'Debe ingresar el material del producto'],
            [['color'], 'required', 'message' => 'Debe definir el color del producto'],
            [['forma'], 'required', 'message' => 'Debe definir la forma del producto'],
            [['foto1'], 'required', 'message' => 'Debe definir la foto 1 del producto'],
            [['foto2'], 'required', 'message' => 'Debe definir la foto 2 del producto'],
            [['tipo'], 'required', 'message' => 'Debe definir el tipo del producto'],
        ];
    }

}
