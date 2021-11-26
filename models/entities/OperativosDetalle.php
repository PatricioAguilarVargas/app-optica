<?php

namespace app\models\entities;

use Yii;

class OperativosDetalle extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_operativos_detalle';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
                'DIA', 
                'HORA', 
                'RUT_DOCTOR', 
                'RUT_CLIENTE', 
                'CERCA_OJO_D_E',
                'CERCA_OJO_D_C',
                'CERCA_OJO_D_G',
                'CERCA_OJO_I_E',
                'CERCA_OJO_I_C',
                'CERCA_OJO_I_G',
                'LEJOS_OJO_D_E', 
                'LEJOS_OJO_D_C', 
                'LEJOS_OJO_D_G', 
                'LEJOS_OJO_I_E', 
                'LEJOS_OJO_I_C', 
                'LEJOS_OJO_I_G', 
                'DPC', 
                'DPL', 
                'DESCRIPCION_RECETA', 
                'DESCUENTO_RECETA'], 
            'required'],
            [['RUT_DOCTOR', 'RUT_CLIENTE'], 'integer'],
            [['DESCUENTO_RECETA'], 'integer'],
            [['CERCA_OJO_D_E','CERCA_OJO_D_C','CERCA_OJO_D_G',
              'CERCA_OJO_I_E','CERCA_OJO_I_C','CERCA_OJO_I_G',
              'LEJOS_OJO_D_E','LEJOS_OJO_D_C','LEJOS_OJO_D_G', 
              'LEJOS_OJO_I_E', 'LEJOS_OJO_I_C','LEJOS_OJO_I_G', ], 'string', 'max' => 5],
            [['DESCRIPCION_RECETA'], 'string', 'max' => 500],
            [['DPC', 'DPL'], 'string', 'max' => 5],
            [['DIA'], 'string', 'max' => 8],
            [['HORA'], 'string', 'max' => 6],
            [['ASISTENCIA'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'DIA' => 'DÍA',
            'HORA' => 'HORA',
            'RUT_DOCTOR' => 'DOCTOR',
            'RUT_CLIENTE' => 'PACIENTE',
            'CERCA_OJO_D' => 'OJO DERECHO CERCA',
            'CERCA_OJO_I' => 'OJO IZQUIERDO CERCA',
            'DPC' => 'DPC',
            'LEJOS_OJO_D' => 'OJO DERECHO LEJOS',
            'LEJOS_OJO_I' => 'OJO IZQUIERDO LEJOS',
            'DESCRIPCION_RECETA' => 'OBSERVACIÓN RECETA',
            'DPL' => 'DPL',
            'DESCUENTO_RECETA' => 'DESCUENTO',
            'ASISTENCIA' => 'ASISTENCIA'
        ];
    }

    public static function obtenerDetalleOperativo($doctorPost, $fechaPost, $horaPost, $siDataProvider = false) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_operativos_detalle.DIA',
                    'brc_operativos_detalle.HORA',
                    'brc_operativos_detalle.RUT_DOCTOR',
                    "concat(brc_operativos_detalle.RUT_CLIENTE, '-', brc_persona.DV) AS RUT_CLIENTE",
                    'brc_persona.NOMBRE',
                    'brc_persona.TELEFONO',
                    'brc_persona.EMAIL',
                    'brc_operativos_detalle.CERCA_OJO_D_E',
                    'brc_operativos_detalle.CERCA_OJO_D_C',
                    'brc_operativos_detalle.CERCA_OJO_D_G',
                    'brc_operativos_detalle.CERCA_OJO_I_E',
                    'brc_operativos_detalle.CERCA_OJO_I_C',
                    'brc_operativos_detalle.CERCA_OJO_I_G',
                    'brc_operativos_detalle.DPC',
                    'brc_operativos_detalle.LEJOS_OJO_D_E',
                    'brc_operativos_detalle.LEJOS_OJO_D_C',
                    'brc_operativos_detalle.LEJOS_OJO_D_G',
                    'brc_operativos_detalle.LEJOS_OJO_I_E',
                    'brc_operativos_detalle.LEJOS_OJO_I_C',
                    'brc_operativos_detalle.LEJOS_OJO_I_G',
                    'brc_operativos_detalle.DPL',
                    'brc_operativos_detalle.DESCRIPCION_RECETA',
                    'brc_operativos_detalle.DESCUENTO_RECETA',
                    'brc_operativos_detalle.ASISTENCIA'
                ])
                ->from('brc_operativos_detalle')
                ->join('INNER JOIN', 'brc_persona', 'brc_persona.RUT = brc_operativos_detalle.RUT_CLIENTE')
                ->where(['brc_operativos_detalle.RUT_DOCTOR' => $doctorPost])
                ->andWhere(['brc_operativos_detalle.DIA' => $fechaPost])
                ->andWhere(['brc_operativos_detalle.HORA' => $horaPost])
                ->andWhere(['brc_persona.CAT_PERSONA' => 'P00001']);



        if ($siDataProvider) {
            $command = $query->createCommand();
            $dataProvider = $command->queryAll();
            return $dataProvider;
        }
        return $query;
    }

    public static function obtenerDetalleOperativoPorFecha($fechaPost, $tipo = "P00001") {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_operativos_detalle.DIA',
                    'brc_operativos_detalle.HORA',
                    'brc_operativos_detalle.RUT_DOCTOR',
                    "concat(brc_operativos_detalle.RUT_CLIENTE, '-', brc_persona.DV) AS RUT_CLIENTE",
                    'brc_persona.NOMBRE',
                    'brc_persona.TELEFONO',
                    'brc_persona.EMAIL',
                    'brc_operativos_detalle.CERCA_OJO_D_E',
                    'brc_operativos_detalle.CERCA_OJO_D_C',
                    'brc_operativos_detalle.CERCA_OJO_D_G',
                    'brc_operativos_detalle.CERCA_OJO_I_E',
                    'brc_operativos_detalle.CERCA_OJO_I_C',
                    'brc_operativos_detalle.CERCA_OJO_I_G',
                    'brc_operativos_detalle.DPC',
                    'brc_operativos_detalle.LEJOS_OJO_D_E',
                    'brc_operativos_detalle.LEJOS_OJO_D_C',
                    'brc_operativos_detalle.LEJOS_OJO_D_G',
                    'brc_operativos_detalle.LEJOS_OJO_I_E',
                    'brc_operativos_detalle.LEJOS_OJO_I_C',
                    'brc_operativos_detalle.LEJOS_OJO_I_G',
                    'brc_operativos_detalle.DPL',
                    'brc_operativos_detalle.DESCRIPCION_RECETA',
                    'brc_operativos_detalle.DESCUENTO_RECETA',
                    'brc_operativos_detalle.ASISTENCIA'
                ])
                ->from('brc_operativos_detalle')
                ->join('INNER JOIN', 'brc_persona', 'brc_persona.RUT = brc_operativos_detalle.RUT_CLIENTE')
                ->Where(['brc_operativos_detalle.DIA' => $fechaPost])
                ->andWhere(['brc_persona.CAT_PERSONA' => $tipo]);

        /*
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
         */
        return $query;
    }

    public static function obtenerDetalleOperativoPorPaciente($rut, $tipo = "P00001") {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_operativos_detalle.DIA',
                    'brc_operativos_detalle.HORA',
                    'brc_operativos_detalle.RUT_DOCTOR',
                    "concat(brc_operativos_detalle.RUT_CLIENTE, '-', brc_persona.DV) AS RUT_CLIENTE",
                    'brc_persona.NOMBRE',
                    'brc_persona.TELEFONO',
                    'brc_persona.EMAIL',
                    'brc_operativos_detalle.CERCA_OJO_D_E',
                    'brc_operativos_detalle.CERCA_OJO_D_C',
                    'brc_operativos_detalle.CERCA_OJO_D_G',
                    'brc_operativos_detalle.CERCA_OJO_I_E',
                    'brc_operativos_detalle.CERCA_OJO_I_C',
                    'brc_operativos_detalle.CERCA_OJO_I_G',
                    'brc_operativos_detalle.DPC',
                    'brc_operativos_detalle.LEJOS_OJO_D_E',
                    'brc_operativos_detalle.LEJOS_OJO_D_C',
                    'brc_operativos_detalle.LEJOS_OJO_D_G',
                    'brc_operativos_detalle.LEJOS_OJO_I_E',
                    'brc_operativos_detalle.LEJOS_OJO_I_C',
                    'brc_operativos_detalle.LEJOS_OJO_I_G',
                    'brc_operativos_detalle.DPL',
                    'brc_operativos_detalle.DESCRIPCION_RECETA',
                    'brc_operativos_detalle.DESCUENTO_RECETA',
                    'brc_operativos_detalle.ASISTENCIA'
                ])
                ->from('brc_operativos_detalle')
                ->join('INNER JOIN', 'brc_persona', 'brc_persona.RUT = brc_operativos_detalle.RUT_CLIENTE')
                ->Where(['brc_operativos_detalle.RUT_CLIENTE' => $rut])
                ->andWhere(['brc_persona.CAT_PERSONA' => $tipo])
                ->orderBy(['brc_operativos_detalle.DIA'=>SORT_DESC]);

        
          $command = $query->createCommand();
          $dataProvider = $command->queryAll();
        
        return $dataProvider;
    }

    public static function pagIniOperativos(){
        $dia = date("Ymd");
        $month = date("Y-m");
        $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
        $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
        $anoMes = date("Ym");
        $ultdia = explode("-",$last_day)[2];
        $priDia = "01";
        $query = (new \yii\db\Query())->from('brc_operativos')->where("DIA='".$dia."'");
        $countD = $query->count('RUT_DOCTOR');
        
        $query = (new \yii\db\Query())->from('brc_operativos')->where("DIA BETWEEN '".$anoMes.$priDia."' AND '".$anoMes.$ultdia."'");
        $countM = $query->count('RUT_DOCTOR');
        
        $query = (new \yii\db\Query())->from('brc_operativos_detalle')->where("DIA='".$dia."'");
        $sumD = $query->count('RUT_DOCTOR');


        $query = (new \yii\db\Query())->from('brc_operativos_detalle')->where("DIA BETWEEN '".$anoMes.$priDia."' AND '".$anoMes.$ultdia."'");
        $sumM = $query->count('RUT_DOCTOR');

        
        $res['sumD'] = is_null($sumD)?"0":$sumD;
        $res['countD'] = is_null($countD)?"0":$countD;
        $res['sumM'] = is_null($sumM)?"0":$sumM;
        $res['countM'] = is_null($countM)?"0":$countM;
        //var_dump($res);DIE();
        return $res;
    }
}
