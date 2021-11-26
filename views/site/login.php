<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//echo '<pre>'; var_dump($this); echo '/<pre>';;die();
$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div id="login-overlay" class="modal-dialog">
        <div class="modal-content sombra" style="margin-top:15%; ">
            <div class="modal-header text-center headModal">
                <h4 class="modal-title" id="myModalLabel"><?=$this->params['titlePage']?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <img class="profile-img" width="240px" src="<?php echo Yii::$app->request->baseUrl . '/img/beraca.png' ?>" alt="">
                    </div>
                    <div class="col-md-7">
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'login-form',
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "{label}<br><div class=\"col-sm-12\">{input}</div><br><div class=\"col-sm-12\">{error}</div>",
                                        'labelOptions' => ['class' => 'col-sm-1 control-label'],
                                    ],
                        ]);
                        ?>

                        <?= $form->field($model, 'username')->textInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "", "required" => true, "autofocus" => true, "maxlength" => "15", "size" => "15"])
                                ->label("USUARIO:&nbsp;&nbsp;&nbsp;&nbsp;") ?>

                        <?= $form->field($model, 'password')->passwordInput(["class" => "form-control", "onkeyup" => "javascript:this.value=this.value.toUpperCase();", "placeholder" => "", "required" => true, "maxlength" => "15", "size" => "15"])
                                ->label("CLAVE:") ?>

                        <?=
                        $form->field($model, 'rememberMe')->checkbox([
                            'template' => "<div class=\"col-sm-12\">{input} {label}</div><br><div class=\"col-sm-12\">{error}</div>",
                        ])->label("RECORDARME")
                        ?>

                        <div class="form-group">
                            <div class="col-lg-12">
                        <?= Html::submitButton('INGRESAR', ['class' => 'btn btn-block btn-sistema btn-flat',  'name' => 'login-button','VALUE' => 'INGRESAR']) ?>
                            </div>
                        </div>   
<?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: rgba(100,100,100,1);">
                <div style="color:white;font-weight: bold;">Versión 1.0</div>
            </div>
        </div>
    </div>

</div>
