<?php

namespace app\controllers;

/* CORE */

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

/* ENTITIES */
use app\models\entities\Producto;
use app\models\entities\Codigos;
use app\models\entities\CodigosWeb;
use app\models\entities\ProductoWeb;
use app\models\entities\ConveniosWeb;
use app\models\entities\HistoriasWeb;
use app\models\entities\PromocionesWeb;
use app\models\entities\DestacadosWeb;

/* FORM */
use app\models\forms\CodigosWebForm;
use app\models\forms\ProductoWebForm;
use app\models\forms\ConveniosWebForm;
use app\models\forms\HistoriasWebForm;
use app\models\forms\PromocionesWebForm;
use app\models\forms\DestacadosWebForm;

/* UTILIDADES */
use app\models\utilities\Utils;
/* CONTROLLER */
use app\controllers\BaseController;

class SitiowebController extends BaseController {

   
    public function actionIndexCodigos($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $pref = "uploads/codWeb/";
            $model = new CodigosWebForm;

            if ($model->load(Yii::$app->request->post())) {
                //var_dump($model);die();
                $cod = $model->codigo;
                $res = "";
                if (empty($cod) || is_null($cod)) {
                    $res = CodigosWeb::find()->where("TIPO='" . $model->tipo . "'")->max('CODIGO');
                    //var_dump($res);die();
                    if (is_null($res)) {
                        $cod = "000001";
                    } else {
                        $tmpH = $res + 1;
                        $cod = str_pad($tmpH, 6, "0", STR_PAD_LEFT);
                    }
                }
               
                $barra = $model->param1;
                if($model->tipo == "PRODUCTO"){
                    if (empty($barra) || is_null($barra)|| $barra == "") {
                        $utils = new Utils;
                        $barra = $utils->generaCodigoBarras();
                        while (CodigosWeb::find()->where("PARAM1='" . $barra . "'")->one()) {
                            $barra = $utils->generaCodigoBarras();
                        }
                    }
                    //var_dump($barra);die();
                }
                

                $existe = CodigosWeb::find()->where("TIPO='" . $model->tipo . "' AND CODIGO='" . $cod . "'")->all();

                if (empty($existe)) {
                    $codigo = new CodigosWeb;

                    $codigo->TIPO = $model->tipo;
                    $codigo->CODIGO = $cod;
                    $codigo->DESCRIPCION = $model->descripcion;
                    $codigo->PARAM1 = $barra;
                    $codigo->insert();
                    //VAR_DUMP($codigo->getErrors());die();
                } else {
                    if (CodigosWeb::deleteAll("TIPO='" . $model->tipo . "' AND CODIGO='" . $cod . "'")) {
                        $codigo = new CodigosWeb;
                        $codigo->TIPO = $model->tipo;
                        $codigo->CODIGO = $cod;
                        $codigo->DESCRIPCION = $model->descripcion;
                        $codigo->PARAM1 = $barra;
                        $codigo->insert();
                    }
                }
                /*
                // SE ELIMINO POR NO SER SERVIDOR LOCAS Y PUBLIAR EN LA NUBE
                //programar el ingreso al web service
                $ip = "www.google.com";
                if (Utils::GetPing($ip) == 'perdidos),') {
                    
                } else if (Utils::GetPing($ip) == '0ms') {
                    
                } else {
                    $soapClient = Yii::$app->siteApi;
                    $res = $soapClient->InsertarCodWeb(
                            $model->tipo, $cod, $model->descripcion, $base64image1
                    );
                    //var_dump($res);die();
                }
                */
            }
            $codi = "TODOS";
            if (Yii::$app->request->get()) {
                if (!empty($_GET['tipBus'])) {
                    $codi = $_GET['tipBus'];
                }
            }
            $query = CodigosWeb::find();
            if ($codi != "TODOS") {
                $query = CodigosWeb::find()->where("TIPO='" . $codi . "'");
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $utils = new Utils;
            $sql = "SELECT DESCRIPCION FROM brc_codigos WHERE TIPO = 'WEB_CA'";
            $tipo = $utils->ejecutaQuery($sql);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexCodigosWeb', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'tipo' => $tipo,
                        'dataProvider' => $dataProvider,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionIndexProductos($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;

            $model = new ProductoWebForm();
            $color = CodigosWeb::find()->where("TIPO = 'COLOR'")->all();
            $forma = CodigosWeb::find()->where("TIPO = 'FORMA'")->all();
            $material = CodigosWeb::find()->where("TIPO = 'MATERIAL'")->all();
            $marca = CodigosWeb::find()->where("TIPO = 'MARCA'")->all();
            $producto = CodigosWeb::find()->where("TIPO = 'PRODUCTO'")->all();
            $vigencia = Codigos::find()->where("TIPO = 'EST_BO'")->all();
            $tipo = CodigosWeb::find()->where("TIPO = 'TIPO'")->all();
            
            $proBus = "TODOS";
            if (Yii::$app->request->get()) {
                if (!empty($_GET['proBus'])) {
                    $proBus = $_GET['proBus'];
                }
            }
            $query = ProductoWeb::find();
            if ($proBus != "TODOS") {
                $query = ProductoWeb::find()->where("CODIGO='" . $proBus . "'");
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            
            if ($model->load(Yii::$app->request->post())) {
                /* GUARDAMOS LAS FOTOS EN UPLOADS */
                $pref = "uploads/";
                $model->foto1 = UploadedFile::getInstance($model, "foto1");
                $model->foto2 = UploadedFile::getInstance($model, "foto2");
                $imageName1 = "";
                $imageName2 = "";
                if($model->tipo == "000002"){
                    $imageName1 = "foto1-" . $model->codigo . "-". $model->modelo . "." . $model->foto1->extension;
                    $imageName2 = "foto2-" . $model->codigo . "-". $model->modelo . "." . $model->foto2->extension;
                }else{
                    $imageName1 = "foto1-" . $model->codigo . "." . $model->foto1->extension;
                    $imageName2 = "foto2-" . $model->codigo . "." . $model->foto2->extension;
                }
                
                //$model->foto1->saveAs($pref . $imageName1, true);
                //$model->foto2->saveAs($pref . $imageName2, true);
                //var_dump($model->foto1->tempName);die();
                Image::getImagine()->open($model->foto1->tempName)
                ->thumbnail(new Box(250, 250))
                ->save($pref . $imageName1, ['quality' => 90]);

                Image::getImagine()->open($model->foto2->tempName)
                ->thumbnail(new Box(250, 250))
                ->save($pref . $imageName2, ['quality' => 90]);


                $currenProd = Producto::obtenerProductosByCodigoBarraWeb($model->codigo);
                $model->descripcion = $currenProd[0]["DESCRIPCION"];
                $model->valor = $currenProd[0]["VALOR_VENTA"];

                /* GUARDAMOS LOS DATOS */

                $productoWeb = new ProductoWeb;
                $productoWeb->CODIGO = $model->codigo;
                $productoWeb->DESCRIPCION = $model->descripcion;
                $productoWeb->VIGENCIA = $model->vigencia;
                $productoWeb->VALOR = $model->valor;
                $productoWeb->COD_TIPO = $model->tipo;
                $productoWeb->COD_MARCA = $model->marca;
                $productoWeb->MODELO = $model->modelo;
                $productoWeb->COD_MATERIAL = $model->material;
                $productoWeb->COD_COLOR = $model->color;
                $productoWeb->COD_FORMA = $model->forma;

                $pathImg1 = Yii::$app->basePath . "/web/uploads/" . $imageName1;
                $gestor1 = fopen($pathImg1, "rb");
                $base64image1 = base64_encode(fread($gestor1, filesize($pathImg1)));
                fclose($gestor1);
                $productoWeb->FOTO1 = $base64image1;

                $pathImg2 = Yii::$app->basePath . "/web/uploads/" . $imageName2;
                $gestor2 = fopen($pathImg2, "rb");
                $base64image2 = base64_encode(fread($gestor2, filesize($pathImg2)));
                fclose($gestor2);
                $productoWeb->FOTO2 = $base64image2;

                 /*
                // SE ELIMINO POR NO SER SERVIDOR LOCAL Y PUBLICAR EN LA NUBE
                $ip = "www.google.com";
                if (Utils::GetPing($ip) == 'perdidos),') {
                    
                } else if (Utils::GetPing($ip) == '0ms') {
                    
                } else {
                    $client = Yii::$app->siteApi;
                    $res = $client->InsertarItem(
                            $model->codigo, $model->descripcion, $model->vigencia, $model->valor, $model->marca, $model->modelo, $model->material, $model->color, $model->forma, $base64image1, $base64image2, $model->tipo
                    );
                }
                */
                $pw = ProductoWeb::find()->where("CODIGO='" . $model->codigo . "' AND COD_TIPO = '".$model->tipo."'  AND COD_MARCA = '".$model->marca."'  AND COD_MATERIAL = '".$model->material."' AND COD_COLOR = '".$model->color."' AND COD_FORMA = '".$model->forma."' AND MODELO = '".$model->modelo."'")->one();
                //var_dump($pw->createCommand()->sql);die();
                if (is_null($pw)) {

                    if ($productoWeb->insert()) {
                        
                    } else {
                        //var_dump($productoWeb->getErrors());
                    }
                } else {
                    $pw->delete();
                    if ($productoWeb->insert()) {
                        //var_dump("paso");die();
                    } else {
                        //var_dump($productoWeb->getErrors()); 
                    }
                }

                $model = new ProductoWebForm();
               $this->datosPaginasWeb($t,"main");
                return $this->render('indexProductoWeb', [
                            
                            'rutaR' => $rutaR,
                            'model' => $model,
                            'color' => $color,
                            'forma' => $forma,
                            'material' => $material,
                            'marca' => $marca,
                            'producto' => $producto,
                            'vigencia' => $vigencia,
                            'tipo' => $tipo,
                            'enviado' => "SI",
                            'dataProvider' => $dataProvider,
                ]);
            } else {
               $this->datosPaginasWeb($t,"main");
                return $this->render('indexProductoWeb', [
                            
                            'rutaR' => $rutaR,
                            'model' => $model,
                            'color' => $color,
                            'forma' => $forma,
                            'material' => $material,
                            'marca' => $marca,
                            'producto' => $producto,
                            'vigencia' => $vigencia,
                            'tipo' => $tipo,
                            'enviado' => "NO",
                            'dataProvider' => $dataProvider,
                ]);
            }
        }
        return $this->redirect("index.php");
    }

    public function actionIndexConvenios($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $pref = "uploads/convenios/";
            $model = new ConveniosWebForm;

            if ($model->load(Yii::$app->request->post())) {
                //var_dump($cod);die();
                $model->foto = UploadedFile::getInstance($model, "foto");
                $imageName1 = str_replace(" ","-",$model->titulo) . "." . $model->foto->extension;
                //$model->foto->saveAs($pref . $imageName1, true);
				
				
				
                $pathImg1 = Yii::$app->basePath . "/web/uploads/convenios/" . $imageName1;
                $gestor1 = fopen($pathImg1, "rb");
                $base64image1 = base64_encode(fread($gestor1, filesize($pathImg1)));
                fclose($gestor1);
                

                $model->id = ($_POST["ConveniosWebForm"]["id"] == "") ?  "0" : $_POST["ConveniosWebForm"]["id"];
                //var_dump($model->id );die();
                $existe = ConveniosWeb::find()->where(['ID' => $model->id])->all();
                //var_dump($existe);die();
                if (empty($existe)) {
                    $convenio = new ConveniosWeb;

                    $convenio->TITULO = $model->titulo;
                    $convenio->DESCRIPCION = $model->descripcion;
                    $convenio->VIGENCIA = $model->vigencia;
                    $convenio->FOTO =  $base64image1;
                    $convenio->insert();
                    //VAR_DUMP($codigo->getErrors());die();
                } else {
                    if (ConveniosWeb::deleteAll("ID=" . $model->id  . "")) {
                        $convenio = new ConveniosWeb;
                        $convenio->TITULO = $model->titulo;
                        $convenio->DESCRIPCION = $model->descripcion;
                        $convenio->VIGENCIA = $model->vigencia;
                        $convenio->FOTO = $base64image1;
                        $convenio->insert();
                    }
                }
                /*
                // SE ELIMINO POR NO SER SERVIDOR LOCAS Y PUBLIAR EN LA NUBE
                //programar el ingreso al web service
                $ip = "www.google.com";
                if (Utils::GetPing($ip) == 'perdidos),') {
                    
                } else if (Utils::GetPing($ip) == '0ms') {
                    
                } else {
                    $soapClient = Yii::$app->siteApi;
                    $res = $soapClient->InsertarCodWeb(
                            $model->tipo, $cod, $model->descripcion, $base64image1
                    );
                    //var_dump($res);die();
                }
                */
            }
            $codi = "TODOS";
            if (Yii::$app->request->get()) {
                if (!empty($_GET['tipBus'])) {
                    $codi = $_GET['tipBus'];
                }
            }
            $query = ConveniosWeb::find();
            if ($codi != "TODOS") {
                $query = ConveniosWeb::find()->where("VIGENCIA='" . $codi . "'");
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $utils = new Utils;
            $sql = "SELECT CODIGO,DESCRIPCION FROM brc_codigos WHERE TIPO = 'EST_BO'";
            $vigencia = $utils->ejecutaQuery($sql);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexConveniosWeb', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'vigencia' => $vigencia,
                        'dataProvider' => $dataProvider,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionIndexHistorias($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $pref = "uploads/historias/";
            $model = new HistoriasWebForm;

            if ($model->load(Yii::$app->request->post())) {
                
                $model->foto = UploadedFile::getInstance($model, "foto");
                $imageName1 = random_int(0, 100000) . "." . $model->foto->extension;
				//var_dump($pref . $imageName1);die();
                //$model->foto->saveAs($pref . $imageName1, true);
				
				 Image::getImagine()->open($model->foto->tempName)
					->thumbnail(new Box(350, 350))
					->save($pref . $imageName1, ['quality' => 90]);
				
                $pathImg1 = Yii::$app->basePath . "/web/uploads/historias/" . $imageName1;
                $gestor1 = fopen($pathImg1, "rb");
                $base64image1 = base64_encode(fread($gestor1, filesize($pathImg1)));
                fclose($gestor1);
                
				
				$model->id = ($_POST["HistoriasWebForm"]["id"] == "") ?  "0" : $_POST["HistoriasWebForm"]["id"];
				//var_dump($model->id );die();
                $existe = HistoriasWeb::find()->where(['ID' => $model->id])->all();

                if (empty($existe)) {
                    $historias = new HistoriasWeb;

                    $historias->TITULO = $model->titulo;
					$historias->VIGENCIA = $model->vigencia;
                    $historias->FOTO = $base64image1;
                    $historias->insert();

                } else {
                    if (HistoriasWeb::deleteAll("ID=" . $model->id)) {
                        $historias = new HistoriasWeb;
                        $historias->TITULO = $model->titulo;
						$historias->VIGENCIA = $model->vigencia;
                        $historias->FOTO = $base64image1;
                        $historias->insert();
                    }
                }
                /*
                // SE ELIMINO POR NO SER SERVIDOR LOCAS Y PUBLIAR EN LA NUBE
                //programar el ingreso al web service
                $ip = "www.google.com";
                if (Utils::GetPing($ip) == 'perdidos),') {
                    
                } else if (Utils::GetPing($ip) == '0ms') {
                    
                } else {
                    $soapClient = Yii::$app->siteApi;
                    $res = $soapClient->InsertarCodWeb(
                            $model->tipo, $cod, $model->descripcion, $base64image1
                    );
                    //var_dump($res);die();
                }
                */
            }
            $codi = "TODOS";
            if (Yii::$app->request->get()) {
                if (!empty($_GET['tipBus'])) {
                    $codi = $_GET['tipBus'];
                }
            }
            $query = HistoriasWeb::find();
            if ($codi != "TODOS") {
                $query = HistoriasWeb::find()->where("VIGENCIA='" . $codi . "'");
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $utils = new Utils;
            $sql = "SELECT CODIGO,DESCRIPCION FROM brc_codigos WHERE TIPO = 'EST_BO'";
            $vigencia = $utils->ejecutaQuery($sql);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexHistoriasWeb', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'vigencia' => $vigencia,
                        'dataProvider' => $dataProvider,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionIndexPromociones($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $pref = "uploads/promociones/";
            $model = new PromocionesWebForm;

            if ($model->load(Yii::$app->request->post())) {
                
                $model->foto = UploadedFile::getInstance($model, "foto");
                $imageName1 = random_int(0, 100000) . "." . $model->foto->extension;
				//var_dump($pref . $imageName1);die();
                //$model->foto->saveAs($pref . $imageName1, true);
				
				 Image::getImagine()->open($model->foto->tempName)
					->thumbnail(new Box(350, 350))
					->save($pref . $imageName1, ['quality' => 90]);
				
                $pathImg1 = Yii::$app->basePath . "/web/uploads/promociones/" . $imageName1;
                $gestor1 = fopen($pathImg1, "rb");
                $base64image1 = base64_encode(fread($gestor1, filesize($pathImg1)));
                fclose($gestor1);
                
				
				$model->id = ($_POST["PromocionesWebForm"]["id"] == "") ?  "0" : $_POST["PromocionesWebForm"]["id"];
				//var_dump($model->id );die();
                $existe = PromocionesWeb::find()->where(['ID' => $model->id])->all();

                if (empty($existe)) {
                    $promociones = new PromocionesWeb;
					if($model->principal == "S"){
						\Yii::$app->db->createCommand("UPDATE brc_promociones_web SET PRINCIPAL=:principal")
							->bindValue(':principal', "N")
							->execute();
					}
                    $promociones->VALIDEZ = $model->validez;
					$promociones->PRINCIPAL = $model->principal;
					$promociones->VIGENCIA = $model->vigencia;
                    $promociones->FOTO = $base64image1;
                    $promociones->insert();

                } else {
                    if (PromocionesWeb::deleteAll("ID=" . $model->id)) {
						if($model->principal == "S"){
							\Yii::$app->db->createCommand("UPDATE brc_promociones_web SET PRINCIPAL=:principal")
								->bindValue(':principal', "N")
								->execute();
						}
                        $promociones = new PromocionesWeb;
                        $promociones->VALIDEZ = $model->validez;
						$promociones->PRINCIPAL = $model->principal;
						$promociones->VIGENCIA = $model->vigencia;
                        $promociones->FOTO = $base64image1;
                        $promociones->insert();
                    }
                }
                /*
                // SE ELIMINO POR NO SER SERVIDOR LOCAS Y PUBLIAR EN LA NUBE
                //programar el ingreso al web service
                $ip = "www.google.com";
                if (Utils::GetPing($ip) == 'perdidos),') {
                    
                } else if (Utils::GetPing($ip) == '0ms') {
                    
                } else {
                    $soapClient = Yii::$app->siteApi;
                    $res = $soapClient->InsertarCodWeb(
                            $model->tipo, $cod, $model->descripcion, $base64image1
                    );
                    //var_dump($res);die();
                }
                */
            }
            $codi = "TODOS";
            if (Yii::$app->request->get()) {
                if (!empty($_GET['tipBus'])) {
                    $codi = $_GET['tipBus'];
                }
            }
            $query = PromocionesWeb::find();
            if ($codi != "TODOS") {
                $query = PromocionesWeb::find()->where("VIGENCIA='" . $codi . "'");
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $utils = new Utils;
            $sql = "SELECT CODIGO,DESCRIPCION FROM brc_codigos WHERE TIPO = 'EST_BO'";
            $vigencia = $utils->ejecutaQuery($sql);
           $this->datosPaginasWeb($t,"main");
            return $this->render('indexPromocionesWeb', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'vigencia' => $vigencia,
                        'dataProvider' => $dataProvider,
            ]);
        }
        return $this->redirect("index.php");
    }

	public function actionIndexDestacados($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $pref = "uploads/destacados/";
            $model = new DestacadosWebForm;

            if ($model->load(Yii::$app->request->post())) {
                
                $model->foto = UploadedFile::getInstance($model, "foto");
                $imageName1 = random_int(0, 100000) . "." . $model->foto->extension;
				//var_dump($pref . $imageName1);die();
                //$model->foto->saveAs($pref . $imageName1, true);
				
				 Image::getImagine()->open($model->foto->tempName)
					->thumbnail(new Box(350, 350))
					->save($pref . $imageName1, ['quality' => 90]);
				
                $pathImg1 = Yii::$app->basePath . "/web/uploads/destacados/" . $imageName1;
                $gestor1 = fopen($pathImg1, "rb");
                $base64image1 = base64_encode(fread($gestor1, filesize($pathImg1)));
                fclose($gestor1);
                
				
				$model->id = ($_POST["DestacadosWebForm"]["id"] == "") ?  "0" : $_POST["DestacadosWebForm"]["id"];
				//var_dump($model->id );die();
                $existe = DestacadosWeb::find()->where(['ID' => $model->id])->all();

                if (empty($existe)) {
                    $destacados = new DestacadosWeb;

                    $destacados->TITULO = $model->titulo;
					$destacados->VIGENCIA = $model->vigencia;
                    $destacados->FOTO = $base64image1;
                    $destacados->insert();

                } else {
                    if (DestacadosWeb::deleteAll("ID=" . $model->id)) {
                        $destacados = new DestacadosWeb;
                        $destacados->TITULO = $model->titulo;
						$destacados->VIGENCIA = $model->vigencia;
                        $destacados->FOTO = $base64image1;
                        $destacados->insert();
                    }
                }
                /*
                // SE ELIMINO POR NO SER SERVIDOR LOCAS Y PUBLIAR EN LA NUBE
                //programar el ingreso al web service
                $ip = "www.google.com";
                if (Utils::GetPing($ip) == 'perdidos),') {
                    
                } else if (Utils::GetPing($ip) == '0ms') {
                    
                } else {
                    $soapClient = Yii::$app->siteApi;
                    $res = $soapClient->InsertarCodWeb(
                            $model->tipo, $cod, $model->descripcion, $base64image1
                    );
                    //var_dump($res);die();
                }
                */
            }
            $codi = "TODOS";
            if (Yii::$app->request->get()) {
                if (!empty($_GET['tipBus'])) {
                    $codi = $_GET['tipBus'];
                }
            }
            $query = DestacadosWeb::find();
            if ($codi != "TODOS") {
                $query = DestacadosWeb::find()->where("VIGENCIA='" . $codi . "'");
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            $utils = new Utils;
            $sql = "SELECT CODIGO,DESCRIPCION FROM brc_codigos WHERE TIPO = 'EST_BO'";
            $vigencia = $utils->ejecutaQuery($sql);
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexDestacadosWeb', [
                        
                        'rutaR' => $rutaR,
                        'model' => $model,
                        'vigencia' => $vigencia,
                        'dataProvider' => $dataProvider,
            ]);
        }
        return $this->redirect("index.php");
    }

}
