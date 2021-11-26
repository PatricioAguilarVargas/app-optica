<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BrcPerfilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->params['titulo'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs']['rutaR'] = $rutaR;
?>
<div class="brc-perfiles-index">

    <h1>Asignar Perfil</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'ID_PADRE',
            'ID_HIJO',
            'DESCRIPCION',
            'IMG',
            'RUTA',
            ['class' =>
                'yii\grid\ActionColumn',
                'template' => '{create}',
                'buttons' => [
                    'create' => function ($url, $model) {
                        return Html::a(
                                        '<span class="glyphicon glyphicon-plus"></span>', str_replace("create", "asignar-perfil", $url) . $this->params['breadcrumbs']['rutaR'], [
                                    'title' => Yii::t('yii', 'Asignar'),
                                        ]
                        );
                    }
                ]
            ],
        ],
    ]);
    ?>
</div>
