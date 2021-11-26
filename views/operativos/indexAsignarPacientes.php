<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\grid\GridView;
use keygenqt\autocompleteAjax\AutocompleteAjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['pacientes'] = ArrayHelper::map($pacientes, 'RUT', 'NOMBRE');

$indice = 1;
$posi = strrpos(get_class($model), "\\");
$largo = strlen(get_class($model));
$nombreModelLow = strtolower(substr(get_class($model), $posi + 1));
$nombreModel = substr(get_class($model), $posi + 1);
?>



<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="En este formulario se agrega los pacientes que tendran los operativos" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-10">	
                    &nbsp;
                </div>
            </div>
            <br>
            <br>
            <hr class="linea">
            <div id="tomaHora">
                <div class="row">
                    <div class="col-md-10">
                        <p class="lead">DATOS DEL OPERATIVO:</span></p>
                    </div>
                    <div class="col-md-2 text-right">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div data-step="2" data-intro="Muestra el doctor acargo del operativo" class="form-group">
                            <span class="label label-default">DOCTOR:</span>
                            <input type="text" class="form-control" name="opDoctor" id="opDoctor" readonly="readonly" value="<?= $doctor ?>" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div data-step="3" data-intro="Muestra la hora del operativo" class="form-group">
                            <span class="label label-default">HORA:</span>
                            <input type="text" class="form-control" name="opHora" id="opHora" readonly="readonly" value="<?= $hora ?>" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div data-step="4" data-intro="Muestra el dia del operativo" class="form-group">
                            <span class="label label-default">DÍA:</span>
                            <input type="text" class="form-control" name="opDia" id="opDia" readonly="readonly" value="<?= $dia ?>" />
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div data-step="5" data-intro="Muestra la observacion del operativo" class="form-group">
                            <span class="label label-default">OBSERVACIÓN OPERATIVO:</span>
                            <input type="text" class="form-control" name="opObservacion" id="opObservacion" readonly="readonly" value="<?= $obser ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <hr class="linea">
            <div id="detalleOperativo">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            
                            <div class="row">
                                 <?php $form = ActiveForm::begin(['id' => 'login-form']);?>
                                   
                                <div data-step="6" data-intro="Debe elegir dentro de la lista al paciente" class="col-md-4">
                                   
                                    <?php
                                    /*$form->field($model, 'pacientes')->widget(Select2::classname(), [
                                        'data' => $this->params['breadcrumbs']['pacientes'],
                                        'language' => 'es',
                                        'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label("PACIENTE:", ['class' => 'label label-default']);*/
                                    ?>
                                    <?= $form->field($model, 'pacientes')->widget(AutocompleteAjax::classname(), [
                                        'multiple' => false,
                                        'url' => ['site/buscar-cliente'],
                                        'options' => [
                                            'autoFill'=>true,
                                            'placeholder' => 'Ingrese el rut o nombre del cliente.',
                                            "class" => "form-control",
                                            "onkeyup" => "javascript:this.value=this.value.toUpperCase();",
                                            "onkeypress" =>  "actualizaW0(this,event)",
                                            "required" => true, 
                                            "maxlength" => "50", 
                                            "size" => "50"
                                        ]
                                    ])->label("PACIENTE:", ['class' => 'label label-default']); ?>
                                </div>
                                <div data-step="7" data-intro="Agrega el paciente al operativo" class="col-md-2" style="padding:15px;">

                                    <?= Html::submitButton('ASIGNAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']); ?>
                                    <?= $form->field($model, 'dia')->hiddenInput(['value' => $diaSF])->label(false); ?>
                                    <?= $form->field($model, 'hora')->hiddenInput(['value' => $horaSF])->label(false); ?>
                                    <?= $form->field($model, 'doctor')->hiddenInput(['value' => $rdoc])->label(false); ?>
                                    <?= $form->field($model, 'obser')->hiddenInput(['value' => $obser])->label(false); ?>
                                   
                                </div>
                                 <?php ActiveForm::end(); ?>
                                 
                               <div data-step="8" data-intro="Te lleva a la pantalla para ingresar los pacientes"  class="col-md-2" style="padding:15px;">
                                   <a href="<?=Yii::$app->request->baseUrl?>/index.php?r=mantencion/index-persona&id=550000000&t=PERSONAS" class="btn btn-block btn-sistema btn-flat">INGRESAR</a>
                                </div>
                                <div data-step="9" data-intro="Te lleva a la pantalla para realizar la gestion del operativo" class="col-md-2" style="padding:15px;">
                                    <?php $form = ActiveForm::begin(['id' => 'operativo-form','action' => Yii::$app->request->baseUrl . '/index.php?r=operativos/index-operativo&id=320000000&t=OPERATIVOS',"method"=>"post" ]);?>
                                    
                                        <?= Html::submitButton('IR OPERATIVO', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']); ?>
                                        <?= '<input name="diaO" id="diaO" type="hidden" value="'.$dia.'">' ?>
                                        <?= '<input name="horaO" id="horaO" type="hidden" value="'.$hora.'" />' ?>
                                        <?= '<input name="doctorO" id="doctorO" type="hidden" value="'.$rdoc.'" />' ?>
                                        <?= '<input name="obserO" id="obserO" type="hidden" value="'.$obser.'" />' ?>
                                    <?php ActiveForm::end(); ?>
                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>
                            <div data-step="10" data-intro="Muestra los pacientes que estan en el operativo"  class="row">
                                <?=
                                GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'columns' => [
                                        [
                                            'label' => 'RUT PACIENTE',
                                            'format' => 'raw',
                                            'class' => 'yii\grid\DataColumn',
                                            'value' => function ($data) {
                                                return $data["RUT_CLIENTE"];
                                            },
                                        ],
                                        [
                                            'label' => 'NOMBRE PACIENTE',
                                            'format' => 'raw',
                                            'class' => 'yii\grid\DataColumn',
                                            'value' => function ($data) {
                                                return $data["NOMBRE"];
                                            },
                                        ],
                                        [
                                            'label' => 'TELÉFONO',
                                            'format' => 'raw',
                                            'class' => 'yii\grid\DataColumn',
                                            'value' => function ($data) {
                                                return $data["TELEFONO"];
                                            },
                                        ],
                                        [
                                            'label' => 'E-MAIL',
                                            'format' => 'raw',
                                            'class' => 'yii\grid\DataColumn',
                                            'value' => function ($data) {
                                                return $data["EMAIL"];
                                            },
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{estado}',
                                            'header' => 'ELIMINAR',
                                            'buttons' => [
                                                'estado' => function ($url, $model) {
                                                    $nombre = $model["NOMBRE"];
                                                    $dia = $model["DIA"]; 
                                                    $hora = $model["HORA"]; 
                                                    $doc = $model["RUT_DOCTOR"]; 
                                                    $pac = $model["RUT_CLIENTE"]; 
                                                    $id_tran = Yii::$app->request->getCsrfToken();
                                                    $url = Yii::$app->request->baseUrl.'/index.php?r=operativos/quita-paciente';
                                                    $script = <<<HTML
                                                            <a href="#" class="btn btn-sistema btn-flat" data-toggle="modal" data-target="#$pac"><span class="glyphicon glyphicon-remove"></span></a>
                                                            <div class="modal modal-danger fade" role="dialog" aria-hidden="true" id="$pac">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                                            <h4 class="modal-title">ELIMINAR PACIENTE</h4>
                                                                        </div>
                                                                        <div class="modal-body" style="background-color: white !important; color:black !important;">
                                                                            <p>¿REALMENTE DESEA ELIMINAR AL PACIENTE $nombre?</p>
                                                                            <form method="post" name="elimina-paciente" action="$url">
                                                                                <input type="hidden" name="_csrf" value="$id_tran">
                                                                                <input type="hidden" name="dia" value="$dia">
                                                                                <input type="hidden" name="hora" value="$hora">
                                                                                <input type="hidden" name="doctor" value="$doc">
                                                                                <input type="hidden" name="pac" value="$pac">
                                                                                <button type="button" class="btn btn-sistema btn-flat" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-sistema btn-flat">Eliminar</button>
                                                                            </form>
                                                                        </div>
                                                                        
                                                                    </div><!-- /.modal-content -->
                                                                </div><!-- /.modal-dialog -->
                                                            </div><!-- /.modal -->
HTML;
                                                    RETURN $script;
                                                },
                                            ],
                                        ],
                                    ],
                                ]);
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function initialComponets() {
        var msg = "<?=$msg?>"
        if(msg != ""){
            
            $("#modTitulo").html("Validación");
            $("#modBody").html("<?=$msg?>");
            $("#myModal").removeClass();
            if(msg == "OK"){
                $("#modBody").html("Estado cambiado con éxito");
                $("#myModal").addClass("modal modal-success fade");
            }else{
                $("#myModal").addClass("modal modal-danger fade");
            }
            $("#myModal").modal();
        }
    }

    
</script>  
<?php
$miUrlbase = Yii::$app->request->absoluteUrl;
$scritp = <<<JS
        $('#quitaPaciente').on('click', function (event) {
            $.pjax.reload({container: "#detalleOpera", url: '$miUrlbase', replace: false});
        });
       
JS;

$this->registerJs(
        $scritp, yii\web\View::POS_READY, 'agregarPaciente'
);
?>
