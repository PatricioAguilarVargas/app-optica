<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use yii\base\ErrorException;
use app\models\utilities\Utils;
use app\models\entities\Usuario;
use app\models\entities\UsuariosSearch;
use app\models\entities\Perfiles;
use app\models\entities\PerfilesSearch;
use app\models\entities\Persona;
use app\models\entities\Codigos;
use app\models\entities\Cajas;
use app\models\entities\Producto;
use app\models\entities\OperativosDetalle;
use app\models\entities\Ventas;
use app\models\entities\VentasAbono;
use app\models\entities\VentasDetalle;
use app\models\entities\Folio;
use app\models\entities\InformeVenta;
use app\models\forms\LoginForm;
use app\models\forms\VentaProductoForm;
use app\models\forms\CajasForm;
use app\models\forms\CierreForm;
use app\models\forms\InformeVentaForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
//EXCL
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Html;
/* CONTROLLER */
use app\controllers\BaseController;

/**
 * BrcUsuariosController implements the CRUD actions for BrcUsuarios model.
 */
class VentasController extends BaseController {

   
    public function actionIndexVenta($id, $t) {
       
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $date = "00000000"; //date("Ymd");
            if (Yii::$app->request->get()) {
                if (!empty($_GET['date'])) {
                    $date = $_GET['date'];
                }
            }


            $usuarioA = explode("-", Yii::$app->user->identity->id);
            $usuario = $usuarioA[0];

            $model = new VentaProductoForm;
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $caja = Cajas::find()->where("USUARIO=" . $usuario . " and DIA='". date("Ymd")."'")->one();
            //var_dump($caja);die();
            if(is_null($caja)){
               return $this->redirect(['site/index', 'msg' => "No existe apertura de caja para el usuario."]);
            }elseif($caja->ESTADO == "C"){
               return $this->redirect(['site/index', 'msg' => "La caja del día esta cerrada."]);
            }else{
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    //return $this->redirect("index.php");
                    //var_dump($_POST['VentaProductoForm']);
                    $formData = $_POST['VentaProductoForm'];
                    $cliente = $formData['cliente'];
                    $subtotal = $formData['subTotal'];
                    $descuento = $formData['descuento'];
                    $neto = $formData['neto'];
                    $iva = $formData['iva'];
                    $total = $formData['total'];
                    $fecha = date("Ymd");
                    $lstCantidad = $formData['cantidad'];
                    $lstProducto = $formData['producto'];
                    $aniFor = date("Y");
                    $folio = $aniFor."00000001";
                    //obtenermos el folio

                    if ($_SESSION["action"] == "envio") {
                        if ($folioN = Folio::find()->where("OPERACION = 'VENTAS' AND RUT_USUARIO = " . $usuario)->one()) {
                            $folio = (string)$folioN->FOLIO + 1;//sprintf("%'.012d", $folioN->FOLIO + 1);
                            //var_dump($folio);
                            
                            if ($folioN->delete()) {
                                $folioN1 = new Folio;
                                $folioN1->RUT_USUARIO = $usuario;
                                $folioN1->FOLIO = (string)$folio;
                                $folioN1->OPERACION = 'VENTAS';
                                if ($folioN1->insert()) {
                                    $res = "OK";
                                } else {
                                    $res = "NOK: No se pudo actualizar el folio. ";
                                    //var_dump($folioN1->getErrors());
                                }
                            } else {
                                $res = "NOK: No se pudo actualizar el folio. ";
                                //var_dump($folioN->getErrors());
                            }
                        } else {
                            $folioN = new Folio;
                            $folioN->RUT_USUARIO = $usuario;
                            $folioN->FOLIO = (string)$folio;
                            $folioN->OPERACION = 'VENTAS';
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

                    if (Ventas::find()->where("FOLIO='" . $folio . "'")->one()) {

                        $reg = Ventas::find()->where("FOLIO='" . $folio . "'")->one();
                        $reg->FOLIO = (string)$folio;
                        $reg->RUT_CLIENTE = $cliente;
                        $reg->DV_CLIENTE = '0';
                        $reg->SUBTOTAL = $subtotal;
                        $reg->DESCUENTO = $descuento;
                        $reg->NETO = $neto;
                        $reg->IVA = $iva;
                        $reg->TOTAL = $total;
                        $reg->FECHA_VENTA = $fecha;
                        $reg->USUARIO = $usuario;
                        if ($reg->update()) {
                            $res = "OK";
                        } else {
                            $res = "NOK: No se pudo actualizar la categoria. ";
                            //var_dump($reg->getErrors());
                        }
                    } else {
                        $reg = new Ventas;
                        $reg->FOLIO = (string)$folio;
                        $reg->RUT_CLIENTE = $cliente;
                        $reg->DV_CLIENTE = '0';
                        $reg->SUBTOTAL = $subtotal;
                        $reg->DESCUENTO = $descuento;
                        $reg->NETO = $neto;
                        $reg->IVA = $iva;
                        $reg->TOTAL = $total;
                        $reg->FECHA_VENTA = $fecha;
                        $reg->USUARIO = $usuario;
                        if ($reg->insert()) {
                            $res = "OK";
                        } else {
                            $res = "NOK: no se puedo ingresar la categoria. ";
                            //var_dump($reg->getErrors());
                        }
                    }

                    //guardamos el detalle de la venta
                    if (VentasDetalle::find()->where("FOLIO='" . $folio . "'")->all()) {
                        if (VentasDetalle::deleteAll("FOLIO=:FOLIO", [":FOLIO" => $folio])) {
                            for ($i = 0; $i < count($lstCantidad); $i++) {
                                $prodA = explode(";", $lstProducto[$i]);
                                $reg = new VentasDetalle;
                                $reg->FOLIO = (string)$folio;
                                $reg->CANTIDAD = $lstCantidad[$i];
                                $reg->PRODUCTO = $prodA[0];
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
                            $reg = new VentasDetalle;
                            $reg->FOLIO = (string)$folio;
                            $reg->CANTIDAD = $lstCantidad[$i];
                            $reg->PRODUCTO = $prodA[0];
                            if ($reg->insert()) {
                                $res = "OK";
                            } else {
                                $res = "NOK: no se puedo ingresar la categoria. ";
                                //var_dump($reg->getErrors());
                            }
                        }
                    }

                    $query = Ventas::obtenerVentasPorFolio($folio);
                    $dataProviderVentas = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pagesize' => 7,
                        ],
                    ]);

                    $query = VentasAbono::obtenerSaldosPorFolio($folio);
                    $dataProviderSaldos = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pagesize' => 7,
                        ],
                    ]);

                    $query = VentasDetalle::obtenerDetallePorFolio($folio);
                    $dataProviderDetalle = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pagesize' => 7,
                        ],
                    ]);

                    $query = Ventas::obtenerVentasPorFolio($folio);
                    $command = $query->createCommand();
                    $venta = $command->queryAll();
					
					$formaPago = Codigos::find()->where(['brc_codigos.TIPO' => "FO_PAG"])->all();
					
                    $this->datosPaginasWeb("Consulta de Saldo","main");
                    return $this->render('indexSaldo', [
                                
                                'rutaR' => $rutaR,
                                'folio' => $folio,
                                'isPjax' => false,
                                'venta' => $venta,
								'formaPago' => $formaPago,
                                'dataProviderVentas' => $dataProviderVentas,
                                'dataProviderSaldos' => $dataProviderSaldos,
                                'dataProviderDetalle' => $dataProviderDetalle,
                    ]);
                } else {
                    //$folio = Folio::find()->where("RUT_USUARIO = ".$usuario)->one();
                    $_SESSION["action"] = "envio";
                    $folio = "000000000000";
                    $clientes = Persona::find()->where("CAT_PERSONA = 'P00001'")->all();
                    $productos = Producto::find()->all();

                    $query = OperativosDetalle::obtenerDetalleOperativoPorFecha($date);
                    $data = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pagesize' => 7,
                        ],
                    ]);
                    if ($data->totalCount == 0) {
                        $query = OperativosDetalle::obtenerDetalleOperativoPorFecha("00000000", 'P00003');
                        $data = new ActiveDataProvider([
                            'query' => $query,
                            'pagination' => [
                                'pagesize' => 7,
                            ],
                        ]);
                    }
                    $dataProvider = $data;
                    //var_dump($dataProvider);
                    $this->datosPaginasWeb("venta de lentes","main");
                    return $this->render('indexVenta', [
                                'model' => $model,
                                
                                'rutaR' => $rutaR,
                                //'folio' => $folio->FOLIO,
                                'folio' => $folio,
                                'clientes' => $clientes,
                                'productos' => $productos,
                                'dataProvider' => $dataProvider
                    ]);
                }
            }
        }
        return $this->redirect("index.php");
    }

    public function actionIndexSaldo($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            $titulo = "";
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $folio = "000000000000";
            $folioF = "000000000000";
            $folioD = "000000000000";
            $rut = "";
            $date = date("Ymd");
            $isPjax = false;

            if (Yii::$app->request->get()) {
                if (!empty($_GET['date'])) {
                    $date = $_GET['date'];
                    $isPjax = true;
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['folio'])) {
                    $folio = $_GET['folio'];
                    $isPjax = true;
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['folioF'])) {
                    $folioF = $_GET['folioF'];//sprintf("%'.012d", $_GET['folioF']);
                    $isPjax = true;
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['folioD'])) {
                    $folioD = $_GET['folioD'];//sprintf("%'.012d", $_GET['folioD']);
                    $isPjax = true;
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['rut'])) {
                    $rut = $_GET['rut'];
                    $isPjax = true;
                }
            }

            $dataProviderVentas = "";
            if ($folioF != "000000000000") {
                $query = Ventas::obtenerVentasPorFolio($folioF);
                $dataProviderVentas = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pagesize' => 7,
                    ],
                ]);
            } elseif ($rut != "") {
                $query = Ventas::obtenerVentasPorRut($rut);
                $dataProviderVentas = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pagesize' => 7,
                    ],
                ]);
            } else {
                $query = Ventas::obtenerVentasPorDia($date);
                $dataProviderVentas = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pagesize' => 7,
                    ],
                ]);
            }

            $query = VentasAbono::obtenerSaldosPorFolio($folio);
            $dataProviderSaldos = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);

            $query = VentasDetalle::obtenerDetallePorFolio($folioD);
            $dataProviderDetalle = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);

            $query = Ventas::obtenerVentasPorFolio($folioD);
            $command = $query->createCommand();
            $venta = $command->queryAll();

            $formaPago = Codigos::find()->where(['brc_codigos.TIPO' => "FO_PAG"])->all();

            //var_dump($venta);
            //var_dump($dataProviderVentas);
            //var_dump($dataProviderSaldos);
            //var_dump($dataProviderDetalle);
            $this->datosPaginasWeb("consulta de saldo","main");
            return $this->render('indexSaldo', [
                        
                        'rutaR' => $rutaR,
                        'folio' => $folio,
                        'isPjax' => $isPjax,
                        'venta' => $venta,
                        'formaPago' => $formaPago,
                        'dataProviderVentas' => $dataProviderVentas,
                        'dataProviderSaldos' => $dataProviderSaldos,
                        'dataProviderDetalle' => $dataProviderDetalle,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionIndexCajas($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            $titulo = "";
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            
            $usuarioA = explode("-", Yii::$app->user->identity->id);
            $usuario = $usuarioA[0];
            
            $model = new CajasForm;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $caja = Cajas::find()->where("USUARIO=" . $usuario . " and DIA='". date("Ymd")."'")->one();
                if($caja){
                    //falta validar s hay compras y ventas 
                    $caja->DIA = date("Ymd");
                    $caja->USUARIO = $usuario;
                    $caja->ESTADO = "A";
                    $caja->MONTO = $model->monto; 
                    if ($caja->update()) {
                        $res = "OK";
                    } else {
                        $res = "NOK: no se puedo ingresar la categoria. ";
                        //var_dump($p->getErrors());
                    }
                }else{
                    $caja = new Cajas;
                    $caja->DIA = date("Ymd");
                    $caja->USUARIO = $usuario;
                    $caja->ESTADO = "A";
                    $caja->MONTO = $model->monto; 
                    if ($caja->insert()) {
                        $res = "OK";
                    } else {
                        $res = "NOK: no se puedo ingresar la categoria. ";
                        //var_dump($p->getErrors());
                    }
                }
            }else{
                $this->datosPaginasWeb("Apertura de caja","main");
                return $this->render('indexCajas', [
                            
                            'rutaR' => $rutaR,
                            'model' => $model,
                ]);
            }
        }
        return $this->redirect("index.php");
    }

    public function actionIndexInformeVenta($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $model = new InformeVentaForm;
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $tipo = "TODOS";
            $fecIni = date("Ymd");
            $fecFin = date("Ymd");
            if (Yii::$app->request->get()) {
                if (!empty($_GET['tipo'])) {
                    $tipo = $_GET['tipo'];
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['fecIni'])) {
                    $fecIni = $_GET['fecIni'];
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['fecFin'])) {
                    $fecFin = $_GET['fecFin'];
                }
            }
            if ($model->load(Yii::$app->request->post())) {
                return $this->redirect("index.php");
            } else {
                $query = "";
                if ($tipo == "TODOS") {
                    $query = InformeVenta::obtenerVentasAndAbonos($fecIni,$fecFin);
                } else if ($tipo == "V00001") {
                    $query = InformeVenta::obtenerVentas($fecIni,$fecFin);
                } else if ($tipo == "V00002") {
                    $query = InformeVenta::obtenerAbonos($fecIni,$fecFin);
                }

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pagesize' => 7,
                    ],
                ]);
                
//var_dump($query); die();

                $codigos = Codigos::find()->where("TIPO = 'ES_VEN'")->all();
                $this->datosPaginasWeb($t,"main");
                return $this->render('indexInformeVenta', [
                            'model' => $model,
                            
                            'rutaR' => $rutaR,
                            'codigos' => $codigos,
                            'dataProvider' => $dataProvider,
                ]);
            }
        }
        return $this->redirect("index.php");
    }
    
    public function actionInformeVentaXls() {
		/*
		$doc = new Spreadsheet();
		$hoja = $doc->getActiveSheet();
		$hoja->setCellValue('A1','hola mundo');
		
		$writer = new Xlsx($doc);
		$writer->save('rpt/hola.xlsx');
		echo "<meta http-equiv='refresh' content='0;url=rpt/hola.xlsx' />";
		*/
		
		$html = "<table><tr><td>no hay datos</td></tr></table>";
        if (!Yii::$app->user->isGuest) {
            $tipo = "TODOS";
            $fecIni = date("Ymd");
            $fecFin = date("Ymd");
            if (Yii::$app->request->get()) {
                if (!empty($_GET['tipo'])) {
                    $tipo = $_GET['tipo'];
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['fecIni'])) {
                    $fecIni = $_GET['fecIni'];
                }
            }
            if (Yii::$app->request->get()) {
                if (!empty($_GET['fecFin'])) {
                    $fecFin = $_GET['fecFin'];
                }
            }
			if($tipo == "null"){
				$tipo = "TODOS";
			}
			//var_dump($tipo);die();
            $query = "";
            if ($tipo == "TODOS") {
                $query = InformeVenta::obtenerVentasAndAbonos($fecIni,$fecFin);
            } else if ($tipo == "V00001") {
                $query = InformeVenta::obtenerVentas($fecIni,$fecFin);
            } else if ($tipo == "V00002") {
                $query = InformeVenta::obtenerAbonos($fecIni,$fecFin);
            }
            $command = $query->createCommand();
            $resProducto = $command->queryAll();
            $html = $this->listaInformeVentaHTML($resProducto);
			
        }
		//var_dump($query);die();
        $reader = new Html();
        $spreadsheet = $reader->loadFromString($html);
        $path = "rpt/informe-ventas.xlsx";
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save($path);
		echo "<meta http-equiv='refresh' content='0;url=".$path."' />";
	}

    public function listaInformeVentaHTML($resProducto){
        $html = '<table border="1">
                      <tr bgcolor="#FF0000">
                          <th>TIPO</th>
                          <th>FOLIO</th>
                          <th>FECHA (AAAAMMDD)</th>
                          <th>ESTADO</th>
                          <th>VALOR</th>
                      </tr>';
                      foreach ($resProducto as $row) {
                        $html = $html.'<tr>
                            <td>'. $row['TIPO'] .'</td>
                            <td>'. $row['FOLIO'] .'</td>
                            <td>'. $row['FECHA'] .'</td>
                            <td>'. $row['ESTADO'] .'</td>
                            <td>'. $row['VALOR'] .'</td>
                        </tr>';

                      }
        $html = $html.'</table>
        ';
        return $html;
    } 

    public function actionIndexCierreCaja($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            $msg = "";
            $titulo = "";
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            
            $usuarioA = explode("-", Yii::$app->user->identity->id);
            $usuario = $usuarioA[0];
            
            $model = new CierreForm;
            $tipo = Codigos::find()->where("TIPO = 'ES_CAJ'")->all();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $caja = Cajas::find()->where("USUARIO=" . $usuario . " and DIA='". date("Ymd")."'")->one();
                if($caja){
                    //falta validar s hay compras y ventas 
                    $caja->ESTADO = $model->tipo;
                    if ($caja->update()) {
                        $msg = "OK";
                    } else {
                        $msg = "NOK: no se puedo cambiar el estado. ";
                        //var_dump($p->getErrors());
                    }
                }else{
                    $msg = "No existe apertura de caja para los datos ingresados.";
                }
                $this->datosPaginasWeb("estado de caja","main");
                return $this->render('indexCierre', [
                            
                            'rutaR' => $rutaR,
                            'model' => $model,
                            'msg' => $msg,
                            'tipo' => $tipo
                ]);
            }else{
                $this->datosPaginasWeb("Estado de caja","main");
                return $this->render('indexCierre', [
                            
                            'rutaR' => $rutaR,
                            'model' => $model,
                            'msg' => $msg,
                            'tipo' => $tipo
                ]);
            }
        }
        return $this->redirect("index.php");
    }

    public function actionBuscarProductos() {
        if (Yii::$app->request->isAjax) {
            $productos = Producto::obtenerProductos();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'productos' => $productos,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarPromociones() {
        if (Yii::$app->request->isAjax) {
            $productos = Producto::obtenerPromociones();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'productos' => $productos,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarProductosByCodigoBarra() {
        $cod = Yii::$app->request->post('_cod');
        if (Yii::$app->request->isAjax) {
            $productos = Producto::obtenerProductosByCodigoBarraVenta($cod);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'productos' => $productos,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionEntregaSaldosGlobales() {
        $folio = Yii::$app->request->post('_folio');
        if (Yii::$app->request->isAjax) {
            $ventasAbono = VentasAbono::find()->where("FOLIO = '" . $folio . "'")->all();
            $abonos = 0;
            foreach ($ventasAbono as $va) {
                $abonos = $abonos + (integer) $va["VALOR"];
                //var_dump($va["VALOR"]);
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $abonos;
        }
        return $this->redirect("index.php");
    }

    public function actionIngresoAbono() {
        if (Yii::$app->request->isAjax) {
            $folio = Yii::$app->request->post('_folio');
            $abono = Yii::$app->request->post('_abono');
            $saldo = Yii::$app->request->post('_saldo');
            $formaPago = Yii::$app->request->post('_formaPago');
            $tipo = "A00001";
            $fecha = date("Ymd");
            if ($saldo == $abono) {
                $tipo = "A00002";
            }
            $reg = new VentasAbono;
            $reg->FOLIO = $folio;
            $reg->FECHA_ABONO = $fecha;
            $reg->TIPO_PAGO = $tipo;
            $reg->VALOR = $abono;
            $reg->FORMA_PAGO = $formaPago;

            if ($reg->insert()) {
                $res = "OK";
            } else {
                $res = "NOK: no se puedo ingresar el abono. ";
                //var_dump($reg->getErrors());
                $res = $reg->getErrors();
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $res;
        }
        return $this->redirect("index.php");
    }

    public function actionVoucher() {
        /*
        if (Yii::$app->request->isAjax) {
            try {
                $fecha = date("d/m/Y");
                $folio = "000000000000";
                $total = 0;
                $tAbono = 0;
                $abono = 0;
                $saldo = 0;
                $estado = "ABONO";
                if (Yii::$app->request->post()) {
                    $folio = Yii::$app->request->post('_folio');
                    $abono = (integer) Yii::$app->request->post('_abono');
                    $saldo = (integer) Yii::$app->request->post('_saldo');
                    $total = (integer) Yii::$app->request->post('_total');
                    $tAbono = (integer) Yii::$app->request->post('_tAbono');
                }
                if ($saldo == $abono) {
                    $estado = "CANCELADO";
                }
                $tAbono = $tAbono + $abono;
                $saldo = $saldo - $abono;
                //var_dump($tAbono);

                $utils = new Utils;
                $connector = new WindowsPrintConnector($utils->impresoraPOS);
                $printer = new Printer($connector);
                //ENCABEZADO
                $printer->selectPrintMode(Printer::MODE_FONT_A);
                $printer->text("\n");
                $printer->text("*******************************\n");
                $printer->selectPrintMode(Printer::MODE_FONT_A);
                $printer->text("        VOUCHER DE ABONO       \n");
                $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer->text(" ÓPTICAS BERACA\n");
                $printer->selectPrintMode(Printer::MODE_FONT_A);
                $printer->text("   DOMINGO SANTA MARÍA #3785   \n");
                $printer->text("         RENCA, SANTIAGO       \n");
                $printer->selectPrintMode(Printer::MODE_FONT_A);
                $printer->text("*******************************\n");
                //DETALLE
                $printer->selectPrintMode(Printer::MODE_FONT_B);
                $printer->text(" * FECHA    :" . $fecha . "\n");
                $printer->text(" * FOLIO    :" . $folio . "\n");
                $printer->text(" * ABONO    :" . $abono . "\n");
                $printer->text(" * P. VENTA :" . $total . "\n");
                $printer->text(" * T. ABONO :" . $tAbono . "\n");
                $printer->text(" * SALDO    :" . $saldo . "\n");
                $printer->text(" * ESTADO   :" . $estado . "\n");
                //PIE DE PAGINA
                $printer->selectPrintMode(Printer::MODE_FONT_A);
                $printer->text("*******************************\n");
                $printer->text("     GRACIAS POR SU COMPRA \n");
                $printer->text("*******************************\n\n\n\n");
                $printer->cut();
                $printer->close();

                return "OK";
            } catch (ErrorException $e) {
                return "Error al imprimir el comprobante. Comuniquese con el administrador del sistema";
            }
        }
        return $this->redirect("index.php");*/
        return "OK";
    }

}
