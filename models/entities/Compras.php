<?php

namespace app\models\entities;

use Yii;

class Compras extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_compra';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['FOLIO', 'ID_PROVEEDOR', 'ID_DOCUMENTO', 'N_DOCUMENTO', 'SUBTOTAL', 'DESCUENTO', 'TOTAL_NETO', 'IVA', 'TOTAL', 'FECHA_COMPRA', 'USUARIO'], 'required'],
            [['ID_PROVEEDOR', 'SUBTOTAL', 'DESCUENTO', 'TOTAL_NETO', 'IVA', 'TOTAL', 'USUARIO'], 'integer'],
            [['FOLIO'], 'string', 'max' => 12],
            [['N_DOCUMENTO'], 'string', 'max' => 11],
            [['ID_DOCUMENTO'], 'string', 'max' => 6],
            [['FECHA_COMPRA'], 'string', 'max' => 8],
        ];
    }
    
    public static function pagIniCompras(){
        $dia = date("Ymd");
        $month = date("Y-m");
        $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
        $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
        $anoMes = date("Ym");
        $ultdia = explode("-",$last_day)[2];
        $priDia = "01";
        $query = (new \yii\db\Query())->from('brc_compra')->where("FECHA_COMPRA='".$dia."' AND ID_PROVEEDOR <> 0");
        $sumD = $query->sum('TOTAL');
        $countD = $query->count('TOTAL');
        
        $query = (new \yii\db\Query())->from('brc_compra')->where("ID_PROVEEDOR <> 0 AND FECHA_COMPRA BETWEEN '".$anoMes.$priDia."' AND '".$anoMes.$ultdia."'");
        $sumM = $query->sum('TOTAL');
        $countM = $query->count('TOTAL');
        $res['sumD'] = is_null($sumD)?"0":$sumD;
        $res['countD'] = is_null($countD)?"0":$countD;
        $res['sumM'] = is_null($sumM)?"0":$sumM;
        $res['countM'] = is_null($countM)?"0":$countM;
        //var_dump($res);DIE();
        return $res;
    }
    
    public static function pagIniDonaciones(){
        $dia = date("Ymd");
        $month = date("Y-m");
        $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
        $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
        $anoMes = date("Ym");
        $ultdia = explode("-",$last_day)[2];
        $priDia = "01";
        $query = (new \yii\db\Query())->from('brc_compra')->where("FECHA_COMPRA='".$dia."' AND ID_PROVEEDOR = 0");
        $countD = $query->count('TOTAL');
        
        $query = (new \yii\db\Query())->from('brc_compra')->where("ID_PROVEEDOR = 0 AND FECHA_COMPRA BETWEEN '".$anoMes.$priDia."' AND '".$anoMes.$ultdia."'");
        $countM = $query->count('TOTAL');
        $res['countD'] = is_null($countD)?"0":$countD;
        $res['countM'] = is_null($countM)?"0":$countM;
        //var_dump($res);DIE();
        return $res;
    }

}
