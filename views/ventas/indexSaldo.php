<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\grid\DataColumn;

use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['formaPago'] = ArrayHelper::map($formaPago, 'CODIGO', 'DESCRIPCION');

/*
  $indice = 1;
  $posi = strrpos(get_class($model),"\\");
  $largo = strlen(get_class($model));
  $nombreModelLow = strtolower (substr(get_class($model),$posi + 1));
  $nombreModel = substr(get_class($model),$posi + 1);
 */
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
                <div  class="col-md-2 " data-step="2" data-intro="Filtra la busqueda por el rut del cliente">
                    <span class="label label-default">RUT:</span>
                    <input type="text" class="form-control guion-rut" id="venRutBusSal" placeholder="000000000">
                </div>	
                <div  class="col-md-2 " data-step="3" data-intro="Filtra por las ventas del dia seleccionado">
                    <span class="label label-default">DÍA:</span>
                     <?= DatePicker::widget([
                            'name' => 'venFecBusSal',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => date('d/m/Y'),
                            'language' => 'es',
                            'options' => ['placeholder' => '00/00/0000','id' => 'venFecBusSal'],
                            'pluginOptions' => [
                                    'format' => 'dd/mm/yyyy',
                                    'todayHighlight' => true
                            ]
                    ]);?>
                </div>	
			
                <div  class="col-md-2 " data-step="4" data-intro="Filtra la busqueda por el folio del cliente">
                    <span class="label label-default">FOLIO:</span>
                    <input type="text" class="form-control folio solo-numero" id="venFolBusSal" placeholder="000000000000">
                </div>	
                <div  class="col-md-2" data-step="5" data-intro="Busca las ventas por los filtros">	
                    <br>
                    <button type="button" id="btnBusSal" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
                </div>
                <div class="col-md-2">	
                    <br>
                    <button data-step="1" data-intro="En esta pantalla se registran los pagos de abonos de ventas anteriores" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-2">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row" data-step="6" data-intro="registros de las ventas">

                <div  class="col-md-12">	
                    <?php \yii\widgets\Pjax::begin(['id' => 'ventas', 'enablePushState' => false]); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderVentas,
                        'showOnEmpty'=>true,
                        'columns' => [
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'FOLIO',
                                'format' => 'text',
                                'label' => 'FOLIO',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'RUT',
                                'format' => 'text',
                                'label' => 'RUT',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'DV',
                                'format' => 'text',
                                'label' => 'DV',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'NOMBRE',
                                'format' => 'text',
                                'label' => 'NOMBRE',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'TELEFONO',
                                'format' => 'text',
                                'label' => 'TELÉFONO',
                            ],
                            [
                                'class' => DataColumn::className(), // this line is optional
                                'attribute' => 'TOTAL',
                                'format' => 'text',
                                'label' => 'TOTAL',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{estado}',
                                'header' => 'DET.',
                                'buttons' => [
                                    'estado' => function ($url, $model) {
                                        //var_dump($model);
                                        if ($model["RUT"] != "0") {
                                            return '<button type="button" onClick="javascript:verDetalleVenta(\'' . $model["FOLIO"] . '\',\'' . $model["FECHA_VENTA"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span></button>';
                                        } else {
                                            return "";
                                        }
                                    }
                                ],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{estado}',
                                'header' => 'PAG.',
                                'buttons' => [
                                    'estado' => function ($url, $model) {
                                        //var_dump($model);
                                        if ($model["RUT"] != "0") {
                                            return '<button type="button" onClick="javascript:verSaldos(\'' . $model["FOLIO"] . '\',\'' . $model["FECHA_VENTA"] . '\',\'' . $model["NOMBRE"] . '\',\'' . $model["TOTAL"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
                                        } else {
                                            return "";
                                        }
                                    }
                                ],
                            ],
                        ],
                        'tableOptions' => [
                            'id' => 'tblVen',
                            'class' => "table table-striped table-bordered"
                        ],
                    ]);
                    ?>
<?php \yii\widgets\Pjax::end(); ?>
                </div>
            </div>
            <div id="div-saldos">
                <hr class="linea">
                <div class="row">
                    <div  class="col-md-4">
                        <span class="label label-default">FOLIO:</span>
                        <input type="text" class="form-control" name="salFolio" id="salFolio" readonly="readonly">
                    </div>
                    <div  class="col-md-4">
                        <span class="label label-default">NOMBRE:</span>
                        <input type="text" class="form-control" name="salNombre" id="salNombre" readonly="readonly">
                    </div>
                    <div  class="col-md-4"  style="padding:20px">
                        <button type="button" onClick="javascript:ingresoSaldos()" class="btn btn-block btn-sistema btn-flat">ABONAR</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div  class="col-md-4">
                        <span class="label label-default">DEUDA TOTAL:</span>
                        <input type="text" class="form-control" name="salTotal" id="salTotal" readonly="readonly">
                    </div>
                    <div  class="col-md-4">
                        <span class="label label-default">ABONADO:</span>
                        <input type="text" class="form-control" name="salAbono" id="salAbono" readonly="readonly">
                    </div>
                    <div  class="col-md-4">
                        <span class="label label-default">SALDO A PAGAR:</span>
                        <input type="text" class="form-control" name="salSaldo" id="salSaldo" readonly="readonly">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div  class="col-md-12">	
                        <?php \yii\widgets\Pjax::begin(['id' => 'saldos', 'enablePushState' => false]); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProviderSaldos,
                            'columns' => [
                                'FOLIO',
                                'FECHA_ABONO',
                                'VALOR',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Forma Pago',
                                    'template' => '{forPag}',
                                    'buttons' => [
                                        'forPag' => function ($url, $model) {
                                            //var_dump($model);
                                            $sql = "SELECT DESCRIPCION FROM brc_codigos WHERE TIPO='FO_PAG' AND CODIGO='" . $model["FORMA_PAGO"] . "'";
                                            //var_dump($sql);
                                            $utils = new app\models\utilities\Utils;
                                            $s = $utils->ejecutaQuery($sql);
                                            //var_dump($s);
                                            return $s[0]["DESCRIPCION"];
                                        },
                                    ],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Tipo',
                                    'template' => '{doc}',
                                    'buttons' => [
                                        'doc' => function ($url, $model) {
                                            //var_dump($model);
                                            $sql = "SELECT DESCRIPCION FROM brc_codigos WHERE TIPO='ABONO' AND CODIGO='" . $model["TIPO_PAGO"] . "'";
                                            //var_dump($sql);
                                            $utils = new app\models\utilities\Utils;
                                            $s = $utils->ejecutaQuery($sql);
                                            //var_dump($s);
                                            return $s[0]["DESCRIPCION"];
                                        },
                                    ],
                                ],
                            ],
                            'tableOptions' => [
                                'id' => 'tblSal',
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
</div>
<?php ActiveForm::end(); ?>
<!-- modal saldos -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="saldoModal" role="dialog">
    <div class="modal-dialog" style="width: 80% !important;" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header headModal">
                <h4 class="modal-title text-center">INGRESAR ABONO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div  class="col-md-4">
                        <span class="label label-default">FOLIO:</span>
                        <input type="text" class="form-control" name="ingSalFolio" id="ingSalFolio" readonly="readonly">
                    </div>
                    <div  class="col-md-4">
                        <span class="label label-default">NOMBRE:</span>
                        <input type="text" class="form-control" name="ingSalNombre" id="ingSalNombre" readonly="readonly">
                    </div>
                    <div  class="col-md-4">
                        <span class="label label-default">TOTAL VENTA:</span>
                        <input type="text" class="form-control" name="ingSalDeu" id="ingSalDeu" readonly="readonly">
                    </div>
                </div>
                <hr class="linea">
                <div class="row">
                    <div  class="col-md-3">
                        <span class="label label-default">ABONADO:</span>
                        <input type="text" class="form-control" name="ingSalAbono" id="ingSalAbono" readonly="readonly">
                    </div>
                    <div  class="col-md-3">
                        <span class="label label-default">SALDO A PAGAR:</span>
                        <input type="text" class="form-control" name="ingSalSaldo" id="ingSalSaldo" readonly="readonly">
                    </div>
                    <div  class="col-md-3">
                        <span class="label label-default">FORMA PAGO:</span>
                        <select class="form-control" name="ingSalForPago"  id="ingSalForPago">
                        <option value="">Elija una opción</option>
                        <?php 
                            foreach($this->params['breadcrumbs']['formaPago'] as $clave => $valor){
                                echo '<option value="'.$clave.'">'.$valor.'</option>';
                            }
                        ?>
                        </select>
                    </div>
                    <div  class="col-md-3">
                        <span class="label label-default">ABONO:</span>
                        <input type="text" class="form-control solo-numero" name="ingSalIngAbono" placeholder="000000000" id="ingSalIngAbono">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div  class="col-md-8">	

                    </div>
                     <div  class="col-md-2">	
                         <button type="button" id="btnIngSal" class="btn btn-block btn-sistema btn-flat">INGRESAR</button>
                       
                    </div>
                    <div  class="col-md-2">	
                         <button type="button" class="btn btn-block btn-sistema btn-flat" data-dismiss="modal">CANCELAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal detalle -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="detalleVentaModal" role="dialog">
    <div class="modal-dialog" style="width: 80% !important;" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header headModal">
                <h4 class="modal-title text-center">DETALLE DE VENTA</h4>
            </div>
            <div class="modal-body">
<?php \yii\widgets\Pjax::begin(['id' => 'detalle', 'enablePushState' => false]); ?>
                <div class="row">
                    <div  class="col-md-4">
                        <span class="label label-default">FOLIO:</span>
                        <input type="text" class="form-control" value="<?= isset($venta[0]["FOLIO"]) ? $venta[0]["FOLIO"] : "" ?>" readonly="readonly">
                    </div>
                    <div  class="col-md-4">
                        <span class="label label-default">NOMBRE:</span>
                        <input type="text" class="form-control" value="<?= isset($venta[0]["NOMBRE"]) ? $venta[0]["NOMBRE"] : "" ?>" readonly="readonly">
                    </div>
                    <div  class="col-md-4">
                        <span class="label label-default">SUBTOTAL:</span>
                        <input type="text" value="<?= isset($venta[0]["SUBTOTAL"]) ? $venta[0]["SUBTOTAL"] : "" ?>"  class="form-control"  readonly="readonly">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div  class="col-md-3">
                        <span class="label label-default">DESCUENTO:</span>
                        <input type="text" value="<?= isset($venta[0]["DESCUENTO"]) ? $venta[0]["DESCUENTO"] : "" ?>"  class="form-control"  readonly="readonly">
                    </div>
                    <div  class="col-md-3">
                        <span class="label label-default">NETO:</span>
                        <input type="text" value="<?= isset($venta[0]["NETO"]) ? $venta[0]["NETO"] : "" ?>"  class="form-control"  readonly="readonly">
                    </div>
                    <div  class="col-md-3">
                        <span class="label label-default">IVA:</span>
                        <input type="text" value="<?= isset($venta[0]["IVA"]) ? $venta[0]["IVA"] : "" ?>"  class="form-control"  readonly="readonly">
                    </div>
                    <div  class="col-md-3">
                        <span class="label label-default">TOTAL A PAGAR:</span>
                        <input type="text" value="<?= isset($venta[0]["TOTAL"]) ? $venta[0]["TOTAL"] : "" ?>" class="form-control"  readonly="readonly">
                    </div>
                </div>
                <hr class="linea">
                <div class="row">
                    <div  class="col-md-12">	

                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProviderDetalle,
                            'columns' => [
                                'CANTIDAD',
                                'DESCRIPCION',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'Valor Unitario',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["VALOR_VENTA"] != "0") {
                                                return $model["VALOR_VENTA"];
                                            } else {
                                                return "0";
                                            }
                                        }
                                    ],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'Valor Venta',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["CANTIDAD"] != "0") {
                                                return $model["CANTIDAD"] * $model["VALOR_VENTA"];
                                            } else {
                                                return "0";
                                            }
                                        }
                                    ],
                                ],
                            ],
                            'tableOptions' => [
                                'id' => 'tblDeta',
                                'class' => "table table-striped table-bordered"
                            ],
                        ]);
                        ?>

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div  class="col-md-10">	

                        </div>
                        <div  class="col-md-2">	
                            <button type="button" class="btn btn-block btn-sistema btn-flat" data-dismiss="modal">SALIR</button>
                        </div>
                    </div>
                </div>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById("btnIngSal").addEventListener("click", guardarAbono, false);
    var fechaFolio = "";
    function initialComponets() {
        $("#div-saldos").hide();
<?php if ($folio != "000000000000" and $isPjax == false) { ?>
            $("#modTitulo").html("Venta Realizada");
            $("#modBody").html("El folio asignado a la venta es <?= $folio ?>");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-success fade");
            $("#myModal").modal();

<?php } ?>
        $("#btnBusSal").click(function () {
            var fecBus = $("#venFecBusSal").val();
            var folBus = $("#venFolBusSal").val();
            var rutBus = $("#venRutBusSal").val();
            $("#div-saldos").hide();
            if (fecBus != "" || folBus != "" || rutBus != "") {
                var fecha = fecBus.split("/");
                var fecFormat = fecha[2] + fecha[1] + fecha[0]
                var Url = '<?= Yii::$app->request->hostInfo . ':' . Yii::$app->request->serverPort . Yii::$app->request->scriptUrl . '?r=ventas/index-saldo' . str_replace("rt=", "id=", $rutaR) . '' ?>';
                if (fecBus != "") {
                    Url = Url + '&date=' + fecFormat;
                }
                if (folBus != "") {
                    Url = Url + '&folioF=' + folBus;
                }
                if (rutBus != "") {
                    Url = Url + '&rut=' + rutBus;
                }

                $.pjax.reload({container: "#ventas", url: Url, replace: false});
            } else {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar al menos uno de los datos solicitados para la busqueda.");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });
    }

    function guardarAbono() {
        var folio = $("#ingSalFolio").val();
        var abono = parseInt($("#ingSalIngAbono").val()); //abono hoy
        var saldo = parseInt($("#ingSalSaldo").val());
        var nombre = $("#ingSalNombre").val();
        var tAbono = parseInt($("#ingSalAbono").val());
        var total = parseInt($("#ingSalDeu").val()); // total deuda
        var formaPago =$("#ingSalForPago").val();

        if (isNaN(abono) || abono == "" || parseInt(abono) == 0) {
            $("#modTitulo").html("Validación");
            $("#modBody").html("Debe ingresar el abono");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-danger fade");
            $("#myModal").modal();
        } else if (abono > saldo) {
            $("#modTitulo").html("Validación");
            $("#modBody").html("El abono es mayor al saldo por pagar");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-danger fade");
            $("#myModal").modal();
        }else if (formaPago == "") {
            $("#modTitulo").html("Validación");
            $("#modBody").html("Se debe seleccionar una forma de pago");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-danger fade");
            $("#myModal").modal();
        } else {

            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=ventas/ingreso-abono' ?>',
                type: 'post',
                data: {
                    _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                    _folio: folio,
                    _abono: abono,
                    _formaPago: formaPago,
                    _saldo: saldo
                },
                success: function (data) {
                    if (data == "OK") {
                        verSaldos(folio, fechaFolio, nombre, total);
                        $.ajax({
                            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=ventas/voucher' ?>',
                            type: 'post',
                            data: {
                                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                                _folio: folio,
                                _abono: abono,
                                _saldo: saldo,
                                _total: total,
                                _tAbono: tAbono
                            },
                            success: function (data) {
                                if (data == "OK") {

                                } else {
                                    $("#modTitulo").html("Validación");
                                    $("#modBody").html(data);
                                    $("#myModal").removeClass();
                                    $("#myModal").addClass("modal modal-danger fade");
                                    $("#myModal").modal();
                                }
                            },
                            error: function (request, status, error) {
                                $("#modTitulo").html("Validación");
                                $("#modBody").html("Fallo en el sistema. Error: " + request.responseText);
                                $("#myModal").removeClass();
                                $("#myModal").addClass("modal modal-danger fade");
                                $("#myModal").modal();
                            }
                        });
                        $("#saldoModal").modal("toggle");
                        $("#ingSalSaldo").val("");
                    } else {
                        $("#modTitulo").html("Validación");
                        $("#modBody").html(data);
                        $("#myModal").removeClass();
                        $("#myModal").addClass("modal modal-danger fade");
                        $("#myModal").modal();
                    }
                },
                error: function (request, status, error) {
                    $("#modTitulo").html("Validación");
                    $("#modBody").html("Fallo en el sistema. Error: " + request.responseText);
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-danger fade");
                    $("#myModal").modal();
                }
            });
        }
    }

    function ingresoSaldos() {
        $("#saldoModal").modal();
    }

    function verDetalleVenta(folio, fecha) {
        $("#div-saldos").hide();
        var Url = '<?= Yii::$app->request->hostInfo . ':' . Yii::$app->request->serverPort . Yii::$app->request->scriptUrl . '?r=ventas/index-saldo' . str_replace("rt=", "id=", $rutaR) . '&folioD=' ?>' + folio + '&date=' + fecha + "&folioF=" + folio;
        //var Url = '<?= Yii::$app->request->absoluteUrl . '&folio=' ?>' + folio + '&date=' + fecha;
        $.pjax.reload({container: "#detalle", url: Url, replace: false});
        $("#detalleVentaModal").modal();

    }

    function verSaldos(folio, fecha, nombre, total) {
        var Url = '<?= Yii::$app->request->hostInfo . ':' . Yii::$app->request->serverPort . Yii::$app->request->scriptUrl . '?r=ventas/index-saldo' . str_replace("rt=", "id=", $rutaR) . '&folio=' ?>' + folio + '&date=' + fecha;
        //var Url = '<?= Yii::$app->request->absoluteUrl . '&folio=' ?>' + folio + '&date=' + fecha;
        fechaFolio = fecha;
        $.pjax.reload({container: "#saldos", url: Url, replace: false});
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=ventas/entrega-saldos-globales' ?>',
            type: 'post',
            data: {
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                _folio: folio
            },
            success: function (data) {
                var res = typeof data;
                if (res == "number") {
                    $("#salFolio").val(folio);
                    $("#salNombre").val(nombre);
                    $("#salTotal").val(total);
                    $("#salAbono").val(data);
                    $("#salSaldo").val(total - data);
                    $("#ingSalFolio").val(folio);
                    $("#ingSalNombre").val(nombre);
                    $("#ingSalDeu").val(total);
                    $("#ingSalAbono").val(data);
                    $("#ingSalSaldo").val(total - data);
                    $("#div-saldos").show();
                }
            },
            error: function (request, status, error) {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Fallo en el sistema. Error: " + request.responseText);
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });

    }

</script>  