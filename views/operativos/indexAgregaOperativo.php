<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\models\entity\Perfiles;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use keygenqt\autocompleteAjax\AutocompleteAjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
$this->params['breadcrumbs']['doctor'] = ArrayHelper::map($doctor, 'RUT', 'NOMBRE');
$this->params['breadcrumbs']['tipoOper'] = ArrayHelper::map($tipoOper, 'CODIGO', 'DESCRIPCION');
//$this->params['breadcrumbs']['proveedor'] = ArrayHelper::map($proveedor,'ID_PROVEEDOR','NOMBRE_EMPRESA');
$indice = 1;
$posi = strrpos(get_class($model), "\\");
$largo = strlen(get_class($model));
$nombreModelLow = strtolower(substr(get_class($model), $posi + 1));
$nombreModel = substr(get_class($model), $posi + 1);
?>
<?php $form = ActiveForm::begin(['id' => 'operativo-form',]); ?>	
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div data-step="8" data-intro="Guarda el operativo" class="col-md-2">
                    <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div data-step="9" data-intro="Limpia el formulario" class="col-md-2">	
                    <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="En este formulario se agrega en que día habrá operativo. Este estara disponible en la pagina web de la optica" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
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
                        <div data-step="2" data-intro="Debe elegir el dia del operativo" class="form-group">
                             <?= 
                                $form->field($model, 'dia')->widget(DatePicker::className(),[
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
                                ])->label("DÍA:", ['class' => 'label label-default']);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div data-step="3" data-intro="Debe ingresar la hora del operativo" class="form-group">
                            <?= 
                               $form->field($model, 'hora')->widget(MaskedInput::className(),[
                                    'mask' => '##:##',
                                ])->label("HORA:", ['class' => 'label label-default']);
                            ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div data-step="4" data-intro="Debe elegir el doctor del operativo" class="form-group">
                            
                           <?= $form->field($model, 'doctor')->widget(AutocompleteAjax::classname(), [
                                'multiple' => false,
                                'url' => ['site/buscar-doctor'],
                                'options' => [
                                    'placeholder' => 'Ingrese el rut o nombre del doctor.',
                                    "class" => "form-control",
                                    "required" => true, 
                                    "maxlength" => "50", "size" => "50",
                                ],
                            ])->label("DOCTOR:", ['class' => 'label label-default']); ?>
							
						</div>
                    </div>
                    <div class="col-md-2">
                        <div data-step="5" data-intro="Debe elegir el tipo del operativo" class="form-group">
                            <?= $form->field($model, 'tipo')->widget(Select2::classname(), [
                                    'data' => $this->params['breadcrumbs']['tipoOper'],
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'ELEGIR', "class" => "form-control select2", "style" => 'width: 100%;'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("TIPO:", ['class' => 'label label-default']);
							?>
						</div>
                    </div>
                    <div class="col-md-4">
                        <div data-step="6" data-intro="Debe ingresar alguna observacion del operativo" class="form-group">
                            <?= $form->field($model, 'obser')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Observación Operativo"])
                                        ->label("OBSERVACIÓN OPERATIVO:", ['class' => 'label label-default']); ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="linea">
            <div data-step="7" data-intro="Aqui estan los operativos agendados para este dia"  id="detalleOperativo">
                <div  class="row">
                    <div class="col-md-10">
                        <p class="lead">OPERATIVOS VIGENTES PARA EL DÍA</p>
                    </div>
                    <div class="col-md-2 text-right">

                    </div>
                </div>
                <div class="row">
                    <div  class="col-md-12">
                        <div class="form-group">
                            <?php \yii\widgets\Pjax::begin(['id' => 'opera', 'enablePushState' => false]); ?>
                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'label' => 'DÍA',
                                        'format' => 'raw',
                                        'class' => 'yii\grid\DataColumn',
                                        'value' => function ($data) {
                                            return substr($data->DIA, -2) . "/" . substr($data->DIA, 4, -2) . "/" . substr($data->DIA, 0, -4);
                                        },
                                    ],
                                    [
                                        'label' => 'HORA',
                                        'format' => 'raw',
                                        'class' => 'yii\grid\DataColumn',
                                        'value' => function ($data) {
											
                                            return substr($data->HORA, 0, -2) . ":" . substr($data->HORA, -2);
                                        },
                                    ],
                                    [
                                        'label' => 'OBSERVACIÓN',
                                        'format' => 'raw',
                                        'class' => 'yii\grid\DataColumn',
                                        'value' => function ($data) {
                                            return $data->OBSERVACION;
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'DOCTOR',
                                        'template' => '{doc}',
                                        'buttons' => [
                                            'doc' => function ($url, $model) {
                                                $sql = "SELECT NOMBRE FROM brc_persona WHERE CAT_PERSONA='P00002' AND RUT=" . $model->RUT_DOCTOR;
                                                //var_dump($sql);
                                                $utils = new app\models\utilities\Utils;
                                                $s = $utils->ejecutaQuery($sql);
                                                //var_dump($s);
                                                return $s[0]["NOMBRE"];
                                            },
                                        ],
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'TIPO',
                                        'template' => '{tipo}',
                                        'buttons' => [
                                            'tipo' => function ($url, $model) {
                                                $sql = "SELECT DESCRIPCION FROM brc_codigos WHERE TIPO='OPERAT' AND CODIGO='" . $model->TIPO_OPERATIVO."'";
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
                                        'template' => '{estado}',
                                        'header' => 'INGRE. PACIENTES',
                                        'buttons' => [
                                            'estado' => function ($url, $model) {
                                                return '<a class="btn btn-success" href="' . Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/index.php?r=operativos/index-agrega-pacientes&dia=' . substr($model->DIA, -2) . "/" . substr($model->DIA, 4, -2) . "/" . substr($model->DIA, 0, -4) . '&hora=' . substr($model->HORA, 0, -2) . ":" . substr($model->HORA, -2) . '&rut=' . $model->RUT_DOCTOR . '">Asignar</a>';
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
            </div>
        </div>

    </div>
</div>
</div>
<script type="text/javascript">
    function initialComponets() {
       /* $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl . '/index.php?r=site/buscar-doctor' ?>',
            method: 'GET',
            async: false,
            data: {
                term: "1",
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            dataType: 'json',
            success: function (data, textStatus, xhr) {
                console.log(data);
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                $("#modTitulo").html("Validación");
                $("#modBody").html("Fallo en el sistema. Error: " + request.responseText);
                $("#myModal").removeClass();
                $("#myModal").addClass("modal modal-danger fade");
                $("#myModal").modal();
            }
        });*/
    }
</script>  
<?php ActiveForm::end(); 

$miUrlbase = Yii::$app->request->absoluteUrl;
$scritp = <<<JS
        $('#$nombreModelLow-dia').on('change', function (event) {
            var _fecha = $('#$nombreModelLow-dia').val();
            var id = _fecha.split('/');
            var f = id[2] + id[1] + id[0];
            var Url = '$miUrlbase&f=' + f;
            $.pjax.reload({container: "#opera", url: Url, replace: false});
        });

 
JS;

$this->registerJs(
    $scritp,
    yii\web\View::POS_READY,
    $nombreModelLow.'-dia'
);
