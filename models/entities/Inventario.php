<?php

namespace app\models\entities;

use Yii;

class Inventario {

    public static function obtenerInventarioPorProducto($p) {
        $query = new \yii\db\Query;
        $query->select([
                    'CASE WHEN SUM(brc_compra_detalle.CANTIDAD) IS NULL THEN 0  ELSE SUM(brc_compra_detalle.CANTIDAD) END AS COMPRA',
                    'CASE WHEN SUM(brc_venta_detalle.CANTIDAD) IS NULL THEN 0  ELSE SUM(brc_venta_detalle.CANTIDAD) END AS VENTA',
                    'brc_producto.DESCRIPCION',
                    'brc_producto.STOCK_CRITICO',
                    'brc_producto.VIGENCIA',
                    'brc_producto.COD_BARRA',
                ])
                ->from('brc_producto')
                ->join('LEFT JOIN', 'brc_compra_detalle', 'brc_compra_detalle.ID_PRODUC_PROVE =brc_producto.ID_HIJO')
                ->join('LEFT JOIN', 'brc_venta_detalle', 'brc_venta_detalle.PRODUCTO =brc_producto.ID_HIJO')
                ->where(['brc_producto.VIGENCIA' => "S"])
                ->andWhere(['brc_producto.ID_HIJO' => $p])
                ->groupBy(['brc_producto.DESCRIPCION', 'brc_producto.STOCK_CRITICO', 'brc_producto.VIGENCIA', 'brc_producto.COD_BARRA'])
                ->orderBy(['brc_producto.ID_HIJO' => SORT_ASC]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
          return $dataProvider;
         */
        return $query;
    }

    public static function obtenerInventario() {
        $query = new \yii\db\Query;
        $query->select([
                    'CASE WHEN SUM(brc_compra_detalle.CANTIDAD) IS NULL THEN 0  ELSE SUM(brc_compra_detalle.CANTIDAD) END AS COMPRA',
                    'CASE WHEN SUM(brc_venta_detalle.CANTIDAD) IS NULL THEN 0  ELSE SUM(brc_venta_detalle.CANTIDAD) END AS VENTA',
                    'brc_producto.DESCRIPCION',
                    'brc_producto.STOCK_CRITICO',
                    'brc_producto.VIGENCIA',
                    'brc_producto.COD_BARRA',
                ])
                ->from('brc_producto')
                ->join('LEFT JOIN', 'brc_compra_detalle', 'brc_compra_detalle.ID_PRODUC_PROVE =brc_producto.ID_HIJO')
                ->join('LEFT JOIN', 'brc_venta_detalle', 'brc_venta_detalle.PRODUCTO =brc_producto.ID_HIJO')
                ->where(['brc_producto.VIGENCIA' => "S"])
                ->groupBy(['brc_producto.DESCRIPCION', 'brc_producto.STOCK_CRITICO', 'brc_producto.VIGENCIA', 'brc_producto.COD_BARRA'])
                ->orderBy(['brc_producto.ID_HIJO' => SORT_ASC]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
          return $dataProvider;
         */
        return $query;
    }

}
