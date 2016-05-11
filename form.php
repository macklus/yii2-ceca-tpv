<form action="<?= $action ?>" method="post" enctype="application/x-www-form-urlencoded">
    <input name="AcquirerBIN" type=hidden value="<?= $AcquirerBIN ?>">
    <input name="MerchantID" type=hidden value="<?= $MerchantID ?>">
    <input name="TerminalID" type=hidden value="<?= $TerminalID ?>">
    <input name="Exponente" type=hidden value="<?= $Exp ?>">
    <input name="URL_OK" type=hidden value="<?= $URL_OK ?>">
    <input name="URL_NOK" type=hidden value="<?= $URL_NOK ?>">
    <input name="Cifrado" type=hidden value="<?= $encode ?>">
    <input name="Firma" type=hidden value="<?= $firma ?>">
    <input name="Num_operacion" type=hidden value="<?= $num_operacion ?>">
    <input name="Importe" type=hidden value="<?= $importe ?>">
    <input name="TipoMoneda" type=hidden value="<?= $tipomoneda ?>">
    <input name="Idioma" type=hidden value="<?= $idioma ?>">
    <input name="Pago_soportado" type=hidden value="SSL">
    <center>
        <input type="submit" value="comprar">
    </center>
</form>
