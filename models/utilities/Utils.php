<?php

namespace app\models\utilities;

use Yii;
use yii\data\ActiveDataProvider;
USE yii\db\Query;
use yii\base\BaseObject;
use app\models\entities\UsuariosPerfiles;

class Utils {

    private $rProducto = "";
    private $rProveedor = "";
    private $rProdProv = "";
    private $rUsuarios = "";
    private $rPersona = "";
    private $rCodigo = "";
    private static $rMenu = array();
    public $impresoraPOS = "POS-58";

    public static function generaMenuLeft($rut,$idPadre) {
        $menuPadre = UsuariosPerfiles::getUsuariosPerfilesJoinPerfilesByRut($rut,$idPadre);
        $menu = array();
        //verificamos si el nodo tiene hijos
        foreach ($menuPadre as $nodoPadre) {

            $menuHijo = UsuariosPerfiles::getUsuariosPerfilesJoinPerfilesByRut($rut,$nodoPadre["ID_HIJO"]);
            if (empty($menuHijo)) {
                //si no tiene hijos
                $ruta =  Yii::$app->request->baseUrl.$nodoPadre['RUTA'];
		        $ruta = str_replace("[id]",$nodoPadre['ID_HIJO'],$ruta);
		        $ruta = str_replace("[t]",$nodoPadre['DESCRIPCION'],$ruta);
                $menu[] = [
                    'label' => $nodoPadre["DESCRIPCION"],
                    //'options' => ['class' => 'treeview'],
                    'icon' => $nodoPadre['IMG'],
                    'url' => $ruta];
            } else {
                //si tiene hijos
                 $menu[] = [
                    'label' => $nodoPadre["DESCRIPCION"],
                    //'options' => ['class' => 'treeview'],
                    'icon' => $nodoPadre['IMG'],
                    'url' => '#',
                    'items' => self::generaMenuLeft($rut,$nodoPadre["ID_HIJO"])
                ];  
            }
        }
        return $menu;
    }

    public function recorreProducto($id_padre, $producto, $arbol) {
        //traemos los datos del id padre

        $productoPadre = null;
        if ($arbol == "CAT") {
            $productoPadre = $producto->find()->where('ID_PADRE=' . $id_padre . " and LENGTH(ID_HIJO) < 11")->all();
        } elseif ($arbol == "PRO") {
            $productoPadre = $producto->find()->where('ID_PADRE=' . $id_padre . "")->all();
        }

        //verificamos si el nodo tiene hijos
        foreach ($productoPadre as $nodoPadre) {


            if ($arbol == "CAT") {
                $productoHijo = $producto->find()->where('ID_PADRE=' . $nodoPadre->ID_HIJO . " and LENGTH(ID_HIJO) < 11")->all();
                if (empty($productoHijo)) {
                    //si no tiene hijos
                    //echo $nodoPadre->ID_PADRE."-".$nodoPadre->ID_HIJO."-".$nodoPadre->DESCRIPCION."<br>";
                    $this->rProducto = $this->rProducto . '{"label": "' . $nodoPadre->DESCRIPCION . '","id":"' . $nodoPadre->ID_PADRE . "-" . $nodoPadre->ID_HIJO . '" }';
                } else {
                    //si tiene hijos

                    $tmp = "";
                    //echo $nodoPadre->ID_PADRE."-".$nodoPadre->ID_HIJO."-".$nodoPadre->DESCRIPCION."<br>";
                    $this->rProducto = $this->rProducto . '{ "label": "' . $nodoPadre->DESCRIPCION . '","id":"' . $nodoPadre->ID_PADRE . "-" . $nodoPadre->ID_HIJO . '", "items": [';
                    $tmp = $tmp . $this->recorreProducto($nodoPadre->ID_HIJO, $producto, $arbol);
                    $tmp = substr($tmp, 0, -1);
                    $tmp = $tmp . "]}";
                    $this->rProducto = $this->rProducto . $tmp;
                }
            } elseif ($arbol == "PRO") {
                if (strlen($nodoPadre->ID_HIJO) == 11) {
                    $this->rProducto = $this->rProducto . '{"label": "' . $nodoPadre->DESCRIPCION . '","id":"' . $nodoPadre->ID_PADRE . "-" . $nodoPadre->ID_HIJO . '" }';
                }
            }
        }
    }

    public function recorreProveedor($proveedor) {
        $p = $proveedor->find()->all();
        foreach ($p as $nodo) {
            $this->rProveedor = $this->rProveedor . '{"label": "' . $nodo->NOMBRE_EMPRESA . '","id":"' . $nodo->ID_PROVEEDOR . '" }';
        }
    }

    public function recorreUsuarios($usuarios) {
        $p = $usuarios->find()->all();
        foreach ($p as $nodo) {
            $this->rUsuarios = $this->rUsuarios . '{"label": "' . $nodo->USUARIO . '","id":"' . $nodo->RUT . '" }';
        }
    }

    public function recorreProdProv($proveedor, $producto, $provProd) {
        //traemos los datos del id padre

        $rut = $provProd->find()->select('ID_PROVEEDOR')->distinct()->all();
        //verificamos si el nodo tiene hijos
        //echo "<br><br><br><br>";

        foreach ($rut as $nodo) {
            $prov = $proveedor->find()->where('ID_PROVEEDOR=' . $nodo->ID_PROVEEDOR)->all();
            $this->rProdProv = $this->rProdProv . '{ "label": "' . $prov[0]["NOMBRE_EMPRESA"] . '","id":"' . $prov[0]["ID_PROVEEDOR"] . '", "parentId":"0" ,"items": [';
            $ids = $provProd->find()->where("ID_PROVEEDOR=" . $nodo->ID_PROVEEDOR)->all();
            //var_dump($ids[0]["ID_HIJO"]);
            foreach ($ids as $p) {
                //var_dump($p->ID_PADRE);
                $padre = $p->ID_PADRE;
                $hijo = $p->ID_HIJO;
                $prod = $producto->find()->where("ID_PADRE=" . $padre)->andWhere("ID_HIJO=" . $hijo)->all();
                $this->rProdProv = $this->rProdProv . '{"label": "' . $prod[0]["DESCRIPCION"] . '","id":"' . $prod[0]["ID_PADRE"] . "-" . $prod[0]["ID_HIJO"] . '", "value":"' . $p["VALOR_PROVEEDOR"] . '" }';
            }
            //$this->rProdProv = substr($this->rProdProv,0,-1);
            $this->rProdProv = $this->rProdProv . "]}";
        }
        //var_dump($this->rProdProv);
    }

    public function recorrePersona($persona, $codigos) {
        $cat = $codigos->find()->where("TIPO = 'PER_CT'")->all();
        //var_dump($cat);
        foreach ($cat as $nodo) {

            $this->rPersona = $this->rPersona . '{ "label": "' . $nodo["DESCRIPCION"] . '","id":"' . $nodo["CODIGO"] . '", "parentId":"0" ,"items": [';
            $per = $persona->find()->where("CAT_PERSONA='" . $nodo->CODIGO . "'")->all();
            foreach ($per as $p) {
                //var_dump($p);
                $this->rPersona = $this->rPersona . '{"label": "' . $p["NOMBRE"] . '","id":"' . $p["RUT"] . "-" . $p["DV"] . '", "parentId":"' . $nodo["CODIGO"] . '", "padre":"' . $nodo["CODIGO"] . '" }';
            }
            $this->rPersona = $this->rPersona . "]}";
        }
    }

    public function recorreCodigos($codigos) {
        $query = new Query;
        $tipo = $query->select(['TIPO'])
                ->from('brc_codigos')
                ->distinct()
                ->all();

        foreach ($tipo as $nodo) {

            $this->rCodigo = $this->rCodigo . '{ "label": "' . $nodo["TIPO"] . '","id":"' . $nodo["TIPO"] . '", "parentId":"0" ,"items": [';
            $cod = $codigos->find()->where("TIPO='" . $nodo["TIPO"] . "'")->all();
            foreach ($cod as $c) {
                //var_dump($p);
                $this->rCodigo = $this->rCodigo . '{"label": "' . $c["DESCRIPCION"] . '","id":"' . $c["CODIGO"] . '", "parentId":"' . $nodo["TIPO"] . '" }';
            }
            $this->rCodigo = $this->rCodigo . "]}";
        }
    }

    public static function getMenuLeft($rut){
        self::$rMenu = self::generaMenuLeft($rut,0);
        array_unshift(self::$rMenu , ['label' => 'MENÚ ÓPTICA','options' => ['class' => 'header']]);
        return self::$rMenu;
    }
    
    public function entregaProveedor() {
        $proveedores = "[";
        $proveedores = $proveedores . substr($this->rProveedor, 0, -1);
        $proveedores = str_replace("}{", "},{", $proveedores);
        $proveedores = $proveedores . "}]";
        $proveedores = preg_replace("[\n|\r|\n\r]", '', $proveedores);
        return $proveedores;
    }

    public function entregaProducto() {
        $productos = "[";
        $productos = $productos . substr($this->rProducto, 0, -1);
        $productos = str_replace("}{", "},{", $productos);
        $productos = $productos . "}]";
        $productos = preg_replace("[\n|\r|\n\r]", '', $productos);
        return $productos;
    }

    public function entregaUsuarios() {
        $usuarios = "[";
        $usuarios = $usuarios . substr($this->rUsuarios, 0, -1);
        $usuarios = str_replace("}{", "},{", $usuarios);
        $usuarios = $usuarios . "}]";
        $usuarios = preg_replace("[\n|\r|\n\r]", '', $usuarios);
        return $usuarios;
    }

    public function entregaProdProv() {
        $proveProd = "[";
        $proveProd = $proveProd . substr($this->rProdProv, 0, -1);
        $proveProd = str_replace("}{", "},{", $proveProd);
        $proveProd = $proveProd . "}]";
        $proveProd = preg_replace("[\n|\r|\n\r]", '', $proveProd);
        return $proveProd;
    }

    public function entregaPersona() {
        $persona = "[";
        $persona = $persona . substr($this->rPersona, 0, -1);
        $persona = str_replace("}{", "},{", $persona);
        $persona = $persona . "}]";
        $persona = preg_replace("[\n|\r|\n\r]", '', $persona);
        return $persona;
    }

    public function entregaCodigo() {
        $codigo = "[";
        $codigo = $codigo . substr($this->rCodigo, 0, -1);
        $codigo = str_replace("}{", "},{", $codigo);
        $codigo = $codigo . "}]";
        $codigo = preg_replace("[\n|\r|\n\r]", '', $codigo);
        return $codigo;
    }

    public static function ejecutaQuery($sql) {
        $connection = \Yii::$app->db;
        $dataProvider = $connection->createCommand($sql);

        return $dataProvider->queryAll();
    }

    public static function ejecutaSql($sql) {
        $connection = \Yii::$app->db;
        $dataProvider = $connection->createCommand($sql);

        return $dataProvider->execute();
    }

    public function generaCodigoBarras() {
        $key = '';
        $longitud = 12; // upc-a
        //$pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $pattern = '123456789';
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $longitud; $i++)
            $key .= $pattern{mt_rand(0, $max)};
        return $key;
    }

    public function valida_rut($rut) {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $dv = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut) - 1);
        $i = 2;
        $suma = 0;
        foreach (array_reverse(str_split($numero)) as $v) {
            if ($i == 8)
                $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);

        if ($dvr == 11)
            $dvr = 0;
        if ($dvr == 10)
            $dvr = 'K';
        if ($dvr == strtoupper($dv))
            return "OK";
        else
            return "NOK";
    }

    public function GetPing($ip = NULL) {
        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (getenv("OS") == "Windows_NT") {
            $exec = exec("ping -n 3 -l 64 " . $ip);
            $ping = explode(" ", $exec);
            return end($ping);
        } else {
            $exec = exec("ping -c 3 -s 64 -t 64 " . $ip);
            $array = explode("/", end(explode("=", $exec)));
            return ceil($array[1]) . 'ms';
        }
    }
    
    public static function validateIfUser($idHijo){
        $rut = explode("-", Yii::$app->user->id)[0];
        $sql = "SELECT * FROM brc_usuarios_perfiles WHERE ";
        $sql = $sql."RUT_USUARIO = ".$rut." AND ";
        $sql = $sql."ID_HIJO = ".$idHijo." AND ";
        $sql = $sql."VIGENCIA = 'S' ";
        
        $reg = self::ejecutaQuery($sql);
        //var_dump($reg);die();
        if(empty($reg)){
            return false;
        }else if($reg[0]['VIGENCIA'] == 'S'){
            return true;
        }else{
            return false;
        }
       
    }
}

?>