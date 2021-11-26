<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['producto'] = ArrayHelper::map($producto, 'CODIGO', 'DESCRIPCION');
$this->params['breadcrumbs']['proveedor'] = ArrayHelper::map($proveedor, 'ID_PROVEEDOR', 'NOMBRE_EMPRESA');
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
                <div data-step="5" data-intro="Guarda la asignacion del producto al proveedor elegido" class="col-md-2">
<?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div data-step="6" data-intro="Elimina una asignacion" class="col-md-2">	
<?= Html::button('ELIMINAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'eliminar-button', 'id' => 'eliminar-button']) ?>
                </div>
                <div data-step="7" data-intro="Busca las asignaciones ingresadas al sistema" class="col-md-2">	
<?= Html::button('BUSCAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'buscar-button', 'id' => 'buscar-button',"data-toggle"=>"modal","data-target"=>"#buscarModal"]) ?>
                </div>
                <div data-step="8" data-intro="Limpia la pantalla" class="col-md-2">	
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="Esta formulario sirve para reconocer que productos vienen de los proveedores" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-2">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div  class="col-md-6">
                    <div  data-step="2" data-intro="Debe elegir al proveedor. Este debe esta ingresado en la pantalla de proveedores" class="form-group">
                        <?= $form->field($model, 'id_proveedor')->widget(Select2::classname(), [
                                'data' => $this->params['breadcrumbs']['proveedor'],
                                'language' => 'es',
                                'options' => ['placeholder' => 'ELEGIR', "id"=>"id_proveedor", "class" => "form-control select2", "style" => 'width: 100%;'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label("PROVEEDOR:", ['class' => 'label label-default']);
                        ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div data-step="3" data-intro="Debe elegir el producto. Este debe ser ingresado en la pantalla de productos" class="form-group">
                        <?= $form->field($model, 'id_producto')->widget(Select2::classname(), [
                                'data' => $this->params['breadcrumbs']['producto'],
                                'language' => 'es',
                                'options' => ['placeholder' => 'ELEGIR', "id"=>"id_producto","class" => "form-control select2", "style" => 'width: 100%;'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label("PRODUCTO:", ['class' => 'label label-default']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div  class="col-md-6">
                    <div data-step="4" data-intro="Debe ingresar el precio de compra del producto de acuerdo a el proveedor" class="form-group">
<?= $form->field($model, 'v_compra')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "000000000000", "required" => true, "maxlength" => "12", "size" => "12"])
                                ->label("PRECIO DE COMPRA:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div  class="col-md-6">

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
                <h4 class="modal-title text-center">BUSCAR PRODUCTOS POR PROVEEDORES</h4>
            </div>
            <div class="modal-body">
<?php //\yii\widgets\Pjax::begin(['id' => 'detalle', 'enablePushState' => false]); ?>
                <div class="row">
                    <div  class="col-md-2">	
                        
                    </div>
                    <div  class="col-md-2">	
                        <span class="label label-default text-right">SELECCIONAR PROVEEDOR:</span>
                    </div>
                    <div  class="col-md-4">
                         <?= Select2::widget([
                            'data' => $this->params['breadcrumbs']['proveedor'],
                            'language' => 'es',
                            'name' => 'proveeBuscar',
                            'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2","id"=>"proveeBuscar","style" => 'width: 100%;'],
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
                        <?php \yii\widgets\Pjax::begin(['id' => 'proveedorProductoBus', 'enablePushState' => false]); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'DESCRIPCION',
                                'VALOR_PROVEEDOR',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'IR',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["ID_PRODUCTO"] != "0-0" || IS_NULL($model["ID_PRODUCTO"])) {
                                                return '<button type="button" onClick="javascript:asignarProducto(\'' . $model["ID_PRODUCTO"] . '\',\'' . $model["ID_PROVEEDOR"] . '\',\'' . $model["VALOR_PROVEEDOR"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
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
        $("#eliminar-button").on('click', function () {
            if ($("#<?= $nombreModelLow ?>-id_proveedor").val() == "" || $("#<?= $nombreModelLow ?>-id_proveedor").val() == "ELEGIR") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe seleccionar un proveedor");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if ($("#<?= $nombreModelLow ?>-id_producto").val() == "" || $("#<?= $nombreModelLow ?>-id_producto").val() == "ELEGIR") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe seleccionar un producto");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else {
                var proveedor = $("#id_proveedor").val();
                var producto = $("#id_producto").val();
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/elimina-prove-prod' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        _proveedor: proveedor,
                        _producto: producto,
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                    },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {
                        if (data.respuesta[0] == "OK") {
                            $("#modTitulo").html("Validación");
                            $("#modBody").html("El producto asignado al proveedor se elimino con éxito");
                            $("#myModal").removeClass();
                            $("#myModal").addClass("modal modal-success fade");
                            $("#myModal").modal();
                            $("#<?= $nombreModelLow ?>-codigo").val("");
                            $("#<?= $nombreModelLow ?>-nombreempresa").val("");
                            $("#<?= $nombreModelLow ?>-contacto").val("");
                            $("#<?= $nombreModelLow ?>-direccion").val("");
                            $("#<?= $nombreModelLow ?>-ciudad").val("");
                            $("#<?= $nombreModelLow ?>-mail").val("");
                            $("#<?= $nombreModelLow ?>-telefono").val("");

                        } else {
                            $("#modTitulo").html("Validación");
                            $("#modBody").html(data.respuesta[0]);
                            $("#myModal").removeClass();
                            $("#myModal").addClass("modal modal-danger fade");
                            $("#myModal").modal();
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        $("#modTitulo").html("Validación");
                        $("#modBody").html("Fallo en el sistema. Error: " + request.responseText);
                        $("#myModal").removeClass();
                        $("#myModal").addClass("modal modal-danger fade");
                        $("#myModal").modal();
                    }
                });
            }
        });
        $("#btnBusProveProd").click(function () {
            var id = $("#proveeBuscar").val();
            if (!id == "") {
                var Url = '<?= Yii::$app->request->absoluteUrl . '&proBus=' ?>' + id;
                $.pjax.reload({container: "#proveedorProductoBus", url: Url, replace: false});
            } else {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar un proveedor");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });
    }
    
    function asignarProducto(producto,proveedor,valor){
        $("#productoproveedorform-v_compra").val(valor);
        $("#id_proveedor").val(proveedor).trigger('change.select2');
        $("#id_producto").val(producto).trigger('change.select2');
        $("#buscarModal").modal("toggle");
    }
</script>
