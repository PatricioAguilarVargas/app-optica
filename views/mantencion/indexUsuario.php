<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\models\entity\Perfiles;
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

$posi = strrpos(get_class($umodel), "\\");
$nombreModelLowU = strtolower(substr(get_class($umodel), $posi + 1));
$nombreModelU = substr(get_class($umodel), $posi + 1);
?>
<?php
$form = ActiveForm::begin([
            'id' => 'login-form',
        ]);
?>
<div class="container-fluid">
    <div class="row">
        <div data-step="2" data-intro="En este sector se pueden ver a los usuarios creados" class="col-md-3">
            <h1>USUARIO</h1>
            <hr class="linea">
            <div id="jqxTree" style="float:left;"></div>
            <button data-step="3" data-intro="Este boton sirve para crear un nuevo usuario" type="button" id="agregarUsuario" class="btn btn-default" data-toggle="modal" data-target="#agregarUsuarioModal"><span class="glyphicon glyphicon-plus"></button> 
            <button data-step="4" data-intro="Este boton sirve para editar un nuevo usuario" type="button" id="modificarUsuario" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></button>
        </div>

        <div class="col-md-9">
            <h1>PERFILES DE USUARIO</h1>
            <hr class="linea">
            <div class="row">
                <H2>
                    <div class="col-md-2">
                        <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                    </div>
                    <div class="col-md-2">	
                        <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="Esta formulario sirve para ingresar a los usuarios que pueden acceder al sistema" onclick="javascript:introJs().start();">
                            <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                        </button>         
                    </div>
                    <div class="col-md-4 text-right">
                        <b>USUARIO:</b> 
                    </div>
                    <div class="col-md-4 text-left">
                        <b><span id="lbUsuario"></span></b>
                    </div>
                </H2>


                <div class="row">

                    <div  class="col-md-12">
                        <hr class="linea">
                        <div data-step="5" data-intro="Estos son los accesos que puede tener un usuario. Si esta en verde puede entrar en la pantalla, si esta en rojo no tiene acceso a la pantalla" class="form-group">

                            <?php \yii\widgets\Pjax::begin(['id' => 'perfiles', 'enablePushState' => false]); ?>
                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'DESCRIPCIÓN',
                                        'template' => '{estado}',
                                        'buttons' => [
                                            'estado' => function ($url, $model) {
                                                $sql = 'SELECT DESCRIPCION FROM brc_perfiles WHERE ID_PADRE=' . $model->ID_PADRE . ' AND ID_HIJO=' . $model->ID_HIJO;
                                                $utils = new app\models\utilities\Utils;
                                                $s = $utils->ejecutaQuery($sql);
                                                //var_dump($s);
                                                return $s[0]["DESCRIPCION"];
                                            },
                                        ],
                                    ],
                                    [
                                        'label' => 'ESTADO',
                                        'format' => 'raw',
                                        'value' => function($data) {
                                            $urlImg = "";
                                            if ($data->VIGENCIA == "N") {
                                                $urlImg = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/img/button/Cancel.png";
                                            } else {
                                                $urlImg = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/img/button/Ok.png";
                                            }
                                            return Html::img($urlImg, ['alt' => 'yii', 'width' => "35", 'height' => "35"]);
                                        }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{estado}',
                                        'header' => 'ASIGNACIÓN',
                                        'buttons' => [
                                            'estado' => function ($url, $model) {
                                                return '<select class="form-control" onchange="javascript:cambiaEstado(\'' . $model->RUT_USUARIO . '\',\'' . $model->ID_PADRE . '\',\'' . $model->ID_HIJO . '\',this,\'' . $model->VIGENCIA . '\')" id="select_' . $model->RUT_USUARIO . "-" . $model->ID_PADRE . "-" . $model->ID_HIJO . '"><option value="" selected>ELEGIR</option><option value="N">SIN PERMISO</option><option value="S">PERMITIDO</option></select>';
                                            },
                                        ],
                                    ],
                                ],
                            ]);
                            ?>
                            <?php \yii\widgets\Pjax::end(); ?>
                        </div>
                    </div>
                </div>
                <br>				
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $form = ActiveForm::begin([
                'id' => 'user-form', 
                'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>
    <div class="modal fade" id="agregarUsuarioModal" role="dialog">
        <div class="modal-dialog" style="width: 80% !important;" >
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header headModal">
                    <h4 class="modal-title">AGREGAR USUARIO</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $form->field($umodel, 'rut')
                                        ->textInput(["class" => "form-control guion-rut", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "00000000-K", "required" => true, "maxlength" => "11", "size" => "11"])
                                        ->label("RUT USUARIO", ['class' => 'label label-default']); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $form->field($umodel, 'nombre')
                                        ->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Nombre", "required" => true, "maxlength" => "255", "size" => "255"])
                                        ->label("NOMBRE USUARIO", ['class' => 'label label-default']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $form->field($umodel, 'usuario')
                                        ->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Usuario", "required" => true, "maxlength" => "15", "size" => "15"])
                                        ->label("USUARIO DE INGRESO", ['class' => 'label label-default']); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $form->field($umodel, 'clave')
                                        ->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Contraseña", "required" => true, "maxlength" => "11", "size" => "11"])
                                        ->label("CONTRASEÑA DE INGRESO", ['class' => 'label label-default']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                               <?=
                                $form->field($umodel, 'vigencia')->widget(Select2::classname(), [
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
                        <div class="col-md-6">
                            <div class="form-group">
                               
                            </div>
                        </div>
                    </div>
                    <div id="agregarDoc">
                        <hr class="linea">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-2">
                            <button id="guardaUsuario" type="button" class="btn btn-block btn-sistema btn-flat">GUARDAR USUARIO</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-block btn-sistema btn-flat" data-dismiss="modal">CANCELAR</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $this->registerJs(
            '$("document").ready(function(){ 
			$("#jqxTree").on("itemClick", function (event) {
				var args = event.args;
				var item = $("#jqxTree").jqxTree("getItem", args.element);
				var label = item.label;
				var id = item.id;
				$("#lbUsuario").html(label);
				var Url = "' . Yii::$app->request->absoluteUrl . '&rut=" + id;
				$.pjax.reload({container: "#perfiles",url:Url, replace:false});
			});
		});'
    );
    ?>

    <script type="text/javascript">

        function initialComponets() {
            document.getElementById("guardaUsuario").addEventListener("click", function () {
                guardaUsuario()
            }, false);
            document.getElementById("modificarUsuario").addEventListener("click", function () {
                valModUsuario()
            }, false);
            cargaTree();

        }

        function cargaTree() {
            var source = "";
            sourceString = "";
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/buscar-usuarios' ?>',
                method: 'POST',
                async: false,
                data: {
                    _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                },
                dataType: 'json',
                success: function (data, textStatus, xhr) {
                    sourceString = data.usuarios;
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

            $('#jqxTree').jqxTree('clear');
            $('#jqxTree').jqxTree({source: source, height: '350px', width: '100%'});
        }

        function cambiaEstado(rut, idp, idh, combo, vigBbDd) {
            if (combo.value == vigBbDd) {
                $("#modTitulo").html("Validación");
                $("#modBody").html("El registro " + combo.options[combo.selectedIndex].innerHTML + " ya se encuentra.");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if (combo.value == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe seleccionar una opción válida");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else {
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/asignar-perfil' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                        _rut: rut,
                        _idP: idp,
                        _idH: idh,
                        _vigencia: combo.value
                    },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {
                        if (data.res == "OK") {
                            $("#modTitulo").html("Validación");
                            $("#modBody").html("Guardado con éxito.");
                            $("#myModal").removeClass();
                            $("#myModal").addClass("modal modal-success fade");
                            $("#myModal").modal();
                            var Url = '<?= Yii::$app->request->absoluteUrl ?>&rut=' + rut;
                            $.pjax.reload({container: "#perfiles", url: Url, replace: false});
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

        function guardaUsuario() {
            var source = "";
            sourceString = "";
            var _rut = document.forms["user-form"]["<?= $nombreModelLowU ?>-rut"].value;
            var _nombre = document.forms["user-form"]["<?= $nombreModelLowU ?>-nombre"].value;
            var _usuario = document.forms["user-form"]["<?= $nombreModelLowU ?>-usuario"].value;
            var _clave = document.forms["user-form"]["<?= $nombreModelLowU ?>-clave"].value;
            var _vigencia = document.forms["user-form"]["<?= $nombreModelLowU ?>-vigencia"].value;
            var _avatar = "default-avatar.jpg";
            var guion = _rut.indexOf('-');

            if (_rut == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar el Rut del usuario");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if (guion == -1) {
                $("#modTitulo").html("Validación");
                $("#modBody").html("El dígito verificador debe estar separado por un guión");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if (_nombre == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar el nombre del usuario");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if (_usuario == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar el usuario");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else if (_clave == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe ingresar la clave del usuario");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else {
                var _dv = digitoVerificador(_rut.split('-')[0]);
                if (_dv != _rut.split('-')[1]) {
                    $("#modTitulo").html("Validación");
                    $("#modBody").html("El dígito veficador del rut es incorrecto");
                    $("#myModal").removeClass();
                    $("#myModal").addClass("modal modal-danger fade");
                    $("#myModal").modal();
                } else {
                    $.ajax({
                        url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/guarda-usuario' ?>',
                        method: 'POST',
                        async: false,
                        data: {
                            _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                            _rut: _rut,
                            _dv: _dv,
                            _nombre: _nombre,
                            _usuario: _usuario,
                            _clave: _clave,
                            _vigencia: _vigencia,
                            _avatar: _avatar,
                        },
                        dataType: 'json',
                        success: function (data, textStatus, xhr) {
                            if (data.res == "OK") {
                                $("#modTitulo").html("Validación");
                                $("#modBody").html("Usuario guardado con éxito");
                                $("#myModal").removeClass();
                                $("#myModal").addClass("modal modal-success fade");
                                $("#myModal").modal();
                                $('#agregarUsuarioModal').modal('toggle');
                                cargaTree();
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
                            $("#modBody").html("Fallo en el sistma. Error: " + request.responseText);
                            $("#myModal").removeClass();
                            $("#myModal").addClass("modal modal-danger fade");
                            $("#myModal").modal();
                        }
                    });
                }
            }
        }

        function valModUsuario() {
            if ($("#lbUsuario").html() == "") {
                $("#modTitulo").html("Validación");
                $("#modBody").html("Debe seleccionar un usuario, de la lista, para ser modificado");
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            } else {
                var usuario = $("#lbUsuario").html();
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=mantencion/buscar-usuario' ?>',
                    method: 'POST',
                    async: false,
                    data: {
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                        _usuario: usuario,
                    },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {
                        if (typeof data == 'object') {
                            document.forms["user-form"]["<?= $nombreModelLowU ?>-rut"].value = data.usuario["RUT"] + "-" + data.usuario["DV"];
                            document.forms["user-form"]["<?= $nombreModelLowU ?>-nombre"].value = data.usuario["NOMBRE"];
                            document.forms["user-form"]["<?= $nombreModelLowU ?>-usuario"].value = data.usuario["USUARIO"];
                            document.forms["user-form"]["<?= $nombreModelLowU ?>-clave"].value = data.usuario["CLAVE"];
                            $("#<?=$nombreModelLowU?>-vigencia").val(data.usuario["VIGENCIA"]).trigger('change.select2');
                            $('#agregarUsuarioModal').modal('toggle');
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
                        $("#modTitulo").html("Validación");
                        $("#modBody").html("Fallo en el sistema. Error: " + request.responseText);
                        $("#myModal").removeClass();
                        $("#myModal").addClass("modal modal-danger fade");
                        $("#myModal").modal();
                    }
                });
            }
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