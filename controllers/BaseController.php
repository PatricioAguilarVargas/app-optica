<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;
/* UTILIDADES */
use app\models\utilities\Utils;

class BaseController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
	
	public function datosPaginasWeb($titulo,$layout){
		$this->view->params['titlePage'] = strtoupper($titulo);
        $this->view->params['menuLeft'] = Utils::getMenuLeft(explode("-", Yii::$app->user->id)[0]);
        $this->layout = $layout;
		$this->view->params['icono'] = '/img/icono-isood.png';
		$this->view->params['logo'] = '/img/opIsTransparente.png';
		$this->view->params['titulo'] = $GLOBALS["nombreSistema"]. " - ".strtoupper($titulo);
	}

}
