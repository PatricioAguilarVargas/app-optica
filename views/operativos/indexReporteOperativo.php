<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
$this->params['breadcrumbs']['doctor'] = ArrayHelper::map($doctores, 'RUT', 'NOMBRE');

?>
<?php
$form = ActiveForm::begin([
            'id' => 'login-form',
        ]);
?>
<div class="container-fluid">
            <div class="row">
                <div class="col-md-2"  data-step="6" data-intro="Busca el operativo para ser mostrado">
                    <button type="button" id="btnBusProd" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
                </div>
                <div class="col-md-2"  data-step="7" data-intro="limpia el formulario">	
                    <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="En esta pantalla se genera un reporte que muestra los pacientes ingresados a un operativo" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
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
                    <div class="col-md-2">
                        <div class="form-group" data-step="2" data-intro="Seleccionar la fecha del operativo">
                            <label class="label label-default">FECHA BUSQUEDA:</label>
                            <?= 
                                DatePicker::widget([
                                        'value' => date("d/m/Y"),
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
                    </div>
                    <div class="col-md-8">
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
                    <div class="col-md-2">
                        
                    </div>
                </div>
            </div>
<?php ActiveForm::end(); ?>
    <hr class="linea">
    <div class="row">
        <div class="col-md-12" data-step="5" data-intro="Reporte">
            <iframe id="reporte" width="100%" height="600px" src=""></iframe>

        </div>
    </div>
</div>
<script type="text/javascript">

    function initialComponets() {

        $("#reporte").hide();
        BuscarDatosOperativo();

        $('#fechaOperativo').on('change', function () {
            $("#reporte").hide("slow");
            BuscarDatosOperativo();
        });
  
        $("#btnBusProd").click(function(){
            $("#reporte").hide();
            var control = $("#dataOperativo").val().split("-");
            var doc = control[2];
            var hora =control[1];
            var dia = $("#fechaOperativo").val();
            if(doc != "" && dia != "" && hora != ""){
                miArray = dia.split("/");
                dia = miArray[2].concat(miArray[1]).concat(miArray[0]);
                hora = hora.replace(":","");
                var url = "<?php echo Yii::$app->request->baseUrl . '/index.php?r=operativos/reporte-operativo&doc='?>" + doc +  "&dia=" + dia + "&hora=" + hora;
                $("#reporte").attr("src",url);
                $("#reporte").show();
            }else{
                $("#modTitulo").html("Validación");
                $("#modBody").html("Se debe ingresar todos los valores");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
            
        });
        var d = new Date();
        var dia = (d.getDate() < 10) ? "0".concat(d.getDate()) : d.getDate();
        var mes = (d.getMonth() < 9) ? "0".concat(d.getMonth()+ 1) : (d.getMonth()+ 1);
        var ano = d.getFullYear()
        var fecha = dia + '/' + mes+ '/' + ano;
        $("#fechaOperativo").val(fecha);
    }

     function BuscarDatosOperativo() {
        $("#detalleOperativo").hide("slow");
        var doc = <?php
                            echo json_encode(ArrayHelper::toArray($doctores, [
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
</script>

