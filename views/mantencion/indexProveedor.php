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
                <div  data-step="9" data-intro="Ingresa o actualiza los datos de un proveedor"  class="col-md-2">
<?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div data-step="10" data-intro="Elimina un proveedor" class="col-md-2">	
<?= Html::button('ELIMINAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'eliminar-button', 'id' => 'eliminar-button']) ?>
                </div>
                <div data-step="11" data-intro="Busca a los proveedores de la base de datos" class="col-md-2">	
<?= Html::button('BUSCAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'buscar-button', 'id' => 'buscar-button',"data-toggle"=>"modal","data-target"=>"#buscarModal"]) ?>
                </div>
                <div data-step="12" data-intro="Limpia el formulario" class="col-md-2">	
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="Esta formulario sirve para ingresar los proveedores de la optica" onclick="javascript:introJs().start();">
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-2">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div  class="col-md-12">
                    <div data-step="2" data-intro="Debe ingresar el nombre de la empresa proveedora" class="form-group">
<?= $form->field($model, 'nombreEmpresa')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Nombre de Empresa", "required" => true, "autofocus" => true, "maxlength" => "255", "size" => "255"])
        ->label("NOMBRE EMPRESA:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div  class="col-md-6">
                    <div data-step="3" data-intro="Debe ingresar el RUT de la empresa proveedora" class="form-group">
<?= $form->field($model, 'codigo')->textInput(["class" => "form-control guion-rut", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Rut", "required" => true, "maxlength" => "11", "size" => "11"])
                ->label("RUT:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div data-step="4" data-intro="Debe ingresar la direccion de la empresa proveedora" class="form-group">
<?= $form->field($model, 'direccion')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Direccion", "required" => true, "maxlength" => "255", "size" => "255"])
                        ->label("DIRECCIÓN:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div  class="col-md-6">
                    <div data-step="5" data-intro="Debe ingresar la ciudad de la empresa proveedora" class="form-group">
<?= $form->field($model, 'ciudad')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Ciudad", "required" => true, "maxlength" => "50", "size" => "50"])
                                ->label("CIUDAD:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div data-step="6" data-intro="Debe ingresar el correo electronico de la empresa proveedora" class="form-group">
<?= $form->field($model, 'mail')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Email", "required" => true, "maxlength" => "150", "size" => "150"])
                                        ->label("E-MAIL:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>		
            <div class="row">
                <div  class="col-md-6">
                    <div data-step="7" data-intro="Debe ingresar el telefono de la empresa proveedora" class="form-group">
<?= $form->field($model, 'telefono')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+(99)-9-9999-9999',])
                                                ->label("TELÉFONO:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div data-step="8" data-intro="Debe ingresar la persona de contacto de la empresa proveedora" class="form-group">
<?= $form->field($model, 'contacto')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Contacto", "required" => true, "maxlength" => "255", "size" => "255"])
                                                        ->label("CONTACTO:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>				

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
                                'NOMBRE_EMPRESA',
                                'CONTACTO',
                                'DIRECCION',
                                'MAIL',
                                'TELEFONO',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'IR',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["ID_PROVEEDOR"] != "0-0" || IS_NULL($model["ID_PROVEEDOR"])) {
                                                return '<button type="button" onClick="javascript:asignarProveedor(\'' . $model["ID_PROVEEDOR"] . '\',\'' . $model["NOMBRE_EMPRESA"] . '\',\'' . $model["CONTACTO"] . '\',\'' . $model["DIRECCION"] . '\',\'' . $model["CIUDAD"] . '\',\'' . $model["MAIL"]. '\',\'' . $model["TELEFONO"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
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
            if ($("#<?= $nombreModelLow ?>-codigo").val() == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe seleccionar un proveedor");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else {
                var id = $("#<?= $nombreModelLow ?>-codigo").val();
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/elimina-proveedor' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        id: id,
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                    },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {
                        if (data.respuesta[0] == "OK") {
                            $("#modTitulo").html("Validación");
                            $("#modBody").html("Proveedor eliminado con éxito");
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
                            cargaTree();
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

    function asignarProveedor(id,nombre,contacto,direccion,cuidad,mail,telefono){
        $("#<?= $nombreModelLow ?>-codigo").val(id + '-' + digitoVerificador(id));
        $("#<?= $nombreModelLow ?>-nombreempresa").val(nombre);
        $("#<?= $nombreModelLow ?>-contacto").val(contacto);
        $("#<?= $nombreModelLow ?>-direccion").val(direccion);
        $("#<?= $nombreModelLow ?>-ciudad").val(cuidad);
        $("#<?= $nombreModelLow ?>-mail").val(mail);
        $("#<?= $nombreModelLow ?>-telefono").val(telefono);
        
        $("#buscarModal").modal("toggle");
    }
        
    function digitoVerificador(rut) {
        // type check
        if (!rut || !rut.length || typeof rut !== 'string') {
            return -1;
        }
        // serie numerica
        var secuencia = [2, 3, 4, 5, 6, 7, 2, 3];
        var sum = 0;
        //
        for (var i = rut.length - 1; i >= 0; i--) {
            var d = rut.charAt(i)
            sum += new Number(d) * secuencia[rut.length - (i + 1)];
        }
        ;
        // sum mod 11
        var rest = 11 - (sum % 11);
        // si es 11, retorna 0, sino si es 10 retorna K,
        // en caso contrario retorna el numero
        return rest === 11 ? 0 : rest === 10 ? "K" : rest;
    }


</script>
