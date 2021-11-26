<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['proveedor'] = ArrayHelper::map($proveedor, 'ID_PROVEEDOR', 'NOMBRE_EMPRESA');
?>

<div class="container-fluid">
    <div class="row">
        <div  class="col-md-1">	

        </div>
        <div  class="col-md-2">	
            <span class="label label-default text-right">SELECCIONAR PROVEEDOR:</span>
        </div>
        <div data-step="2" data-intro="se elige el proveedor de los que se quiere saber los productos" class="col-md-4">
            <?=
            Select2::widget([
                'data' => $this->params['breadcrumbs']['proveedor'],
                'language' => 'es',
                'name' => 'proveedorBuscar',
                'options' => [ "class" => "form-control select2", "id" => "proveedorBuscar", "style" => 'width: 100%;'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>		
        <div data-step="3" data-intro="realiza la busqueda de los productos de un proveedor" class="col-md-2">	
            <button type="button" id="btnBusProvee" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
        </div>
        <div class="col-md-2">	
            <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="muestra los productos de un proveedor" onclick="javascript:introJs().start();">
                <span class="glyphicon glyphicon-question-sign"></span> AYUDA
            </button>         
        </div>
        <div  class="col-md-1">	
        </div>
    </div>
    <hr class="linea">
    <div class="row">
        <div class="col-md-12">
             <iframe id="reporte" width="100%" height="600px" src=""></iframe>
       </div>
    </div>
</div>
<script type="text/javascript">

    function initialComponets() {
        $("#reporte").hide();
        if ($("#proveedorBuscar").find("option[value='TODOS']").length) {
            $("#proveedorBuscar").val('TODOS').trigger("change.select2");
        } else { 
            var newState = new Option('TODOS', 'TODOS', true, true);
            $("#proveedorBuscar").prepend(newState).trigger('change.select2');
            $("#proveedorBuscar").val('TODOS').trigger("change.select2");
        } 
        $("#btnBusProvee").click(function(){
            $("#reporte").hide();
            var idBus = $("#proveedorBuscar").val();
            $("#reporte").attr("src","<?php echo Yii::$app->request->baseUrl . '/index.php?r=compras/reporte-prod-prov&idBus='?>" + idBus);
            $("#reporte").show();
        });
    }
</script>
