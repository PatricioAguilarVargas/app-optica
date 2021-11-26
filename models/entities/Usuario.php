<?php

namespace app\models\entities;

use Yii;

/**
 * This is the model class for table "brc_usuarios".
 *
 * @property string $RUT
 * @property string $DV
 * @property string $NOMBRE
 * @property string $USUARIO
 * @property string $CLAVE
 */
class Usuario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brc_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RUT', 'DV', 'NOMBRE', 'USUARIO', 'CLAVE','AVATAR','VIGENCIA'], 'required'],
            [['RUT'], 'number'],
            [['DV','VIGENCIA'], 'string', 'max' => 1],
            [['NOMBRE','AVATAR'], 'string', 'max' => 255],
            [['USUARIO', 'CLAVE'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RUT' => 'RUT',
            'DV' => 'DV',
            'NOMBRE' => 'NOMBRE',
            'USUARIO' => 'USUARIO',
            'CLAVE' => 'CLAVE',
            'AVATAR' => 'AVATAR',
            'VIGENCIA' => 'VIGENCIA',
        ];
    }
}
