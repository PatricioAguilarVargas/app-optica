<?php

namespace app\models\entities;

use Yii;

class ComprasDetalle extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brc_compra_detalle';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['FOLIO', 'CANTIDAD', 'LOTE', 'ID_PRODUC_PROVE'], 'required'],
            [['CANTIDAD', 'ID_PRODUC_PROVE'], 'integer'],
            [['FOLIO'], 'string', 'max' => 12],
            [['LOTE'], 'string', 'max' => 20],
        ];
    }

}
