<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\models\entity\Perfiles;
use kartik\select2\Select2;
use kartik\date\DatePicker;

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['tipo'] = ArrayHelper::map($tipo, 'TIPO', 'TIPO');

$posi = strrpos(get_class($model), "\\");
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
                <div data-step="4" data-intro="Guarda el nuevo codigo" class="col-md-2">
<?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div data-step="5" data-intro="Busca los codigos del sistema" class="col-md-2">	
<?= Html::button('BUSCAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'buscar-button', 'id' => 'buscar-button',"data-toggle"=>"modal","data-target"=>"#buscarModal"]) ?>
                </div>
                <div data-step="6" data-intro="Limpia el formulario" class="col-md-2">	
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="Este formulario sirve para ingresar los codigos que usa el sistema de forma general. Esta pantalla la ocupa solo el desarrollador del sistema" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-4">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div  class="col-md-12">
                    <div data-step="2" data-intro="Debe elegir a que tipo se va ingresar el nuevo codigo" class="form-group">
                        <?=
                                $form->field($model, 'tipo')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['tipo'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("TIPO:", ['class' => 'label label-default']);
                                ?>
                    </div>
                </div>
            </div>
            <br>	
            <div class="row">
                <div  class="col-md-12">
                    <div data-step="3" data-intro="La descripcion del nuevo codigo" class="form-group">
                        <?= $form->field($model, 'descripcion')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Descripciòn", "required" => true, "maxlength" => "50", "size" => "50"])
                                        ->label("DESCRIPCIÓN:", ['class' => 'label label-default']); ?>
                        <?= $form->field($model, 'codigo')->hiddenInput()->label(false); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="buscarModal" role="dialog">
    <div class="modal-dialog" style="width: 80% !important;" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header headModal">
                <h4 class="modal-title text-center">BUSCAR CÓDIGOS GENERALES</h4>
            </div>
            <div class="modal-body">
<?php //\yii\widgets\Pjax::begin(['id' => 'detalle', 'enablePushState' => false]); ?>
                <div class="row">
                    <div  class="col-md-2">	
                        
                    </div>
                    <div  class="col-md-2">	
                        <span class="label label-default text-right">SELECCIONAR TIPO:</span>
                    </div>
                    <div  class="col-md-4">
                         <?= Select2::widget([
                            'data' => $this->params['breadcrumbs']['tipo'],
                            'language' => 'es',
                            'name' => 'tipoBuscar',
                            'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2","id"=>"tipoBuscar","style" => 'width: 100%;'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>		
                    <div  class="col-md-2">	
                        <button type="button" id="btnBusProveProd" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
                    </div>
					<div  class="col-md-2">	
                        <button type="button" class="btn btn-block btn-sistema btn-flat" data-dismiss="modal">CANCELAR</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div  class="col-md-12">
                    </div>
                </div>
                <hr class="linea">
                <div class="row">
                    <div  class="col-md-12">	
                        <?php \yii\widgets\Pjax::begin(['id' => 'codigosBus', 'enablePushState' => false]); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'TIPO',
                                'DESCRIPCION',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'IR',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["TIPO"] != "0-0" || IS_NULL($model["TIPO"])) {
                                                return '<button type="button" onClick="javascript:asignarCodigo(\'' . $model["TIPO"] . '\',\'' . $model["CODIGO"] . '\',\'' . $model["DESCRIPCION"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
                                            } else {
                                                return "";
                                            }
                                        }
                                    ],
                                ],
                            ],
                            'tableOptions' => [
                                'id' => 'tblOpePac',
                                'class' => "table table-striped table-bordered"
                            ],
                        ]);
                        ?>
                        <?php \yii\widgets\Pjax::end(); ?>
                    </div>
                </div>

                <div class="modal-footer">
                    
                </div>
                <?php //\yii\widgets\Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function initialComponets() {
        document.forms["login-form"]["<?= $nombreModelLow ?>-tipo"].value = "";
        document.forms["login-form"]["<?= $nombreModelLow ?>-codigo"].value = "";
        document.forms["login-form"]["<?= $nombreModelLow ?>-descripcion"].value = "";
        $("#btnBusProveProd").click(function () {
            var tipBus = $("#tipoBuscar").val();
           
            var Url = '<?= Yii::$app->request->absoluteUrl . '&tipBus=' ?>' + tipBus ;
            $.pjax.reload({container: "#codigosBus", url: Url, replace: false});
           
        });
    
    }

    function asignarCodigo(tipo,codigo,descripcion){

        $("#<?= $nombreModelLow ?>-tipo").val(tipo).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-descripcion").val(descripcion);
        $("#<?= $nombreModelLow ?>-codigo").val(codigo);
        $("#buscarModal").modal("toggle");
    }
    
</script>