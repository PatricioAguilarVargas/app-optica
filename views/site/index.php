<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = $this->params['titulo'];
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-md-6" >
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>VENTAS DEL DÍA</h3>
                        <div class="row">
                            <div class="col-md-5" >
                                <p>CANT. VENTAS: <?=$data['ventas']['countD']?></p>
                                <p>TOTAL: <?=$data['ventas']['sumD']?></p>
                            </div>
                            <div class="col-md-5" >
                                <p>CANT. ABONOS: <?=$data['abonos']['countD']?></p>
                                <p>TOTAL: <?=$data['abonos']['sumD']?></p>
                            </div>
                            <div class="col-md-2" >
                                
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                     &nbsp;
                    </a>
                </div>
            </div>
            <div class="col-md-6" >
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>VENTAS DEL MES</h3>
                        <div class="row">
                            <div class="col-md-5" >
                                <p>CANT. VENTAS: <?=$data['ventas']['countM']?></p>
                                <p>TOTAL: <?=$data['ventas']['sumM']?></p>
                            </div>
                            <div class="col-md-5" >
                                <p>CANT. ABONOS: <?=$data['abonos']['countM']?></p>
                                <p>TOTAL: <?=$data['abonos']['sumM']?></p>
                            </div>
                            <div class="col-md-2" >
                                
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                       &nbsp;
                    </a>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-6" >
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>COMPRAS DEL DÍA</h3>
                        <div class="row">
                            <div class="col-md-5" >
                                <p>CANT. COMPRAS: <?=$data['compras']['countD']?></p>
                                <p>TOTAL: <?=$data['compras']['sumD']?></p>
                            </div>
                            <div class="col-md-5" >
                                <p>CANT. DONACIONES: <?=$data['donaciones']['countD']?></p>
                                <p></p>
                            </div>
                            <div class="col-md-2" >
                                
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                      &nbsp;
                    </a>
                </div>
            </div>
            <div class="col-md-6" >
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>COMPRAS DEL MES</h3>
                        <div class="row">
                            <div class="col-md-5" >
                                <p>CANT. COMPRAS: <?=$data['compras']['countM']?></p>
                                <p>TOTAL: <?=$data['compras']['sumM']?></p>
                            </div>
                            <div class="col-md-5" >
                                <p>CANT. DONACIONES: <?=$data['donaciones']['countM']?></p>
                                <p></p>
                            </div>
                            <div class="col-md-2" >
                                
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        &nbsp;
                    </a>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-6" >
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>OPERATIVOS DEL DÍA</h3>
                        <p>AGENDADOS: <?=$data['operativos']['countD']?></p>
                        <p>PER. AGENDADAS: <?=$data['operativos']['sumD']?></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-stethoscope"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                     &nbsp;
                    </a>
                </div>
            </div>
            <div class="col-md-6" >
                <div class="small-box bg-yellow">
                    <div class="inner">
                      <h3>OPERATIVOS DEL MES</h3>

                       <p>AGENDADOS: <?=$data['operativos']['countM']?></p>
                        <p>PER. AGENDADAS: <?=$data['operativos']['sumM']?></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-stethoscope"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        &nbsp;
                    </a>
                </div>
            </div>
        </div>
    
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    function initialComponets() {
        <?php if($msg != ""){ ?>
            $("#modTitulo").html("Error de Validación");
            $("#modBody").html('<?=$msg?>');
            $("#myModal").modal("show");
        <?php } ?>
        
    }
    
    
</script>
