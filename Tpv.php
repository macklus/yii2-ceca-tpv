<?php

namespace macklus\CECATpv;

use Yii;
use yii\base\Widget;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use macklus\CECATpv\TpvResponse;

/**
 * This is just an example.
 */
class Tpv extends Widget
{

    public $mode = 'debug';
    public $debug = [];
    public $prod = [];
    public $productionAction = 'https://pgw.ceca.es/cgi-bin/tpv';
    public $developmentAction = 'http://tpv.ceca.es:8000/cgi-bin/tpv';
    public $view = false;
    public $encode = 'SHA1';
    private $_response;

    public function init()
    {
        // Config check
        if ($this->mode != 'debug' && $this->mode != 'prod') {
            throw new InvalidConfigException("Mode should be 'debug' or 'prod'");
        }

        foreach (['AcquirerBIN', 'MerchantID', 'TerminalID', 'Exp', 'URL_OK', 'URL_NOK', 'Key'] as $key) {
            if (!isset($this->{$this->mode}[$key])) {
                throw new InvalidConfigException("Config var " . $key . " should be defined!");
            }
        }

        if (!function_exists('sha1') || sha1("El coche amarillo") != '968be676ad7988e8d911fce686da3fececbb22eb') {
            throw new Exception("sha1 function seems not work");
        }

        if (!$this->view) {
            $this->view = Yii::getAlias('@vendor/macklus/yii2-ceca-tpv/form.php');
        }

        $this->_response = new TpvResponse();

        return parent::init();
    }

    public function getPostAction()
    {
        return ( ($this->mode == 'prod' ) ? $this->productionAction : $this->developmentAction);
    }

    public function getConfig($key)
    {
        return $this->{$this->mode}[$key];
    }

    public function generateForm($num_operacion, $importe, $tipomoneda = 978, $idioma = '1', $showButton = true)
    {
        $importe *= 100;

        $cadena = $this->getConfig('Key') . $this->getConfig('MerchantID') . $this->getConfig('AcquirerBIN') . $this->getConfig('TerminalID') . $num_operacion . $importe . $tipomoneda . $this->getConfig('Exp') . "SHA1" . $this->getConfig('URL_OK') . $this->getConfig('URL_NOK');
        $firma = sha1($cadena);

        $params = [
            'action' => $this->getPostAction(),
            'AcquirerBIN' => $this->getConfig('AcquirerBIN'),
            'MerchantID' => $this->getConfig('MerchantID'),
            'TerminalID' => $this->getConfig('TerminalID'),
            'Exp' => $this->getConfig('Exp'),
            'URL_OK' => $this->getConfig('URL_OK'),
            'URL_NOK' => $this->getConfig('URL_NOK'),
            'encode' => $this->encode,
            'num_operacion' => $num_operacion,
            'importe' => $importe,
            'tipomoneda' => $tipomoneda,
            'idioma' => $idioma,
            'firma' => $firma,
            'showButton' => $showButton
        ];
        return $this->renderFile($this->view, $params);
    }

    public function getTPVResponse()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $this->_response->raw = $request->bodyParams;
            foreach (['MerchantID', 'AcquirerBIN', 'TerminalID', 'Num_operacion', 'Importe', 'TipoMoneda', 'Exponente', 'Referencia', 'Firma', 'Num_aut', 'BIN', 'FinalPAN', 'Cambio_moneda', 'Idioma', 'Descripcion', 'Pais', 'Tipo_tarjeta', 'Codigo_pedido', 'Codigo_cliente', 'Codigo_comercio', 'Caducidad', 'Idusuario'] as $var) {
                $this->_response->{$var} = $request->getBodyParam($var);
            }
            $this->_response->validate();
            return $this->_response;
        }
        return false;
    }
}
