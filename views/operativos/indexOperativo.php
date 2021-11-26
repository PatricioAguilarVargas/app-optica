<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\models\entity\Perfiles;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['doctor'] = ArrayHelper::map($doctor, 'RUT', 'NOMBRE');
//$this->params['breadcrumbs']['proveedor'] = ArrayHelper::map($proveedor,'ID_PROVEEDOR','NOMBRE_EMPRESA');
$indice = 1;
$posi = strrpos(get_class($model), "\\");
$largo = strlen(get_class($model));
$nombreModelLow = strtolower(substr(get_class($model), $posi + 1));
$nombreModel = substr(get_class($model), $posi + 1);
//var_dump($model);
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
                <div class="col-md-2" data-step="4" data-intro="limpia los datos del formulario">
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2" data-step="5" data-intro="Te lleva a la pantalla para agendar un operativo">
<?= Html::a('AGENDAR OPERATIVO', Yii::$app->request->baseUrl . '/index.php?r=operativos/index-agrega-operativo&id=310000000&t=AGENDAR%20OPERATIVO', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'salir-button']) ?>			
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="En esta pantalla se asignan los valores para que figuren en las recetas medicas que se almacenan en el sistema" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-6">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div id="tomaHora">
                <div class="row">

                    <div class="col-md-3" data-step="2" data-intro="Selecciona la fecha de busqueda del operativo">
                            <label class="label label-default">FECHA BUSQUEDA:</label>
                            <?= 
                                DatePicker::widget([
                                        'value' => (is_null($model->dia))? date('d/m/Y') : $model->dia,
                                        'language' => 'es',
                                        'name' => "fechaOperativo",
                                        'type' =>  DatePicker::TYPE_INPUT,
                                        'pickerButton' => [
                                            'icon'=>'ok',
                                        ],
                                        'options' => ['placeholder' => 'ELEGIR','id'=>'fechaOperativo'],
                                        'pluginOptions' => [
                                                'format' => 'dd/mm/yyyy',
                                                'todayHighlight' => true
                                        ]
                                ]);
                            ?>
                    </div>
                    <div class="col-md-9" data-step="3" data-intro="Operativos vigentes de la fecha seleccionada">
                        <label class="label label-default">OPERATIVO:</label>
                        <?= 
                            Select2::widget([
                                'name' => 'dataOperativo', 
                                'theme' => Select2::THEME_BOOTSTRAP,                              
                                'options' => ['placeholder' => 'Seleccione un operativo','id'=>'dataOperativo'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'width' => '100%'
                                ],
                            ]);
                        ?>
                                    
                    </div>
                </div>
            </div>
            <hr class="linea">
            <div id="detalleOperativo">
                <div class="row">
                    <div class="col-md-10">
                        <p class="lead">LISTA DE PACIENTES</p>
                    </div>
                    <div class="col-md-2 text-right">
                        <a id="asignaPaciente" href="" class="btn btn-block btn-sistema btn-flat">AGENDAR PACIENTE</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="row">
                                <table id="detalleOperativoTabla" class="table table-bordered table-hover table-condensed">
                                    <thead>
                                    <th>
                                        RUT
                                    </th>
                                    <th>
                                        NOMBRE
                                    </th>
                                    <th >
                                        ASISTENCIA
                                    </th>
                                    <th width="10%">
                                        RECETA
                                    </th>
                                    </tr>
                                    </thead>
                                    <tbody> 

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<?php ActiveForm::end(); ?>
<form action="<?= Yii::$app->request->baseUrl . '/index.php?r=operativos/guarda-receta' ?>" method="post" id="formGuaRec">
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="recetaModal" role="dialog">
        <div class="modal-dialog" style="width: 80% !important;" >
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header headModal">
                    <h4 class="modal-title text-center">RECETA MÉDICA</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="label label-default">RUT:</span>
                            <div class="form-group">
                                <input type="text" class="form-control" name="recRut" id="recRut" readonly="readonly">
                                <input name="recRutDoc" id="recRutDoc" type="hidden" />
                                <input name="recHora" id="recHora" type="hidden" />
                                <input name="recFecha" id="recFecha" type="hidden" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-default">NOMBRE:</span>
                            <div class="form-group">
                                <input type="text" class="form-control" name="recNombre" id="recNombre" readonly="readonly">
                            </div>
                        </div>
                    </div>
                    <hr class="linea">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center"><strong>LEJOS</strong></h4>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <span class="label label-default">OJO D.:</span>
                            <div class="input-group">
                                <span class="input-group-addon" style="background-color: #EEEEEE"><span class="glyphicon glyphicon-eye-open"></span></span>
                                <input type="text" class="form-control" name="recOjoDerLejEsf" id="recOjoDerLejEsf" maxlength="6" size="6" placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">-</span>
                                <input type="text" class="form-control" name="recOjoDerLejCil" id="recOjoDerLejCil" maxlength="6" size="6"  placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">/</span>
                                <input type="text" class="form-control" name="recOjoDerLejGra" id="recOjoDerLejGra" maxlength="5" size="5"  placeholder="000°">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <span class="label label-default">OJO I.:</span>
                            <div class="input-group">
                                <span class="input-group-addon" style="background-color: #EEEEEE"><span class="glyphicon glyphicon-eye-open"></span></span>
                                <input type="text" class="form-control" name="recOjoIzqLejEsf" id="recOjoIzqLejEsf" maxlength="6" size="6" placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">-</span>
                                <input type="text" class="form-control" name="recOjoIzqLejCil" id="recOjoIzqLejCil" maxlength="6" size="6"  placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">/</span>
                                <input type="text" class="form-control" name="recOjoIzqLejGra" id="recOjoIzqLejGra" maxlength="5" size="5"  placeholder="000°">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <span class="label label-default">DP:</span>
                            <div class="form-group">
                                <input type="text" placeholder="000" maxlength="3" class="solo-numero form-control" name="recDPL" id="recDPL">
                            </div>

                        </div>
                    </div>
                    <hr class="linea">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center"><strong>CERCA</strong></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <span class="label label-default">OJO D.:</span>
                            <div class="input-group">
                                <span class="input-group-addon" style="background-color: #EEEEEE"><span class="glyphicon glyphicon-eye-open"></span></span>
                                <input type="text" class="form-control" name="recOjoDerCerEsf" id="recOjoDerCerEsf" maxlength="6" size="6" placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">-</span>
                                <input type="text" class="form-control" name="recOjoDerCerCil" id="recOjoDerCerCil" maxlength="6" size="6"  placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">/</span>
                                <input type="text" class="form-control" name="recOjoDerCerGra" id="recOjoDerCerGra" maxlength="5" size="5"  placeholder="000°">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <span class="label label-default">OJO I.:</span>
                            <div class="input-group">
                                <span class="input-group-addon" style="background-color: #EEEEEE"><span class="glyphicon glyphicon-eye-open"></span></span>
                                <input type="text" class="form-control" name="recOjoIzqCerEsf" id="recOjoIzqCerEsf" maxlength="6" size="6" placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">-</span>
                                <input type="text" class="form-control" name="recOjoIzqCerCil" id="recOjoIzqCerCil" maxlength="6" size="6"  placeholder="00,00">
                                <span class="input-group-addon" style="background-color: #EEEEEE">/</span>
                                <input type="text" class="form-control" name="recOjoIzqCerGra" id="recOjoIzqCerGra" maxlength="5" size="5"  placeholder="000°">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <span class="label label-default">DP:</span>
                            <div class="form-group">
                                <input type="text" placeholder="000" maxlength="3" class="solo-numero form-control" name="recDPC" id="recDPC">
                            </div>
                        </div>
                    </div>
                    <hr class="linea">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="label label-default">OBSERVACIÓN:</span>
                            <div class="form-group">
                                <input type="text" onkeyup="javascript:this.value = this.value.toUpperCase();" class="form-control" name="recObs" id="recObs">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <span class="label label-default">% DESC.</span>
                            <div class="form-group">
                                <input type="text" placeholder="00" maxlength="2"  class="solo-numero form-control" name="recDes" id="recDes">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-8">
                            
                        </div>
                        <div class="col-md-2">
                            <input type="submit" value="GUARDAR RECETA" class="btn btn-block btn-sistema btn-flat"></input>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-block btn-sistema btn-flat" data-dismiss="modal">CANCELAR</button>
                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    var fecha = "";
    var operativo;
    var recRD = "";
    var recH = "";
    function initialComponets() {
        BuscarDatosOperativo();
        
        $("#detalleOperativo").hide();
        $('#fechaOperativo').on('change', function () {
            $("#detalleOperativo").hide("slow");
            BuscarDatosOperativo();
        });
        $("#dataOperativo").change(function () {
            var control = $(this).val().split("-");
            var doc = control[2];
            var hora =control[1];
            CargaOperativo(doc, hora);
        });
        $('#formGuaRec').ajaxForm({
            beforeSubmit: function (formData, jqForm, options) {
                var error = true;
                for (var i = 0; i < formData.length; i++) {
                    if (!formData[i].value) {
                        $("#modTitulo").html("Validación");
                        $("#modBody").html("Se debe ingresar todos los valores");
                        $("#myModal").removeClass();
                        $("#myModal").addClass("modal modal-danger fade");
                        $("#myModal").modal();
                        error = false;
                    } else {
                    }
                    if (formData[i].name == "recRutDoc") {
                        recRD = formData[i].value
                    }
                    if (formData[i].name == "recHora") {
                        recH = formData[i].value
                    }
                }

                return error;

            },
            beforeSend: function () {
                /*status.empty();
                 var percentVal = '0%';
                 bar.width(percentVal)
                 percent.html(percentVal);*/
            },
            uploadProgress: function (event, position, total, percentComplete) {
                /*var percentVal = percentComplete + '%';
                 bar.width(percentVal)
                 percent.html(percentVal);*/
                //console.log(percentVal, position, total);
            },
            success: function () {
                /* var percentVal = '100%';
                 bar.width(percentVal)
                 percent.html(percentVal);*/
            },
            complete: function (xhr) {
                if (xhr.responseText.trim() == "OK") {
                    CargaOperativo(recRD, recH)
                    $("#recetaModal").modal("toggle");
                    $("#modTitulo").html("Validación");
                    $("#modBody").html("La receta se guardo con éxito");
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-success fade");
                    $("#myModal").modal();
                } else {
                    $("#modTitulo").html("Validación");
                    $("#modBody").html("Fallo en el sistema. Error: " + xhr.responseText);
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-danger fade");
                    $("#myModal").modal();
                }
            },
            error: function (xhr, status, error) {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Fallo en el sistema. Error: " + xhr.responseText);
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });
    }

    function BuscarDatosOperativo() {
        $("#detalleOperativo").hide("slow");
        var doc = <?php
                            echo json_encode(ArrayHelper::toArray($doctor, [
                                        'app\models\entity\Persona' => [
                                            'RUT',
                                            'DV',
                                            'CAT_PERSONA',
                                            'NOMBRE',
                                            'DIRECCION',
                                            'TELEFONO',
                                            'EMAIL'
                                        ],
                            ]));
                        ?>;
        comboOperativo = document.getElementById("dataOperativo");
        comboOperativo.length = 0
        $("#dataOperativo").trigger("change.select");
        var arrayFecha = $('#fechaOperativo').val().split("/");
        var dia = arrayFecha[0];
        var mes = arrayFecha[1];
        var anio = arrayFecha[2];
        fecha = anio + mes + dia;
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=operativos/buscar-datos-operativo' ?>',
            method: 'POST',
            async: false,
            data: {
                fecha: fecha,
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            dataType: 'json',
            success: function (data, textStatus, xhr) {
                largoOperativo = data.operativo.length;
                if (largoOperativo > 0) {

                    option = document.createElement("option");
                    option.text = "Seleccione un operativo";
                    option.value = "";
                    comboOperativo.add(option, comboOperativo[0]);
                    for (i = 1; i <= largoOperativo; i++) {
                        var nameDoc = "";
                        var horaOpe = data.operativo[i - 1]["HORA"].substring(0, 2) + ":" + data.operativo[i - 1]["HORA"].substring(2, 4);
                        var diaOpe = data.operativo[i - 1]["DIA"].substring(6, 8) + "/" + data.operativo[i - 1]["DIA"].substring(4, 6) + "/" + data.operativo[i - 1]["DIA"].substring(0, 4);
                        for(x = 0; x < doc.length; x++){
                            if(doc[x]["RUT"] ==  data.operativo[i - 1]["RUT_DOCTOR"]){
                                nameDoc = doc[x]["NOMBRE"] 
                            }
                        }

                        var miText = diaOpe + " - " + horaOpe + " - " + nameDoc + " - " + data.operativo[i - 1]["OBSERVACION"];
                        var miValue = data.operativo[i - 1]["DIA"] + "-" + data.operativo[i - 1]["HORA"] + "-" + data.operativo[i - 1]["RUT_DOCTOR"] + "-" + data.operativo[i - 1]["OBSERVACION"] + "-" + data.operativo[i - 1]["TIPO_OPERATIVO"];
                        var option = document.createElement("option");
                        option.text = miText;
                        option.value = miValue;
                        comboOperativo.add(option, comboOperativo[i]);
                        
                    }
                     $("#comboOperativo").val('<?= str_replace(":", "", $model->hora)?>').trigger('change.select2')
                    operativo = data.operativo;
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

    function CargaOperativo(rutDoc, hora) {
        var filas = document.getElementById("detalleOperativoTabla").rows.length;
        for (var i = filas; i > 1; i--) {
            document.getElementById("detalleOperativoTabla").deleteRow(i - 1);
        }

        for (var i = 0; i < operativo.length; i++) {
            if (operativo[i]["HORA"] == hora && operativo[i]["RUT_DOCTOR"] == rutDoc) {
                $("#opDia").val(operativo[i]["DIA"]);
                $("#opObservacion").val(operativo[i]["OBSERVACION"]);
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=operativos/buscar-detalle-operativo' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        fecha: fecha,
                        rut_doc: rutDoc,
                        hora: hora,
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                    },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {
                        largoDetallOperativo = data.detalleOperativo.length;
                        if (largoDetallOperativo > 0) {
                            for (var ind = 1; ind <= largoDetallOperativo; ind++) {
                                var nom = data.detalleOperativo[ind - 1]["NOMBRE"];
                                var rutP = data.detalleOperativo[ind - 1]["RUT_CLIENTE"];
                                var DPL = data.detalleOperativo[ind - 1]["DPL"];
                                var LODE = data.detalleOperativo[ind - 1]["LEJOS_OJO_D_E"];
                                var LODC = data.detalleOperativo[ind - 1]["LEJOS_OJO_D_C"];
                                var LODG = data.detalleOperativo[ind - 1]["LEJOS_OJO_D_G"];
                                var LOIE = data.detalleOperativo[ind - 1]["LEJOS_OJO_I_E"];
                                var LOIC = data.detalleOperativo[ind - 1]["LEJOS_OJO_I_C"];
                                var LOIG = data.detalleOperativo[ind - 1]["LEJOS_OJO_I_G"];
                                var DPC = data.detalleOperativo[ind - 1]["DPC"];
                                var CODE = data.detalleOperativo[ind - 1]["CERCA_OJO_D_E"];
                                var CODC = data.detalleOperativo[ind - 1]["CERCA_OJO_D_C"];
                                var CODG = data.detalleOperativo[ind - 1]["CERCA_OJO_D_G"];
                                var COIE = data.detalleOperativo[ind - 1]["CERCA_OJO_I_E"];
                                var COIC = data.detalleOperativo[ind - 1]["CERCA_OJO_I_C"];
                                var COIG = data.detalleOperativo[ind - 1]["CERCA_OJO_I_G"];
                                var decrip = data.detalleOperativo[ind - 1]["DESCRIPCION_RECETA"];
                                var desc = data.detalleOperativo[ind - 1]["DESCUENTO_RECETA"];

                                var table = document.getElementById("detalleOperativoTabla");
                                var filas = document.getElementById("detalleOperativoTabla").rows.length;
                                var row = table.insertRow(filas);
                                var cell1 = row.insertCell(0);
                                var cell2 = row.insertCell(1);
                                var cell3 = row.insertCell(2);
                                var cell4 = row.insertCell(3);
                                cell1.innerHTML = data.detalleOperativo[ind - 1]["RUT_CLIENTE"];
                                cell2.innerHTML = data.detalleOperativo[ind - 1]["NOMBRE"];
                                if (data.detalleOperativo[ind - 1]["ASISTENCIA"] === "S") {
                                    cell3.innerHTML = '<select type="text" id="asistencia' + filas + '" onchange="javascript:actualizaEstado(\'asistencia' + filas + '\',\'' + rutP + '\',\'' + fecha + '\',\'' + hora + '\',\'' + rutDoc + '\');" class="form-control" name="asistencia"><option value="N">NO</option><option value="S" selected>SI</option></select>';
                                } else {
                                    cell3.innerHTML = '<select type="text" id="asistencia' + filas + '" onchange="javascript:actualizaEstado(\'asistencia' + filas + '\',\'' + rutP + '\',\'' + fecha + '\',\'' + hora + '\',\'' + rutDoc + '\');"  class="form-control" name="asistencia"><option value="N" selected>NO</option><option value="S">SI</option></select>';
                                }

                                cell4.innerHTML = '<button type="button" id="receta' + filas + '" onClick="javascript:VerReceta(\'' + nom + '\',\'' + rutP + '\',\'' + DPL + '\',\'' + LODE + '\',\'' + LODC + '\',\'' + LODG + '\',\'' + LOIE + '\',\'' + LOIC + '\',\'' + LOIG + '\',\'' + DPC + '\',\'' + CODE + '\',\'' + CODC + '\',\'' + CODG + '\',\'' + COIE + '\',\'' + COIC + '\',\'' + COIG + '\',\'' + decrip + '\',\'' + desc + '\',\'' + fecha + '\',\'' + hora + '\',\'' + rutDoc + '\')" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span></button>';
                            }
                        }
                        var fechaOperativo = $("#fechaOperativo").val();
                        var UrlAP = '<?php echo Yii::$app->request->baseUrl ?>' + "/index.php?r=operativos/index-agrega-pacientes&dia=" + fechaOperativo + "&hora=" + hora + "&rut=" + rutDoc;
                        $("#asignaPaciente").attr("href", UrlAP);
                        $("#detalleOperativo").show("slow");
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
        }
    }

    function actualizaEstado(id, recRut, fechaE, hora, rutDoc) {
        var estado = document.getElementById(id).value;
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=operativos/actualiza-estado' ?>',
            method: 'POST',
            async: false,
            data: {
                rutDoc: rutDoc,
                hora: hora,
                fecha: fechaE,
                estado: estado,
                recRut: recRut,
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            dataType: 'json',
            success: function (data, textStatus, xhr) {
                if (data == "OK") {

                    $("#modTitulo").html("Validación");
                    $("#modBody").html("La asistencia se actualizó con éxito");
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-success fade");
                    $("#myModal").modal();
                } else {
                    $("#modTitulo").html("Validación");
                    $("#modBody").html(data);
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-danger fade");
                    $("#myModal").modal();
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                if (request.responseText == "OK") {
                    $("#modTitulo").html("Validación");
                    $("#modBody").html("La asistencia se actualizó con éxito");
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-success fade");
                    $("#myModal").modal();
                } else {
                    $("#modTitulo").html("Validación");
                    $("#modBody").html(data);
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-danger fade");
                    $("#myModal").modal();
                }
            }
        });
    }

    function VerReceta(nom, rutP, DPL, LODE, LODC, LODG, LOIE ,LOIC, LOIG, DPC, CODE, CODC, CODG, COIE, COIC, COIG, decrip, desc, fecha, hora, rutDoc) {
        $("#recNombre").val(nom);
        $("#recRut").val(rutP);
        $("#recDPL").val(DPL);
        $("#recOjoDerLejEsf").val(LODE);
        $("#recOjoDerLejCil").val(LODC);
        $("#recOjoDerLejGra").val(LODG);
        $("#recOjoIzqLejEsf").val(LOIE);
        $("#recOjoIzqLejCil").val(LOIC);
        $("#recOjoIzqLejGra").val(LOIG);
        $("#recDPC").val(DPC);
        $("#recOjoDerCerEsf").val(CODE);
        $("#recOjoDerCerCil").val(CODC);
        $("#recOjoDerCerGra").val(CODG);
        $("#recOjoIzqCerEsf").val(COIE);
        $("#recOjoIzqCerCil").val(COIC);
        $("#recOjoIzqCerGra").val(COIG);
        $("#recRutDoc").val(rutDoc);
        $("#recHora").val(hora);
        $("#recFecha").val(fecha);
        $("#recObs").val(decrip);
        $("#recDes").val(desc);
        $("#recetaModal").modal();
    }

    function volverOperativo() {
        $("#selectDia").show();
        $("#tomaHora").hide();
        $("#detalleOperativo").hide("slow");
    }

</script>  
