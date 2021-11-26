<?php

namespace app\models\entities;

use Yii;
use yii\data\ActiveDataProvider;
USE yii\db\Query;

class Producto extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_producto';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'ID_PADRE',
            'ID_HIJO',
            'DESCRIPCION',
            'STOCK_CRITICO',
            'VIGENCIA',
            'COD_BARRA',
            'VALOR_VENTA'
                ],
                'required'],
            [['ID_PADRE', 'ID_HIJO', 'STOCK_CRITICO', 'VALOR_VENTA'], 'integer'],
            [['DESCRIPCION'], 'string', 'max' => 255],
            [['VIGENCIA'], 'string', 'max' => 1],
            [['COD_BARRA'], 'string', 'max' => 30],
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
            'VIGENCIA' => 'VIGENCIA',
            'COD_BARRA' => 'CÓDIGO DE BARRA',
            'VALOR_VENTA' => 'VALOR VENTA'
        ];
    }

    public static function obtenerProductoPorProveedor($rut) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto_proveedor.ID_HIJO',
                    'brc_producto.DESCRIPCION',
                    'brc_producto_proveedor.VALOR_PROVEEDOR',
                    'brc_producto.VALOR_VENTA',
                ])
                ->from('brc_producto')
                ->join('INNER JOIN', 'brc_producto_proveedor', 'brc_producto_proveedor.ID_HIJO =brc_producto.ID_HIJO AND brc_producto_proveedor.ID_PADRE =brc_producto.ID_PADRE')
                ->where(['brc_producto_proveedor.ID_PROVEEDOR' => $rut])
                ->andWhere(['brc_producto.VIGENCIA' => "S"]);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }

    public static function obtenerProductos() {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto.ID_HIJO',
                    'brc_producto.DESCRIPCION',
                    'brc_producto.VALOR_VENTA',
                ])
                ->from('brc_producto')
                ->andWhere(['brc_producto.VIGENCIA' => "S"]);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }

    public static function obtenerPromociones() {
        $search = "VENTAS";
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto.ID_HIJO',
                    'brc_producto.DESCRIPCION',
                    'brc_producto.VALOR_VENTA',
                ])
                ->from('brc_producto')
                ->where(['like', 'brc_producto.DESCRIPCION', $search])
                ->andWhere(['brc_producto.VIGENCIA' => "N"]);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();
        
        $codigo = $dataProvider[0]["ID_HIJO"];

        //var_dump($dataProvider);die();
        

        $query->select([
            'brc_producto.ID_HIJO',
            'brc_producto.DESCRIPCION',
            'brc_producto.VALOR_VENTA',
        ])
        ->from('brc_producto')
        ->where(['brc_producto.ID_PADRE' => $codigo])
        ->andWhere(['brc_producto.VIGENCIA' => "S"]);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }
    public static function obtenerProductosByCodigoBarraVenta($cod) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto.ID_HIJO',
                    'brc_producto.DESCRIPCION',
                    'brc_producto.VALOR_VENTA',
                ])
                ->from('brc_producto')
                ->where("brc_producto.COD_BARRA = '" . $cod . "'");

        //var_dump($query);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }
    
    public static function obtenerProductosByCodigoBarraCompra($cod,$rut) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto_proveedor.ID_HIJO',
                    'brc_producto.DESCRIPCION',
                    'brc_producto_proveedor.VALOR_PROVEEDOR',
                    'brc_producto.VALOR_VENTA',
                ])
                ->from('brc_producto')
                ->join('INNER JOIN', 'brc_producto_proveedor', 'brc_producto_proveedor.ID_HIJO =brc_producto.ID_HIJO AND brc_producto_proveedor.ID_PADRE =brc_producto.ID_PADRE')
                ->where("brc_producto.COD_BARRA = '" . $cod . "'")->andWhere("brc_producto_proveedor.ID_PROVEEDOR = ".$rut);

        //var_dump($query);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }
    
    public static function obtenerProductosByCodigoBarraWeb($cod) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto.DESCRIPCION',
                    'brc_producto.VALOR_VENTA',
                ])
                ->distinct()
                ->from('brc_producto')
                ->where(['brc_producto.COD_BARRA' => $cod]);

        //var_dump($query);


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }

    public static function obtenerIDCompuesto() {
        $query = new \yii\db\Query;
        $query->select([
                    'CONCAT(ID_PADRE,\'-\', ID_HIJO) as CODIGO',
                    'DESCRIPCION',
                ])
                ->from('brc_producto')
                ->where('LENGTH(ID_HIJO) = 11')->andWhere("VIGENCIA = 'S'");

        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }

    public static function obtenerTodosProductos() {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_producto.ID_HIJO',
                    'brc_producto.DESCRIPCION',
                ])
                ->from('brc_producto')
                ->where('LENGTH(ID_HIJO) = 11')->andWhere("VIGENCIA = 'S'");

        $command = $query->createCommand();
        $dataProvider = $command->queryAll();

        return $dataProvider;
    }

    public static function obtenerProductosRpt($id) {
        $connection = \Yii::$app->db;
        $sql = "DELETE FROM rpt_productos;";
        $connection->createCommand($sql)->execute();
        if ($id == "TODOS") {
            $sql = "INSERT INTO rpt_productos ";
            $sql = $sql . 'SELECT A.DESCRIPCION as DESCRIPCION_PADRE, B.DESCRIPCION as DESCRIPCION, B.STOCK_CRITICO as STOCK_CRITICO,CASE B.VIGENCIA WHEN "S" THEN "VIGENTE"  WHEN "N" THEN "ANULADO" END as VIGENCIA  ,B.COD_BARRA AS CODIGO, B.VALOR_VENTA AS VALOR_VENTA  ';
            $sql = $sql . "FROM brc_producto  A INNER JOIN brc_producto B ON A.ID_HIJO = B.ID_PADRE ";
            $sql = $sql . "WHERE LENGTH(B.ID_HIJO) = 11 ORDER BY A.DESCRIPCION, B.DESCRIPCION; ";
            $connection->createCommand($sql)->execute();
        } else {
            $sql = "INSERT INTO rpt_productos ";
            $sql = $sql . 'SELECT A.DESCRIPCION as DESCRIPCION_PADRE, B.DESCRIPCION as DESCRIPCION, B.STOCK_CRITICO as STOCK_CRITICO,CASE B.VIGENCIA WHEN "S" THEN "VIGENTE"  WHEN "N" THEN "ANULADO" END as VIGENCIA  ,B.COD_BARRA AS CODIGO, B.VALOR_VENTA AS VALOR_VENTA  ';
            $sql = $sql . "FROM brc_producto  A INNER JOIN brc_producto B ON A.ID_HIJO = B.ID_PADRE ";
            $sql = $sql . "WHERE LENGTH(B.ID_HIJO) = 11 AND B.ID_PADRE=" . $id;
            $sql = $sql . " ORDER BY A.DESCRIPCION, B.DESCRIPCION; ";
            $connection->createCommand($sql)->execute();
        }
        $sql = "SELECT * FROM  rpt_productos; ";
        $dataProvider = $connection->createCommand($sql);
        return $dataProvider->queryAll();
    }

    public static function obtenerCategoriaProductosRpt() {
        $connection = \Yii::$app->db;
        $sql = "DELETE FROM rpt_productos;";
        $connection->createCommand($sql)->execute();

        $sql = "INSERT INTO rpt_productos ";
        $sql = $sql . "SELECT A.DESCRIPCION as DESCRIPCION_PADRE, A.ID_HIJO as ID_HIJO, '','' ,'', ''  ";
        $sql = $sql . "FROM brc_producto  A INNER JOIN brc_producto B ON A.ID_HIJO = B.ID_PADRE ";
        $sql = $sql . "WHERE LENGTH(B.ID_HIJO) = 11 ORDER BY A.DESCRIPCION, B.DESCRIPCION; ";
        $connection->createCommand($sql)->execute();

        $sql = "SELECT * FROM  rpt_productos; ";
        $dataProvider = $connection->createCommand($sql);
        return $dataProvider->queryAll();
    }

}
