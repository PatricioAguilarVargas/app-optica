<?php

namespace app\controllers;

/* CORE */

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
/* CONTROLLER */
use app\controllers\BaseController;
/* ENTITY */
use app\models\entities\Usuario;
use app\models\entities\Perfiles;
use app\models\entities\Producto;
use app\models\entities\Proveedor;
use app\models\entities\ProveedorProducto;
use app\models\entities\Persona;
use app\models\entities\Codigos;
use app\models\entities\UsuariosPerfiles;
/* FORM */
use app\models\forms\LoginForm;
use app\models\forms\ProductoForm;
use app\models\forms\ProveedorForm;
use app\models\forms\ProductoProveedorForm;
use app\models\forms\UsuarioForm;
use app\models\forms\PersonaForm;
use app\models\forms\CodigosForm;

/* UTILIDADES */
use app\models\utilities\Utils;

class MantencionController extends BaseController {

    public function actionIndexUsuario($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rut = 0;
            if (Yii::$app->request->get()) {
                if (!empty($_GET['rut'])) {
                    $rut = $_GET['rut'];
                }
            }
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $perUsu = new UsuariosPerfiles;
            $uModel = new UsuarioForm;
            $model = new Perfiles;
            $query = $perUsu->find()->where('RUT_USUARIO = ' . $rut)->andWhere("ID_HIJO != 100000000")->andWhere("ID_HIJO != 200000000");
            $vigencia = Codigos::find()->where("TIPO = 'EST_BO'")->all();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $conx = \Yii::$app->db;
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexUsuario', [
                        'model' => $model,
                        'umodel' => $uModel,
                        'dataProvider' => $dataProvider,
                        
                        'rutaR' => $rutaR,
                        'vigencia' => $vigencia,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionGuardaCategoria() {
        if (Yii::$app->request->isAjax) {
            $res = "NOK";
            $p = new Producto;
            $codH = Yii::$app->request->post('_codH');
            $codP = Yii::$app->request->post('_codP');
            $des = Yii::$app->request->post('_des');
            if ($codH == "") {
                $res = $p->find()->where('ID_PADRE=' . $codP)->max('ID_HIJO');
                //var_dump($res);
                if (is_null($res)) {
                    $tmpH = str_replace("0", "", $codP);
                    $tmpH = $tmpH . "1";
                    $codH = str_pad($tmpH, 10, "0");
                    //var_dump($codH);
                } else {
                    $tmpH = str_replace("0", "", $res);
                    $tmpH = $tmpH + 1;
                    $codH = str_pad($tmpH, 10, "0");
                }
            }

            //var_dump($codH);var_dump($codP);var_dump($res);
            if ($p->find()->where('ID_PADRE=' . $codP . ' and ID_HIJO=' . $codH)->one()) {
                $reg = $p->find()->where('ID_PADRE=' . $codP . ' and ID_HIJO=' . $codH)->one();
                $reg->ID_PADRE = $codP;
                $reg->ID_HIJO = $codH;
                $reg->DESCRIPCION = $des;
                $reg->STOCK_CRITICO = "0";
                $reg->VIGENCIA = "N";
                $reg->COD_BARRA = "000000000000";
                $reg->VALOR_VENTA = "0";
                if ($reg->update()) {
                    $res = "OK";
                } else {
                    $res = "NOK: No se pudo actualizar la categoria. ";
                    //var_dump($reg->getErrors());
                }
            } else {
                $p->ID_PADRE = $codP;
                $p->ID_HIJO = $codH;
                $p->DESCRIPCION = $des;
                $p->STOCK_CRITICO = "0";
                $p->VIGENCIA = "N";
                $p->COD_BARRA = "000000000000";
                $p->VALOR_VENTA = "0";
                if ($p->insert()) {
                    $res = "OK";
                } else {
                    $res = "NOK: no se puedo ingresar la categoria. ";
                    //var_dump($p->getErrors());
                }
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'res' => $res,
                'code' => 100,
            ];
        }
        return $this->redirect("index.php");
    }

    public function actionGuardaUsuario() {
        if (Yii::$app->request->isAjax) {
            $res = "NOK";
            $up = new Usuario;
            $usuPer = new UsuariosPerfiles;
            $utils = new Utils;
            $rut = (integer) Yii::$app->request->post('_rut');
            $dv = Yii::$app->request->post('_dv');
            $nom = Yii::$app->request->post('_nombre');
            $usu = Yii::$app->request->post('_usuario');
            $cla = Yii::$app->request->post('_clave');
            $ava = Yii::$app->request->post('_avatar');
            $vig = Yii::$app->request->post('_vigencia');
            //var_dump(Yii::$app->request->post());die();
            if ($up->find()->where('RUT= ' . $rut)->one()) {
                $reg = $up->find()->where('RUT= ' . $rut)->one();
                $reg->RUT = (integer) $rut;
                $reg->DV = $dv;
                $reg->NOMBRE = $nom;
                $reg->USUARIO = $usu;
                $reg->CLAVE = $cla;
                $reg->VIGENCIA = $vig;
                if ($reg->update()) {
                    $res = "OK";
                } else {
                    $res = "NOK: No se pudo actualizar el usuario. ";
                }
            } else {
                $up->RUT = (integer) $rut;
                $up->DV = $dv;
                $up->NOMBRE = $nom;
                $up->USUARIO = $usu;
                $up->CLAVE = $cla;
                $up->AVATAR = $ava;
                $up->VIGENCIA = $vig;
                if ($up->insert()) {
                    if ($usuPer->find()->where('RUT_USUARIO= ' . $rut)->one()) {
                        if ($usuPer->delete('RUT_USUARIO= ' . $rut)) {
                           
                        }
                    } 
                    $sql = "insert into brc_usuarios_perfiles";
                    $sql = $sql." select " . $rut . ",ID_PADRE,ID_HIJO,CASE ID_HIJO WHEN 100000000 THEN 'S' WHEN 200000000 THEN 'S'  ELSE 'N'  END ";
                    $sql = $sql." from brc_perfiles";
                    $sql = $sql." where ID_PADRE=0";
                    $utils->ejecutaSql($sql);
                    $sql = "insert into brc_usuarios_perfiles";
                    $sql = $sql." select " . $rut . ",ID_PADRE,ID_HIJO,'S'";
                    $sql = $sql." from brc_perfiles";
                    $sql = $sql." where ID_PADRE<>0";
                    $utils->ejecutaSql($sql);
                    $res = "OK";
                } else {
                    $res = "NOK: no se puedo ingresar el usuario. ";
                    //VAR_DUMP($up->getErrors());
                }
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'res' => $res,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionAsignarPerfil() {
        if (Yii::$app->request->isAjax) {
            $res = "NOK";
            $up = new UsuariosPerfiles;
            $rut = Yii::$app->request->post('_rut');
            $idP = Yii::$app->request->post('_idP');
            $idH = Yii::$app->request->post('_idH');
            $vigencia = Yii::$app->request->post('_vigencia');
            if ($up->find()->where('RUT_USUARIO= ' . $rut . ' and ID_PADRE=' . $idP . ' and ID_HIJO=' . $idH)->one()) {
                $reg = $up->find()->where('RUT_USUARIO= ' . $rut . ' and ID_PADRE=' . $idP . ' and ID_HIJO=' . $idH)->one();
                $reg->RUT_USUARIO = $rut;
                $reg->ID_PADRE = $idP;
                $reg->ID_HIJO = $idH;
                $reg->VIGENCIA = $vigencia;
                if ($reg->update()) {
                    $res = "OK";
                } else {
                    $res = "NOK: Error al actualizar el perfil";
                }
            } else {
                $res = "NOK: Perfil no Existe";
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'res' => $res,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionIndexProducto($id, $t) {
        if (!Yii::$app->user->isGuest  && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $model = new ProductoForm;
            $utils = new Utils;
            $producto = new Producto;
            $codigo = "";
            $vigencia = Codigos::find()->where("TIPO = 'EST_BO'")->all();
            $posi = strrpos(get_class($model), "\\");
            $nombreModel = substr(get_class($model), $posi + 1);
            if ($model->load(Yii::$app->request->post())) {
                //echo get_class($model);
                $post = Yii::$app->request->post($nombreModel);
                $id_padre = 0;
                $id_hijo = 0;
                if (strpos($post['codigo'], '-')) {
                    $cod = explode("-", $post['codigo']);
                    $id_padre = $cod[0];
                    $id_hijo = $cod[1];
                } else {
                    $id_padre = $post['codigo'];
                    $res = $producto->find()->where('ID_PADRE=' . $id_padre)->max('ID_HIJO');
                    if (is_null($res)) {
                        $id_hijo = $id_padre . "1";
                    } else {
                        $id_hijo = $id_padre + 1;
                    }
                }
                $codigo = $post['codBarra'];

                if ($producto->find()->where("COD_BARRA='" . $post['codBarra'] . "'")->one()) {
                    $codigo = $utils->generaCodigoBarras();
                }
                $pe = $producto->find()->where('ID_PADRE = ' . $id_padre . ' AND ID_HIJO =' . $id_hijo)->all();
                if ($pe) {
                    $producto->deleteAll('ID_PADRE = ' . $id_padre . ' AND ID_HIJO =' . $id_hijo);
                    $producto->ID_PADRE = $id_padre;
                    $producto->ID_HIJO = $id_hijo;
                    $producto->DESCRIPCION = $post['descripcion'];
                    $producto->STOCK_CRITICO = $post['stockCritico'];
                    $producto->VIGENCIA = $post['vigencia'];
                    $producto->COD_BARRA = $codigo;
                    $producto->VALOR_VENTA = $post['valorVenta'];
                    if ($producto->insert()) {
                        //echo "OK update";
                    } else {
                        echo "NOK ";
                        var_dump($producto->getErrors());
                    }
                } else {
                    $max = 0;
                    $p = $producto->find()->where('ID_PADRE = ' . $id_padre)->orderBy('ID_HIJO DESC')->all();
                    if (!$p) {
                        $p2 = $producto->find()->where('ID_HIJO = ' . $id_padre)->orderBy('ID_HIJO DESC')->all();
                        $max = $p2[0]['ID_HIJO'] . "1";
                    } else {
                        $max = $p[0]['ID_HIJO'] + 1;
                    }
                    $producto->ID_PADRE = $id_padre;
                    $producto->ID_HIJO = $max;
                    $producto->DESCRIPCION = $post['descripcion'];
                    $producto->STOCK_CRITICO = $post['stockCritico'];
                    $producto->VIGENCIA = $post['vigencia'];
                    $producto->COD_BARRA = $codigo;
                    $producto->VALOR_VENTA = $post['valorVenta'];
                    if ($producto->insert()) {
                        //echo "OK insert";
                    } else {
                        var_dump($producto->getErrors());
                    }
                }
            }
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexProducto', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'vigencia' => $vigencia
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarProductos() {
        if (Yii::$app->request->isAjax) {
            $producto = new Producto;
            $utils = new Utils;
            $arbol = Yii::$app->request->post('_arbol');
            $id_padre = Yii::$app->request->post('_id_padre');
            if (is_null($arbol)) {
                $arbol = "CAT";
            }
            if (is_null($id_padre)) {
                $id_padre = 0;
            }
            $utils->recorreProducto($id_padre, $producto, $arbol);
            $productos = $utils->entregaProducto();
            //echo "<br><br><br><br><br>".$arbol;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'productos' => $productos,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarProductosById() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $idTmp = explode(":", $data['id']);
            $id = $idTmp[0];
            $idTmp = explode("-", $id);
            $idPadre = $idTmp[0];
            $idHijo = $idTmp[1];
            $producto = new Producto;
            $p = $producto->find()->where('ID_PADRE=' . $idPadre)->andWhere(['ID_HIJO' => $idHijo])->all();
            //echo $productos;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'productos' => $p,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionEntregaCodigoById() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $idTmp = explode(":", $data['id']);
            $id = $idTmp[0];
            //$id = $_GET["id"];
            $producto = new Producto;
            $p = $producto->find()->where('ID_PADRE=' . $id)->max('ID_HIJO');
            //var_dump($p);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'codigo' => $p,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionIndexProveedor($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;

            $model = new ProveedorForm;
            $proveedor = new Proveedor;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $cod = explode("-", $model->codigo);
                $rut = $cod[0];
                $dv = $cod[1];
                $existe = $proveedor->find()->where('ID_PROVEEDOR=' . $rut)->all();
                if (empty($existe)) {
                    $proveedor->ID_PROVEEDOR = $rut;
                    $proveedor->NOMBRE_EMPRESA = $model->nombreEmpresa;
                    $proveedor->CONTACTO = $model->contacto;
                    $proveedor->DIRECCION = $model->direccion;
                    $proveedor->CIUDAD = $model->ciudad;
                    $proveedor->MAIL = $model->mail;
                    $proveedor->TELEFONO = $model->telefono;
                    $proveedor->insert();
                } else {
                    //echo "<br><br><br><br>";
                    $posi = strrpos(get_class($model), "\\");
                    $nombreModel = substr(get_class($model), $posi + 1);
                    $cod = explode("-", $_POST[$nombreModel]["codigo"]);
                    $rut = $cod[0];
                    $dv = $cod[1];
                    if ($proveedor->deleteAll("ID_PROVEEDOR=:ID_PROVEEDOR", [":ID_PROVEEDOR" => $rut])) {
                        $proveedor->ID_PROVEEDOR = Html::encode($_POST[$nombreModel]["codigo"]);
                        $proveedor->NOMBRE_EMPRESA = Html::encode($_POST[$nombreModel]["nombreEmpresa"]);
                        $proveedor->CONTACTO = Html::encode($_POST[$nombreModel]["contacto"]);
                        $proveedor->DIRECCION = Html::encode($_POST[$nombreModel]["direccion"]);
                        $proveedor->CIUDAD = Html::encode($_POST[$nombreModel]["ciudad"]);
                        $proveedor->MAIL = Html::encode($_POST[$nombreModel]["mail"]);
                        $proveedor->TELEFONO = Html::encode($_POST[$nombreModel]["telefono"]);
                        $proveedor->insert();
                    }
                }
            }
            $dataProvider = $dataProvider = new ActiveDataProvider([
                'query' => $proveedor->find(),
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexProveedor', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'proveedor' => $proveedor->find()->all(),
                        'dataProvider' => $dataProvider,
                ]);
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarUsuario() {
        if (Yii::$app->request->isAjax) {
            $u = new Usuario;
            $usuario = "";
            if (Yii::$app->request->post()) {
                if (!empty($_POST['_usuario'])) {
                    $rut = $_POST['_usuario'];
                    $usuario = $u->find()->where("USUARIO='" . $rut . "'")->one();
                } else {
                    $usuario = "no se recibio el parametro del usuario";
                }
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'usuario' => $usuario,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarProveedor() {
        if (Yii::$app->request->isAjax) {
            $p = new Proveedor;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $utils = new Utils;
            $utils->recorreProveedor($p);
            $proveedor = $utils->entregaProveedor();
            return [
                'proveedor' => $proveedor,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarUsuarios() {
        if (Yii::$app->request->isAjax) {
            $u = new Usuario;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $utils = new Utils;
            $utils->recorreUsuarios($u);
            $usuarios = $utils->entregaUsuarios();
            return [
                'usuarios' => $usuarios,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarProveedorById() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $idTmp = explode(":", $data['id']);
            $id = $idTmp[0];
            $proveedor = new Proveedor;
            $p = $proveedor->find()->where('ID_PROVEEDOR=' . $id)->all();
            //echo $productos;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'proveedor' => $p,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionEliminaProveedor() {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            $proveedor = new Proveedor;
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();
                $idTmp = explode(":", $data['id']);
                $id = $idTmp[0];
                if ($proveedor->deleteAll("ID_PROVEEDOR=:ID_PROVEEDOR", [":ID_PROVEEDOR" => $id])) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => array("OK"),
                        'code' => 100,
                    ];
                } else {
                    return [
                        'respuesta' => array("No se ha eliminado el proveedor"),
                        'code' => 100,
                    ];
                }
            }
            return [
                'respuesta' => array("No es una consulta ajax, no se ha eliminado el proveedor"),
                'code' => 100,
            ];
        }
        return $this->redirect("index.php");
    }

    public function actionEliminaProveProd() {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            $model = new ProveedorForm;
            $provProd = new ProveedorProducto;
            if (Yii::$app->request->isAjax) {
                $prov = Yii::$app->request->post('_proveedor');
                $prod = explode("-",Yii::$app->request->post('_producto'));
                if ($provProd->deleteAll("ID_PROVEEDOR=".$prov." AND ID_PADRE=".$prod[0]." AND ID_HIJO=".$prod[1])){
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => array("OK"),
                        'code' => 100,
                    ];
                } else {
                    return [
                        'respuesta' => array("No se ha eliminado el proveedor"),
                        'code' => 100,
                    ];
                }
            }
            return [
                'respuesta' => array("No es una consulta ajax, no se ha eliminado el proveedor"),
                'code' => 100,
            ];
        }
        return $this->redirect("index.php");
    }

    public function actionIndexProductoProveedor($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            $proBus = "0";
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            if(Yii::$app->request->get()){
                if(!empty($_GET['proBus'])){
                    $proBus = $_GET['proBus'];
                }
            }
            $pp = new ProveedorProducto;
            $model = new ProductoProveedorForm;
            $proveedor = new Proveedor;
            $producto = new Producto;
            if ($model->load(Yii::$app->request->post())) {

                $cod = explode("-", $model->id_producto);
                $id_p = $cod[0];
                $id_h = $cod[1];
                //var_dump($pp->find()->where('ID_PADRE ='.$id_p.' and ID_HIJO ='.$id_h.' and ID_PROVEEDOR='.$model->id_proveedor)->all());
                if (!$pp->find()->where('ID_PADRE =' . $id_p)->andWhere('ID_HIJO =' . $id_h)->andWhere('ID_PROVEEDOR=' . $model->id_proveedor)->all()) {
                    $pp->ID_PADRE = $id_p;
                    $pp->ID_HIJO = $id_h;
                    $pp->ID_PROVEEDOR = $model->id_proveedor;
                    $pp->VALOR_PROVEEDOR = $model->v_compra;
                    $pp->insert();
                    //echo "paso";
                } else {
                    //echo "<br><br><br><br>";
                    $posi = strrpos(get_class($model), "\\");
                    $nombreModel = substr(get_class($model), $posi + 1);
                    $cod = explode("-", $_POST[$nombreModel]["id_producto"]);
                    $id_p = $cod[0];
                    $id_h = $cod[1];
                    //var_dump($id_p);
                    if ($pp->deleteAll('ID_PADRE =' . $id_p . ' and ID_HIJO =' . $id_h . ' and ID_PROVEEDOR=' . $_POST[$nombreModel]["id_proveedor"])) {
                        $pp->ID_PADRE = Html::encode($id_p);
                        $pp->ID_HIJO = Html::encode($id_h);
                        $pp->ID_PROVEEDOR = Html::encode($_POST[$nombreModel]["id_proveedor"]);
                        $pp->VALOR_PROVEEDOR = Html::encode($_POST[$nombreModel]["v_compra"]);
                        $pp->insert();
                    }
                }
                $model = new ProductoProveedorForm;
            }
            $dataProvider = $dataProvider = new ActiveDataProvider([
                'query' => ProveedorProducto::buscarProductoPorProveedor($proBus),
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexProductoProveedor', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'dataProvider'=> $dataProvider,
                        'proveedor' => $proveedor->find()->all(),
                        'producto' => $producto->obtenerIDCompuesto(),
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarProveedorProducto() {
        if (Yii::$app->request->isAjax) {
            $pv = new Proveedor;
            $pd = new Producto;
            $pvp = new ProveedorProducto;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $utils = new Utils;
            $utils->recorreProdProv($pv, $pd, $pvp);
            $provProd = $utils->entregaProdProv();
            return [
                'provProd' => $provProd,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionGeneraCodigoBarra() {
        if (Yii::$app->request->isAjax) {
            $pd = new Producto;
            $utils = new Utils;
            $codigo = $utils->generaCodigoBarras();
            while ($pd->find()->where("COD_BARRA='" . $codigo . "'")->one()) {
                $codigo = $utils->generaCodigoBarras();
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'codigo' => $codigo,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionIndexPersona($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $codigos = new Codigos;
            $persona = new Persona;
            $model = new PersonaForm;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $cod = explode("-", $model->rut);
                $rut = $cod[0];
                $dv = $cod[1];
                $existe = $persona->find()->where("RUT=" . $rut . " AND CAT_PERSONA='" . $model->categoria . "'")->all();
                if (empty($existe)) {
                    $persona->RUT = $rut;
                    $persona->DV = $dv;
                    $persona->CAT_PERSONA = $model->categoria;
                    $persona->NOMBRE = $model->nombre;
                    $persona->DIRECCION = $model->direccion;
                    $persona->TELEFONO = $model->telefono;
                    $persona->EMAIL = $model->eMail;
                    $persona->insert();
                } else {
                    //echo "<br><br><br><br>";
                    $posi = strrpos(get_class($model), "\\");
                    $nombreModel = substr(get_class($model), $posi + 1);
                    $cod = explode("-", $_POST[$nombreModel]["rut"]);
                    $rut = $cod[0];
                    $dv = $cod[1];
                    if ($persona->deleteAll("RUT=" . $rut . " AND CAT_PERSONA='" . $_POST[$nombreModel]["categoria"] . "'")) {

                        $persona->RUT = $rut;
                        $persona->DV = $dv;
                        $persona->CAT_PERSONA = Html::encode($_POST[$nombreModel]["categoria"]);
                        ;
                        $persona->NOMBRE = Html::encode($_POST[$nombreModel]["nombre"]);
                        ;
                        $persona->DIRECCION = Html::encode($_POST[$nombreModel]["direccion"]);
                        ;
                        $persona->TELEFONO = Html::encode($_POST[$nombreModel]["telefono"]);
                        ;
                        $persona->EMAIL = Html::encode($_POST[$nombreModel]["eMail"]);
                        ;
                        $persona->insert();
                    }
                }
                $model = new PersonaForm;
            }
            $catBus = "TODOS";
            $perBust = "TODOS";
            if(Yii::$app->request->get()){
                if(!empty($_GET['catBus'])){
                    $catBus = $_GET['catBus'];
                }
                if(!empty($_GET['perBus'])){
                    $perBust = $_GET['perBus'];
                }
            }
            $query = $persona->find();
            if($catBus != "TODOS"){
                $query = $persona->find()->where("CAT_PERSONA='".$catBus."'");
            }
            if($perBust != "TODOS"){
                $query = $persona->find()->where("RUT=".$perBust);
            }
            
            if($perBust != "TODOS"  && $catBus != "TODOS"){
                $query = $persona->find()->where("RUT=".$perBust." AND CAT_PERSONA='".$catBus."'");
            }
            $dataProvider = $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexPersona', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'codigos' => $codigos->find()->where("TIPO='PER_CT'")->all(),
                        'personas' => $persona->find()->all(),
                        'dataProvider'=> $dataProvider,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarPersonas() {
        if (Yii::$app->request->isAjax) {
            $p = new Persona;
            $c = new Codigos;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $utils = new Utils;
            $utils->recorrePersona($p, $c);
            $personas = $utils->entregaPersona();
            return [
                'personas' => $personas,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionEliminaPersona() {
        if (!Yii::$app->user->isGuest) {
            $persona = new Persona;
            if (Yii::$app->request->isAjax) {
               $rut = Yii::$app->request->post('_rut');
               $cat = Yii::$app->request->post('_tipo');
                
                if ($persona->deleteAll("RUT= ".$rut." AND CAT_PERSONA='".$cat."'")) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'respuesta' => array("OK"),
                        'code' => 100,
                    ];
                } else {
                    return [
                        'respuesta' => array("No se ha eliminado la persona"),
                        'code' => 100,
                    ];
                }
            }
            return [
                'respuesta' => array("No es una consulta ajax, no se ha eliminado la persona"),
                'code' => 100,
            ];
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarPersonasByRut() {
        if (Yii::$app->request->isAjax) {
            $p = new Persona;
            $c = new Codigos;
            $_rut = explode("-", Yii::$app->request->post('_rut'));
            $cod = Yii::$app->request->post('_cod');
            $rut = $_rut[0];
            $dv = $_rut[1];
            $persona = $p->find()->where("CAT_PERSONA = '" . $cod . "' AND RUT=" . $rut)->one();
            if (is_null($persona)) {
                $persona = "NOK: No se encontro la persona seleccionada";
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'persona' => $persona,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionIndexCodGeneral($id, $t) {
        if (!Yii::$app->user->isGuest) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;

            $model = new CodigosForm;
            $codigo = new Codigos;
            if ($model->load(Yii::$app->request->post())) {
                //var_dump(Yii::$app->request->post());
                $posi = strrpos(get_class($model), "\\");
                $nombreModel = substr(get_class($model), $posi + 1);
                $cod = $_POST[$nombreModel]["codigo"];
                $res = "";
                if (empty($cod)) {
                    $res = $codigo->find()->where("TIPO='" . $_POST[$nombreModel]["tipo"] . "'")->max('CODIGO');
                    if (is_null($res)) {
                        $cod = substr($_POST[$nombreModel]["tipo"], 0, 1) . "00001";
                    } else {
                        $tmpH = substr($res, -5);
                        $tmpH = 1 + (integer) $tmpH;
                        $cod = substr($_POST[$nombreModel]["tipo"], 0, 1) . str_pad($tmpH, 5, "0", STR_PAD_LEFT);
                    }
                }
                $existe = $codigo->find()->where("TIPO='" . $_POST[$nombreModel]["tipo"] . "' AND CODIGO='" . $cod . "'")->all();
                if (empty($existe)) {
                    $codigo->TIPO = Html::encode($_POST[$nombreModel]["tipo"]);
                    $codigo->CODIGO = Html::encode($cod);
                    $codigo->DESCRIPCION = Html::encode($_POST[$nombreModel]["descripcion"]);
                    $codigo->insert();
                } else {
                    if ($codigo->deleteAll("TIPO='" . $_POST[$nombreModel]["tipo"] . "' AND CODIGO='" . $cod . "'")) {
                        $codigo->TIPO = Html::encode($_POST[$nombreModel]["tipo"]);
                        $codigo->CODIGO = Html::encode($cod);
                        $codigo->DESCRIPCION = Html::encode($_POST[$nombreModel]["descripcion"]);
                        $codigo->insert();
                    }
                }
            }
            $codi = "TODOS";
            if(Yii::$app->request->get()){
                if(!empty($_GET['tipBus'])){
                    $codi = $_GET['tipBus'];
                }
            }
            $query = Codigos::find();
            if($codi != "TODOS"){
                $query = Codigos::find()->where("TIPO='".$codi."'");
            }
            
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $utils = new Utils;
            $sql = "SELECT DISTINCT TIPO FROM brc_codigos";
            $tipo = $utils->ejecutaQuery($sql);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexCodigoGeneral', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'tipo' => $tipo,
                        'dataProvider' => $dataProvider,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarCodigos() {
        if (Yii::$app->request->isAjax) {
            $c = new Codigos;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $utils = new Utils;
            $utils->recorreCodigos($c);
            $codigo = $utils->entregaCodigo();
            return [
                'codigo' => $codigo,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarCodigoById() {
        if (Yii::$app->request->isAjax) {
            $c = new Codigos;
            $tipo = Yii::$app->request->post('_tipo');
            $cod = Yii::$app->request->post('_cod');
            $codigo = $c->find()->where("TIPO = '" . $tipo . "' AND CODIGO='" . $cod . "'")->one();
            if (is_null($codigo)) {
                $persona = "NOK: No se encontro el codigo seleccionada";
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'codigo' => $codigo,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

}
