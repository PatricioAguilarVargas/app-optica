<?php

namespace app\models\entities;

use Yii;

class VentasDetalle extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_venta_detalle';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['FOLIO', 'CANTIDAD', 'PRODUCTO'], 'required'],
            [['FOLIO'], 'string', 'max' => 12],
            [['CANTIDAD'], 'integer'],
            [['PRODUCTO'], 'integer'],
        ];
    }

    public static function obtenerDetallePorFolio($folio) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_venta_detalle.CANTIDAD',
                    'brc_producto.VALOR_VENTA',
                    'brc_producto.DESCRIPCION',
                ])
                ->from('brc_venta_detalle')
                ->join('INNER JOIN', 'brc_producto', 'brc_venta_detalle.PRODUCTO = brc_producto.ID_HIJO')
                ->where(['brc_venta_detalle.FOLIO' => $folio])
                ->orderBy(['brc_venta_detalle.FOLIO' => SORT_DESC]);
        
        
          /*$command = $query->createCommand();
          $dataProvider = $command->queryAll();
         var_dump($dataProvider);die();
          //return $dataProvider;
         */
        return $query;
    }

}
