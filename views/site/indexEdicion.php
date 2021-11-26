<?php
use kartik\switchinput\SwitchInput;
use kartik\file\FileInput;
use yii\helpers\Url;
$this->title = $this->params['titulo'];
?>
<div class="row">
    <div class="col-md-2">	
        <button type="button" class="btn btn-block btn-sistema btn-flat" data-step="1" data-intro="Este formulario redimenciona las fotos al tamaño de las que se solicitan en el formulario de ingreso de productos web. Esta las deja del tamaño necesario para los catalogos" onclick="javascript:introJs().start();">
            <span class="glyphicon glyphicon-question-sign"></span> AYUDA
        </button>         
    </div>
    <div class="col-md-10">	
         &nbsp;
    </div>
</div>
 <hr class="linea">
<div class="row">
   
    <div data-step="2" data-intro="Se dede ingresar la imagen, se presiona upload. despues presionar descargar" class="col-md-12" >
        <label class="label label-default">INGRESE UNA IMÁGEN PARA RIDIMENSIONAR</label>
       
        <?=
        FileInput::widget([
            'name' => 'file',
            'id' => 'file',
            'options' => [
                'multiple' => false
            ],
            'pluginOptions' => [
                'resizeImages' => true,
                'allowedFileExtensions' => ['jpg'],
                'removeFromPreviewOnError' => true,
                'uploadUrl' => Url::to(['/site/file-upload']),
                'maxFileCount' => 1
            ]
        ]);
        ?>

        <br>
        <a id="descarga" class="btn btn-block btn-sistema btn-flat" href="<?= Url::toRoute(["site/file-download"]) ?>">DESCARGAR IMÁGEN</a>
    </div>
</div>


<script>

    function initialComponets() {
        $('#descarga').hide();
        $('#file').on('filebatchuploadcomplete', function (event, files, extra) {
            $('#file').fileinput('clear');
            $('#descarga').show();
        });
        $('#file').on('change', function (event) {
            $('#descarga').hide();
        });
    }
</script>