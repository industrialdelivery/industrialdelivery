<?php

/*
echo 'hola<br>';
echo $_GET['codbarras'];
echo '<br>';
echo $_GET['uid'];
*/
$tracking_url = 'http://www.asmred.com/Extranet/Public/ExpedicionASM.aspx?uid='.  $_GET['uid'] .'&codigo='.$_GET['codbarras'];

header("Location: ".$tracking_url);

?>