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
class HistoriasWebForm extends Model {

    public $id;
    public $titulo;
    public $foto;
    public $vigencia;

    public function rules() {
        return [
            [['titulo'], 'required', 'message' => 'Debe ingresar el título'],
            [['foto'], 'required', 'message' => 'Debe ingresar una imagen'],
			[['foto'], 'file','extensions'=>'jpg'],
            [['vigencia'], 'string'],
            [['id'], 'integer'],
        ];
    }

}
