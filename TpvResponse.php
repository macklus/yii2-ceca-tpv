<?php

namespace macklus\CECATpv;

use Yii;
use yii\base\Widget;
use yii\base\Exception;

/**
 * This is just an example.
 */
class TpvResponse extends Widget
{

    public $MerchantID;
    public $AcquirerBIN;
    public $TerminalID;
    public $Num_operacion;
    public $Importe;
    public $TipoMoneda;
    public $Exponente;
    public $Referencia;
    public $Firma;
    public $Codigo_pedido;
    public $Codigo_cliente;
    public $Codigo_comercio;
    public $Num_aut;
    public $BIN;
    public $FinalPAN;
    public $Cambio_moneda;
    public $Idioma;
    public $Pais;
    public $Tipo_tarjeta;
    public $Descripcion;
    public $Caducidad;
    public $Idusuario;
    public $raw;
    private $_Validated = false;
    private $_IsValid = false;

    public function validate()
    {
        $clave = Yii::$app->tpv->getConfig('Key') . $this->MerchantID . $this->AcquirerBIN . $this->TerminalID . $this->Num_operacion . $this->Importe .
                $this->TipoMoneda . $this->Exponente . $this->Referencia;

        $firma = sha1($clave);
        $this->_Validated = true;

        if ($firma == $this->Firma) {
            $this->_IsValid = true;
        }

        return $this->_IsValid;
    }

    public function isValid()
    {
        return $this->_IsValid;
    }

    public function getKeyValue($key)
    {
        switch ($key) {
            case 'Importe':
                $importe = (int) ($this->Importe / 100);
                return $importe;
            default:
                return (isset($this->{$key}) ? $this->{$key} : false );
        }
    }

    public function returnOkToServer()
    {
        echo '$*$OKY$*$';
        Yii::$app->end();
    }

    public function returnErrorToServer()
    {
        echo '$*$ERROR$*$';
        Yii::$app->end();
    }
}
