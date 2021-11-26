<?php

use yii\helpers\Html;

$this->title = $name;
?>



<!DOCTYPE html>
<html lang=&quot;es&quot;>
     <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="descripcion" content="error de validacion" />
      
    </head>
    <body >
        <div class="site-index">
			<div class="body-content">
            <header>
                <h2 style="color:red"><span>ERROR N°: <?=$name?>.</span></h2>
                <h2>Message: <?= $message ?></h2>
                
            </header>
            <section>
                
                <p>Ha ocurrido un error en algún momento del procesamiento de datos.  
                   Se aconseja que se comunique con el administrador del sistema o presionar el link 
                   para retorno a la pagina de inicio. <a href="<?= Yii::$app->request->baseUrl?>">Ir a Sistema</a>
                </p>
            </section>
            <footer>
                
            </footer>
        </div>
        </div>
    </body>
</html>

