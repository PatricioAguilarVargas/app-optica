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
            'VALOR_PROVEEDOR'
                ],
                'required'],
            [['ID_PADRE', 'ID_HIJO', 'ID_PROVEEDOR', 'VALOR_PROVEEDOR'], 'integer'],
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

    public static function buscarProductoPorProveedor($rut) {
        $query = new \yii\db\Query;
        $query->select([
                    "CONCAT(brc_producto_proveedor.ID_PADRE,'-', brc_producto_proveedor.ID_HIJO) AS ID_PRODUCTO",
                    "brc_producto.DESCRIPCION",
                    'brc_producto_proveedor.ID_PROVEEDOR',
                    'brc_producto_proveedor.VALOR_PROVEEDOR',
                ])
                ->from('brc_producto')
                ->join('INNER JOIN', 'brc_producto_proveedor', 'brc_producto_proveedor.ID_HIJO =brc_producto.ID_HIJO AND brc_producto_proveedor.ID_PADRE =brc_producto.ID_PADRE')
                ->where(['brc_producto_proveedor.ID_PROVEEDOR' => $rut])
                ->andWhere(['brc_producto.VIGENCIA' => "S"]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
         */
        return $query;
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

    public static function obtenerProductosXProveedorRpt($id) {
        $connection = \Yii::$app->db;
        $sql = "DELETE FROM rpt_productos_proveedor;";
        $connection->createCommand($sql)->execute();
        //var_dump($sql);die();
        if ($id == "TODOS") {
            $sql = "INSERT INTO rpt_productos_proveedor ";
            $sql = $sql . 'SELECT NOMBRE_EMPRESA, DESCRIPCION, VALOR_VENTA , CASE VIGENCIA WHEN "S" THEN "VIGENTE"  WHEN "N" THEN "ANULADO" END as VIGENCIA, COD_BARRA ';
            $sql = $sql . "FROM brc_producto INNER JOIN brc_producto_proveedor ON ";
            $sql = $sql . "brc_producto.ID_HIJO = brc_producto_proveedor.ID_HIJO AND brc_producto.ID_PADRE = brc_producto_proveedor.ID_PADRE ";
            $sql = $sql . "INNER JOIN brc_proveedor ON brc_producto_proveedor.ID_PROVEEDOR = brc_proveedor.ID_PROVEEDOR; ";
            $connection->createCommand($sql)->execute();
        } else {
            $sql = "INSERT INTO rpt_productos_proveedor ";
            $sql = $sql . 'SELECT NOMBRE_EMPRESA, DESCRIPCION, VALOR_VENTA , CASE VIGENCIA WHEN "S" THEN "VIGENTE"  WHEN "N" THEN "ANULADO" END as VIGENCIA, COD_BARRA ';
            $sql = $sql . "FROM brc_producto INNER JOIN brc_producto_proveedor ON ";
            $sql = $sql . "brc_producto.ID_HIJO = brc_producto_proveedor.ID_HIJO AND brc_producto.ID_PADRE = brc_producto_proveedor.ID_PADRE ";
            $sql = $sql . "INNER JOIN brc_proveedor ON brc_producto_proveedor.ID_PROVEEDOR = brc_proveedor.ID_PROVEEDOR ";
            $sql = $sql . "WHERE brc_proveedor.ID_PROVEEDOR = " . $id;

            $connection->createCommand($sql)->execute();
        }
        $sql = "SELECT * FROM  rpt_productos_proveedor; ";
        $dataProvider = $connection->createCommand($sql);
        return $dataProvider->queryAll();
    }

}
