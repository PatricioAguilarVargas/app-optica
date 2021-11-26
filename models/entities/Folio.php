<?php

namespace app\models\entities;

use Yii;

class Folio extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_folio';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['RUT_USUARIO', 'FOLIO', 'OPERACION'], 'required'],
            [['RUT_USUARIO'], 'integer'],
            [['FOLIO'], 'string', 'max' => 12],
            [['OPERACION'], 'string', 'max' => 15],
        ];
    }

}
