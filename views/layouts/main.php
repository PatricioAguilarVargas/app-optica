<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it. 
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
            'main-login', ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\SystemAsset::register($this);
    }

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
	
	//echo '<pre>'; var_dump($this->params["titlePage"]); echo '/<pre>';;die();
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="icon" type="image/png" href="<?php echo Yii::$app->request->baseUrl . $this->params["icono"] ?>" />

            <?= Html::csrfMetaTags() ?>
            <title><?= Html::encode($this->title) ?></title>
            <?php $this->head() ?>
    
        </head>
        <body class="<?= \dmstr\helpers\AdminLteHelper::skinClass() ?> sidebar-mini" >
            <?php $this->beginBody() ?>
            <div class="wrapper">

                <?=
                $this->render(
                        'header.php', ['directoryAsset' => $directoryAsset]
                )
                ?>

                <?=
                $this->render(
                        'left.php', ['directoryAsset' => $directoryAsset]
                )
                ?>

                <?=
                $this->render(
                        'content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]
                )
                ?>

            </div>
            <script src="<?= Yii::$app->request->baseUrl ?>/js/jquery-3.2.1.min.js"></script>
            <script src="<?= Yii::$app->request->baseUrl ?>/js/jquery-migrate-3.0.0.js"></script>
            <?php $this->endBody() ?>
            <script type="text/javascript">

                $(document).ready(function () {
                    //$("#myModal").modal();
                    
                    initialComponets();
                     body = $("body");
                    $("[data-toggle='offcanvas']").click(function () {
                       
                        localStorage.setItem('sidebar-collapse', body.hasClass('sidebar-collapse') ? 1 : 0);
                        if (!body.hasClass("sidebar-collapse")) {
                            body.addClass("sidebar-collapse");
                            // bf.slideUp();
                        } else {
                            body.removeClass("sidebar-collapse");
                            //bf.slideDown();
                        }
						
						localStorage.setItem('sidebar-open', body.hasClass('sidebar-open') ? 1 : 0);
                        if (!body.hasClass("sidebar-open")) {
                            body.addClass("sidebar-open");
                            // bf.slideUp();
                        } else {
                            body.removeClass("sidebar-open");
                            //bf.slideDown();
                        }
                    });
                    if (localStorage.getItem('sidebar-collapse') === '0') {
                        body.addClass("sidebar-collapse");
						body.addClass("sidebar-collapse");
                        // bf.slideUp();
                    } else {
                        body.removeClass("sidebar-collapse");
						body.removeClass("sidebar-collapse");
                        //bf.slideDown();
                    }
					
					if (localStorage.getItem('sidebar-open') === '0') {
                        body.addClass("sidebar-open");
						body.addClass("sidebar-open");
                        // bf.slideUp();
                    } else {
                        body.removeClass("sidebar-open");
						body.removeClass("sidebar-open");
                        //bf.slideDown();
                    }
                    
                    /* Guion al rut */
                    $('.guion-rut').keyup(function (e) {
                        var valor = $(this).val();
                        var valorFinal = "";
                        valor = valor.replace(/-/gi, "");
                        var valorMax = valor.length;
                        if (valorMax == 1) {
                            valorFinal = "-" + valor.substring(valorMax - 1);
                        } else {
                            valorFinal = valor.substr(0, valorMax - 1) + "-" + valor.substring(valorMax - 1);
                        }
                        $(this).val(valorFinal);
                    });

                });
            </script>

            <!-- Modal para los mensajes -->
            <div class="modal modal-danger fade" id="myModal" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modColHeader" class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 id="modTitulo" class="modal-title"></h4>
                        </div>
                        <div id="modColBody" class="modal-body" style="background-color: white !important; color: black !important">
                            <p id="modBody"></p>
                        </div>

                    </div> 
                </div>
            </div>
        </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
