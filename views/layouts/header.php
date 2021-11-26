    <?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header" >

    <?= Html::a('<span class="logo-mini"><img style="padding-top:7px; padding-left:7px;" src="'. Yii::$app->request->baseUrl. $this->params["icono"].'" class="img-responsive"></span><span class="logo-lg"><img style="padding-top:10px" src="'. Yii::$app->request->baseUrl. $this->params['logo'] . '" class="img-responsive"></span>', Yii::$app->homeUrl, ['class' => 'logo',"style" => "background-color:white"]) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">OPT. ISOOD</span>
        </a>
        
        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="user">
                    <div style="padding-right: 24px; padding-top: 7px; color:white;font-size: 24px;"><?= $this->params['titlePage'] ?></div>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                
            </ul>
        </div>
    </nav>
</header>
