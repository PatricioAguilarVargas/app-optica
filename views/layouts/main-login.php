<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

dmstr\web\AdminLteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link rel="icon" type="image/png" href="<?php echo Yii::$app->request->baseUrl. '/img/icono-isood.png' ?>" />
 
    <title><?= Html::encode($this->title) ?></title>
    <?php 
        $this->head();
        if (class_exists('backend\assets\AppAsset')) {
            backend\assets\AppAsset::register($this);
        } else {
            app\assets\SystemAsset::register($this);
        }
    ?>
    
</head>
<body class="login-page bodyLogin" >

<?php $this->beginBody() ?>

    <?= $content ?>
    <script src="<?= Yii::$app->request->baseUrl ?>/js/jquery-3.2.1.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/js/jquery.form.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
