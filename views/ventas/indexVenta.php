<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use keygenqt\autocompleteAjax\AutocompleteAjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['clientes'] = ArrayHelper::map($clientes, 'RUT', 'NOMBRE');
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
                <div class="col-md-2" data-step="17" data-intro="Guarda la venta de los productos">
                    <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div class="col-md-2" data-step="18" data-intro="Limpia los datos del formulario">	
                    <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2" data-step="19" data-intro="Busca a los clientes por los operativos en los que estan inscritos">	
                    <button type="button" onClick="javascript:VerOperativos()" class="btn btn-block btn-sistema btn-flat">OPERATIVOS</button>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="En esta pantalla se registran las ventas de productos que se realizan" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-4">	
                    &nbsp;
                </div>
            </div>

            <hr class="linea">

            <?php //Encabezado  ?>
            <div class="row">
                <div  class="col-md-12">
                    <div class="row">
                        <div  class="col-md-4">
                            <div class="form-group"  data-step="2" data-intro="Numero del folio con el cual quedara registrada la venta">
                                <?= $form->field($model, 'folio')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Folio", "value" => $folio, "readonly" => "readonly", "required" => true, "autofocus" => true, "maxlength" => "12", "size" => "12"])->label("FOLIO:", ['class' => 'label label-default']); ?>

                            </div>
                        </div>
                        <div  class="col-md-4">
                            <div class="form-group" data-step="3" data-intro="Debe elegir al cliente al que se le vendera el producto">
                                <?php
                                /*$form->field($model, 'cliente')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['clientes'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("CLIENTE:", ['class' => 'label label-default']);*/
                                ?>
                                <?= $form->field($model, 'cliente')->widget(AutocompleteAjax::classname(), [
                                    'multiple' => false,
                                    'url' => ['site/buscar-cliente'],
                                    'options' => [
                                        'placeholder' => 'Ingrese el rut o nombre del doctor.',
                                        "class" => "form-control",
                                        "onkeyup" => "javascript:this.value=this.value.toUpperCase();",
                                        "required" => true, 
                                        "maxlength" => "50", 
                                        "size" => "50"
                                    ]
                                ])->label("CLIENTE:", ['class' => 'label label-default']); ?>
                            </div>
                        </div>
                        <div  data-step="4" data-intro="Nombre del cliente" class="col-md-4">
                            <span class="label label-default">NOMBRE:</span>
                            <input type="text" class="form-control" name="proNombre" id="proNombre" readonly="readonly">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col-md-4" data-step="5" data-intro="Direccion del cliente">
                            <span class="label label-default">DIRECCIÓN:</span>
                            <input type="text" class="form-control" name="proDireccion" id="proDireccion" readonly="readonly">
                        </div>
                        <div  class="col-md-4" data-step="6" data-intro="Correo electronico del cliente">
                            <span class="label label-default">E-MAIL:</span>
                            <input type="text" class="form-control" name="proMail" id="proMail" readonly="readonly">
                        </div>
                        <div  class="col-md-4" data-step="7" data-intro="Telefono del cliente">
                            <span class="label label-default">TELÉFONO:</span>
                            <input type="text" class="form-control" name="proFono" id="proFono" readonly="readonly">
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <hr class="linea">
<?php // busqueda de codigo de barras   ?>
            <div class="row">
                <div  data-step="8" data-intro="Se ingresa el codigo de barras del producto y este se carga en la tabla de productos a venderse" class="col-md-4">
                    <span class="label label-default">INGRESE EL CÓDIGO DE BARRAS:</span>
                    <input type="text" class="form-control" name="venCodigoBarra" id="venCodigoBarra">
                </div>
            </div>
            <hr class="linea">
<?php // detalle venta   ?>
            <div class="row" data-step="9" data-intro="Detalle de los productos a venderse">
                <table id="detalleCompra" class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th width="5%">
                                #
                            </th>
                            <th width="10%">
                                CANTIDAD
                            </th>
                            <th width="50%">
                                PRODUCTO
                            </th>
                            <th width="15%">
                                VALOR UNIDAD
                            </th>
                            <th width="20%">
                                VALOR
                            </th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

<?php //calculo final   ?>						
            <div class="row">
                <div  class="col-md-2">
                    <button  data-step="10" data-intro="Agrega de la venta un producto" type="button" id="insertaFila" class="btn btn-default">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                    <button data-step="11" data-intro="Elimina de la venta un producto" type="button" id="borraFila" class="btn btn-default">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </div>
                <div  class="col-md-10">
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1">
                            <span class="label label-default">SUBTOTAL:</span>
                        </div>
                        <div data-step="12" data-intro="Este el subtotal de la venta" class="col-md-2">
<?= $form->field($model, 'subTotal', ['template' => "{input}"])->textInput(["class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1">
                            <span class="label label-default">DESCUENTO:</span>
                        </div>
                        <div data-step="13" data-intro="Se registra si le aplicaran un descuento a la venta" class="col-md-2" >
<?= $form->field($model, 'descuento', ['template' => "{input}"])->textInput(["class" => "form-control", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1 ">
                            <span class="label label-default">VALOR NETO:</span>
                        </div>
                        <div data-step="14" data-intro="Valor neto con descuento" class="col-md-2">
<?= $form->field($model, 'neto', ['template' => "{input}"])->textInput(["class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1">
                            <span class="label label-default">IVA:</span>
                        </div>
                        <div data-step="15" data-intro="Calculo del iva" class="col-md-2" >
<?= $form->field($model, 'iva', ['template' => "{input}"])->textInput(["class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1">
                            <span class="label label-default">TOTAL:</span>
                        </div>
                        <div data-step="16" data-intro="Total de la venta" class="col-md-2">
<?= $form->field($model, 'total', ['template' => "{input}"])->textInput(["class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"]); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="operativoModal" role="dialog">
    <div class="modal-dialog" style="width: 80% !important;" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header headModal">
                <h4 class="modal-title text-center">BUSCAR PACIENTES DE OPERATIVOS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div  class="col-md-2">	
                        
                    </div>
                    <div  class="col-md-2">	
                        <span class="label label-default text-right">DÍA A BUSCAR:</span>
                    </div>
                    <div  class="col-md-4">
                         <?= DatePicker::widget([
                                'name' => 'venFecBusPac', 
                                'value' => date('d/m/Y'),
                                'language' => 'es',
                                'type' => DatePicker::TYPE_INPUT,
                                'options' => ['placeholder' => '00/00/0000',"id"=> 'venFecBusPac'],
                                'pluginOptions' => [
                                        'format' => 'dd/mm/yyyy',
                                        'todayHighlight' => true
                                ]
                            ]);
                        ?>
                    </div>		
                    <div  class="col-md-2">	
                        <button type="button" id="btnBusPac" class="btn btn-block btn-sistema btn-flat">BUSCAR</button>
                    </div>
					<div  class="col-md-2">	
                        <button type="button" class="btn btn-block btn-sistema btn-flat" data-dismiss="modal">CANCELAR</button>
                    </div>
                </div>
                <hr class="linea">
                <div class="row">

                    <div  class="col-md-12">	
                        <?php \yii\widgets\Pjax::begin(['id' => 'pacientes', 'enablePushState' => false]); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'RUT_CLIENTE',
                                'NOMBRE',
                                'TELEFONO',
                                'RUT_DOCTOR',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{estado}',
                                    'header' => 'IR',
                                    'buttons' => [
                                        'estado' => function ($url, $model) {
                                            //var_dump($model);
                                            if ($model["RUT_CLIENTE"] != "0-0" || IS_NULL($model["RUT_CLIENTE"])) {
                                                return '<button type="button" onClick="javascript:asignarCliente(\'' . $model["RUT_CLIENTE"] . '\',\'' . $model["NOMBRE"] . '\')" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></button>';
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
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById("insertaFila").addEventListener("click", insertaFila, false);
    document.getElementById("<?= $nombreModelLow ?>-descuento").addEventListener("change", function () {
        calculaValores()
    }, false);
    document.getElementById("borraFila").addEventListener("click", QuitarProducto, false);
    document.getElementById("venCodigoBarra").addEventListener("keyup", buscarCodigoBarra, false);

    function initialComponets() {
        //$("#tblOpePac").DataTable();
        $("#w0").on('blur', function() {
            cargaDatosCliente(document.getElementById("w0-hidden"));
        });
        $("#btnBusPac").click(function () {
            var id = $("#venFecBusPac").val();
            if (!id == "") {
                var fecha = id.split("/");
                var fecFormat = fecha[2] + fecha[1] + fecha[0]
                var Url = '<?= Yii::$app->request->absoluteUrl . '&date=' ?>' + fecFormat;
                $.pjax.reload({container: "#pacientes", url: Url, replace: false});
            } else {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar una fecha válida");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });
        
    }

    function VerOperativos() {
        $("#operativoModal").modal();
    }

    function asignarCliente(rut, nombre) {
        miRut = rut.split('-')
        /*var id = "<?= $nombreModelLow ?>-cliente";
        var combo = document.getElementById(id);
        for (var i = 1; i < combo.length; i++) {
            if (combo.options[i].value == miRut[0]) {
                combo.selectedIndex = i;
                $("#" + id).trigger('change.select2');
            }
        }*/
        var input = document.getElementById("w0");
        input.value = nombre + " (" + miRut[0] + ")";
        document.getElementById("w0-hidden").value = miRut[0];
        cargaDatosCliente(document.getElementById("w0-hidden"));

        $("#operativoModal").modal("toggle");
    }

    function cargaDatosCliente(combo) {
        var proveedores = <?php
echo json_encode(ArrayHelper::toArray($clientes, [
            'app\models\entity\Persona' => [
                'RUT',
                'DV',
                'CAT_PERSONA',
                'NOMBRE',
                'DIRECCION',
                'TELEFONO',
                'EMAIL',
            ],
]));
?>;

        proveedores.forEach(function (value, index, ar) {
            if (value.RUT == combo.value) {
                document.forms["login-form"]["proNombre"].value = value.NOMBRE;
                document.forms["login-form"]["proDireccion"].value = value.DIRECCION;
                document.forms["login-form"]["proMail"].value = value.EMAIL;
                document.forms["login-form"]["proFono"].value = value.TELEFONO;
            }

        });

        var filas = document.getElementById("detalleCompra").rows.length - 1;
        for(i=filas;i>0;i--){
            document.getElementById("detalleCompra").deleteRow(i);
        }
        calculaValores()
    }

    function buscarCodigoBarra() {
        cod = document.getElementById("venCodigoBarra").value;
        if(cod.trim().length == 12){
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=ventas/buscar-productos-by-codigo-barra' ?>',
                type: 'post',
                data: {
                    _cod: cod,
                    _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                },
                success: function (producto) {
                    if (producto.productos.length > 0) {

                        var table = document.getElementById("detalleCompra");
                        var filas = document.getElementById("detalleCompra").rows.length;
                        //var columnas = document.getElementById("detalleCompra").rows[0].cells.length;
                        var row = table.insertRow(filas);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        var cell5 = row.insertCell(4);
                        cell1.innerHTML = '<input value="' + filas + '" type="text" class="form-control" name="indice" id="indice' + filas + '" readonly="readonly">';
                        cell2.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-cantidad required"><input type="text" id="<?= $nombreModelLow ?>-cantidad' + filas + '" class="form-control" onblur="javascript:onBlurCantidad(this)" name="<?= $nombreModel ?>[cantidad]" size="11" maxlength="11" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="000000" required aria-required="true"></div>';
                        cell3.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-producto required"><select id="<?= $nombreModelLow ?>-producto' + filas + '" class="form-control" name="<?= $nombreModel ?>[producto]" onChange="javascript:onChangeProducto(' + filas + ',this.value)" aria-required="true"><option value="">ELEGIR</option></select></div>';
                        cell4.innerHTML = '<input type="text" class="form-control" name="venValorUnit" id="venValorUnit' + filas + '" readonly="readonly">';
                        cell5.innerHTML = '<input type="text" class="form-control" name="venValor" id="venValor' + filas + '" readonly="readonly">';

                        $.ajax({
                            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=ventas/buscar-promociones' ?>',
                            type: 'post',
                            data: {
                                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                            },
                            success: function (data) {
                                var id = "<?= $nombreModelLow ?>-producto";
                                var nombre = "";
                                if (filas > 0) {
                                    nombre = id + filas;
                                } else {
                                    nombre = id;
                                }
                                comboProductos = document.getElementById(nombre);
                                comboProductos.length = 0
                                option = document.createElement("option");
                                option.text = "ELEGIR";
                                option.value = "";
                                comboProductos.add(option, comboProductos[0]);

                                total = comboProductos.options.length;
                                for (i = 1; i <= data.productos.length; i++) {
                                    var miText = data.productos[i - 1]["DESCRIPCION"];
                                    var miValue = data.productos[i - 1]["ID_HIJO"] + ";" + data.productos[i - 1]["VALOR_VENTA"];
                                    var option = document.createElement("option");
                                    option.text = miText;
                                    option.value = miValue;
                                    comboProductos.add(option, comboProductos[i]);
                                    var proCodBar = producto.productos[producto.productos.length - 1].ID_HIJO + ";" + producto.productos[producto.productos.length - 1].VALOR_VENTA;
                                    var lisProd = comboProductos[i].value;
                                    if (lisProd == proCodBar) {
                                        comboProductos[i].selected = true;
                                    }
                                }
                                $("#" + id).trigger('change.select2');
                                calculaValores();
                                document.getElementById("venCodigoBarra").value = "";
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

    function insertaFila() {
        var table = document.getElementById("detalleCompra");
        var filas = document.getElementById("detalleCompra").rows.length;
        //var columnas = document.getElementById("detalleCompra").rows[0].cells.length;
        var row = table.insertRow(filas);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        cell1.innerHTML = '<input value="' + filas + '" type="text" class="form-control" name="indice" id="indice' + filas + '" readonly="readonly">';
        cell2.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-cantidad required"><input type="text" id="<?= $nombreModelLow ?>-cantidad' + filas + '" class="form-control" onblur="javascript:onBlurCantidad(this)" name="<?= $nombreModel ?>[cantidad][]" size="11" maxlength="11" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="000000" required aria-required="true"></div>';
        cell3.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-producto required"><select id="<?= $nombreModelLow ?>-producto' + filas + '" class="form-control" name="<?= $nombreModel ?>[producto][]" onChange="javascript:onChangeProducto(' + filas + ',this.value)" aria-required="true"><option value="">ELEGIR</option></select></div>';
        cell4.innerHTML = '<input type="text" class="form-control" name="venValorUnit" id="venValorUnit' + filas + '" readonly="readonly">';
        cell5.innerHTML = '<input type="text" class="form-control" name="venValor" id="venValor' + filas + '" readonly="readonly">';
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=ventas/buscar-promociones' ?>',
            type: 'post',
            data: {
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            success: function (data) {
                var id = "<?= $nombreModelLow ?>-producto";
                var nombre = "";
                if (filas > 0) {
                    nombre = id + filas;
                } else {
                    nombre = id;
                }
                comboProductos = document.getElementById(nombre);
                comboProductos.length = 0
                option = document.createElement("option");
                option.text = "ELEGIR";
                option.value = "";
                comboProductos.add(option, comboProductos[0]);

                total = comboProductos.options.length;
                for (i = 1; i <= data.productos.length; i++) {
                    var miText = data.productos[i - 1]["DESCRIPCION"];
                    var miValue = data.productos[i - 1]["ID_HIJO"] + ";" + data.productos[i - 1]["VALOR_VENTA"];
                    var option = document.createElement("option");
                    option.text = miText;
                    option.value = miValue;
                    comboProductos.add(option, comboProductos[i]);
                }
                calculaValores();
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

    function QuitarProducto() {
        var filas = document.getElementById("detalleCompra").rows.length;
        if (filas <= 1) {
            $("#modTitulo").html("Validación");
            $("#modBody").html("No se puede eliminar");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-danger fade");
            $("#myModal").modal();
        } else {
            //eliminar a la base de datos
            document.getElementById("detalleCompra").deleteRow(filas - 1);
        }
        calculaValores();
    }

    function onChangeProducto(ind, valorComboBox) {
        var idValorUni = "venValorUnit"
        var nombreValorUni = "";
        var idValorXCant = "venValor"
        var nombreValorXCant = "";
        var idCantidad = "<?= $nombreModelLow ?>-cantidad";
        var nombreCantidad = "";
        var idComboProducto = "<?= $nombreModelLow ?>-producto";
        var nombreComboProducto = "";


        var pos = 0;
        if (isNaN(ind) || ind == 0) {
            nombreCantidad = idCantidad;
            nombreValorUni = idValorUni;
            nombreValorXCant = idValorXCant;
            nombreComboProducto = idComboProducto;
        } else {
            nombreCantidad = idCantidad + ind;
            nombreValorUni = idValorUni + ind;
            nombreValorXCant = idValorXCant + ind;
            nombreComboProducto = idComboProducto + ind;
        }
        var vCantidadActual = document.getElementById(nombreCantidad).value;
        if (vCantidadActual == "" || valorComboBox == "") {
            document.getElementById(nombreValorUni).value = 0;
            document.getElementById(nombreValorXCant).value = 0;
        } else {
            var miArray = valorComboBox.split(";")
            document.getElementById(nombreValorUni).value = miArray[1];
            document.getElementById(nombreValorXCant).value = miArray[1] * vCantidadActual;
        }
        calculaValores();
    }

    function onBlurCantidad(inputCantidad) {
        var idValorUni = "venValorUnit"
        var nombreValorUni = "";
        var idValorXCant = "venValor"
        var nombreValorXCant = "";
        var idComboProducto = "<?= $nombreModelLow ?>-producto";
        var nombreComboProducto = "";

        var largo = inputCantidad.id.length;
        var indice = inputCantidad.id.substring(largo - 1, largo);

        if (isNaN(indice)) {
            nombreComboProducto = idComboProducto;
            nombreValorUni = idValorUni;
            nombreValorXCant = idValorXCant;
        } else {
            nombreComboProducto = idComboProducto + indice;
            nombreValorUni = idValorUni + indice;
            nombreValorXCant = idValorXCant + indice;
        }
        var vCBProductoActual = document.getElementById(nombreComboProducto).value;
        if (vCBProductoActual == "" || inputCantidad.value == "") {
            document.getElementById(nombreValorUni).value = 0;
            document.getElementById(nombreValorXCant).value = 0;
        } else {
            var miArray = vCBProductoActual.split(";")
            document.getElementById(nombreValorUni).value = miArray[1];
            document.getElementById(nombreValorXCant).value = miArray[1] * inputCantidad.value;
        }
        calculaValores();
    }

    function calculaValores() {
        var arrayValor = document.getElementsByName("venValor");
        var subTotal = 0;
        for (i = 0; i < arrayValor.length; i++) {
            subTotal = subTotal + ((arrayValor[i].value == "") ? 0 : parseInt(arrayValor[i].value));
        }
        document.forms["login-form"]["<?= $nombreModelLow ?>-subtotal"].value = subTotal;
        var descuento = document.forms["login-form"]["<?= $nombreModelLow ?>-descuento"].value;
        if (descuento == "") {
            descuento = 0;
            document.forms["login-form"]["<?= $nombreModelLow ?>-descuento"].value = 0;
        }
        var neto = parseInt(subTotal) - parseInt(descuento);
        var iva = parseInt(parseInt(neto) * 0.19);
        var total = parseInt(parseInt(neto) * 1.19);
        document.forms["login-form"]["<?= $nombreModelLow ?>-neto"].value = neto;
        document.forms["login-form"]["<?= $nombreModelLow ?>-iva"].value = iva;
        document.forms["login-form"]["<?= $nombreModelLow ?>-total"].value = total;
    }
</script>  