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
$this->params['breadcrumbs']['color'] = ArrayHelper::map($color, 'CODIGO', 'DESCRIPCION');
$this->params['breadcrumbs']['forma'] = ArrayHelper::map($forma, 'CODIGO', 'DESCRIPCION');
$this->params['breadcrumbs']['material'] = ArrayHelper::map($material, 'CODIGO', 'DESCRIPCION');
$this->params['breadcrumbs']['marca'] = ArrayHelper::map($marca, 'CODIGO', 'DESCRIPCION');
$this->params['breadcrumbs']['producto'] = ArrayHelper::map($producto, 'PARAM1', 'DESCRIPCION');
$this->params['breadcrumbs']['vigencia'] = ArrayHelper::map($vigencia, 'CODIGO', 'DESCRIPCION');
$this->params['breadcrumbs']['tipo'] = ArrayHelper::map($tipo, 'CODIGO', 'DESCRIPCION');

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
                <div data-step="12" data-intro="Guarda los datos" class="col-md-2">
<?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div data-step="14" data-intro="te permiste buscar los registros ingresados en el sistema" class="col-md-2">	
<?= Html::button('BUSCAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'buscar-button', 'id' => 'buscar-button',"data-toggle"=>"modal","data-target"=>"#buscarModal"]) ?>
                </div>
                <div data-step="13" data-intro="limpia los datos del formulario" class="col-md-2">	
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="Esta formulario sirve para ingresar los marcos que se mostraran en el sitio web de la optica" onclick="javascript:introJs().start();">
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-4">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div data-step="2" data-intro="Se selecciona el marco a mostrar en el sitio web" class="col-md-6">
                    <div  class="form-group">
                            <?=
                                $form->field($model, 'codigo')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['producto'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("PRODUCTOS:", ['class' => 'label label-default']);
                            ?>                    
                    </div>
                </div>
                <div data-step="3" data-intro="Se selecciona el tipo de lente para mostrar en los catalogos del sitio web" class="col-md-6">
                            <?=
                                $form->field($model, 'tipo')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['tipo'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("TIPO DE LENTE:", ['class' => 'label label-default']);
                            ?>
                    </div>
            </div>
            <br>	
            <div class="row">
                <div data-step="4" data-intro="Se debe seleccionar la marca del marco a mostrar" class="col-md-6">
                    <div class="form-group">
                            <?=
                                $form->field($model, 'marca')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['marca'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("MARCA:", ['class' => 'label label-default']);
                            ?>
                    </div>
                </div>
                <div data-step="5" data-intro="Se debe ingresar el modelo " class="col-md-6">
                    <div class="form-group">
                            
                        <?= $form->field($model, 'modelo')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "modelo", "required" => true, "maxlength" => "50", "size" => "50"])
                                    ->label("MODELO:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>	
            <div class="row">
                <div data-step="6" data-intro="se debe elegir el tipo de material del marco" class="col-md-6">
                    <div class="form-group">
                            <?=
                                $form->field($model, 'material')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['material'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("MATERIAL:", ['class' => 'label label-default']);
                            ?>
                    </div>
                </div>
                <div data-step="7" data-intro="Se debe elegir el color del marco" class="col-md-6">
                    <div class="form-group">
                            <?=
                                $form->field($model, 'color')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['color'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("COLOR:", ['class' => 'label label-default']);
                            ?>
                    </div>
                </div>
            </div>
            <br>	
            <div class="row">
                <div data-step="8" data-intro="se debe elegir la forma del marco" class="col-md-6">
                    <div class="form-group">
                            <?=
                                $form->field($model, 'forma')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['forma'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("FORMA:", ['class' => 'label label-default']);
                            ?>
                    </div>
                </div>
                <div data-step="9" data-intro="se decide si se muestra o no en la pagina web" class="col-md-6">
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
            <br>	
            <div class="row">
                <div data-step="10" data-intro="es la primera foto que aparece del marco en el catalogo" class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'foto1')->fileInput(["class" => "filestyle", "data-btnClass" => "btn-block btn-sistema btn-flat"])
                                    ->label("FOTO1:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div data-step="11" data-intro="Es la foto qeu aparece al pasar sobre el marco que aparecen en el catalogo" class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'foto2')->fileInput(["class" => "filestyle", "data-btnClass" => "btn-block btn-sistema btn-flat"])
                                    ->label("FOTO2:", ['class' => 'label label-default']); ?>
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
                <h4 class="modal-title text-center">BUSCAR PRODUCTOS WEB</h4>
            </div>
            <div class="modal-body">
<?php //\yii\widgets\Pjax::begin(['id' => 'detalle', 'enablePushState' => false]); ?>
                <div class="row">
                    <div  class="col-md-2">	
                        
                    </div>
                    <div  class="col-md-2">	
                        <span class="label label-default text-right">SELECCIONAR PRODUCTO:</span>
                    </div>
                    <div  class="col-md-4">
                         <?= Select2::widget([
                            'data' => $this->params['breadcrumbs']['producto'],
                            'language' => 'es',
                            'name' => 'productoBuscar',
                            'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2","id"=>"productoBuscar","style" => 'width: 100%;'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>		
                    <div  class="col-md-2">	
                        <button type="button" id="btnBusProducto" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
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
                        <?php \yii\widgets\Pjax::begin(['id' => 'prodBus', 'enablePushState' => false]); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'DESCRIPCION',
                                'VIGENCIA',
                                'VALOR',
                                'MODELO',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'IR',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["CODIGO"] != "0-0" || IS_NULL($model["CODIGO"])) {
                                                return '<button type="button" onClick="javascript:asignarProducto(\'' . $model["CODIGO"] . '\',
                                                                                                                  \'' . $model["COD_TIPO"] . '\',
                                                                                                                  \'' . $model["COD_MARCA"] . '\',
                                                                                                                  \'' . $model["MODELO"] . '\',
                                                                                                                  \'' . $model["COD_MATERIAL"] . '\',
                                                                                                                  \'' . $model["COD_COLOR"] . '\',
                                                                                                                  \'' . $model["COD_FORMA"] . '\',
                                                                                                                  \'' . $model["VIGENCIA"] . '\')"
                                                         class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
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

        $('select option[value="0"]').attr("selected", true);
        $("#<?= $nombreModelLow ?>-marca").trigger("chosen:updated");
        $("#<?= $nombreModelLow ?>-material").trigger("chosen:updated");
        $("#<?= $nombreModelLow ?>-color").trigger("chosen:updated");
        $("#<?= $nombreModelLow ?>-forma").trigger("chosen:updated");
        $("#<?= $nombreModelLow ?>-vigencia").trigger("chosen:updated");
        $("#<?= $nombreModelLow ?>-tipo").trigger("chosen:updated");
<?php if ($enviado == "SI") { ?>
            $("#modTitulo").html("Validación");
            $("#modBody").html("Producto guardado con exito");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-success fade");
            $("#myModal").modal();

<?php } ?>
<?php if ($enviado == "ERROR") { ?>
            $("#modTitulo").html("Validación");
            $("#modBody").html("El producto no ha sido guardado");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-danger fade");
            $("#myModal").modal();

<?php } ?>
        if ($("#productoBuscar").find("option[value='TODOS']").length) {
            $("#productoBuscar").val('TODOS').trigger("change.select2");
        } else { 
            var newState = new Option('TODOS', 'TODOS', true, true);
            $("#productoBuscar").prepend(newState).trigger('change.select2');
            $("#productoBuscar").val('TODOS').trigger("change.select2");
        } 
        $("#btnBusProducto").click(function () {
            var proBus = $("#productoBuscar").val();
            var Url = '<?= Yii::$app->request->absoluteUrl . '&proBus=' ?>' + proBus ;
            $.pjax.reload({container: "#prodBus", url: Url, replace: false});
           
        });
    }
    
    function asignarProducto(codigo,tipo,marca,modelo,material,color,forma,vigencia){

        $("#<?= $nombreModelLow ?>-codigo").val(codigo).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-tipo").val(tipo).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-marca").val(marca).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-vigencia").val(vigencia).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-material").val(material).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-color").val(color).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-forma").val(forma).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-modelo").val(modelo);
        $("#buscarModal").modal("toggle");
    }

</script>