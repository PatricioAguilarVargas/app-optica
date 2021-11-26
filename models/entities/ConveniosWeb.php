<?php

namespace app\models\entities;

use Yii;

class ConveniosWeb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brc_convenio_web';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TITULO', 'DESCRIPCION', 'FOTO','VIGENCIA'], 'required'],
            [['TITULO'], 'string', 'max' => 50],
            [['DESCRIPCION'], 'string', 'max' => 3000],
            [['VIGENCIA'], 'string', 'max' => 1],
            [['FOTO'], 'string'],
            [['ID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'TITULO' => 'TÍTULO',
            'CODIGO' => 'CÓDIGO',
            'DESCRIPCION' => 'DESCRIPCIÓN',
            'VIGENCIA' => 'VIGENCIA',
            'FOTO' => 'FOTO',
        ];
    }
}