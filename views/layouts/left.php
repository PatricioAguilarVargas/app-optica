<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\helpers\Html;
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'items' => $this->params['menuLeft']
            ]
        ) ?>

    </section>

</aside>
