<?php

namespace app\models\entities;

use Yii;

class PromocionesWeb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brc_promociones_web';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VALIDEZ', 'FOTO','VIGENCIA','PRINCIPAL'], 'required'],
            [['VALIDEZ'], 'string', 'max' => 50],
            [['VIGENCIA'], 'string', 'max' => 1],
            [['FOTO'], 'string'],
            [['PRINCIPAL'], 'string', 'max' => 1],
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
            'VALIDEZ' => 'VALIDEZ',
            'VIGENCIA' => 'VIGENCIA',
            'FOTO' => 'FOTO',
			'PRINCIPAL' => 'PRINCIPAL'
        ];
    }
}