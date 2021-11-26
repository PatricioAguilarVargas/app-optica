<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\grid\DataColumn;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['producto'] = ArrayHelper::map($producto, 'ID_HIJO', 'DESCRIPCION');

$indice = 1;
$posi = strrpos(get_class($model), "\\");
$largo = strlen(get_class($model));
$nombreModelLow = strtolower(substr(get_class($model), $posi + 1));
$nombreModel = substr(get_class($model), $posi + 1);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'login-form',
        ]);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div  class="col-md-1">	

                </div>
                <div  class="col-md-2">	
                    <span class="label label-default text-right">SELECCIONAR PRODUCTO:</span>
                </div>
                <div data-step="2" data-intro="Se debe elegir el producto que se quiere filtrar" class="col-md-4">	
                     <?=
                        $form->field($model, 'producto')->widget(Select2::classname(), [
                            'data' => $this->params['breadcrumbs']['producto'],
                            'language' => 'es',
                            'options' => ["class" => "form-control select2", "style" => 'width: 100%;'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);
                        ?>

                </div>						
                <div data-step="3" data-intro="se presiona buscar" class="col-md-2 text-left">
                    <button type="button" id="btnBusPro" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
                </div>	
                <div class="col-md-2">	
                    <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="Este formulario muestra el stock de productos que existe en la optica. esto segun las compras y las ventas" onclick="javascript:introJs().start();">
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div  class="col-md-1">	

                </div>
            </div>
            <hr class="linea">
            <div class="row">

                <div data-step="4" data-intro="se muestra el resultado de la busqueda" class="col-md-12">	
                    <?php \yii\widgets\Pjax::begin(['id' => 'inventario', 'enablePushState' => false]); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'DESCRIPCION',
                                'format' => 'text',
                                'label' => 'DESCRIPCIÓN',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'COMPRA',
                                'format' => 'text',
                                'label' => 'COMPRA',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'VENTA',
                                'format' => 'text',
                                'label' => 'VENTA',
                            ],
                            [
                                'label' => 'STOCK',
                                'format' => 'raw',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data) {
                                    //var_dump($data);
                                    return $data["COMPRA"] - $data["VENTA"];
                                },
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'STOCK_CRITICO',
                                'format' => 'text',
                                'label' => 'MÍNIMO',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'VIGENCIA',
                                'format' => 'text',
                                'label' => 'VIGENCIA',
                            ],
                        ],
                        'rowOptions' => function ($model, $index, $widget, $grid) {
                            if (($model["COMPRA"] - $model["VENTA"]) < $model["STOCK_CRITICO"])
                                return ['style' => 'color:RED;'];
                            else
                                return ['style' => 'color:BLACK;'];
                        },
                        'tableOptions' => [
                            'id' => 'tblInv',
                            'class' => "table table-striped table-bordered"
                        ],
                    ]);
                    ?>
                    <?php \yii\widgets\Pjax::end(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">

    function initialComponets() {
        $("#btnBusPro").click(function () {
            var proBus = $("#<?= $nombreModelLow ?>-producto").val();
            var Url = '<?= Yii::$app->request->hostInfo . ':' . Yii::$app->request->serverPort . Yii::$app->request->scriptUrl . '?r=inventario/index-inventario' . str_replace("rt=", "id=", $rutaR) . '' ?>';
            if (proBus != "") {
                Url = Url + '&producto=' + proBus;
            }
            $.pjax.reload({container: "#inventario", url: Url, replace: false});
        });
        if ($("#<?=$nombreModelLow?>-producto").find("option[value='TODOS']").length) {
            $("#<?=$nombreModelLow?>-producto").val('TODOS').trigger("change.select2");
        } else { 
            var newState = new Option('TODOS', 'TODOS', true, true);
            $("#<?=$nombreModelLow?>-producto").prepend(newState).trigger('change.select2');
            $("#<?=$nombreModelLow?>-producto").val('TODOS').trigger("change.select2");
        } 
    }

</script>  