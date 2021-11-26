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
$this->params['breadcrumbs']['codigos'] = ArrayHelper::map($codigos, 'CODIGO', 'DESCRIPCION');



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
                <div data-step="8" data-intro="Guarda la persona en el sistema" class="col-md-2">
<?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div data-step="9" data-intro="Elemina una persona del sistema" class="col-md-2">	
<?= Html::button('ELIMINAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'eliminar-button', 'id' => 'eliminar-button']) ?>
                </div>
                <div data-step="10" data-intro="Busca las personas guardadas en el sistema" class="col-md-2">	
<?= Html::button('BUSCAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'buscar-button', 'id' => 'buscar-button',"data-toggle"=>"modal","data-target"=>"#buscarModal"]) ?>
                </div>
                <div data-step="11" data-intro="limpia el formulario" class="col-md-2">	
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="Este formulario sirve para ingresar los clientes o doctores con los que trabajara el sistema" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-2">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div class="col-md-6">
                    <div data-step="2" data-intro="Debe elegir si es cliente o doctor" class="form-group">
                             <?=
                                $form->field($model, 'categoria')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['codigos'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("CÓDIGO:", ['class' => 'label label-default']);
                                ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div data-step="3" data-intro="Debe ingresar el RUT de la persona" class="form-group">
                        <?= $form->field($model, 'rut')->textInput(["class" => "form-control guion-rut", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "00000000-K", "required" => true, "maxlength" => "12", "size" => "12"])
                ->label("RUT:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>	
            <div class="row">
                <div class="col-md-6">
                    <div data-step="4" data-intro="Debe ingrear el nombre de la persona"  class="form-group">
                        <?= $form->field($model, 'nombre')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Nombre", "required" => true, "maxlength" => "255", "size" => "255"])
                        ->label("NOMBRE:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div data-step="5" data-intro="Debe ingresar la direccion de la persona" class="form-group">
                        <?= $form->field($model, 'direccion')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Dirección", "required" => true, "maxlength" => "255", "size" => "255"])
                                ->label("DIRECCIÓN:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>			
            <div class="row">
                <div  class="col-md-6">
                    <div data-step="6" data-intro="Debe ingresar el telefono de la persona" class="form-group">
                        <?= $form->field($model, 'telefono')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+(99)-9-9999-9999',])
                                        ->label("TELÉFONO:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div data-step="7" data-intro="Debe ingresar el correo electronico de la persona" class="form-group">
                        <?= $form->field($model, 'eMail')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "E-Mail", "required" => true, "maxlength" => "255", "size" => "255"])
                                ->label("E-MAIL:", ['class' => 'label label-default']); ?>
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
                <h4 class="modal-title text-center">BUSCAR PERSONAS REGISTRADAS</h4>
            </div>
            <div class="modal-body">
<?php //\yii\widgets\Pjax::begin(['id' => 'detalle', 'enablePushState' => false]); ?>
                <div class="row">
                    <div  class="col-md-1">	
                        <span class="label label-default text-right">CATEGORÍA:</span>
                    </div>
                    <div  class="col-md-3">	
                         <?= Select2::widget([
                            'data' => ArrayHelper::map($codigos, 'CODIGO', 'DESCRIPCION'),
                            'language' => 'es',
                            'name' => 'codigosBus',
                            'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2","id"=>"codigosBus","style" => 'width: 100%;'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                    <div  class="col-md-1">	
                        <span class="label label-default text-right">PERSONA:</span>
                    </div>
                    <div  class="col-md-3">	
                         <?= Select2::widget([
                            'data' => ArrayHelper::map($personas, 'RUT', 'NOMBRE'),
                            'language' => 'es',
                            'name' => 'personasBus',
                            'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2","id"=>"personasBus","style" => 'width: 100%;'],
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
                        <?php \yii\widgets\Pjax::begin(['id' => 'personaBus', 'enablePushState' => false]); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'RUT',
                                'DV',
                                'CAT_PERSONA',
                                'NOMBRE',
                                'DIRECCION',
                                'TELEFONO',
                                'EMAIL',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'IR',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["RUT"] != "0-0" || IS_NULL($model["RUT"])) {
                                                return '<button type="button" onClick="javascript:asignarPersona(\'' . $model["RUT"] . '\',\'' . $model["DV"] . '\',\'' . $model["CAT_PERSONA"] . '\',\'' . $model["NOMBRE"] . '\',\'' . $model["DIRECCION"] . '\',\'' . $model["TELEFONO"]. '\',\'' . $model["EMAIL"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
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
         if ($("#codigosBus").find("option[value='TODOS']").length) {
            $("#codigosBus").val('TODOS').trigger("change.select2");
        } else { 
            var newState = new Option('TODOS', 'TODOS', true, true);
            $("#codigosBus").prepend(newState).trigger('change.select2');
            $("#codigosBus").val('TODOS').trigger("change.select2");
        } 
         if ($("#personasBus").find("option[value='TODOS']").length) {
            $("#personasBus").val('TODOS').trigger("change.select2");
        } else { 
            var newState = new Option('TODOS', 'TODOS', true, true);
            $("#personasBus").prepend(newState).trigger('change.select2');
            $("#personasBus").val('TODOS').trigger("change.select2");
        } 
        $("#eliminar-button").on('click', function () {
            if ($("#<?= $nombreModelLow ?>-categoria").val() == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe seleccionar la categoría de la persona");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if ($("#<?= $nombreModelLow ?>-rut").val() == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar el rut");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else{
                var tipo = $("#<?= $nombreModelLow ?>-categoria").val();
                var rut = $("#<?= $nombreModelLow ?>-rut").val().split("-")[0];
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/elimina-persona' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        _rut: rut,
                        _tipo: tipo,
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
                            $("#<?= $nombreModelLow ?>-categoria").val("").trigger("change.select2");
                            $("#<?= $nombreModelLow ?>-nombre").val("");
                            $("#<?= $nombreModelLow ?>-rut").val("");
                            $("#<?= $nombreModelLow ?>-direccion").val("");
                            $("#<?= $nombreModelLow ?>-email").val("");
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
            var catBus = $("#codigosBus").val();
            var perBus = $("#personasBus").val();
           
            var Url = '<?= Yii::$app->request->absoluteUrl . '&catBus=' ?>' + catBus + '&perBus=' + perBus;
            $.pjax.reload({container: "#personaBus", url: Url, replace: false});
           
        });
    
    }

    function asignarPersona(rut,dv,categoria,nombre,direccion,telefono,mail){
        $("#<?= $nombreModelLow ?>-categoria").val(categoria).trigger("change.select2");
        $("#<?= $nombreModelLow ?>-rut").val(rut + '-' + dv);
        $("#<?= $nombreModelLow ?>-nombre").val(nombre);
        $("#<?= $nombreModelLow ?>-direccion").val(direccion);
        $("#<?= $nombreModelLow ?>-email").val(mail);
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