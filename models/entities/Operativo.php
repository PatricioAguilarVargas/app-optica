<?php

namespace app\models\entities;

use Yii;
use yii\data\ActiveDataProvider;

class Operativo extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_operativos';
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
                'TIPO_OPERATIVO'
                ],
                'required'],
            [['RUT_DOCTOR',], 'integer'],
            [['DIA'], 'string', 'max' => 8],
            [['HORA'], 'string', 'max' => 6],
            [['TIPO_OPERATIVO'], 'string', 'max' => 6],
            [['OBSERVACION'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'DIA' => 'DIA',
            'HORA' => 'HORA',
            'RUT_DOCTOR' => 'RUT DOCTOR',
            'OBSERVACION' => 'OBSERVACIÓN'
        ];
    }

    public static function obtenerDetalleOperativoRpt($doc,$dia,$hora) {
        $connection = \Yii::$app->db;
        $sql = "DELETE FROM rpt_operativos;";
        $connection->createCommand($sql)->execute();

        $sql = "INSERT INTO rpt_operativos ";
        $sql = $sql . "SELECT NOMBRE, TELEFONO,CONCAT('') AS HORA ";
        $sql = $sql . "FROM brc_operativos_detalle A INNER JOIN brc_persona B ";
        $sql = $sql . "ON B.CAT_PERSONA = 'P00001' AND B.RUT = A.RUT_CLIENTE ";
        $sql = $sql . "WHERE DIA = '".$dia."' AND HORA = '".$hora."' AND RUT_DOCTOR = ".$doc;
        $connection->createCommand($sql)->execute();
      
        $sql = "SELECT * FROM  rpt_operativos; ";
        $dataProvider = $connection->createCommand($sql);
        return $dataProvider->queryAll();
    }
}
