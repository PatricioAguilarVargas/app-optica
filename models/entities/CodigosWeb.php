<?php

namespace app\models\entities;

use Yii;

class CodigosWeb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brc_codigos_web';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TIPO', 'CODIGO', 'DESCRIPCION','PARAM1'], 'required'],
            [['CODIGO'], 'string', 'max' => 6],
            [['TIPO'], 'string', 'max' => 15],
            [['DESCRIPCION'], 'string', 'max' => 50],
            [['PARAM1'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TIPO' => 'TIPO',
            'CODIGO' => 'CÓDIGO',
            'DESCRIPCION' => 'DESCRIPCIÓN',
        ];
    }
}