<?php

//require_once __DIR__ . '../composer/autoload_real.php';

namespace app\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use app\models\entities\Codigos;
use app\models\entities\Proveedor;
use app\models\entities\Producto;
use app\models\entities\ProveedorProducto;
use app\models\forms\CompraProductoForm;
use app\models\forms\LoginForm;
use app\models\entities\Folio;
use app\models\entities\Cajas;
use app\models\entities\Compras;
use app\models\entities\ComprasDetalle;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use JasperPHP\JasperPHP;
use app\models\utilities\Utils;
/* CONTROLLER */
use app\controllers\BaseController;

/**
 * BrcUsuariosController implements the CRUD actions for BrcUsuarios model.
 */
class ComprasController extends BaseController {


    public function actionIndexCompra($id, $t) 
    {
        if (!Yii::$app->user->isGuest  && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $usuarioA = explode("-", Yii::$app->user->identity->id);
            $usuario = $usuarioA[0];
            
            $model = new CompraProductoForm;
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $aniFor = date("Y");
            $folio = $aniFor."00000001";
            $caja = Cajas::find()->where("USUARIO=" . $usuario . " and DIA='". date("Ymd")."'")->one();
            //var_dump($caja);die();
            if(is_null($caja)){
               return $this->redirect(['site/index', 'msg' => "No existe apertura de caja para el usuario."]);
            }elseif($caja->ESTADO == "C"){
               return $this->redirect(['site/index', 'msg' => "La caja del día esta cerrada."]);
            }else{
                if ($model->load(Yii::$app->request->post())) {
                    $formData = $_POST['CompraProductoForm'];
                    $tipDoc = $formData['tipDoc'];
                    $numDoc = $formData['numDoc'];
                    $proveedor = $formData['proveedor'];
                    $subtotal = $formData['subTotal'];
                    $descuento = $formData['descuento'];
                    $neto = $formData['neto'];
                    $iva = $formData['iva'];
                    $total = $formData['total'];
                    $fecha = date("Ymd");
                    $lstCantidad = $formData['cantidad'];
                    $lstProducto = $formData['producto'];
                    $lstLote = $formData['lote'];

                    //obtenermos el folio
                    //var_dump($_SESSION["action"]);die();
                    if ($_SESSION["action"] == "envio") {
                        $folioN = Folio::find()->where("OPERACION = 'COMPRAS' AND RUT_USUARIO = " . $usuario)->one();
                        if(!is_null($folioN)) {
                            
                            $folio = (string)$folioN->FOLIO + 1;//sprintf("%'.012d", $folioN->FOLIO + 1);
                            
                            if ($folioN->delete()) {
                                $folioN1 = new Folio;
                                $folioN1->RUT_USUARIO = $usuario;
                                $folioN1->FOLIO = (string)$folio;
                                $folioN1->OPERACION = 'COMPRAS';
                                //var_dump($folio);die();
                                if ($folioN1->insert()) {
                                    $res = "OK";
                                } else {
                                    $res = "NOK: No se pudo actualizar el folio. ";
                                    //var_dump($folioN1->getErrors());die();
                                }
                            } else {
                                $res = "NOK: No se pudo actualizar el folio. ";
                                //var_dump($folioN->getErrors());die();
                            }
                        } else {
                            $folioN = new Folio;
                            $folioN->RUT_USUARIO = $usuario;
                            $folioN->FOLIO = (string)$folio;
                            $folioN->OPERACION = 'COMPRAS';
                            //var_dump($folio);die();
                            if ($folioN->insert()) {
                                $res = "OK";
                            } else {
                                $res = "NOK: No se pudo actualizar el folio. ";
                                //var_dump($folioN->getErrors());
                            }
                        }
                        $_SESSION["action"] = "reenvio";
                        $_SESSION["folio"] = (string)$folio;
                    } else {
                        $folio = (string)$_SESSION["folio"];
                    }

                    if (Compras::find()->where("FOLIO='" . $folio . "'")->one()) {

                        $reg = Compras::find()->where("FOLIO='" . $folio . "'")->one();
                        $reg->FOLIO = (string)$folio;
                        $reg->ID_PROVEEDOR = $proveedor;
                        $reg->ID_DOCUMENTO = $tipDoc;
                        $reg->N_DOCUMENTO = $numDoc;
                        $reg->SUBTOTAL = $subtotal;
                        $reg->DESCUENTO = $descuento;
                        $reg->TOTAL_NETO = $neto;
                        $reg->IVA = $iva;
                        $reg->TOTAL = $total;
                        $reg->FECHA_COMPRA = $fecha;
                        $reg->USUARIO = $usuario;
                        if ($reg->update()) {
                            $res = "OK";
                        } else {
                            $res = "NOK: No se pudo actualizar la categoria. ";
                            //var_dump($reg->getErrors());
                        }
                    } else {
                        $reg = new Compras;
                        $reg->FOLIO = (string)$folio;
                        $reg->ID_PROVEEDOR = $proveedor;
                        $reg->ID_DOCUMENTO = $tipDoc;
                        $reg->N_DOCUMENTO = $numDoc;
                        $reg->SUBTOTAL = $subtotal;
                        $reg->DESCUENTO = $descuento;
                        $reg->TOTAL_NETO = $neto;
                        $reg->IVA = $iva;
                        $reg->TOTAL = $total;
                        $reg->FECHA_COMPRA = $fecha;
                        $reg->USUARIO = $usuario;
                        if ($reg->insert()) {
                            $res = "OK";
                        } else {
                            $res = "NOK: no se puedo ingresar la categoria. ";
                            //var_dump($reg->getErrors());
                        }
                    }

                    //guardamos el detalle de la venta
                    if (ComprasDetalle::find()->where("FOLIO='" . $folio . "'")->all()) {
                        if (ComprasDetalle::deleteAll("FOLIO=:FOLIO", [":FOLIO" => $folio])) {
                            for ($i = 0; $i < count($lstCantidad); $i++) {
                                $prodA = explode(";", $lstProducto[$i]);
                                $reg = new ComprasDetalle;
                                $reg->FOLIO = (string)$folio;
                                $reg->CANTIDAD = $lstCantidad[$i];
                                $reg->LOTE = $lstLote[$i];
                                $reg->ID_PRODUC_PROVE = $prodA[0];
                                if ($reg->insert()) {
                                    $res = "OK";
                                } else {
                                    $res = "NOK: no se puedo ingresar la categoria. ";
                                    //var_dump($reg->getErrors());
                                }
                            }
                        }
                    } else {
                        for ($i = 0; $i < count($lstCantidad); $i++) {
                            $prodA = explode(";", $lstProducto[$i]);
                            $reg = new ComprasDetalle;
                            $reg->FOLIO = (string)$folio;
                            $reg->CANTIDAD = $lstCantidad[$i];
                            $reg->LOTE = $lstLote[$i];
                            $reg->ID_PRODUC_PROVE = $prodA[0];
                            if ($reg->insert()) {
                                $res = "OK";
                            } else {
                                $res = "NOK: no se puedo ingresar la categoria. ";
                                //var_dump($reg->getErrors());
                            }
                        }
                    }
                    
                    $codigos = new Codigos;
                    $tipDoc = $codigos->find()->where(['brc_codigos.TIPO' => 'DOC'])->all();
                    $proveedor = Proveedor::find()->all();
                    $model = new CompraProductoForm;
                    $this->datosPaginasWeb($t,"main");
                    return $this->render('indexCompra', [
                                'model' => $model,
                                
                                'rutaR' => $rutaR,
                                'tipDoc' => $tipDoc,
                                'exito' => true,
                                'folio' => $folio,
                                'proveedor' => $proveedor,
                    ]);
                    //return $this->redirect(['site/index', 'msg' => "Compra registrada con éxito."]);
                } else {
                    $_SESSION["action"] = "envio";
                    $tipDoc = Codigos::find()->where(['brc_codigos.TIPO' => 'DOC'])->all();
                    $proveedor = Proveedor::find()->all();
                    $this->datosPaginasWeb($t,"main");
                    return $this->render('indexCompra', [
                                'model' => $model,
                                
                                'rutaR' => $rutaR,
                                'tipDoc' => $tipDoc,
                                'exito' => false,
                                'folio' => $folio,
                                'proveedor' => $proveedor,
                    ]);
                }  
            }
        }
        return $this->redirect("index.php");
    }

    public function actionIndexDonaciones($id, $t) {
        if (!Yii::$app->user->isGuest  && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $model = new CompraProductoForm;
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $aniFor = date("Y");
            $folio = $aniFor."00000001";
            $usuarioA = explode("-", Yii::$app->user->identity->id);
            $usuario = $usuarioA[0];
            $caja = Cajas::find()->where("USUARIO=" . $usuario . " and DIA='". date("Ymd")."'")->one();
            //var_dump($caja);die();
            if(is_null($caja)){
               return $this->redirect(['site/index', 'msg' => "No existe apertura de caja para el usuario."]);
            }elseif($caja->ESTADO == "C"){
               return $this->redirect(['site/index', 'msg' => "La caja del día esta cerrada."]);
            }else{
                if ($model->load(Yii::$app->request->post())) {
                    //var_dump($_POST);

                    $fecha = date("Ymd");
                    $formData = $_POST['CompraProductoForm'];
                    $tipDoc = "0";
                    $numDoc = "0";
                    $proveedor = "0";
                    $subtotal = $formData['subTotal'];
                    $descuento = $formData['descuento'];
                    $neto = $formData['neto'];
                    $iva = $formData['iva'];
                    $total = $formData['total'];
                    $fecha = date("Ymd");
                    $lstCantidad = $formData['cantidad'];
                    $lstProducto = $formData['producto'];
                    $lstLote = $formData['lote'];

                    //obtenermos el folio

                    if ($_SESSION["action"] == "envio") {
                        $folioN = Folio::find()->where("OPERACION = 'COMPRAS' AND RUT_USUARIO = " . $usuario)->one();
                        if(!is_null($folioN)) {
                            $folio = (string)$folioN->FOLIO + 1;//sprintf("%'.012d", $folioN->FOLIO + 1);
                            if ($folioN->delete()) {
                                $folioN1 = new Folio;
                                $folioN1->RUT_USUARIO = $usuario;
                                $folioN1->FOLIO = (string)$folio;
                                $folioN1->OPERACION = 'COMPRAS';
                                //var_dump($folio);die();
                                if ($folioN1->insert()) {
                                    $res = "OK";
                                } else {
                                    $res = "NOK: No se pudo actualizar el folio. ";
                                    //var_dump($folioN1->getErrors());die();
                                }
                            } else {
                                $res = "NOK: No se pudo actualizar el folio. ";
                                //var_dump($folioN->getErrors());die();
                            }
                        } else {
                            $folioN = new Folio;
                            $folioN->RUT_USUARIO = $usuario;
                            $folioN->FOLIO = (string)$folio;
                            $folioN->OPERACION = 'COMPRAS';
                            if ($folioN->insert()) {
                                $res = "OK";
                            } else {
                                $res = "NOK: No se pudo actualizar el folio. ";
                                //var_dump($folioN->getErrors());
                            }
                        }
                        $_SESSION["action"] = "reenvio";
                        $_SESSION["folio"] = (string)$folio;
                    } else {
                        $folio = (string)$_SESSION["folio"];
                    }

                    if (Compras::find()->where("FOLIO='" . $folio . "'")->one()) {

                        $reg = Compras::find()->where("FOLIO='" . $folio . "'")->one();
                        $reg->FOLIO = (string)$folio;
                        $reg->ID_PROVEEDOR = $proveedor;
                        $reg->ID_DOCUMENTO = $tipDoc;
                        $reg->N_DOCUMENTO = $numDoc;
                        $reg->SUBTOTAL = $subtotal;
                        $reg->DESCUENTO = $descuento;
                        $reg->TOTAL_NETO = $neto;
                        $reg->IVA = $iva;
                        $reg->TOTAL = $total;
                        $reg->FECHA_COMPRA = $fecha;
                        $reg->USUARIO = $usuario;
                        if ($reg->update()) {
                            $res = "OK";
                        } else {
                            $res = "NOK: No se pudo actualizar la categoria. ";
                            //var_dump($reg->getErrors());
                        }
                    } else {
                        $reg = new Compras;
                        $reg->FOLIO = (string)$folio;
                        $reg->ID_PROVEEDOR = $proveedor;
                        $reg->ID_DOCUMENTO = $tipDoc;
                        $reg->N_DOCUMENTO = $numDoc;
                        $reg->SUBTOTAL = $subtotal;
                        $reg->DESCUENTO = $descuento;
                        $reg->TOTAL_NETO = $neto;
                        $reg->IVA = $iva;
                        $reg->TOTAL = $total;
                        $reg->FECHA_COMPRA = $fecha;
                        $reg->USUARIO = $usuario;
                        if ($reg->insert()) {
                            $res = "OK";
                        } else {
                            $res = "NOK: no se puedo ingresar la categoria. ";
                            //var_dump($reg->getErrors());
                        }
                    }

                    //guardamos el detalle de la venta
                    if (ComprasDetalle::find()->where("FOLIO='" . $folio . "'")->all()) {
                        if (ComprasDetalle::deleteAll("FOLIO=:FOLIO", [":FOLIO" => $folio])) {
                            for ($i = 0; $i < count($lstCantidad); $i++) {
                                $prodA = explode(";", $lstProducto[$i]);
                                $reg = new ComprasDetalle;
                                $reg->FOLIO = (string)$folio;
                                $reg->CANTIDAD = $lstCantidad[$i];
                                $reg->LOTE = $lstLote[$i];
                                $reg->ID_PRODUC_PROVE = $prodA[0];
                                if ($reg->insert()) {
                                    $res = "OK";
                                } else {
                                    $res = "NOK: no se puedo ingresar la categoria. ";
                                    //var_dump($reg->getErrors());
                                }
                            }
                        }
                    } else {
                        for ($i = 0; $i < count($lstCantidad); $i++) {
                            $prodA = explode(";", $lstProducto[$i]);
                            $reg = new ComprasDetalle;
                            $reg->FOLIO = (string)$folio;
                            $reg->CANTIDAD = $lstCantidad[$i];
                            $reg->LOTE = $lstLote[$i];
                            $reg->ID_PRODUC_PROVE = $prodA[0];
                            if ($reg->insert()) {
                                $res = "OK";
                            } else {
                                $res = "NOK: no se puedo ingresar la categoria. ";
                                //var_dump($reg->getErrors());
                            }
                        }
                    }
                    $codigos = new Codigos;
                    $tipDoc = $codigos->find()->where(['brc_codigos.TIPO' => 'DOC'])->all();
                    $proveedor = Proveedor::find()->all();
                    $model = new CompraProductoForm;
                    $this->datosPaginasWeb($t,"main");

                    return $this->render('indexDonaciones', [
                                'model' => $model,
                                
                                'rutaR' => $rutaR,
                                'tipDoc' => $tipDoc,
                                'exito' => true,
                                'folio' => $folio,
                                'proveedor' => $proveedor,
                    ]);
                    //return $this->redirect(['site/index', 'msg' => "Donación registrada con éxito."]);
                } else {
                    $_SESSION["action"] = "envio";
                    $tipDoc = Codigos::find()->where(['brc_codigos.TIPO' => 'DOC'])->all();
                    $proveedor = Proveedor::find()->all();
                    $this->datosPaginasWeb($t,"main");
                    return $this->render('indexDonaciones', [
                                'model' => $model,
                                
                                'rutaR' => $rutaR,
                                'tipDoc' => $tipDoc,
                                'exito' => false,
                                'folio' => $folio,
                                'proveedor' => $proveedor,
                    ]);
                }
            }
        }
        return $this->redirect("index.php");
    }

    public function actionReporteProductoIndex($id, $t) {
        if (!Yii::$app->user->isGuest  && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $categorias = Producto::obtenerCategoriaProductosRpt();
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexReporteProducto', [
                        
                        'rutaR' => $rutaR,
                        'categorias' => $categorias,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionReporteProProvIndex($id, $t) {
        if (!Yii::$app->user->isGuest  && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $proveedor = Proveedor::find()->all();
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexReporteProvProd', [
                        
                        'rutaR' => $rutaR,
                        'proveedor' => $proveedor
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarProductos() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $dataPost = explode(":", $data['rut']);
            $rut = $dataPost[0];
            $productos = "";
            if ($rut == 0) {
                $productosXRut = new Producto;
                $productos = $productosXRut->find()->where('LENGTH(ID_HIJO) = 11')->andWhere("VIGENCIA = 'S'")->all();
            } else {
                $productosXRut = new Producto;
                $productos = $productosXRut->obtenerProductoPorProveedor($rut);
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'productos' => $productos,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }
    
    public function actionBuscarProductosByCodigoBarra()
    {
        $cod = Yii::$app->request->post('_cod');
        $rut = Yii::$app->request->post('_rut');
        if(empty($rut) || isnull($rut)){
            $rut = 0;
        }
        if (Yii::$app->request->isAjax) {
                $productos = Producto::obtenerProductosByCodigoBarraCompra($cod,$rut);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                        'productos' => $productos,
                        'code' => 100,
                ];
        }

        return $this->redirect("index.php");
        
    }
    
    public function actionReporteProdProv($idBus) {
        
        //obtiene productos formateados;
        $resPP = ProveedorProducto::obtenerProductosXProveedorRpt($idBus);
        //var_dump($idBus);die();
        //creacion de XML
        /*
        $rutaBase = $_SERVER["DOCUMENT_ROOT"]."/";
        $ruta = $rutaBase."jReport/data.xml"; //'C:\\jReport\\data.xml';
        $xml = fopen($ruta, "w+");
        fwrite($xml, "<resourse>");
        foreach ($resPP as $row) {
            fwrite($xml, "<item>");
            fwrite($xml, "<PROVEEDOR>" . $row['PROVEEDOR'] . "</PROVEEDOR>");
            fwrite($xml, "<PRODUCTO>" . $row['PRODUCTO'] . "</PRODUCTO>");
            fwrite($xml, "<VALOR_VENTA>" . $row['VALOR_VENTA'] . "</VALOR_VENTA>");
            fwrite($xml, "<VIGENCIA>" . $row['VIGENCIA'] . "</VIGENCIA>");
            fwrite($xml, "<CODIGO>" . Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/barcode/barcode_img.php?num=" . $row['CODIGO'] . "</CODIGO>");
            //fwrite($xml,"<CODIGO>".$row['CODIGO']."</CODIGO>");
            fwrite($xml, "</item>");
        }
        fwrite($xml, "</resourse>");
        fclose($xml);


        $jasperPHP = new JasperPHP($rutaBase.'jReport/');
        $jasperPHP->compile(
            $rutaBase.'jReport/produProv.jrxml', $rutaBase.'jReport/produProv'
        )->execute();
        $jasperPHP->process(
            $rutaBase.'jReport/produProv.jasper', $rutaBase.'jReport/produProv', array("pdf"),["xml"=>"data.xml", "xpath"=>"/resourse/item"]
        )->execute();

        $pdf = $rutaBase.'jReport/produProv.pdf';
        return Yii::$app->response->sendFile($pdf, "produProv.pdf", ["inline" => true]);
        */
        $pdf = new Pdf([
			'content' => $this->listaProductosProveedorHTML($resPP),
			'format' => Pdf::FORMAT_A4, 
			
		]);
		return $pdf->render();
		//return $this->listaProductosProveedorHTML($resPP);
    }
    public function listaProductosProveedorHTML($resPP){
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
        </head>
        <body style="padding: 0 5% ;margin: 0 5%;color:dimgrey;font-family: Arial, Helvetica, sans-serif;">
        
            <table style="width:100%">
                <tr>
                    <td style="text-align: center;font-weight: bold;" colspan="12">
                        <h2 ><img style="vertical-align:text-bottom;" src="reportes/opBeTransparente.png" /></h2>
                        <hr style="border: dimgrey 2px solid;text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;font-weight: bold;" colspan="12">
                        <h2 >LISTA DE PRODUCTOS POR PROVEEDOR</h2>
                        <hr style="border: dimgrey 2px solid;text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td colspan="12">';
                        $provTmp = "";
                        foreach ($resPP as $row) {
                            if($row['PROVEEDOR'] != $provTmp && $provTmp != ""){
                                $html = $html. '</table>
                                </td></tr>
                                </table> <tr>
                                <td  colspan="12">&nbsp;</td>
                            </tr>
                            <tr>
                                <td  colspan="12">&nbsp;</td>
                            </tr>';
                            }
                            if($row['PROVEEDOR'] != $provTmp) {
                                $provTmp = $row['PROVEEDOR'];
                                $html = $html.'
                                <table class="table table-bordered" style="border: 2px solid dimgrey; text-align: center; width:100%;">
                                    <tr>
                                        <td style="background-color: dimgrey;color:white;text-align: left;width:100%">PROVEEDOR :'.$row['PROVEEDOR'].' </td>
                                    </tr>
                                    <tr><td>
                                        <table class="table table-bordered" style="text-align: center;width:100%  ">
                                            <tr>
                                                <td style="background-color: dimgrey;color:white; text-align: left;width:50%">PRODUCTO</td>
                                                <td style="background-color: dimgrey;color:white; text-align: center;;width:15%">PRECIO VENTA</td>
                                                <td style="background-color: dimgrey;color:white; text-align: center;;width:15%">VIGENCIA</td>
                                                <td style="background-color: dimgrey;color:white; text-align: center;;width:20%">COD. BARRA</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">'. $row['PRODUCTO'] .'</td>
                                                <td>'. $row['VALOR_VENTA'] .'</td>
                                                <td>'. $row['VIGENCIA'] .'</td>
                                                <td><img src="'.Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/barcode/barcode_img.php?num=" . $row['CODIGO'] .'"/></td>
                                            </tr>
                                       ';
                            } else{
                                $html = $html.'<tr>
                                                    <td style="text-align: left;">'. $row['PRODUCTO'] .'</td>
                                                    <td>'. $row['VALOR_VENTA'] .'</td>
                                                    <td>'. $row['VIGENCIA'] .'</td>
                                                    <td><img src="'.Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/barcode/barcode_img.php?num=" . $row['CODIGO'] .'"/></td>
                                                </tr>';
                            }                            
                        }
                        
                    $html = $html. '</table></td>
                                </tr>
                            </table><tr>
                            <td  colspan="12">&nbsp;</td>
                        </tr>
                        <tr>
                            <td  colspan="12">&nbsp;</td>
                        </tr>';
        $html = $html .'</td>
                </tr>
                <tr>
                    <td colspan="6"><div>FECHA:'.date("d/m/Y").' </div></td>
                        <td colspan="3"></td>
                        <td colspan="3"></td>
                </tr>
                <tr>
                    <td  colspan="12">&nbsp;</td>
                </tr>
            </table>
        </body>
        </html>';
        return $html;
    } 
    public function actionReporteProducto($idBus) {

        $producto = new Producto;
        $resProducto = $producto->obtenerProductosRpt($idBus);
        //creacion de XML
        /*
        $rutaBase = $_SERVER["DOCUMENT_ROOT"]."/";
        $ruta = $rutaBase."jReport/data.xml"; //'C:\\jReport\\data.xml';
        $xml = fopen($ruta, "w+");
        fwrite($xml, "<resourse>");
        foreach ($resProducto as $row) {
            fwrite($xml, "<item>");
            fwrite($xml, "<DESCRIPCION_PADRE>" . $row['DESCRIPCION_PADRE'] . "</DESCRIPCION_PADRE>");
            fwrite($xml, "<DESCRIPCION>" . $row['DESCRIPCION'] . "</DESCRIPCION>");
            fwrite($xml, "<STOCK_CRITICO>" . $row['STOCK_CRITICO'] . "</STOCK_CRITICO>");
            fwrite($xml, "<VIGENCIA>" . $row['VIGENCIA'] . "</VIGENCIA>");
            fwrite($xml, "<CODIGO>" . Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/barcode/barcode_img.php?num=" . $row['CODIGO'] . "</CODIGO>");
            //fwrite($xml,"<CODIGO>".$row['CODIGO']."</CODIGO>");
            fwrite($xml, "<VALOR_VENTA>" . $row['VALOR_VENTA'] . "</VALOR_VENTA>");
            fwrite($xml, "</item>");
        }
        fwrite($xml, "</resourse>");
        fclose($xml);


        $jasperPHP = new JasperPHP($rutaBase.'jReport/');
        $jasperPHP->compile(
            $rutaBase.'jReport/productos.jrxml', $rutaBase.'jReport/productos'
        )->execute();
        $jasperPHP->process(
            $rutaBase.'jReport/productos.jasper', $rutaBase.'jReport/productos', array("pdf"),["xml"=>"data.xml", "xpath"=>"/resourse/item"]
        )->execute();

        $pdf = $rutaBase.'jReport/productos.pdf';
        return Yii::$app->response->sendFile($pdf, "productos.pdf", ["inline" => true]);
        */
        //var_dump($resProducto);die();
        $pdf = new Pdf([
			'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
			'content' => $this->listaProductosHTML($resProducto),
			'format' => Pdf::FORMAT_A4, 
			
		]);
		return $pdf->render();
		//return $this->listaProductosHTML($resProducto);
    }

    public function listaProductosHTML($resProducto){
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
        </head>
        <body align="center" style="padding: 0 5% ;margin: 0 5%;color:dimgrey;font-family: Arial, Helvetica, sans-serif;">
        
            <table style="width:100%">
                <tr>
                    <td style="text-align: center;font-weight: bold;" colspan="12">
                        <h2 ><img style="vertical-align:text-bottom;" src="reportes/opBeTransparente.png" /></h2>
                        <hr style="border: dimgrey 2px solid;text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;font-weight: bold;" colspan="12">
                        <h2 >LISTA DE PRODUCTOS</h2>
                        <hr style="border: dimgrey 2px solid;text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td colspan="12">';
                        $provTmp = "";
                        foreach ($resProducto as $row) {
                            if($row['DESCRIPCION_PADRE'] != $provTmp && $provTmp != ""){
                                $html = $html. '</table>
                                </td></tr>
                                </table> <tr>
                                <td  colspan="12">&nbsp;</td>
                            </tr>
                            <tr>
                                <td  colspan="12">&nbsp;</td>
                            </tr>';
                            }
                            if($row['DESCRIPCION_PADRE'] != $provTmp) {
                                $provTmp = $row['DESCRIPCION_PADRE'];
                                $html = $html.'
                                <table class="table table-bordered" style="border: 2px solid dimgrey; text-align: center; width:100%;">
                                    <tr>
                                        <td style="background-color: dimgrey;color:white;text-align: left;width:100%">FAMILIA :'.$row['DESCRIPCION_PADRE'].' </td>
                                    </tr>
                                    <tr><td>
                                        <table class="table table-bordered" style="text-align: center;width:100%  ">
                                            <tr>
                                                <td style="background-color: dimgrey;color:white; text-align: left;width:50%">PRODUCTO</td>
                                                <td style="background-color: dimgrey;color:white; text-align: center;;width:15%">PRECIO VENTA</td>
                                                <td style="background-color: dimgrey;color:white; text-align: center;;width:15%">VIGENCIA</td>
                                                <td style="background-color: dimgrey;color:white; text-align: center;;width:20%">COD. BARRA</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">'. $row['DESCRIPCION'] .'</td>
                                                <td>'. $row['VALOR_VENTA'] .'</td>
                                                <td>'. $row['VIGENCIA'] .'</td>
                                                <td><img src="'.Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/barcode/barcode_img.php?num=" . $row['CODIGO'] .'"/></td>
                                            </tr>
                                       ';
                            } else{
                                $html = $html.'<tr>
                                                    <td style="text-align: left;">'. $row['DESCRIPCION'] .'</td>
                                                    <td>'. $row['VALOR_VENTA'] .'</td>
                                                    <td>'. $row['VIGENCIA'] .'</td>
                                                    <td><img src="'.Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/barcode/barcode_img.php?num=" . $row['CODIGO'] .'"/></td>
                                                </tr>';
                            }                            
                        }
                        
                    $html = $html. '</table></td>
                                </tr>
                            </table><tr>
                            <td  colspan="12">&nbsp;</td>
                        </tr>
                        <tr>
                            <td  colspan="12">&nbsp;</td>
                        </tr>';
        $html = $html .'</td>
                </tr>
                <tr>
                    <td colspan="6"><div>FECHA:'.date("d/m/Y").' </div></td>
                        <td colspan="3"></td>
                        <td colspan="3"></td>
                </tr>
                <tr>
                    <td  colspan="12">&nbsp;</td>
                </tr>
            </table>
        </body>
        </html>';
        return $html;
    } 

  
}
