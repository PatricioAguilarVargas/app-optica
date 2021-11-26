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
$this->params['breadcrumbs']['categorias'] = ArrayHelper::map($categorias, 'DESCRIPCION', 'DESCRIPCION_PADRE');
?>

<div class="container-fluid">
    <div class="row">
        <div  class="col-md-1">	

        </div>
        <div  class="col-md-2">	
            <span class="label label-default text-right">SELECCIONAR CATEGORÍA:</span>
        </div>
        <div data-step="2" data-intro="Se selecciona la categoria a la que pertenece el producto a buscar" class="col-md-4">
            <?=
            Select2::widget([
                'data' => $this->params['breadcrumbs']['categorias'],
                'language' => 'es',
                'name' => 'productoBuscar',
                'options' => [ "class" => "form-control select2", "id" => "productoBuscar", "style" => 'width: 100%;'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>		
        <div data-step="3" data-intro="Se presiona buscar y aparecera el resultado mas abajo" class="col-md-2">	
            <button type="button" id="btnBusProd" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
        </div>
        <div class="col-md-2">	
            <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="muestra un listado de productos con su precio de venta" onclick="javascript:introJs().start();">
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
        if ($("#productoBuscar").find("option[value='TODOS']").length) {
            $("#productoBuscar").val('TODOS').trigger("change.select2");
        } else { 
            var newState = new Option('TODOS', 'TODOS', true, true);
            $("#productoBuscar").prepend(newState).trigger('change.select2');
            $("#productoBuscar").val('TODOS').trigger("change.select2");
        } 
        $("#btnBusProd").click(function(){
            $("#reporte").hide();
            var idBus = $("#productoBuscar").val();
            $("#reporte").attr("src","<?php echo Yii::$app->request->baseUrl . '/index.php?r=compras/reporte-producto&idBus='?>" + idBus);
            $("#reporte").show();
        });
    }
</script>