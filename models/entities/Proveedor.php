<?php

namespace app\models\entities;

use Yii;
use yii\data\ActiveDataProvider;

class Proveedor extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_proveedor';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'ID_PROVEEDOR',
            'NOMBRE_EMPRESA',
            'CONTACTO',
            'DIRECCION',
            'CIUDAD',
            'MAIL',
            'TELEFONO'],
                'required'],
            [['ID_PROVEEDOR'], 'integer'],
            [['NOMBRE_EMPRESA', 'CONTACTO', 'DIRECCION'], 'string', 'max' => 255],
            [['CIUDAD'], 'string', 'max' => 50],
            [['MAIL'], 'string', 'max' => 150],
            [['TELEFONO'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID_PROVEEDOR' => "ID",
            'NOMBRE_EMPRESA' => 'NOMBRE',
            'CONTACTO' => 'CONTACTO',
            'DIRECCION' => 'DIRECCIÓN',
            'CIUDAD' => 'CIUDAD',
            'MAIL' => 'E-MAIL',
            'TELEFONO' => 'TELÉFONO'
        ];
    }

}
