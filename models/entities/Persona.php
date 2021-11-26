<?php

namespace app\models\entities;

use Yii;
use yii\data\ActiveDataProvider;

class Persona extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_persona';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'RUT',
            'DV',
            'CAT_PERSONA',
            'NOMBRE',
            'DIRECCION',
            'TELEFONO',
            'EMAIL'
                ],
                'required'],
            [['RUT',], 'integer'],
            [['NOMBRE', 'DIRECCION', 'EMAIL'], 'string', 'max' => 255],
            [['DV'], 'string', 'max' => 1],
            [['CAT_PERSONA'], 'string', 'max' => 6],
            [['TELEFONO'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'RUT' => 'RUT',
            'DV' => 'DV',
            'CAT_PERSONA' => 'TIPO PERSONA',
            'NOMBRE' => 'NOMBRE',
            'DIRECCION' => 'DIRECCIÓN',
            'TELEFONO' => 'TELÉFONO',
            'EMAIL' => 'E-MAIL'
        ];
    }

}
