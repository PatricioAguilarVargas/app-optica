<?php

namespace app\models\entities;

use Yii;

class HistoriasWeb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brc_historias_web';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TITULO', 'FOTO','VIGENCIA'], 'required'],
            [['TITULO'], 'string', 'max' => 50],
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
            'VIGENCIA' => 'VIGENCIA',
            'FOTO' => 'FOTO',
        ];
    }
}