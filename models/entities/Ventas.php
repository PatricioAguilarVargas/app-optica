<?php

namespace app\models\entities;

use Yii;

class Ventas extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_venta';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['FOLIO', 'RUT_CLIENTE', 'DV_CLIENTE', 'SUBTOTAL', 'DESCUENTO', 'NETO', 'IVA', 'TOTAL', 'FECHA_VENTA', 'USUARIO'], 'required'],
            [['RUT_CLIENTE', 'SUBTOTAL', 'DESCUENTO', 'NETO', 'IVA', 'TOTAL', 'USUARIO'], 'integer'],
            [['FOLIO'], 'string', 'max' => 12],
            [['DV_CLIENTE'], 'string', 'max' => 1],
            [['FECHA_VENTA'], 'string', 'max' => 8],
        ];
    }

    public static function obtenerVentasPorDia($dia) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_persona.RUT',
                    'brc_persona.DV',
                    'brc_persona.NOMBRE',
                    'brc_persona.TELEFONO',
                    'brc_venta.FOLIO',
                    'brc_venta.SUBTOTAL',
                    'brc_venta.DESCUENTO',
                    'brc_venta.NETO',
                    'brc_venta.IVA',
                    'brc_venta.TOTAL',
                    'brc_venta.FECHA_VENTA',
                ])
                ->from('brc_venta')
                ->join('INNER JOIN', 'brc_persona', 'brc_venta.RUT_CLIENTE = brc_persona.RUT')
                ->where(['brc_venta.FECHA_VENTA' => $dia])
                ->andWhere(['brc_persona.CAT_PERSONA' => "P00001"])
                ->orderBy(['brc_venta.FOLIO' => SORT_DESC]);
        //var_dump($query);die();
        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
          return $dataProvider; */
        return $query;
    }

    public static function obtenerVentasPorDiaYFolio($folioF) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_persona.RUT',
                    'brc_persona.DV',
                    'brc_persona.NOMBRE',
                    'brc_persona.TELEFONO',
                    'brc_venta.FOLIO',
                    'brc_venta.SUBTOTAL',
                    'brc_venta.DESCUENTO',
                    'brc_venta.NETO',
                    'brc_venta.IVA',
                    'brc_venta.TOTAL',
                    'brc_venta.FECHA_VENTA',
                ])
                ->from('brc_venta')
                ->join('INNER JOIN', 'brc_persona', 'brc_venta.RUT_CLIENTE = brc_persona.RUT')
                ->where(['brc_persona.CAT_PERSONA' => "P00001"])
                ->andWhere(['brc_venta.FOLIO' => $folioF])
                ->orderBy(['brc_venta.FOLIO' => SORT_DESC, 'brc_venta.FECHA_VENTA'=> SORT_DESC]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
          return $dataProvider; */
        return $query;
    }

    public static function obtenerVentasPorRut($rut) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_persona.RUT',
                    'brc_persona.DV',
                    'brc_persona.NOMBRE',
                    'brc_persona.TELEFONO',
                    'brc_venta.FOLIO',
                    'brc_venta.SUBTOTAL',
                    'brc_venta.DESCUENTO',
                    'brc_venta.NETO',
                    'brc_venta.IVA',
                    'brc_venta.TOTAL',
                    'brc_venta.FECHA_VENTA',
                ])
                ->from('brc_venta')
                ->join('INNER JOIN', 'brc_persona', 'brc_venta.RUT_CLIENTE = brc_persona.RUT')
                ->where(['brc_persona.CAT_PERSONA' => "P00001"])
                ->andWhere(['brc_persona.RUT' => $rut])
                ->orderBy(['brc_venta.FOLIO' => SORT_DESC]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
          return $dataProvider; */
        return $query;
    }

    public static function obtenerVentasPorFolio($folio) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_persona.RUT',
                    'brc_persona.DV',
                    'brc_persona.NOMBRE',
                    'brc_persona.TELEFONO',
                    'brc_venta.FOLIO',
                    'brc_venta.SUBTOTAL',
                    'brc_venta.DESCUENTO',
                    'brc_venta.NETO',
                    'brc_venta.IVA',
                    'brc_venta.TOTAL',
                    'brc_venta.FECHA_VENTA',
                ])
                ->from('brc_venta')
                ->join('INNER JOIN', 'brc_persona', 'brc_venta.RUT_CLIENTE = brc_persona.RUT')
                ->where(['brc_venta.FOLIO' => $folio])
                ->andWhere(['brc_persona.CAT_PERSONA' => "P00001"])
                ->orderBy(['brc_venta.FOLIO' => SORT_DESC,'brc_venta.FECHA_VENTA' => SORT_ASC]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
          return $dataProvider;
         */
        return $query;
    }
    
    public static function pagIniVentas(){
        $dia = date("Ymd");
        $month = date("Y-m");
        $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
        $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
        $anoMes = date("Ym");
        $ultdia = explode("-",$last_day)[2];
        $priDia = "01";
        $query = (new \yii\db\Query())->from('brc_venta')->where("FECHA_VENTA='".$dia."'");
        $sumD = $query->sum('TOTAL');
        $countD = $query->count('TOTAL');
        
        $query = (new \yii\db\Query())->from('brc_venta')->where("FECHA_VENTA BETWEEN '".$anoMes.$priDia."' AND '".$anoMes.$ultdia."'");
        $sumM = $query->sum('TOTAL');
        $countM = $query->count('TOTAL');
        $res['sumD'] = is_null($sumD)?"0":$sumD;
        $res['countD'] = is_null($countD)?"0":$countD;
        $res['sumM'] = is_null($sumM)?"0":$sumM;
        $res['countM'] = is_null($countM)?"0":$countM;
        //var_dump($res);DIE();
        return $res;
    }

}
