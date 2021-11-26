<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\grid\DataColumn;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['codigos'] = ArrayHelper::map($codigos, 'CODIGO', 'DESCRIPCION');

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
                <div  class="col-md-2" data-step="2" data-intro="Filtra por el tipo de venta">	
                     <?=
                        $form->field($model, 'tipo')->widget(Select2::classname(), [
                            'data' => $this->params['breadcrumbs']['codigos'],
                            'language' => 'es',
                            'options' => ["class" => "form-control select2", "style" => 'width: 100%;'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label("TIPO:", ['class' => 'label label-default']); 
                        ?>
                </div>	
                <div class="col-md-2" data-step="3" data-intro="Fecha incial para filtrar">	
                     <?= 
                                $form->field($model, 'fecIni')->widget(DatePicker::className(),[
                                        'value' => date('d/m/Y'),
                                        'language' => 'es',
                                        'type' =>  DatePicker::TYPE_INPUT,
                                        'pickerButton' => [
                                            'icon'=>'ok',
                                        ],
                                        'options' => ['placeholder' => 'ELEGIR'],
                                        'pluginOptions' => [
                                                'format' => 'dd/mm/yyyy',
                                                'todayHighlight' => true
                                        ]
                                ])->label("FEC. INI:", ['class' => 'label label-default']);
                            ?>

                </div>
                <div  class="col-md-2" data-step="4" data-intro="Fecha final para filtrar">	
                    <?= 
                                $form->field($model, 'fecFin')->widget(DatePicker::className(),[
                                        'value' => date('d/m/Y'),
                                        'language' => 'es',
                                        'type' =>  DatePicker::TYPE_INPUT,
                                        'pickerButton' => [
                                            'icon'=>'ok',
                                        ],
                                        'options' => ['placeholder' => 'ELEGIR'],
                                        'pluginOptions' => [
                                                'format' => 'dd/mm/yyyy',
                                                'todayHighlight' => true
                                        ]
                                ])->label("FEC. FIN:", ['class' => 'label label-default']);
                            ?>
                </div>
                <div  class="col-md-2" data-step="5" data-intro="Busca las ventas en el rango de fecha seleccionadas">
                    <br>
                    <button type="button" id="btnBusPro" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
                </div>	
                <div class="col-md-2">	
                    <br>
                    <a data-step="6" data-intro="Exporta los registros a excel" href="" id="btnDesArc" target="_blank" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-cloud-download"></span> EXCEL
                    </a>         
                </div>
                <div class="col-md-2">	
                <br>
                    <button data-step="1" data-intro="En esta pantalla se ven las ventas realizadas en un rango de fecha" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>       
                </div>
            </div>
            <hr class="linea">
            <div class="row">

                <div  class="col-md-12" data-step="7" data-intro="Resultados de las ventas realizadas">	
                    <?php \yii\widgets\Pjax::begin(['id' => 'ventas', 'enablePushState' => false]); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'TIPO',
                                'format' => 'text',
                                'label' => 'TIPO',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'FOLIO',
                                'format' => 'text',
                                'label' => 'FOLIO',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'FORMA_PAGO',
                                'format' => 'text',
                                'label' => 'FORMA PAGO',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'FECHA',
                                'format' => 'text',
                                'label' => 'FECHA',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'ESTADO',
                                'format' => 'text',
                                'label' => 'ESTADO',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'VALOR',
                                'format' => 'text',
                                'label' => 'VALOR',
                            ],
                        ],
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
		$("#<?= $nombreModelLow ?>-tipo").val("TODOS").trigger("change.select2");
		var d = new Date();
        var dia = (d.getDate() < 10) ? "0".concat(d.getDate()) : d.getDate();
        var mes = (d.getMonth() < 9) ? "0".concat(d.getMonth()+ 1) : (d.getMonth()+ 1);
        var ano = d.getFullYear()
        var fecha = dia + '/' + mes+ '/' + ano;
        $("#<?= $nombreModelLow ?>-fecini").val(fecha);
        $("#<?= $nombreModelLow ?>-fecfin").val(fecha);
		var tipo = $("#<?= $nombreModelLow ?>-tipo").val();
        var fecIni = $("#<?= $nombreModelLow ?>-fecini").val();
        var fecFin = $("#<?= $nombreModelLow ?>-fecfin").val();
		var timeI = fecIni.split("/");
        var timeF = fecFin.split("/");
		var urlInforme = "<?= Yii::$app->request->hostInfo . ':' . Yii::$app->request->serverPort . Yii::$app->request->scriptUrl . '?r=ventas/informe-venta-xls' ?>&tipo="+tipo+"&fecIni="+ timeI[2].concat(timeI[1]).concat(timeI[0])+"&fecFin="+timeF[2].concat(timeF[1]).concat(timeF[0]);
		$("#btnDesArc").attr("href",urlInforme);
		
        $("#btnBusPro").click(function () {
            var tipo = $("#<?= $nombreModelLow ?>-tipo").val();
            var fecIni = $("#<?= $nombreModelLow ?>-fecini").val();
            var fecFin = $("#<?= $nombreModelLow ?>-fecfin").val();
            
            var Url = '<?= Yii::$app->request->hostInfo . ':' . Yii::$app->request->serverPort . Yii::$app->request->scriptUrl . '?r=ventas/index-informe-venta' . str_replace("rt=", "id=", $rutaR) . '' ?>';
            if (tipo != "" && fecIni != "" && fecFin != "") {
                Url = Url + '&tipo=' + tipo;
                timeI = fecIni.split("/");
                timeF = fecFin.split("/");
                Url = Url + '&fecIni=' + timeI[2].concat(timeI[1]).concat(timeI[0]);
                Url = Url + '&fecFin=' + timeF[2].concat(timeF[1]).concat(timeF[0]);
                $.pjax.reload({container: "#ventas", url: Url, replace: false});
				urlInforme = "<?= Yii::$app->request->hostInfo . ':' . Yii::$app->request->serverPort . Yii::$app->request->scriptUrl . '?r=ventas/informe-venta-xls' ?>&tipo="+tipo+"&fecIni="+ timeI[2].concat(timeI[1]).concat(timeI[0])+"&fecFin="+timeF[2].concat(timeF[1]).concat(timeF[0]);
				$("#btnDesArc").attr("href",urlInforme);
            }else{
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar fechas válida");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });
		
		
        if ($("#<?=$nombreModelLow?>-tipo").find("option[value='TODOS']").length) {
            $("#<?=$nombreModelLow?>-tipo").val('TODOS').trigger("change.select2");
        } else { 
            var newState = new Option('TODOS', 'TODOS', true, true);
            $("#<?=$nombreModelLow?>-tipo").prepend(newState).trigger('change.select2');
            $("#<?=$nombreModelLow?>-tipo").val('TODOS').trigger("change.select2");
        } 
        
    }

</script>  
