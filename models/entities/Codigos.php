<?php

namespace app\models\entities;

use Yii;

class Codigos extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_codigos';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['TIPO', 'CODIGO', 'DESCRIPCION'], 'required'],
            [['TIPO', 'CODIGO'], 'string', 'max' => 6],
            [['DESCRIPCION'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'TIPO' => 'TIPO',
            'CODIGO' => 'CÓDIGO',
            'DESCRIPCION' => 'DESCRIPCIÓN',
        ];
    }

}
