<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
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
    <div class="row-fluid">
        <div class="col-md-3">
            <div class="short-div">
                <div data-step="2" data-intro="En esta seccion se ingresan las categorias de productos">
                    <h3>CATEGORÍAS</h3>
                    <div id="jqxTree" style="float:left;"></div>
                    <button data-step="3" data-intro="Este boton sirve para ingresar una categoria" type="button" id="agUsu" class="btn btn-default">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                    <button data-step="4" data-intro="Este boton sirve para editar una categoria" type="button" id="moUsu" class="btn btn-default">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                </div>    
                <hr class="linea">
                <div data-step="5" data-intro="En esta seccion se muestran los productos que pertenecen a una categoria">
                    <h3>PRODUCTOS</h3>
                    <div id="jqxTree2" style="float:left;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div  data-step="13" data-intro="Guarda un producto en el sistema" class="col-md-2">
                    <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div  data-step="14" data-intro="Limpia el formulario" class="col-md-2">	
                    <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="Este formulario sirve para ingresar los productos que vendera o comprara la optica" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-6">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div class="col-md-12">
                    <div data-step="6" data-intro="Codigo interno del producto. Este lo genera el sistema" class="form-group">
                        <?= $form->field($model, 'codigo')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Codigo", "required" => true, "readonly" => "readonly"])
                            ->label("CÓDIGO:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div  class="col-md-12">
                    <div data-step="7" data-intro="Nombre del producto" class="form-group">
                        <?= $form->field($model, 'descripcion')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Descripcion", "required" => true, "autofocus" => true])
                                    ->label("DESCRIPCIÓN:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
            <br>		
            <div class="row">
                <div  class="col-md-6">
                    <div  data-step="8" data-intro="stock minimo del producto, debe ser un numero" class="form-group">
                        <?= $form->field($model, 'stockCritico')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Stock Minimo", "required" => true, "maxlength" => "11", "size" => "11"])
                                            ->label("STOCK MÍNIMO:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div  data-step="9" data-intro="debe elegir si el producto debe aparecer o no en el sistema" class="form-group">
                        <?=
                                $form->field($model, 'vigencia')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map($vigencia, 'CODIGO', 'DESCRIPCION'),
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
            <div class="row">
                <div  class="col-md-6">
                    <div  data-step="10" data-intro="Codigo de barras del producto." class="input-group">
                        <?= $form->field($model, 'codBarra')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Codigo de Barras", "readonly" => "readonly", "required" => true, "maxlength" => "12", "size" => "12"])
                                                            ->label("CÓDIGO DE BARRAS:", ['class' => 'label label-default']); ?>
                        <label for="verCodBarra" class="label ">&nbsp;</label>
                        <span class="input-group-btn">

                            <button data-step="11" data-intro="Genera el codigo de barras del producto." type="button" id="verCodBarra" class="btn btn-default">
                                <span class="glyphicon glyphicon-barcode"></span>
                            </button>
                        </span>
                    </div>
                </div>
                <div  class="col-md-6">
                    <div  data-step="12" data-intro="Precio de venta del producto" class="form-group">
                        <?= $form->field($model, 'valorVenta')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Precio de Venta", "required" => true, "maxlength" => "12", "size" => "12"])
                                                                    ->label("PRECIO DE VENTA:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="modal fade" id="agregarUsuarioModal" role="dialog">
        <div class="modal-dialog" style="width: 80% !important;" >
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header headModal">
                    <h4 class="modal-title">AGREGAR CATEGORÍA</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label label-default" for="proDescrip">DESCRIPCIÓN:</label>
                                <input type="text" id="proDescrip" name="proDescrip" class="form-control" placeholder="Descripción" onkeyup="javascript:this.value = this.value.toUpperCase();" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-8">
                            
                        </div>
                        <div class="col-md-2">
                            <button id="guardaCategoria" type="button" class="btn btn-block btn-sistema btn-flat">GUARDAR</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-block btn-sistema btn-flat" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var opCat = "";
        document.getElementById("guardaCategoria").addEventListener("click", guardaCategoria, false);
        document.getElementById("verCodBarra").addEventListener("click", consultaCodigoBarra, false);
        document.getElementById("moUsu").addEventListener("click", moUsu, false);
        document.getElementById("agUsu").addEventListener("click", agUsu, false);

        function initialComponets() {
            $('#jqxTree').jqxTree({height: '100px', width: '100%'});
            $('#jqxTree2').jqxTree({height: '300px', width: '100%'});
            $("#<?= $nombreModelLow ?>-codigo").val("");
            $("#<?= $nombreModelLow ?>-descripcion").val("");
            $("#<?= $nombreModelLow ?>-stockcritico").val("");
            $("#<?= $nombreModelLow ?>-vigencia").val("");
            $("#<?= $nombreModelLow ?>-codbarra").val("");
            $("#<?= $nombreModelLow ?>-valorventa").val("");
            cargaTree(0, 'CAT');
            $('#jqxTree').on('itemClick', function (event) {
                $('#jqxTree2').jqxTree('clear');
                $("#<?= $nombreModelLow ?>-codigo").val("");
                var args = event.args;
                var item = $('#jqxTree').jqxTree('getItem', args.element);
                var label = item.label;
                var id = item.id;
                var myArray = id.split('-');
                var id_padre = myArray[0];
                var id_hijo = myArray[1];
                $("#<?= $nombreModelLow ?>-codigo").val(id_hijo);
                $("#<?= $nombreModelLow ?>-descripcion").val("");
                $("#<?= $nombreModelLow ?>-stockcritico").val("");
                $("#<?= $nombreModelLow ?>-vigencia").val("");
                $("#<?= $nombreModelLow ?>-vigencia").trigger("chosen:updated");
                $("#<?= $nombreModelLow ?>-codbarra").val("");
                $("#<?= $nombreModelLow ?>-valorventa").val("");
                cargaTree(id_hijo, 'PRO');
            });
            $('#jqxTree2').on('itemClick', function (event) {
                var args = event.args;
                var item = $('#jqxTree2').jqxTree('getItem', args.element);
                var label = item.label;
                var id = item.id;
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/buscar-productos-by-id' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        id: item.id,
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                    },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {

                        $("#<?= $nombreModelLow ?>-codigo").val(data.productos[0].ID_PADRE + "-" + data.productos[0].ID_HIJO);
                        $("#<?= $nombreModelLow ?>-descripcion").val(data.productos[0].DESCRIPCION);
                        $("#<?= $nombreModelLow ?>-stockcritico").val(data.productos[0].STOCK_CRITICO);
                        $("#<?= $nombreModelLow ?>-vigencia").val(data.productos[0].VIGENCIA);
                        $("#<?= $nombreModelLow ?>-vigencia").trigger("chosen:updated");
                        $("#<?= $nombreModelLow ?>-codbarra").val(data.productos[0].COD_BARRA);
                        $("#<?= $nombreModelLow ?>-valorventa").val(data.productos[0].VALOR_VENTA);
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
            });
        }

        function moUsu() {
            opCat = "moUsu";
            var item = $("#jqxTree").jqxTree("getSelectedItem");
            var label = $(item).attr('label');
            $("#proDescrip").val(label);
            $("#agregarUsuarioModal").modal("toggle");

        }
        function agUsu() {
            opCat = "agUsu";
            $("#agregarUsuarioModal").modal("toggle");
        }

        function cargaTree(id_padre, arbol) {
            var source = "";
            sourceString = "";
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/buscar-productos' ?>',
                method: 'POST',
                async: false,
                data: {
                    _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                    _arbol: arbol,
                    _id_padre: id_padre
                },
                dataType: 'json',
                success: function (data, textStatus, xhr) {
                    sourceString = data.productos;
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
            console.log(sourceString);
            var source = jQuery.parseJSON(sourceString);

            if (arbol == 'CAT') {
                $('#jqxTree').jqxTree({source: source, height: '100px', width: '100%'});
            } else {

                $('#jqxTree2').jqxTree({source: source, height: '300px', width: '100%'});
            }
        }

        function guardaCategoria() {
            var des = $("#proDescrip").val();
            var item = $("#jqxTree").jqxTree("getSelectedItem");
            var id = $(item).attr('id');

            if (des == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar el nombre de la categoria nueva");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if (typeof id == "undefined") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe seleccionar una categoria de la lista");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else {
                var myArray = id.split('-');
                var codP = "";
                var codH = "";
                if (opCat == "moUsu") {
                    codP = myArray[0];
                    codH = myArray[1];
                } else if (opCat == "agUsu") {
                    codP = myArray[1];
                }
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/guarda-categoria' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        _des: des,
                        _codP: codP,
                        _codH: codH,
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                    },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {
                        if (data.res == "OK") {
                            $("#modTitulo").html("Validación");
                            $("#modBody").html("La categoría fue guardada con éxito");
                            $("#myModal").removeClass();
                            $("#myModal").addClass("modal modal-success fade");
                            $("#myModal").modal();
                            $("#proDescrip").val("");
                            cargaTree(0, 'CAT');
                            $("#agregarUsuarioModal").modal("toggle");
                        } else {
                            $("#modTitulo").html("Validación");
                            $("#modBody").html(data.res);
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
        }

        function eliminaFila() {

        }

        function consultaCodigoBarra() {
            var source = "";
            sourceString = "";
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/genera-codigo-barra' ?>',
                method: 'POST',
                async: false,
                data: {
                    _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                },
                dataType: 'json',
                success: function (data, textStatus, xhr) {
                    $("#<?= $nombreModelLow ?>-codbarra").val(data.codigo);
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
