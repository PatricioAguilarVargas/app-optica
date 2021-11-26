<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\UploadForm;
use app\models\utilities\Utils;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use app\models\entities\Ventas;
use app\models\entities\VentasAbono;
use app\models\entities\VentasDetalle;
use app\models\entities\Compras;
use app\models\entities\ComprasDetalle;
use app\models\entities\Operativo;
use app\models\entities\OperativosDetalle;
use app\models\entities\Persona;
use app\models\entities\Proveedor;
use yii\helpers\Json;
/* CONTROLLER */
use app\controllers\BaseController;

class SiteController extends BaseController {

   


    public function actionIndex() {
        $id;
        if (empty($id)) {
            $id = 0;
        }
		$t = "panel principal";
        $msg = "";
        if (Yii::$app->request->get()) {
            if (!empty($_GET['msg'])) {
                $msg = $_GET['msg'];
            }
        }
        //var_dump($msg);die();
        $model = new LoginForm();
        //si no es invitado
        if (!Yii::$app->user->isGuest) {
            $data['ventas'] = (new Ventas)->pagIniVentas();
            $data['compras'] = (new Compras)->pagIniCompras();
            $data['operativos'] = (new OperativosDetalle)->pagIniOperativos();
            $data['abonos'] = (new VentasAbono)->pagIniAbonos();
            $data['donaciones'] = (new Compras)->pagIniDonaciones();
            $perfiles = $model->getPerfil(Yii::$app->user->identity->username, $id);
            $this->datosPaginasWeb($t,"main");
            //var_dump($data);die();
            return $this->render('index', ["data"=>$data,"msg" => $msg]);
        }

        //si envio post del form de usuario
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
             $data['ventas'] = (new Ventas)->pagIniVentas();
            $data['compras'] = (new Compras)->pagIniCompras();
            $data['operativos'] = (new OperativosDetalle)->pagIniOperativos();
            $data['abonos'] = (new VentasAbono)->pagIniAbonos();
            $data['donaciones'] = (new Compras)->pagIniDonaciones();
            $perfiles = $model->getPerfil($model->username, $id);
            $this->datosPaginasWeb($t,"main");
            //var_dump($data);die();
            return $this->render('index', ["data"=>$data,"msg" => $msg]);
        }

        $t = strtoupper("INICIO DE SESIÓN");
		$this->datosPaginasWeb($t,"main-login");
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->actionIndex();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionEditarImagen() {
        if (empty($id)) {
            $id = 0;
        }
      

        $msg = "";
        $model = new LoginForm();
		$t = "EDICIÓN DE IMÁGENES";
        //si no es invitado
        if (!Yii::$app->user->isGuest) {
            $perfiles = $model->getPerfil(Yii::$app->user->identity->username, $id);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexEdicion', ['descarga' => false,]);
        }

        //si envio post del form de usuario
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            $perfiles = $model->getPerfil($model->username, $id);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexEdicion', ['descarga' => false,]);
        }
        //Al login
        $t = strtoupper("INICIO DE SESIÓN");
		$this->datosPaginasWeb($t,"main-login");
        return $this->render('login', [
                    'model' => $model,        
        ]);
    }

    public function actionFileUpload() {
        if (!Yii::$app->user->isGuest) {
            $tmp = $_FILES['file']['tmp_name'];
            //var_dump($_FILES['file']);die();
            $dir = "uploads/";
            $fileName = 'img.jpg';
            //unlink($dir . $fileName);
            Image::getImagine()->open($tmp)
                ->thumbnail(new Box(250, 150))
                ->save($dir . $fileName, ['quality' => 90]);
            return true;
        }
        return false;
        
    }

    public function actionFileDownload() {
        if (!Yii::$app->user->isGuest) {
            $dir = "uploads/";
            $fileName = 'img.jpg';
            $model = new LoginForm();

                if (!UploadForm::downloadFile($dir, $fileName, ["jpg"])) {
                    //unlink($dir . $fileName);
                    
                    $this->datosPaginasWeb("Edicion de imagenes","main");
                   // return $this->render('indexEdicion', ["titulo" => $titulo,'descarga' => true,]);
                }
        }
    }

    public function actionBuscarCliente($term) {
        
        if (Yii::$app->request->isAjax) {
			$results = [];
            $persona =  (new Persona)->find()
                            ->where("CAT_PERSONA = 'P00001' AND (RUT like '%" .$term."%' OR NOMBRE LIKE '%" .$term."%')")
                              ->all();
            
             foreach($persona as $model) {
                $results[] = [
                    'id' => $model['RUT'],
                    'label' => $model['NOMBRE'] . ' (' . $model['RUT'] . '-'.$model['DV'] .')',
                ];
            }
            //var_dump($persona);die();
            //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
             echo Json::encode($results);
        }
    }

    public function actionBuscarDoctor($term) {
        if (Yii::$app->request->isAjax) {
			$results = [];
            $persona =  (new Persona)->find()
                            ->where("CAT_PERSONA = 'P00002' ")
                              ->all();
            
             foreach($persona as $model) {
                $results[] = [
                    'id' => $model['RUT'],
                    'label' => $model['NOMBRE'] . ' (' . $model['RUT'] . '-'.$model['DV'] .')',
                ];
            }
            //var_dump($persona);die();
            //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
             echo Json::encode($results);
        }
    }

    public function actionBuscarProveedor($term) {
        if (Yii::$app->request->isAjax) {
			$results = [];
            $persona =  (new Proveedor)->find()
                            ->where(" (ID_PROVEEDOR like '%" .$term."%' OR CONTACTO LIKE '%" .$term."%' OR NOMBRE_EMPRESA LIKE '%" .$term."%')")
                              ->all();
            
             foreach($persona as $model) {
                $results[] = [
                    'id' => $model['ID_PROVEEDOR'],
                    'label' => $model['NOMBRE_EMPRESA'] . ' (' . $model['ID_PROVEEDOR'] . ')',
                ];
            }
            //var_dump($persona);die();
            //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
             echo Json::encode($results);
        }
    }
}
