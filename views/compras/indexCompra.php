<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use keygenqt\autocompleteAjax\AutocompleteAjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['docList'] = ArrayHelper::map($tipDoc, 'CODIGO', 'DESCRIPCION');
$this->params['breadcrumbs']['proveedor'] = ArrayHelper::map($proveedor, 'ID_PROVEEDOR', 'NOMBRE_EMPRESA');
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


    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2" data-step="15" data-intro="Guarda la compra en el sistema">
                    <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div class="col-md-2" data-step="16" data-intro="Limpia los datos del formulario">	
                    <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="En esta pantalla se registran las compras de productos que se realizan" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-6">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div  class="col-md-12">
                    <div class="row">
                        <div  class="col-md-4">
                            <div class="form-group" data-step="2" data-intro="Debe ingresar el numero de la factura, orden de compra, etc">
                               <?= $form->field($model, 'numDoc')->textInput([
                                        "class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "000000000000", "required" => true, "autofocus" => true, "maxlength" => "11", "size" => "11"
                                    ])->label("NÚMERO", ['class' => 'label label-default']); 
                                ?>
                            </div>
                        </div>
                        <div  class="col-md-4">
                            <div class="form-group" data-step="3" data-intro="debe seleccionar el tipo de documento con la que se realizo la compra">
                                 <?=
                                     $form->field($model, 'tipDoc')->widget(Select2::classname(), [
                                         'data' => $this->params['breadcrumbs']['docList'],
                                         'language' => 'es',
                                         'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                         'pluginOptions' => [
                                             'allowClear' => true
                                         ],
                                     ])->label("DOCUMENTO:", ['class' => 'label label-default']);
                                 ?>
                            </div>
                        </div>
                        <div  class="col-md-4">
                            <div class="form-group" data-step="4" data-intro="Debe seleccionar al proveedor al que le hizo la compra">
                                <?php
                                   /* $form->field($model, 'proveedor')->widget(Select2::classname(), [
                                        'data' => $this->params['breadcrumbs']['proveedor'],
                                        'language' => 'es',
                                        'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label("PROVEEDOR:", ['class' => 'label label-default']);*/
                                ?>
                                 <?= $form->field($model, 'proveedor')->widget(AutocompleteAjax::classname(), [
                                    'multiple' => false,
                                    'url' => ['site/buscar-proveedor'],
                                    'options' => [
                                        'placeholder' => 'Ingrese el rut o nombre del proveedor.',
                                        "class" => "form-control",
                                        "onkeyup" => "javascript:this.value=this.value.toUpperCase();",
                                        "required" => true, 
                                        "maxlength" => "50", "size" => "50"
                                    ]
                                ])->label("PROVEEDOR:", ['class' => 'label label-default']); ?>
                            </div>
                        </div>
                    </div>
                     <hr class="linea">

                        <div class="row" data-step="5" data-intro="Estos son los datos del proveedor">
                            <div  class="col-md-4">
                                <span class="label label-default">NOMBRE:</span>
                                <input type="text" class="form-control" name="<?= $nombreModel ?>[proNombre]" id="<?= $nombreModelLow ?>-proNombre" readonly="readonly">
                            </div>
                            <div  class="col-md-4">
                                <span class="label label-default">CONTACTO:</span>
                                <input type="text" class="form-control" name="<?= $nombreModel ?>[proContacto]" id="<?= $nombreModelLow ?>-proContacto" readonly="readonly">
                            </div>
                            <div  class="col-md-4">
                                <span class="label label-default">DIRECCIÓN:</span>
                                <input type="text" class="form-control" name="<?= $nombreModel ?>[proDireccion]" id="<?= $nombreModelLow ?>-proDireccion" readonly="readonly">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div  class="col-md-4">
                                <span class="label label-default">CIUDAD:</span>
                                <input type="text" class="form-control" name="<?= $nombreModel ?>[proCiudad]" id="<?= $nombreModelLow ?>-proCiudad" readonly="readonly">
                            </div>
                            <div  class="col-md-4">
                                <span class="label label-default">E-MAIL:</span>
                                <input type="text" class="form-control" name="<?= $nombreModel ?>[proMail]" id="<?= $nombreModelLow ?>-proMail" readonly="readonly">
                            </div>
                            <div  class="col-md-4">
                                <span class="label label-default">TELÉFONO:</span>
                                <input type="text" class="form-control" name="<?= $nombreModel ?>[proFono]" id="<?= $nombreModelLow ?>-proFono" readonly="readonly">
                            </div>
                        </div>
                        <br>

                </div>
            </div>
            <hr class="linea">
            <?php // busqueda de codigo de barras   ?>
            <div class="row">
                <div class="col-md-4" data-step="6" data-intro="Se ingresa el codigo de barras del producto y este se carga en la tabla de productos a comprarse">
                    <span class="label label-default">INGRESE EL CÓDIGO DE BARRAS:</span>
                    <input type="text" class="form-control" name="venCodigoBarra" id="venCodigoBarra">
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <table id="detalleCompra" data-step="7" data-intro="Detalle de los productos a comprarse" class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th width="5%">
                                #
                            </th>
                            <th width="10%">
                                CANTIDAD
                            </th>
                            <th width="45%">
                                PRODUCTO
                            </th>
                            <th width="10%">
                                LOTE
                            </th>
                            <th width="15%">
                                VALOR UNITARIO
                            </th>
                            <th width="15%">
                                VALOR
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <?php //calculo final ?>						

            <div class="row">
                <div  class="col-md-2" >
                    <button data-step="8" data-intro="Agrega de la compra un producto" type="button" id="insertaFila" class="btn btn-default">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                    <button data-step="9" data-intro="Elimina de la compra un producto" type="button" id="borraFila" class="btn btn-default">
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
                        <div  class="col-md-2" data-step="10" data-intro="Este el subtotal de la compra">
                            <?= $form->field($model, 'subTotal')->textInput([
                                "class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"])->label(false); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1">
                            <span class="label label-default">DESCUENTO:</span>
                        </div>
                        <div  class="col-md-2" data-step="11" data-intro="se registra si en la compra realizaron un descuento">
                            <?= $form->field($model, 'descuento')->textInput(["class" => "form-control", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"])->label(false); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1 ">
                            <span class="label label-default">VALOR NETO:</span>
                        </div>
                        <div  class="col-md-2" data-step="12" data-intro="Valor neto con descuento">
                            <?= $form->field($model, 'neto')->textInput(["class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"])->label(false); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1">
                            <span class="label label-default">IVA:</span>
                        </div>
                        <div  class="col-md-2" data-step="13" data-intro="Calculo del iva">
                            <?= $form->field($model, 'iva')->textInput(["class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"])->label(false); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div  class="col-md-9">
                            &nbsp;
                        </div>
                        <div  class="col-md-1">
                            <span class="label label-default">TOTAL:</span>
                        </div>
                        <div  class="col-md-2" data-step="14" data-intro="Total de la compra">
                            <?= $form->field($model, 'total')->textInput(["class" => "form-control", "readonly" => "readonly", "placeholder" => "000000", "required" => true, "maxlength" => "12", "size" => "12"])->label(false); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
    document.getElementById("insertaFila").addEventListener("click", insertaFila, false);
   
    document.getElementById("borraFila").addEventListener("click", eliminaFila, false);
    document.getElementById("<?= $nombreModelLow ?>-descuento").addEventListener("change", function () {
        calculaValores()
    }, false);
    document.getElementById("venCodigoBarra").addEventListener("keyup", buscarCodigoBarra, false);
    
    function initialComponets() {
        $("#w0").blur(function () {
            cargaDatosProveedor(document.getElementById("w0-hidden"));
        });
<?php if ($exito == true && $folio != "000000000000") { ?>
            $("#modTitulo").html("Compra Ingresada");
            $("#modBody").html("El folio asignado a la compra es <?= $folio ?>");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-success fade");
            $("#myModal").modal();

<?php } ?>

    }

    function cargaDatosProveedor(combo) {
        var proveedores = <?php
echo json_encode(ArrayHelper::toArray($proveedor, [
            'app\models\entity\Proveedor' => [
                'ID_PROVEEDOR',
                'NOMBRE_EMPRESA',
                'CONTACTO',
                'DIRECCION',
                'CIUDAD',
                'MAIL',
                'TELEFONO',
            ],
]));
?>;

        proveedores.forEach(function (value, index, ar) {
            if (value.ID_PROVEEDOR == combo.value) {
                $("#<?= $nombreModelLow ?>-proNombre").val(value.NOMBRE_EMPRESA);
                $("#<?= $nombreModelLow ?>-proContacto").val(value.CONTACTO);
                $("#<?= $nombreModelLow ?>-proDireccion").val(value.DIRECCION);
                $("#<?= $nombreModelLow ?>-proCiudad").val(value.CIUDAD);
                $("#<?= $nombreModelLow ?>-proMail").val(value.MAIL);
                $("#<?= $nombreModelLow ?>-proFono").val(value.TELEFONO);
                $(combo.id).trigger("chosen:updated");
                //cargaProductosPorRut(combo.value);

            }
        });
        
        var filas = document.getElementById("detalleCompra").rows.length - 1;
        for(i=filas;i>0;i--){
            document.getElementById("detalleCompra").deleteRow(i);
        }
        calculaValores()

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
        var cell6 = row.insertCell(5);
        cell1.innerHTML = '<input value="' + filas + '" type="text" class="form-control" name="indice" id="indice' + filas + '" readonly="readonly">';
        cell2.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-cantidad required"><input type="text" id="<?= $nombreModelLow ?>-cantidad' + filas + '" class="form-control" onblur="javascript:onBlurCantidad(this)" name="<?= $nombreModel ?>[cantidad][]" size="11" maxlength="11" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="000000" required aria-required="true"></div>';
        cell3.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-producto required"><select id="<?= $nombreModelLow ?>-producto' + filas + '" class="form-control" name="<?= $nombreModel ?>[producto][]" onChange="javascript:onChangeProducto(' + filas + ',this.value)" aria-required="true"><option value="">ELEGIR</option></select></div>';
        cell4.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-lote required"><input type="text" id="<?= $nombreModelLow ?>-lote' + filas + '" class="form-control" name="<?= $nombreModel ?>[lote][]" size="20" maxlength="20" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="L-000000" required aria-required="true"></div>';
        cell5.innerHTML = '<input type="text" class="form-control" name="venValorUnit" id="venValorUnit' + filas + '" readonly="readonly">';
        cell6.innerHTML = '<input type="text" class="form-control" name="venValor" id="venValor' + filas + '" readonly="readonly">';

        cb_proveedores = document.getElementById("<?= $nombreModelLow ?>-proveedor").value;
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=compras/buscar-productos' ?>',
            type: 'post',
            data: {
                rut: cb_proveedores,
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
                    var miValue = data.productos[i - 1]["ID_HIJO"] + ";" + data.productos[i - 1]["VALOR_PROVEEDOR"];
                    var option = document.createElement("option");
                    option.text = miText;
                    option.value = miValue;
                    comboProductos.add(option, comboProductos[i]);
                }
                calculaValores();
            },
            error: function (xhr, status) {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Fallo en la consulta, comun�quese con el administrador del sit�o.");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });

    }

    function eliminaFila() {
        var filas = document.getElementById("detalleCompra").rows.length;
        if (filas <= 1) {
            $("#modTitulo").html("Validación");
            $("#modBody").html("No se puede eliminar la fila");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-danger fade");
            $("#myModal").modal();
        } else {
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
    
    function buscarCodigoBarra() {
        cod = document.getElementById("venCodigoBarra").value;
        cb_proveedores = document.getElementById("<?= $nombreModelLow ?>-proveedor").value;
        if(cod.trim().length < 12){
        }else if(cb_proveedores == ""){
            $("#modTitulo").html("Validación");
            $("#modBody").html("Debe seleccionar un proveedor para cargar los productos");
            $("#myModal").removeClass();
            $("#myModal").addClass("modal modal-danger fade");
            $("#myModal").modal();
        }else{
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=compras/buscar-productos-by-codigo-barra' ?>',
                type: 'post',
                data: {
                    _cod: cod,
                    _rut: cb_proveedores,
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
                        var cell6 = row.insertCell(5);
                        cell1.innerHTML = '<input value="' + filas + '" type="text" class="form-control" name="indice" id="indice' + filas + '" readonly="readonly">';
                        cell2.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-cantidad required"><input type="text" id="<?= $nombreModelLow ?>-cantidad' + filas + '" class="form-control" onblur="javascript:onBlurCantidad(this)" name="<?= $nombreModel ?>[cantidad][]" size="11" maxlength="11" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="000000" required aria-required="true"></div>';
                        cell3.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-producto required"><select id="<?= $nombreModelLow ?>-producto' + filas + '" class="form-control" name="<?= $nombreModel ?>[producto][]" onChange="javascript:onChangeProducto(' + filas + ',this.value)" aria-required="true"><option value="">ELEGIR</option></select></div>';
                        cell4.innerHTML = '<div class="form-group field-<?= $nombreModelLow ?>-lote required"><input type="text" id="<?= $nombreModelLow ?>-lote' + filas + '" class="form-control" name="<?= $nombreModel ?>[lote][]" size="20" maxlength="20" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="L-000000" required aria-required="true"></div>';
                        cell5.innerHTML = '<input type="text" class="form-control" name="venValorUnit" id="venValorUnit' + filas + '" readonly="readonly">';
                        cell6.innerHTML = '<input type="text" class="form-control" name="venValor" id="venValor' + filas + '" readonly="readonly">';

                        $.ajax({
                            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=compras/buscar-productos' ?>',
                            type: 'post',
                            data: {
                                rut: cb_proveedores,
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
                    }else{
                        $("#modTitulo").html("Validación");
                        $("#modBody").html("El producto ingresado no esta asignado al proveedor seleccionado.");
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

</script>  