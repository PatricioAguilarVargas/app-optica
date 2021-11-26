<?php

namespace app\models\entities;

use Yii;
use yii\data\ActiveDataProvider;

class ProveedorProducto extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_producto_proveedor';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'ID_PADRE',
            'ID_HIJO',
            'ID_PROVEEDOR',
            'VALOR_VENTA',
            'VALOR_PROVEEDOR'
                ],
                'required'],
            [['ID_PADRE', 'ID_HIJO', 'ID_PROVEEDOR', 'VALOR_VENTA', 'VALOR_PROVEEDOR'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID_PADRE' => "ID PADRE",
            'ID_HIJO' => 'ID HIJO',
            'DESCRIPCION' => 'DESCRIPCIÓN',
            'DESCRIPCION' => 'VIGENCIA',
        ];
    }

    public static function obtenerProductoPorProveedor($rut) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto_proveedor.ID_HIJO',
                    'brc_producto.DESCRIPCION',
                    'brc_producto_proveedor.VALOR_PROVEEDOR',
                ])
                ->from('brc_producto')
                ->join('INNER JOIN', 'brc_producto_proveedor', 'brc_producto_proveedor.ID_HIJO =brc_producto.ID_HIJO AND brc_producto_proveedor.ID_PADRE =brc_producto.ID_PADRE')
                ->where(['brc_producto_proveedor.ID_PROVEEDOR' => $rut])
                ->andWhere(['brc_producto.VIGENCIA' => "S"]);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }

}
