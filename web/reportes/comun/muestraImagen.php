<?php
	include("../sistemaWS/includes/phpdbc.min.php");
	require_once("../sistemaWS/config/vars.php");
	$SELECT ="";
	if(ISSET($_GET["cod"])){
		$SELECT ="SELECT CODIGO,DESCRIPCION,VIGENCIA,VALOR,COD_MARCA,MODELO,COD_MATERIAL,COD_COLOR,COD_FORMA,FOTO1,FOTO2 FROM brc_producto_web WHERE CODIGO='".$_GET["cod"]."'";
	}else{
		$SELECT ="SELECT TOP(1) CODIGO,DESCRIPCION,VIGENCIA,VALOR,COD_MARCA,MODELO,COD_MATERIAL,COD_COLOR,COD_FORMA,FOTO1,FOTO2 FROM brc_producto_web ";
	}
	$config = new Vars;
	$db = new db();
	$db->setConection($config->host,$config->usuario,$config->contrasenia,$config->bd,"PDO");
	$db->conectar();
	$db->query($SELECT);
	//echo $SELECT;
	$campos=$db->datos();
	
	if(ISSET($_GET["img"])){ 
		if($_GET["img"] == "1"){
                    
                        $foto1 = base64_decode($campos[0]["FOTO1"]);
                        $foto1 = imagecreatefromstring($foto1);
                        header("Content-type: image/jpg");
			imagejpeg($foto1);
                        imagedestroy($foto1);
		}elseif($_GET["img"] == "2"){
                        $foto2 = base64_decode($campos[0]["FOTO2"]);
                        $foto2 = imagecreatefromstring($foto2);
                        header("Content-type: image/jpg");
			imagejpeg($foto2);
                        imagedestroy($foto2);
		}else{
                        header("Content-type: image/jpg");
                        $foto1 = imagecreatefromjpeg("imagennoencontrada.jpg");
			imagejpeg($foto1);
                        imagedestroy($foto1);
		}
	}else{
                header("Content-type: image/jpg");
                $foto1 = imagecreatefromjpeg("imagennoencontrada.jpg");
		imagejpeg($foto1);
                imagedestroy($foto1);
	}
	//VAR_DUMP($campos[0]["FOTO1"]);
	
/*
        $data = $campos[0]["FOTO2"];
        $data = base64_decode($data);

$im = imagecreatefromstring($data);
if ($im !== false) {
    header('Content-Type: image/jpg');
    imagejpeg($im);
    imagedestroy($im);
}
else {
    echo 'Ocurrió un error.';
}
 */
