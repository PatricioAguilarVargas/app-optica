<?php

namespace app\models\entities;

use Yii;

/**
 * This is the model class for table "brc_usuarios_perfiles".
 *
 * @property integer $RUT_USUARIO
 * @property integer $ID_PADRE
 * @property integer $ID_HIJO
 */
class UsuariosPerfiles extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'brc_usuarios_perfiles';
    }

    public function rules() {
        return [
            [['RUT_USUARIO', 'ID_PADRE', 'ID_HIJO', 'VIGENCIA'], 'required'],
            [['RUT_USUARIO', 'ID_PADRE', 'ID_HIJO'], 'integer'],
            [['VIGENCIA'], 'string', 'max' => 1],
        ];
    }

    public function attributeLabels() {
        return [
            'RUT_USUARIO' => 'RUT USUARIO',
            'ID_PADRE' => 'ID PADRE',
            'ID_HIJO' => 'ID HIJO',
            'VIGENCIA' => 'VIGENCIA',
        ];
    }

    public static function getUsuariosPerfilesJoinPerfilesByRut($rut, $idPadre) {
        $query = new \yii\db\Query;

        $query->select(['brc_perfiles.ID_PADRE', 'brc_perfiles.ID_HIJO', 'brc_perfiles.DESCRIPCION', 'brc_perfiles.IMG', 'brc_perfiles.RUTA'])
                ->from('brc_usuarios_perfiles')
                ->join('INNER JOIN', 'brc_perfiles', 'brc_usuarios_perfiles.ID_PADRE = brc_perfiles.ID_PADRE AND brc_usuarios_perfiles.ID_HIJO = brc_perfiles.ID_HIJO')
                ->where(['brc_usuarios_perfiles.RUT_USUARIO' => $rut])
                ->andWhere('brc_usuarios_perfiles.ID_PADRE = ' . $idPadre)
                ->andWhere("brc_usuarios_perfiles.VIGENCIA = 'S'");


        $command = $query->createCommand();
        $dataProvider = $command->queryAll();
        return $dataProvider;
    }

}
