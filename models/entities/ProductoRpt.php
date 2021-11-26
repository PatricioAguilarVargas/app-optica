<?php

namespace app\models\entities;

use Yii;
use yii\data\ActiveDataProvider;

class ProveedorProductoRpt extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'rpt_productos_proveedor';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'PROVEEDOR',
            'PRODUCTO',
            'VALOR_VENTA',
            'VIGENCIA',
            'CODIGO'
                ],
                'required'],
            [['PROVEEDOR', 'PRODUCTO'], 'string', 'max' => 255],
            [['VALOR_VENTA'], 'integer'],
            [['VIGENCIA', 'CODIGO'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'PROVEEDOR' => "PROVEEDOR",
            'PRODUCTO' => "DESCRIPCIÓN PADRE",
            'VALOR_VENTA' => "VALOR VENTA",
            'VIGENCIA' => "VIGENCIA",
            'CODIGO' => "CÓDIGO"
        ];
    }

}
