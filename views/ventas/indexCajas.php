<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\models\entity\Perfiles;
use kartik\select2\Select2;
use kartik\date\DatePicker;

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
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2" data-step="3" data-intro="Guarda la apertura en el sistema">
<?= Html::submitButton('GUARDAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'guardar-button']) ?>
                </div>
                <div class="col-md-2" data-step="4" data-intro="Limpia los datos del formulario">	
<?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-block btn-sistema btn-flat', 'name' => 'limpiar-button']) ?>
                </div>
                <div class="col-md-2">	
                    <button data-step="1" data-intro="En esta pantalla se registran las apertura de cajas diarias" onclick="javascript:introJs().start();" type="button" class="btn btn-block btn-sistema btn-flat" >
                        <span class="glyphicon glyphicon-question-sign"></span> AYUDA
                    </button>         
                </div>
                <div class="col-md-6">	
                    &nbsp;
                </div>
            </div>
            <hr class="linea">
            <div class="row">
                <div  class="col-md-12" data-step="2" data-intro="Se debe ingresar el monto con el que se comienza el dia">
                    <div class="form-group">
                        <?= $form->field($model, 'monto')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "Monto", "required" => true, "maxlength" => "50", "size" => "50"])
                                        ->label("MONTO:", ['class' => 'label label-default']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">

    function initialComponets() {
      
    }
    
</script>