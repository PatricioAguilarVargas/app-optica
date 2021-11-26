<?php

namespace app\models\entities;

use Yii;

/**
 * This is the model class for table "brc_perfiles".
 *
 * @property integer $ID_PADRE
 * @property integer $ID_HIJO
 * @property string $DESCRIPCION
 * @property string $IMG
 * @property string $RUTA
 */
class Perfiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brc_perfiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_PADRE', 'ID_HIJO', 'DESCRIPCION', 'IMG', 'RUTA'], 'required'],
            [['ID_PADRE', 'ID_HIJO'], 'integer'],
            [['DESCRIPCION', 'RUTA'], 'string', 'max' => 255],
            [['IMG'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_PADRE' => 'ID PADRE',
            'ID_HIJO' => 'ID HIJO',
            'DESCRIPCION' => 'DESCRIPCIÓN',
            'IMG' => 'IMAGEN',
            'RUTA' => 'RUTA',
        ];
    }
}
