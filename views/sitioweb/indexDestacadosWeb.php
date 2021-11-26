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
$this->params['breadcrumbs']['vigencia'] = ArrayHelper::map($vigencia, 'CODIGO', 'DESCRIPCION');

$posi = strrpos(get_class($model), "\\");
$nombreModelLow = strtolower(substr(get_class($model), $posi + 1));
$nombreModel = substr(get_class($model), $posi + 1);
?>
<?php
$form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['enctype' => 'multipart/form-data']
        ]);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div data-step="5" data-intro="Se presiona el boton para guardar los datos" class="col-md-2">
<?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div data-step="7" data-intro="busca productos destacados en el sitio web" class="col-md-2">	
<?= Html::button('BUSCAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'buscar-button', 'id' => 'buscar-button',"data-toggle"=>"modal","data-target"=>"#buscarModal"]) ?>
                </div>
                <div data-step="6" data-intro="limpia el formulario de los datos ingresados" class="col-md-2">	
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="Esta formulario sirve para ingresar los productos destacados que se muestran al inicio de la pagina web" onclick="javascript:introJs().start();">
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
                    <div data-step="2" data-intro="Se debe ingresar la descripcion relacionado con el producto destacado" class="form-group" >
                        <?= $form->field($model, 'titulo')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Titulo", "required" => true, "maxlength" => "50", "size" => "50"])
                                        ->label("TITULO:", ['class' => 'label label-default']); ?>
                        <?= $form->field($model, 'id')->hiddenInput(["id" => "destacadoswebform-id"])->label(false); ?>
                    </div>
                </div>
            </div>
			<div class="row">
                <div data-step="3" data-intro="Se debe seleccionar si se muestra el  producto destacado en la pagina web" class="col-md-12">
                    <div class="form-group">
                        <?=
                                $form->field($model, 'vigencia')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['vigencia'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("VIGENCIA:", ['class' => 'label label-default']);
                                ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div data-step="4" data-intro="Se debe seleccionar la foto relacionada con el producto destacado" class="col-md-12">
                    <div class="form-group">
                        <?= $form->field($model, 'foto')->fileInput(["class" => "filestyle", "data-btnClass" => "btn btn-block btn-sistema btn-flat"])
                                ->label("FOTO:", ['class' => 'label label-default']); ?>
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
                <h4 class="modal-title text-center">BUSCAR DSETACADOS WEB</h4>
            </div>
            <div class="modal-body">
<?php //\yii\widgets\Pjax::begin(['id' => 'detalle', 'enablePushState' => false]); ?>
                <div class="row">
                    <div  class="col-md-2">	
                        
                    </div>
                    <div  class="col-md-2">	
                        <span class="label label-default text-right">SELECCIONAR VIGENCIA:</span>
                    </div>
                    <div  class="col-md-4">
                         <?= Select2::widget([
                            'data' => $this->params['breadcrumbs']['vigencia'],
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
                                'TITULO',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'IR',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["ID"] != "0" || IS_NULL($model["ID"])) {
                                                return '<button type="button" onClick="javascript:asignarCodigo(\'' . $model["ID"] . '\',\'' . $model["TITULO"] . '\',\'' . $model["VIGENCIA"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
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

        document.forms["login-form"]["<?= $nombreModelLow ?>-id"].value = "";
        document.forms["login-form"]["<?= $nombreModelLow ?>-titulo"].value = "";
        $("#btnBusProveProd").click(function () {
            var tipBus = $("#tipoBuscar").val();
           
            var Url = '<?= Yii::$app->request->absoluteUrl . '&tipBus=' ?>' + tipBus ;
            $.pjax.reload({container: "#codigosBus", url: Url, replace: false});
           
        });
    
    }

    function asignarCodigo(id,titulo,vigencia){

        $("#<?= $nombreModelLow ?>-vigencia").val(vigencia).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-id").val(id);
        $("#<?= $nombreModelLow ?>-titulo").val(titulo);
        $("#buscarModal").modal("toggle");
    }
    
</script>