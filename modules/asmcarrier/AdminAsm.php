<?php
include_once('asmcarrier.php');
class AdminAsm extends AdminTab {
    public function __construct() {
        $this->className = 'AdminAsm';
        parent::__construct();
    }

    public function display() {
        global $cookie;

        $manejador = new asmcarrier();

        $option = '';
        $id_order_envio = '';

        if(isset($_GET['option'])) {
           $option = $_GET['option'];
        }

        if(isset($_GET['id_order_envio'])) {
            $id_order_envio = $_GET['id_order_envio'];
        }

        switch($option) {
            case 'etiqueta':
                echo $manejador->imprimirEtiquetas($id_order_envio);
            break;
            case 'cancelar':
                echo $manejador->cancelarEnvio($id_order_envio);
            break;
            case 'envio':
                echo $manejador->enviarEmailTrack($id_order_envio);
            break;

            default:
                echo $manejador->pedidosTabla();
            break;
        }
    }
}