<?php

namespace app\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\entities\Codigos;
use app\models\entities\Persona;
use app\models\entities\Operativo;
use app\models\entities\OperativosDetalle;
use app\models\forms\OperativoForm;
use app\models\forms\AgregaPacienteForm;
use app\models\forms\InformeOperativoForm;
use JasperPHP\JasperPHP;
use app\models\utilities\Utils;

/* CONTROLLER */
use app\controllers\BaseController;

/**
 * BrcUsuariosController implements the CRUD actions for BrcUsuarios model.
 */
class OperativosController extends BaseController {


    public function actionIndexOperativo($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
           
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $doctor = Persona::find()->where(['brc_persona.CAT_PERSONA' => "P00002"])->all();
            //var_dump($doctor);
            $model = new OperativoForm;
            $this->datosPaginasWeb($t,"main");
            if(Yii::$app->request->post()){
                $model->dia = Yii::$app->request->post('diaO');
                $model->hora = Yii::$app->request->post('horaO');
                $model->doctor = Yii::$app->request->post('doctorO');
                $model->obser = Yii::$app->request->post('obserO');
                $model->tipo = Yii::$app->request->post('tipoO');
            }
            //var_dump($model);die();
            return $this->render('indexOperativo', [
                        'doctor' => $doctor,
                        'model' => $model,
                        
                        'rutaR' => $rutaR,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionIndexAgregaOperativo($id, $t) {
        if (!Yii::$app->user->isGuest && Utils::validateIfUser($id)) {
            $this->datosPaginasWeb($t,"main");

            $reload = false;
            $model = new OperativoForm;
            $f = Yii::$app->request->get('f');
            if (empty($id)) {
                $id = 0;
            }
            
            if (empty($f)) {
                $f = date("Ymd");
            }
            $rutaR = "&rt=" . $id . "&t=" . $t;

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $res = "NOK";

                $dia = $model->dia;
                $arrDia = explode("/", $dia);
                $tmpDia = $arrDia[2] . $arrDia[1] . $arrDia[0];
                $hora = $model->hora;
                $tmpHora = str_replace(":", "", $hora);
                $rut = $model->doctor;
                $obser = $model->obser;
                $tipo = $model->tipo;

                $o = new Operativo;
                if ($o->find()->where("DIA='" . $tmpDia . "' AND HORA='" . $tmpHora . "' AND RUT_DOCTOR =" . $rut . " AND TIPO_OPERATIVO = ' ".$tipo."'")->one()) {
                    if ($o->deleteAll("DIA='" . $tmpDia . "' AND HORA='" . $tmpHora . "' AND RUT_DOCTOR =" . $rut . " AND TIPO_OPERATIVO = ' ".$tipo."'")) {
                        $o->DIA = $tmpDia;
                        $o->HORA = $tmpHora;
                        $o->RUT_DOCTOR = $rut;
                        $o->OBSERVACION = $obser;
                        $o->TIPO_OPERATIVO = $tipo;
                        if ($o->insert()) {
                            $res = "OK";
                        } else {
                            $res = "NOK: no se puedo ingresar el operativo. ";
                            //var_dump($o->getErrors());
                        }
                    }
                } else {
                    $o->DIA = $tmpDia;
                    $o->HORA = $tmpHora;
                    $o->RUT_DOCTOR = $rut;
                    $o->OBSERVACION = $obser;
                    $o->TIPO_OPERATIVO = $tipo;
                    if ($o->insert()) {
                        $res = "OK";
                    } else {
                        $res = "NOK: no se puedo ingresar el operativo. ";
                        //var_dump($o->getErrors());
                    }
                }
            }
            $model = new OperativoForm;
            $doctor = Persona::find()->where(['brc_persona.CAT_PERSONA' => "P00002"])->all();
            $tipos = Codigos::find()->where(['brc_codigos.TIPO' => 'OPERAT'])->all();
            $query = Operativo::find()->where(['brc_operativos.DIA' => $f]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);         
            //var_dump($dataProvider);

            return $this->render('indexAgregaOperativo', [
                        'doctor' => $doctor,
                        'dataProvider' => $dataProvider,
                        'tipoOper' => $tipos,
                        'model' => $model,
                        
                        'rutaR' => $rutaR,
                        
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionIndexAgregaPacientes($dia, $hora, $rut) {
        if (!Yii::$app->user->isGuest) {
            $msg = "";
            
            $arrDia = explode("/", $dia);
            $tmpDia = $arrDia[2] . $arrDia[1] . $arrDia[0];
            $tmpHora = str_replace(":", "", $hora);
            //var_dump(Yii::$app->request->post());die();
            //var_dump($_POST);die();
            $model = new AgregaPacienteForm;
            if ($model->load(Yii::$app->request->post())) {
                //var_dump($model->pacientes);die();
                $model->pacientes = ($model->pacientes == "") ? "0" : $model->pacientes;
                $persona = Persona::find()->where("CAT_PERSONA = 'P00001' AND RUT=" . $model->pacientes)->one();
                if (is_null($persona)) {
                    $msg = "NOK: No se encontro la persona seleccionada. Debe ser ingresada al sistema.";
                } else {
                    $deOp = OperativosDetalle::find()->where("HORA='" . $model->hora . "' AND DIA = '" . $model->dia . "' AND RUT_DOCTOR=" . $model->doctor . " AND RUT_CLIENTE=" . $model->pacientes)->one();
                    if (!is_null($deOp)) {
                        $msg  = "NOK: El paciente ya tiene una hora tomada para el dia y hora seleccionado";
                    } else {
                        $deOp = new OperativosDetalle;
                        $deOp->DIA = $model->dia;
                        $deOp->HORA = $model->hora;
                        $deOp->RUT_DOCTOR = $model->doctor;
                        $deOp->RUT_CLIENTE = $model->pacientes;
                        
                        $deOp->CERCA_OJO_D_E = "00,00";
                        $deOp->CERCA_OJO_D_C = "00,00";
                        $deOp->CERCA_OJO_D_G = "0°";
                        $deOp->CERCA_OJO_I_E = "00,00";
                        $deOp->CERCA_OJO_I_C = "00,00";
                        $deOp->CERCA_OJO_I_G = "0°";
                        
                        $deOp->LEJOS_OJO_D_E = "00,00";
                        $deOp->LEJOS_OJO_D_C = "00,00";
                        $deOp->LEJOS_OJO_D_G = "0°";
                        $deOp->LEJOS_OJO_I_E = "00,00";
                        $deOp->LEJOS_OJO_I_C = "00,00";
                        $deOp->LEJOS_OJO_I_G = "0°";
                        
                        $deOp->DPL = "000";
                        $deOp->DPC = "000";
                        $deOp->DESCRIPCION_RECETA = "SIN DESCRIPCION";
                        $deOp->DESCUENTO_RECETA = "0";
                        $deOp->ASISTENCIA = "N";
                        //var_dump($deOp);
                        if ($deOp->insert()) {
                            $tmpDia = $model->dia;
                            $tmpHora = $model->hora;
                            $rut = $model->doctor;
                        } else {
                            $msg  = "ERROR: hubo un problema al guardar el paciente, comuniquese con el administrador del sistema.";
                            //var_dump($deOp->getErrors());
                        }
                    }
                }
            }
            $doctor = Persona::find()->where("CAT_PERSONA = 'P00002' AND RUT=" . $rut)->one();
            $operativo = Operativo::find()->where("DIA='" . $tmpDia . "' AND HORA='" . $tmpHora . "' AND RUT_DOCTOR=" . $rut)->one();
            
            $pacientes = Persona::find()->where(['brc_persona.CAT_PERSONA' => "P00001"])->all();
            $query = OperativosDetalle::obtenerDetalleOperativo($rut, $tmpDia, $tmpHora);
            $data = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pagesize' => 7,
                ],
            ]);
            //var_dump($data);die();
            $t = "Asignación de Pacientes";
            $model = new AgregaPacienteForm;
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexAsignarPacientes', [
                        'doctor' => $doctor->NOMBRE,
                        'rdoc' => $doctor->RUT,
                        'obser' => $operativo->OBSERVACION,
                        'dia' => $dia,
                        'diaSF' => $tmpDia,
                        'hora' => $hora,
                        'horaSF' => $tmpHora,
                        'model' => $model,
                        'pacientes' => $pacientes,
                        'dataProvider' => $data,
                        
                        'msg' => $msg
            ]);
        }

        return $this->redirect("index.php");
    }

    public function actionAgregaPaciente() {
        if (Yii::$app->request->isAjax) {
            $p = new Persona;
            $od = new OperativosDetalle;

            $dia = Yii::$app->request->post('_dia');
            $hora = Yii::$app->request->post('_hora');
            $rDoc = Yii::$app->request->post('_rDoc');
            $rPac = Yii::$app->request->post('_rPac');

            $persona = $p->find()->where("CAT_PERSONA = 'P00001' AND RUT=" . $rPac)->one();
            if (is_null($persona)) {
                $persona = "NOK: No se encontro la persona seleccionada";
            } else {
                $deOp = $od->find()->where("HORA='" . $hora . "' AND DIA = '" . $dia . "' AND RUT_DOCTOR=" . $rDoc . " AND RUT_CLIENTE=" . $rPac)->one();
                if (!is_null($deOp)) {
                    $persona = "NOK: El paciente ya tiene una hora tomada para el dia y hora seleccionado";
                } else {
                    $od->DIA = $dia;
                    $od->HORA = $hora;
                    $od->RUT_DOCTOR = $rDoc;
                    $od->RUT_CLIENTE = $rPac;
                    $od->CERCA_OJO_D_E = "00,00";
                    $od->CERCA_OJO_D_C = "00,00";
                    $od->CERCA_OJO_D_G = "0°";
                    $od->CERCA_OJO_I_E = "00,00";
                    $od->CERCA_OJO_I_C = "00,00";
                    $od->CERCA_OJO_I_G = "0°";
                    $od->LEJOS_OJO_D_E = "00,00°";
                    $od->LEJOS_OJO_D_C = "00,00";
                    $od->LEJOS_OJO_D_G = "0°";
                    $od->LEJOS_OJO_I_E = "00,00";
                    $od->LEJOS_OJO_I_C = "00,00";
                    $od->LEJOS_OJO_I_G = "0°";
                    $od->DPL = "000";
                    $od->DPC = "000";
                    $od->DESCRIPCION_RECETA = "SIN DESCRIPCION";
                    $od->DESCUENTO_RECETA = "0";
                    $od->ASISTENCIA = "N";
                    if ($od->insert()) {
                        
                    } else {
                        $persona = "ERROR: hubo un problema al guardar el paciente, comuniquese con el administrador del sistema.";
                        var_dump($od->getErrors());
                    }
                }
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'persona' => $persona,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionQuitaPaciente() {
        if (Yii::$app->request->post()) {
            $p = new Persona;
            $od = new OperativosDetalle;

            $diaF = Yii::$app->request->post('dia');
            $diaF = substr($diaF,-2)."/".substr($diaF,4,2)."/".substr($diaF,0,4);
            $horaF = Yii::$app->request->post('hora');
            $horaF = substr($horaF,0,2).":".substr($horaF,-2);
            $rDoc = Yii::$app->request->post('doctor');
            $rPac = explode("-",Yii::$app->request->post('pac'))[0];
            
            
            $arrDia = explode("/", $diaF);
            $dia = $arrDia[2] . $arrDia[1] . $arrDia[0];
            $hora = str_replace(":", "", $horaF);
            
            $persona = $p->find()->where("CAT_PERSONA = 'P00001' AND RUT=" . $rPac)->one();
            if (is_null($persona)) {
                $persona = "NOK: No se encontro la persona seleccionada";
            } else {
                $deOp = $od->find()->where("HORA='" . $hora . "' AND DIA = '" . $dia . "' AND RUT_DOCTOR=" . $rDoc . " AND RUT_CLIENTE=" . $rPac)->one();
                if (is_null($deOp)) {
                    $persona = "NOK: El paciente no tiene una hora tomada para el dia y hora seleccionado";
                } else {

                    if ($od->deleteAll("HORA='" . $hora . "' AND DIA = '" . $dia . "' AND RUT_DOCTOR=" . $rDoc . " AND RUT_CLIENTE=" . $rPac)) {
                        
                    } else {
                        $persona = "ERROR: hubo un problema al guardar el paciente, comuniquese con el administrador del sistema.";
                        var_dump($od->getErrors());
                    }
                }
            }

            return $this->redirect(["operativos/index-agrega-pacientes","dia"=>$diaF,"hora"=>$horaF,"rut"=>$rDoc]);
        }

        return $this->redirect(["index.php"]);
    }

    public function actionBuscarDatosOperativo() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $dataPost1 = explode(":", $data['fecha']);
            $fechaPost = $dataPost1[0];

            $operativo = Operativo::find()
                    ->where(['brc_operativos.DIA' => $fechaPost])
                    ->all();
            $paciente = Persona::find()
                    ->where(['brc_persona.CAT_PERSONA' => "P00001"])
                    ->all();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'operativo' => $operativo,
                'paciente' => $paciente,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionBuscarDetalleOperativo() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $dataPost1 = explode(":", $data['fecha']);
            $dataPost2 = explode(":", $data['rut_doc']);
            $dataPost3 = explode(":", $data['hora']);
            //var_dump($data);
            $fechaPost = $dataPost1[0];
            $doctorPost = $dataPost2[0];
            $horaPost = $dataPost3[0];

            $detalleOperativo = OperativosDetalle::obtenerDetalleOperativo($doctorPost, $fechaPost, $horaPost, true);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'detalleOperativo' => $detalleOperativo,
                'code' => 100,
            ];
        }

        return $this->redirect("index.php");
    }

    public function actionGuardaReceta() {
        if (Yii::$app->request->isAjax) {
            //var_dump($_POST);
            $res = "";
            $recRutA = explode("-", Yii::$app->request->post('recRut'));
            $recRut = $recRutA[0];
            $recRutDoc = Yii::$app->request->post('recRutDoc');
            $recHora = Yii::$app->request->post('recHora');
            $recFecha = Yii::$app->request->post('recFecha');
            $recOjoDerLejEsf = Yii::$app->request->post('recOjoDerLejEsf');
            $recOjoDerLejCil = Yii::$app->request->post('recOjoDerLejCil');
            $recOjoDerLejGra = Yii::$app->request->post('recOjoDerLejGra');
            $recOjoIzqLejEsf = Yii::$app->request->post('recOjoIzqLejEsf');
            $recOjoIzqLejCil = Yii::$app->request->post('recOjoIzqLejCil');
            $recOjoIzqLejGra = Yii::$app->request->post('recOjoIzqLejGra');
            $recDPL = Yii::$app->request->post('recDPL');
            $recOjoDerCerEsf = Yii::$app->request->post('recOjoDerCerEsf');
            $recOjoDerCerCil = Yii::$app->request->post('recOjoDerCerCil');
            $recOjoDerCerGra = Yii::$app->request->post('recOjoDerCerGra');
            $recOjoIzqCerEsf = Yii::$app->request->post('recOjoIzqCerEsf');
            $recOjoIzqCerCil = Yii::$app->request->post('recOjoIzqCerCil');
            $recOjoIzqCerGra = Yii::$app->request->post('recOjoIzqCerGra');
            $recDPC = Yii::$app->request->post('recDPC');
            $recObs = Yii::$app->request->post('recObs');
            $recDes = Yii::$app->request->post('recDes');

            $deOp = OperativosDetalle::find()->where("HORA='" . $recHora . "' AND DIA = '" . $recFecha . "' AND RUT_DOCTOR=" . $recRutDoc . " AND RUT_CLIENTE=" . $recRut)->one();
            if (!is_null($deOp)) {
                $sql = "DELETE FROM brc_operativos_detalle WHERE HORA='" . $recHora . "' AND DIA = '" . $recFecha . "' AND RUT_DOCTOR=" . $recRutDoc . " AND RUT_CLIENTE=" . $recRut;
                 \Yii::$app->db->createCommand($sql)->execute();
                $sql = "insert into brc_operativos_detalle values (";
                $sql = $sql."'".  $recFecha . "',";
                $sql = $sql."'".  $recHora . "',";
                $sql = $sql."'',";
                $sql = $sql."".  $recRutDoc . ",";
                $sql = $sql."".  $recRut . ",";
                $sql = $sql."'".  $recOjoDerCerEsf . "',";
                $sql = $sql."'".  $recOjoDerCerCil . "',";
                $sql = $sql."'".  $recOjoDerCerGra . "',";
                $sql = $sql."'".  $recOjoIzqCerEsf . "',";
                $sql = $sql."'".  $recOjoIzqCerCil . "',";
                $sql = $sql."'".  $recOjoIzqCerGra . "',";
                $sql = $sql."'".  $recDPC . "',";
                $sql = $sql."'".  $recOjoDerLejEsf . "',";
                $sql = $sql."'".  $recOjoDerLejCil . "',";
                $sql = $sql."'".  $recOjoDerLejGra . "',";
                $sql = $sql."'".  $recOjoIzqLejEsf . "',";
                $sql = $sql."'".  $recOjoIzqLejCil . "',";
                $sql = $sql."'".  $recOjoIzqLejGra. "',";
                $sql = $sql."'".  $recDPL . "',";
                $sql = $sql."'".  $recObs . "',";
                $sql = $sql."".  $recDes . ",";
                $sql = $sql."'S'";
                $sql = $sql. ")";
               
                if (\Yii::$app->db->createCommand($sql)->execute()) {
                    $res = "OK";
                } else {
                    $res = "ERROR: hubo un problema al actualizar el paciente, comuniquese con el administrador del sistema.";
                    //var_dump($deOp->getErrors());
                }
            } else {
                $res = "NOK: El paciente no existe en la toma de hora";
            }
            return $res;
        }
        return $this->redirect("index.php");
    }

    public function actionActualizaEstado() {
        if (Yii::$app->request->isAjax) {
            //var_dump($_POST);
            $res = "";
            $recRutA = explode("-", Yii::$app->request->post('recRut'));
            $recRut = $recRutA[0];
            $recRutDoc = Yii::$app->request->post('rutDoc');
            $recHora = Yii::$app->request->post('hora');
            $recFecha = Yii::$app->request->post('fecha');
            $estado = Yii::$app->request->post('estado');


            $deOp = OperativosDetalle::find()->where("HORA='" . $recHora . "' AND DIA = '" . $recFecha . "' AND RUT_DOCTOR=" . $recRutDoc . " AND RUT_CLIENTE=" . $recRut)->one();
            if (!is_null($deOp)) {
                $deOp->ASISTENCIA = $estado;
                if ($deOp->update()) {
                    $res = "OK";
                } else {
                    $res = "ERROR: hubo un problema al actualizar el paciente, comuniquese con el administrador del sistema.";
                    //$res = $deOp->getErrors();
                }
            } else {
                $res = "NOK: El paciente no existe en la toma de hora";
            }
            return $res;
        }
        return $this->redirect("index.php");
    }
    
    public function actionIndexInformeOperativo($id, $t) {
        if (!Yii::$app->user->isGuest  && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $doctores = Persona::find()->where("CAT_PERSONA = 'P00002'")->all();
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexReporteOperativo', [
                        
                        'rutaR' => $rutaR,
                        'doctores' => $doctores,
            ]);
        }
        return $this->redirect("index.php");
    }

    public function actionReporteOperativo($doc,$dia,$hora) {

        $resProducto = Operativo::obtenerDetalleOperativoRpt($doc,$dia,$hora);
        //creacion de XML
        $doctor = Persona::find()->where("CAT_PERSONA = 'P00002' AND RUT =".$doc)->one();
        /*$rutaBase = $_SERVER["DOCUMENT_ROOT"]."/";
        $ruta = $rutaBase."jReport/data.xml"; //'C:\\jReport\\data.xml';
        $xml = fopen($ruta, "w+");
        fwrite($xml, "<resourse>");
        foreach ($resProducto as $row) {
            fwrite($xml, "<item>");
            fwrite($xml, "<NOMBRE>" . $row['NOMBRE'] . "</NOMBRE>");
            fwrite($xml, "<TELEFONO>" . $row['TELEFONO'] . "</TELEFONO>");
            fwrite($xml, "<HORA>" . $row['HORA'] . "</HORA>");
            fwrite($xml, "</item>");
        }
        fwrite($xml, "</resourse>");
        fclose($xml);

        
        $jasperPHP = new JasperPHP($rutaBase.'jReport/');
        $jasperPHP->compile(
            $rutaBase.'jReport/InfOperativo.jrxml', $rutaBase.'jReport/InfOperativo'
        )->execute();
        $parametros = ["DOCTOR"=>$doctor["NOMBRE"], "FECHA"=>$dia, "HORA" => $hora];
        $jasperPHP->process(
            $rutaBase.'jReport/InfOperativo.jasper', $rutaBase.'jReport/InfOperativo', array("pdf"),["xml"=>"data.xml", "xpath"=>"/resourse/item"],$parametros
        )->execute();
        $pdf = $rutaBase.'jReport/InfOperativo.pdf';
        return Yii::$app->response->sendFile($pdf, "InfOperativo.pdf", ["inline" => true]);
        */
       
        $pdf = new Pdf([
			'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
			'content' => $this->OperativoHTML($resProducto,$doctor,$dia,$hora),
			'format' => Pdf::FORMAT_A4, 
			
		]);
		return $pdf->render();
		//return $this->OperativoHTML($resProducto,$doctor,$dia,$hora);

    }

    public function actionIndexReceta($id, $t) {
        if (!Yii::$app->user->isGuest  && Utils::validateIfUser($id)) {
            if (empty($id)) {
                $id = 0;
            }
            
            $rutaR = "&rt=" . $id . "&t=" . $t;
            $pacientes = Persona::find()->where("CAT_PERSONA = 'P00001'")->all();
            $this->datosPaginasWeb($t,"main");
            return $this->render('indexReceta', [
                        
                        'rutaR' => $rutaR,
                        'pacientes' => $pacientes,
            ]);
        }
        return $this->redirect("index.php");
    }
    
     public function actionReporteReceta($rut) {

        $row = OperativosDetalle::obtenerDetalleOperativoPorPaciente($rut);
        //creacion de XML
        /*
        $rutaBase = $_SERVER["DOCUMENT_ROOT"]."/";
        $ruta = $rutaBase."jReport/data.xml"; //'C:\\jReport\\data.xml';
        $xml = fopen($ruta, "w+");
        fwrite($xml, "<resourse>");
        fwrite($xml, "<item>");  
        fwrite($xml, "<NOMBRE>" . $row[0]['NOMBRE'] . "</NOMBRE>");
        fwrite($xml, "<TELEFONO>" . $row[0]['TELEFONO'] . "</TELEFONO>");
        fwrite($xml, "<RUT>" . $row[0]['RUT_CLIENTE']. "</RUT>");
        fwrite($xml, "<CERCA_OJO_D_E>" . $row[0]['CERCA_OJO_D_E'] . "</CERCA_OJO_D_E>");
        fwrite($xml, "<CERCA_OJO_D_C>" . $row[0]['CERCA_OJO_D_C'] . "</CERCA_OJO_D_C>");
        fwrite($xml, "<CERCA_OJO_D_G>" . $row[0]['CERCA_OJO_D_G'] . "</CERCA_OJO_D_G>");
        fwrite($xml, "<CERCA_OJO_I_E>" . $row[0]['CERCA_OJO_I_E'] . "</CERCA_OJO_I_E>");
        fwrite($xml, "<CERCA_OJO_I_C>" . $row[0]['CERCA_OJO_I_C'] . "</CERCA_OJO_I_C>");
        fwrite($xml, "<CERCA_OJO_I_G>" . $row[0]['CERCA_OJO_I_G'] . "</CERCA_OJO_I_G>");
        fwrite($xml, "<DPC>" . $row[0]['DPC'] . "</DPC>");
        fwrite($xml, "<LEJOS_OJO_D_E>" . $row[0]['LEJOS_OJO_D_E'] . "</LEJOS_OJO_D_E>");
        fwrite($xml, "<LEJOS_OJO_D_C>" . $row[0]['LEJOS_OJO_D_C'] . "</LEJOS_OJO_D_C>");
        fwrite($xml, "<LEJOS_OJO_D_G>" . $row[0]['LEJOS_OJO_D_G'] . "</LEJOS_OJO_D_G>");
        fwrite($xml, "<LEJOS_OJO_I_E>" . $row[0]['LEJOS_OJO_I_E'] . "</LEJOS_OJO_I_E>");
        fwrite($xml, "<LEJOS_OJO_I_C>" . $row[0]['LEJOS_OJO_I_C'] . "</LEJOS_OJO_I_C>");
        fwrite($xml, "<LEJOS_OJO_I_G>" . $row[0]['LEJOS_OJO_I_G'] . "</LEJOS_OJO_I_G>");
        fwrite($xml, "<DPL>" . $row[0]['DPL'] . "</DPL>");
        fwrite($xml, "<DESCRIPCION_RECETA>" . $row[0]['DESCRIPCION_RECETA'] . "</DESCRIPCION_RECETA>");
        fwrite($xml, "</item>");
        fwrite($xml, "</resourse>");
        fclose($xml);


        $jasperPHP = new JasperPHP($rutaBase.'jReport/');
        $jasperPHP->compile(
            $rutaBase.'jReport/receta.jrxml', $rutaBase.'jReport/receta'
        )->execute();
         $jasperPHP->process(
            $rutaBase.'jReport/receta.jasper', $rutaBase.'jReport/receta', array("pdf"),["xml"=>"data.xml", "xpath"=>"/resourse/item"]
        )->execute();

        $pdf = $rutaBase.'jReport/receta.pdf';
        return Yii::$app->response->sendFile($pdf, "receta.pdf", ["inline" => true]);
        */
         $pdf = new Pdf([
			'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
			'content' => $this->RecetaHTML($row),
			'format' => Pdf::FORMAT_A4, 
			
		]);
		return $pdf->render();
		//return $this->RecetaHTML();
    }
	
	private function RecetaHTML($row){
		
		return '<!DOCTYPE html>
				<html lang="en">
				<head>
        		</head>
				<body style="padding: 0 5% ;margin: 0 5%;color:dimgrey;font-family: Arial, Helvetica, sans-serif;">
					<table style="width:100%">
						<tr>
							<td style="text-align: center;font-weight: bold;" colspan="12">
								<h2 ><img style="vertical-align:text-bottom;" src="reportes/icono-beraca.png" /> RECETA DE LENTES</h2>
								<hr style="border: dimgrey 2px solid;text-align: center;">
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<!-- DATOS PERSONALES -->
						<tr>
							<td colspan="12"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #777;"><label>
								NOMBRE : 
							</label></span>&nbsp;'.$row[0]['NOMBRE'].'</td>

						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #777;"><label>
                                RUT :  </label></span>&nbsp;'. $row[0]['RUT_CLIENTE'].'</td>

                            <td colspan="4"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #777;"><label>
                                TELÉFONO :  </label></span>&nbsp;'. $row[0]['TELEFONO'] .'</td>

                            <td colspan="4"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #777;"><label>
                                EDAD : </label></span>&nbsp;</td>

						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<!-- LEJOS -->
						<tr>
							<td colspan="12">
								<h2 style="text-align: left;font-weight: bold;color: dimgrey;font-size: 1.5em;">LEJOS</h2>
								<hr style="border: dimgrey 2px solid;text-align: center;">
							</td>
						</tr>
						<tr>
							<td colspan="11">
								<table  style="border: 2px solid dimgrey; text-align: center;width:100%">
									<tr>
										<td style="background-color: dimgrey;color:white;">&nbsp;</td>
										<td style="background-color: dimgrey;color:white;">ESFERICO</td>
										<td style="background-color: dimgrey;color:white;">CILINDRO</td>
										<td style="background-color: dimgrey;color:white;">EJE</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.I.</td>
										<td>'.$row[0]['LEJOS_OJO_I_E'].'</td>
										<td>'.$row[0]['LEJOS_OJO_I_C'].'</td>
										<td>'.$row[0]['LEJOS_OJO_I_G'].'</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.D.</td>
										<td>'.$row[0]['LEJOS_OJO_D_E'].'</td>
										<td>'.$row[0]['LEJOS_OJO_D_C'].'</td>
										<td>'.$row[0]['LEJOS_OJO_D_G'].'</td>
									</tr>
								</table>
							</td>
							<td colspan="1" style="vertical-align:text-bottom;">
								<table class="table table-bordered"  style="border: 2px solid dimgrey; text-align: center ;width:100%; ">
									<tr>
										<td style="background-color: dimgrey;color:white;">D.P.</td>
									</tr>
									<tr>
										<td>'.$row[0]['DPL'].'</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<!-- CERCA -->
						<tr>
							<td colspan="12">
								<h2 style="text-align: left;font-weight: bold;color: dimgrey;font-size: 1.5em;">CERCA</h2>
								<hr style="border: dimgrey 2px solid;text-align: center;">
							</td>
						</tr>
						<tr>
							<td colspan="11">
								<table  style="border: 2px solid dimgrey; text-align: center;width:100%">
									<tr>
										<td style="background-color: dimgrey;color:white;">&nbsp;</td>
										<td style="background-color: dimgrey;color:white;">ESFERICO</td>
										<td style="background-color: dimgrey;color:white;">CILINDRO</td>
										<td style="background-color: dimgrey;color:white;">EJE</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.I.</td>
										<td>'.$row[0]['CERCA_OJO_I_E'].'</td>
										<td>'.$row[0]['CERCA_OJO_I_C'].'</td>
										<td>'.$row[0]['CERCA_OJO_I_G'].'</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.D.</td>
										<td>'.$row[0]['CERCA_OJO_D_E'].'</td>
										<td>'.$row[0]['CERCA_OJO_D_C'].'</td>
										<td>'.$row[0]['CERCA_OJO_D_G'].'</td>
									</tr>
								</table>
							</td>
							<td colspan="1" style="vertical-align:text-bottom;">
								<table class="table table-bordered"  style="border: 2px solid dimgrey; text-align: center ;width:100%; ">
									<tr>
										<td style="background-color: dimgrey;color:white;">D.P.</td>
									</tr>
									<tr>
										<td>'.$row[0]['DPC'].'</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
						<!-- OBSERVACION -->
						<tr>
							<td colspan="12">
								<h2 style="text-align: left;font-weight: bold;color: dimgrey;font-size: 1.5em;">OBSERVACIÓN</h2>
								<hr style="border: dimgrey 2px solid;text-align: center; ">
							</td>
						</tr>
						<tr>
							<td colspan="12"  style="border: 2px solid dimgrey; text-align: center; width:100%; height: 200px">
                            ' . $row[0]['DESCRIPCION_RECETA'] . '
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						 <!-- FIRMA  -->
						 <tr>
							<td colspan="6"><div>FECHA: '.date("d/m/Y").'</div></td>
							<td colspan="3"></td>
							<td colspan="3" style="border-top:2px solid dimgrey; text-align: center;font-weight: bold;"><div >FIRMA</div></td>
						</tr>
					   
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>  
					</table>
				</body>
				</html>';
    }
    
    private function OperativoHTML($resProducto,$doctor,$dia,$hora){
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
                        <h2 >PACIENTES DEL OPERATIVO</h2>
                        <hr style="border: dimgrey 2px solid;text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td  colspan="12">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="6">
                        <span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #777;">
                            <label>DOCTOR :  </label>
                        </span>&nbsp;'.$doctor["NOMBRE"].'
                    </td>
                    <td colspan="3">
                        <span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #777;">
                            <label>FECHA :   </label>
                        </span>&nbsp;'.$dia.'
                    </td>
                    <td colspan="3">
                        <span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #777;">
                            <label>HORA :</label>
                        </span>&nbsp; '.$hora.'
                    </td>
                </tr>
                <tr>
                    <td  colspan="12">&nbsp;</td>
                </tr>
                <tr>
                    <td  colspan="12">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="12">
                        <table  style="border: 2px solid dimgrey; text-align: center;width:100%">
                            <tr>
                                <td style="background-color: dimgrey;color:white;width:70%">NOMBRE</td>
                                <td style="background-color: dimgrey;color:white;width:20%">TELÉFONO</td>
                                <td style="background-color: dimgrey;color:white;width:10%">FIRMA</td>
                            </tr>';
                                foreach ($resProducto as $row) {
                                $html = $html.'<tr >';
                                    $html = $html.'<td style="border-bottom:1px solid dimgrey;text-align: left">'. $row['NOMBRE'] .'</td>';
                                    $html = $html.'<td style="border-bottom:1px solid dimgrey">'. $row['TELEFONO'] .'</td>';
                                    $html = $html.'<td style="border-bottom:1px solid dimgrey">&nbsp;</td>'; 
                                $html = $html.'</tr>';                                  
                                }
         $html = $html.'</table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><div>FECHA: '.date("d/m/Y").'</div></td>
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
