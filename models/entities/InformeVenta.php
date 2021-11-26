<?php

namespace app\models\entities;

use Yii;

class InformeVenta {

    public static function obtenerVentas($fecIni, $fecFin) {
        $venta = new \yii\db\Query;
        $venta->select([
                    "CONCAT('VENTA') as TIPO",
                    "brc_venta.FOLIO as FOLIO",
                    "CONCAT(SUBSTRING(brc_venta.FECHA_VENTA, 7, 2),'-',SUBSTRING(brc_venta.FECHA_VENTA, 5, 2),'-',SUBSTRING(brc_venta.FECHA_VENTA, 1, 4)) as FECHA",
                    "CONCAT('') as FORMA_PAGO",
                    "CONCAT('VENTA') as ESTADO",
                    "brc_venta.TOTAL as VALOR",
                ])
                ->from('brc_venta')
                ->where(['between', 'brc_venta.FECHA_VENTA',$fecIni, $fecFin])  
                ->orderBy(['brc_venta.FECHA_VENTA' => SORT_ASC,'brc_venta.FOLIO' => SORT_ASC]);
        return $venta;
    }
    
    public static function obtenerAbonos($fecIni, $fecFin) {
        $abono = new \yii\db\Query;
        $abono->select([
            "CONCAT('SALDO') as TIPO",
            "brc_venta_abono.FOLIO as FOLIO",
            "CONCAT(SUBSTRING(brc_venta_abono.FECHA_ABONO, 7, 2),'-',SUBSTRING(brc_venta_abono.FECHA_ABONO, 5, 2),'-',SUBSTRING(brc_venta_abono.FECHA_ABONO, 1, 4)) as FECHA",
            "formPago.DESCRIPCION as FORMA_PAGO",
            "estado.DESCRIPCION as ESTADO",
            "brc_venta_abono.VALOR as VALOR",
        ])
        ->from('brc_venta_abono')
        ->join("LEFT JOIN", "brc_codigos estado", "estado.TIPO = 'ABONO' AND brc_venta_abono.TIPO_PAGO =estado.CODIGO")
        ->join("LEFT JOIN", "brc_codigos formPago", "formPago.TIPO = 'FO_PAG' AND brc_venta_abono.FORMA_PAGO =formPago.CODIGO")
        ->where(['between', 'brc_venta_abono.FECHA_ABONO',$fecIni, $fecFin])
        ->orderBy(['brc_venta_abono.FECHA_ABONO' => SORT_ASC,'brc_venta_abono.FOLIO' => SORT_ASC]);
        return $abono;
    }

    public static function obtenerVentasAndAbonos($fecIni, $fecFin) {
        $venta = new \yii\db\Query;
        $abono = new \yii\db\Query;
        $venta->select([
                    "CONCAT('VENTA') as TIPO",
                    "brc_venta.FOLIO as FOLIO",
                    "CONCAT(SUBSTRING(brc_venta.FECHA_VENTA, 7, 2),'-',SUBSTRING(brc_venta.FECHA_VENTA, 5, 2),'-',SUBSTRING(brc_venta.FECHA_VENTA, 1, 4)) as FECHA",
                    "CONCAT('') as FORMA_PAGO",
                    "CONCAT('VENTA') as ESTADO",
                    "brc_venta.TOTAL as VALOR",
                ])
                ->from('brc_venta')
                ->where(['between', 'brc_venta.FECHA_VENTA',$fecIni, $fecFin]);
        
        $abono->select([
                    "CONCAT('SALDO') as TIPO",
                    "brc_venta_abono.FOLIO as FOLIO",
                    "CONCAT(SUBSTRING(brc_venta_abono.FECHA_ABONO, 7, 2),'-',SUBSTRING(brc_venta_abono.FECHA_ABONO, 5, 2),'-',SUBSTRING(brc_venta_abono.FECHA_ABONO, 1, 4)) as FECHA",
                    "formPago.DESCRIPCION as FORMA_PAGO",
                    "estado.DESCRIPCION as ESTADO",
                    "brc_venta_abono.VALOR as VALOR",
                ])
                ->from('brc_venta_abono')
                ->join("LEFT JOIN", "brc_codigos estado", "estado.TIPO = 'ABONO' AND brc_venta_abono.TIPO_PAGO =estado.CODIGO")
                ->join("LEFT JOIN", "brc_codigos formPago", "formPago.TIPO = 'FO_PAG' AND brc_venta_abono.FORMA_PAGO =formPago.CODIGO")
                ->where(['between', 'brc_venta_abono.FECHA_ABONO',$fecIni, $fecFin]);
        
        $query = (new \yii\db\Query())
        ->from(['dummy_name' => $venta->union($abono)])
        ->orderBy(['FECHA' => SORT_ASC, 'FOLIO' => SORT_ASC,'TIPO' => SORT_DESC]);
        
        return $query;
    }

}
