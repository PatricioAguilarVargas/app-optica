<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper">

    <section class="content">
        <?= Alert::widget() ?>
          <div class="box box-solid with-border" >
                    <div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<?= $content ?>
							</div>
						</div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
        
    </section>
</div>


