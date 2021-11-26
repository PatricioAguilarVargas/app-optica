<?php

namespace app\models\entities;

use Yii;
use yii\data\ActiveDataProvider;
USE yii\db\Query;

class ProductoWeb extends \yii\db\ActiveRecord {

   
    public static function tableName() {
        return 'brc_producto_web';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'CODIGO',
            'DESCRIPCION',
            'VIGENCIA',
            'VALOR',
            'COD_TIPO',
            'COD_MARCA',
            'MODELO',
            'COD_MATERIAL',
            'COD_COLOR',
            'COD_FORMA',
            'FOTO1',
            'FOTO2'
                ],
                'required'],
            [['VALOR'], 'integer'],
            [['DESCRIPCION'], 'string', 'max' => 255],
            [['FOTO1','FOTO2'], 'string'],
            [['VIGENCIA'], 'string', 'max' => 1],
            [['CODIGO'], 'string', 'max' => 30],
            [['COD_TIPO','COD_MARCA','COD_MATERIAL','COD_COLOR','COD_FORMA'], 'string', 'max' => 6],
            [['MODELO'], 'string', 'max' => 50],
        ];
    }
}
