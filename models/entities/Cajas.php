<?php

namespace app\models\entities;

use Yii;

class Cajas extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_cajas';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['DIA', 'USUARIO', 'ESTADO'], 'required'],
            [['DIA'], 'string', 'max' => 8],
            [['USUARIO'], 'integer'],
            [['MONTO'], 'integer'],
            [['ESTADO'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'DIA' => 'DÍA',
            'USUARIO' => 'USUARIO',
            'MONTO' => 'MONTO',
            'ESTADO' => 'ESTADO',
        ];
    }

}
