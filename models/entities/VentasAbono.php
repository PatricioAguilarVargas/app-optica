<?php

namespace app\models\entities;

use Yii;

class VentasAbono extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_venta_abono';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['FOLIO', 'FECHA_ABONO', 'FORMA_PAGO', 'TIPO_PAGO', 'VALOR',], 'required'],
            [['FOLIO'], 'string', 'max' => 12],
            [['FECHA_ABONO'], 'string', 'max' => 8],
            [['FORMA_PAGO'], 'string', 'max' => 8],
            [['TIPO_PAGO'], 'string', 'max' => 6],
            [['VALOR'], 'integer'],
        ];
    }

    public static function obtenerSaldosPorFolio($folio) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_venta_abono.FOLIO',
                    "CONCAT(SUBSTRING(brc_venta_abono.FECHA_ABONO, 7, 2),'-',SUBSTRING(brc_venta_abono.FECHA_ABONO, 5, 2),'-',SUBSTRING(brc_venta_abono.FECHA_ABONO, 1, 4)) as FECHA_ABONO",
                    'brc_venta_abono.FORMA_PAGO',
                    'brc_venta_abono.TIPO_PAGO',
                    'brc_venta_abono.VALOR',
                ])
                ->from('brc_venta_abono')
                ->where(['brc_venta_abono.FOLIO' => $folio])
                ->orderBy(['brc_venta_abono.FECHA_ABONO' => SORT_ASC]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
          return $dataProvider;
         */
        return $query;
    }
    
    public static function pagIniAbonos(){
        $dia = date("Ymd");
        $month = date("Y-m");
        $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
        $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
        $anoMes = date("Ym");
        $ultdia = explode("-",$last_day)[2];
        $priDia = "01";
        $query = (new \yii\db\Query())->from('brc_venta_abono')->where("FECHA_ABONO='".$dia."'");
        $sumD = $query->sum('VALOR');
        $countD = $query->count('VALOR');
        
        $query = (new \yii\db\Query())->from('brc_venta_abono')->where("FECHA_ABONO BETWEEN '".$anoMes.$priDia."' AND '".$anoMes.$ultdia."'");
        $sumM = $query->sum('VALOR');
        $countM = $query->count('VALOR');
        $res['sumD'] = is_null($sumD)?"0":$sumD;
        $res['countD'] = is_null($countD)?"0":$countD;
        $res['sumM'] = is_null($sumM)?"0":$sumM;
        $res['countM'] = is_null($countM)?"0":$countM;
        //var_dump($res);DIE();
        return $res;
    }

}
