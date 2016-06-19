<?php
session_start();;
// Avoid direct access to the file
if (!defined('_PS_VERSION_'))
    exit;
require_once(_PS_MODULE_DIR_.'asmcarrier/lib/Asmlog.php');

class asmcarrier extends CarrierModule {
    public  $id_carrier;
    private $_html = '';
    private $_postErrors = array();
    private $_moduleName = 'asmcarrier';
    private $_version = '2.0.0';
    private $_asmDel = '####';


    /*
	** Construct Method
	**
    */

    public function __construct() {
        $this->name = 'asmcarrier';
        $this->tab = 'shipping_logistics';
        $this->version = '2.0.0';
        $this->author = 'ASM';
        $this->limited_countries = '';//array('fr', 'us', 'es', 'ad', 'pt');

        parent::__construct ();

        $this->displayName = $this->l('ASM Transporte Urgente');
        $this->description = $this->l('Modulo para realizar envios con ASM');

        if (self::isInstalled($this->name) && !$this->registerHook('adminOrder')) {
            // Getting carrier list
            global $cookie;
            $carriers = Carrier::getCarriers($cookie->id_lang, true, false, false, NULL, PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);

            // Saving id carrier list
            $id_carrier_list = array();
            foreach($carriers as $carrier)
                $id_carrier_list[] .= $carrier['id_carrier'];

            // Testing if Carrier Id exists
            $warning = array();

            if (!Configuration::get('ASM_GUID'))
                $warning[] .= $this->l('"GUID"').' ';
            if (!Configuration::get('ASM_URL'))
                $warning[] .= $this->l('"URL del WS"').' ';


            if (count($warning))
                $this->warning .= implode(' , ',$warning).$this->l('debe finalizar la configuración antes de utilizar este módulo.').' ';
        }
    }

    /*
	** Install / Uninstall Methods
	**
    */
	// MIA : Crear un carrier para cada tipo de servicio que ofrece ASM
    public function install() {
    	//preparamos la tabla para los envios
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."asm_envios (
			        id_envio int(11) NOT NULL AUTO_INCREMENT,
			        id_envio_order int(11) NOT NULL,
			        codigo_envio varchar(50) NOT NULL,
			        url_track varchar(255) NOT NULL,
			        num_albaran varchar(100) NOT NULL,
			        codigo_barras text,
			        fecha datetime NOT NULL,
			        PRIMARY KEY (`id_envio`)
			      ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 ";
        if(!Db::getInstance()->Execute($query)){
        	AsmLog::error('Imposible crear la tabla '._DB_PREFIX_.'asm_envios usando el ENGINE='._MYSQL_ENGINE_."\n\r");
	        // do rollback
	        $this->tablesRollback();
	        return false;
	    }
    	//preparamos la tabla para el mensaje personalizado
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."asm_email (
			        id int(11) NOT NULL AUTO_INCREMENT,
			        titulo varchar(128),
			        mensaje text,
			        PRIMARY KEY (`id`)
			      ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 ";
        if(!Db::getInstance()->Execute($query)){
        	AsmLog::error('Imposible crear la tabla '._DB_PREFIX_.'asm_email usando el ENGINE='._MYSQL_ENGINE_."\n\r");
	        // do rollback
	        $this->tablesRollback();
	        return false;
	    }
        $query = "INSERT INTO "._DB_PREFIX_."asm_email (titulo,mensaje) VALUES ('ejemplo','Escriba aqui su mensaje...')";
        if(!Db::getInstance()->Execute($query)){
        	AsmLog::error('Imposible crear registro en la tabla '._DB_PREFIX_.'asm_email usando el ENGINE='._MYSQL_ENGINE_."\n\r");
	        // do rollback
	        $this->tablesRollback();
	        return false;
	    }
        //preparamos los diferentes servicios
        $carrierConfig = array(
                0 => array('name' => 'ASM - Servicio ASM10',
                        'id_tax_rules_group' => 0,
                        'active' => true,
                        'deleted' => 0,
                        'shipping_handling' => false,
                        'range_behavior' => 0,
                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Servicio de entrega antes de las 10:00h del dia siguiente'),
                        'id_zone' => 1,
                        'is_module' => true,
                        'shipping_external' => true,
                        'external_module_name' => $this->_moduleName,
                        'need_range' => true // mirar si ponemos en false para poder usar el CSV
                ),
                1 => array('name' => 'ASM - Servicio ASM14',
                        'id_tax_rules_group' => 0,
                        'active' => true,
                        'deleted' => 0,
                        'shipping_handling' => false,
                        'range_behavior' => 0,
                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Servicio de entrega antes de las 14:00h del dia siguiente'),
                        'id_zone' => 1,
                        'is_module' => true,
                        'shipping_external' => true,
                        'external_module_name' => $this->_moduleName,
                        'need_range' => true
                ),
                2 => array('name' => 'ASM - Servicio ASM24',
                        'id_tax_rules_group' => 0,
                        'active' => true,
                        'deleted' => 0,
                        'shipping_handling' => false,
                        'range_behavior' => 0,
                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Servicio de entrega a lo largo del dia siguiente'),
                        'id_zone' => 1,
                        'is_module' => true,
                        'shipping_external' => true,
                        'external_module_name' => $this->_moduleName,
                        'need_range' => true
                ),
                3 => array('name' => 'ASM - Servicio ECONOMY',
                        'id_tax_rules_group' => 0,
                        'active' => true,
                        'deleted' => 0,
                        'shipping_handling' => false,
                        'range_behavior' => 0,
                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Servicio de entrega en 48 horas'),
                        'id_zone' => 1,
                        'is_module' => true,
                        'shipping_external' => true,
                        'external_module_name' => $this->_moduleName,
                        'need_range' => true
                ),
        );

        $id_carrier1 = $this->installExternalCarrier($carrierConfig[0]);
        $id_carrier2 = $this->installExternalCarrier($carrierConfig[1]);
        $id_carrier3 = $this->installExternalCarrier($carrierConfig[2]);
        $id_carrier4 = $this->installExternalCarrier($carrierConfig[3]);
        Configuration::updateValue('MYCARRIER1_CARRIER_ID', (int)$id_carrier1);
        Configuration::updateValue('MYCARRIER2_CARRIER_ID', (int)$id_carrier2);
        Configuration::updateValue('MYCARRIER3_CARRIER_ID', (int)$id_carrier3);
        Configuration::updateValue('MYCARRIER4_CARRIER_ID', (int)$id_carrier4);

        if (!parent::install() || !$this->registerHook('updateCarrier'))
            return false;

        // creamos el boton para agregar las funcionalidades realizar y cancelar pedido e imprimir etiquetas
        $tab = new Tab();
	    $tab->class_name = 'AdminAsm';
        //Para que funcione con la nueva versión.
	    if (version_compare(_PS_VERSION_, '1.5') >= 0){
            $tab->id_parent = 10;
        }else{
            $tab->id_parent = 3;
        }
	    $tab->module = $this->_moduleName;
	    $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = 'ASM';
	    if(!$tab->add())
	    {
	      $this->tablesRollback();
	      return false;
	    }
        return true;
    }

    public function uninstall() {
        // Uninstall
        if (!parent::uninstall() || !$this->unregisterHook('updateCarrier'))
            return false;

        // Delete External Carrier
        $Carrier1 = new Carrier((int)(Configuration::get('MYCARRIER1_CARRIER_ID')));
        $Carrier2 = new Carrier((int)(Configuration::get('MYCARRIER2_CARRIER_ID')));
        $Carrier3 = new Carrier((int)(Configuration::get('MYCARRIER3_CARRIER_ID')));
        $Carrier4 = new Carrier((int)(Configuration::get('MYCARRIER4_CARRIER_ID')));
        // If external carrier is default set other one as default
        if (Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier1->id) ||
        	Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier2->id) ||
            Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier3->id) ||
        	Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier4->id)) {
            global $cookie;
            $carriersD = Carrier::getCarriers($cookie->id_lang, true, false, false, NULL, PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);
            foreach($carriersD as $carrierD)
                if ($carrierD['active'] AND !$carrierD['deleted'] AND ($carrierD['name'] != $this->_config['name']))
                    Configuration::updateValue('PS_CARRIER_DEFAULT', $carrierD['id_carrier']);
        }

        // Then delete Carrier
        $Carrier1->deleted = 1;
        $Carrier2->deleted = 1;
        $Carrier3->deleted = 1;
        $Carrier4->deleted = 1;
        if (!$Carrier1->update() || !$Carrier2->update() || !$Carrier3->update() || !$Carrier4->update())
            return false;
        // Borramos el tab ASM
        Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'tab WHERE module = "'.$this->_moduleName.'"');
        Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'asm_envios');
        Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'asm_email');
        return true;
    }

    public static function installExternalCarrier($config) {
        $carrier = new Carrier();
        $carrier->name = $config['name'];
        $carrier->id_tax_rules_group = $config['id_tax_rules_group'];
        $carrier->id_zone = $config['id_zone'];
        $carrier->active = $config['active'];
        $carrier->deleted = $config['deleted'];
        $carrier->delay = $config['delay'];
        $carrier->shipping_handling = $config['shipping_handling'];
        $carrier->range_behavior = $config['range_behavior'];
        $carrier->is_module = $config['is_module'];
        $carrier->shipping_external = $config['shipping_external'];
        $carrier->external_module_name = $config['external_module_name'];
        $carrier->need_range = $config['need_range'];

        $languages = Language::getLanguages(true);
        foreach ($languages as $language) {
            if ($language['iso_code'] == 'fr')
                $carrier->delay[(int)$language['id_lang']] = $config['delay'][$language['iso_code']];
            if ($language['iso_code'] == 'en')
                $carrier->delay[(int)$language['id_lang']] = $config['delay'][$language['iso_code']];
            if ($language['iso_code'] == Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')))
                $carrier->delay[(int)$language['id_lang']] = $config['delay'][$language['iso_code']];
        }

        if ($carrier->add()) {
            $groups = Group::getGroups(true);
            foreach ($groups as $group)
                Db::getInstance()->autoExecute(_DB_PREFIX_.'carrier_group', array('id_carrier' => (int)($carrier->id), 'id_group' => (int)($group['id_group'])), 'INSERT');

            $rangePrice = new RangePrice();
            $rangePrice->id_carrier = $carrier->id;
            $rangePrice->delimiter1 = '0';
            $rangePrice->delimiter2 = '1000000000';
            $rangePrice->add();

            $rangeWeight = new RangeWeight();
            $rangeWeight->id_carrier = $carrier->id;
            $rangeWeight->delimiter1 = '0';
            $rangeWeight->delimiter2 = '1000000000';
            $rangeWeight->add();

            $zones = Zone::getZones(true);
            foreach ($zones as $zone) {
                Db::getInstance()->autoExecute(_DB_PREFIX_.'carrier_zone', array('id_carrier' => (int)($carrier->id), 'id_zone' => (int)($zone['id_zone'])), 'INSERT');
                Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_.'delivery', array('id_carrier' => (int)($carrier->id), 'id_range_price' => (int)($rangePrice->id), 'id_range_weight' => NULL, 'id_zone' => (int)($zone['id_zone']), 'price' => '0'), 'INSERT');
                Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_.'delivery', array('id_carrier' => (int)($carrier->id), 'id_range_price' => NULL, 'id_range_weight' => (int)($rangeWeight->id), 'id_zone' => (int)($zone['id_zone']), 'price' => '0'), 'INSERT');
            }

            // Copiamos los logos de cada servicio
            if($config['name'] == 'ASM - Servicio ASM10'){
            	if (!copy(dirname(__FILE__).'/asm_asm24.png', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.png'))
                	return false;
            }
            if($config['name'] == 'ASM - Servicio ASM14'){
            	if (!copy(dirname(__FILE__).'/asm_asm24.png', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.png'))
                	return false;
            }
            if($config['name'] == 'ASM - Servicio ASM24'){
            	if (!copy(dirname(__FILE__).'/asm_asm24.png', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.png'))
                	return false;
            }
            if($config['name'] == 'ASM - Servicio ECONOMY'){
            	if (!copy(dirname(__FILE__).'/asm_asm24.png', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.png'))
                	return false;
            }
            // Return ID Carrier
            return (int)($carrier->id);
        }

        return false;
    }




    /*
	** Form Config Methods
	**
    */

    public function getContent() {
        $this->_html .= '<h2>' . $this->l('My Carrier').'</h2>';
        if (!empty($_POST) AND Tools::isSubmit('submitSave')) {
            $this->_postValidation();
            if (!sizeof($this->_postErrors))
                $this->_postProcess();
            else
                foreach ($this->_postErrors AS $err)
                    $this->_html .= '<div class="alert error"><img src="'._PS_IMG_.'admin/forbbiden.gif" alt="nok" />&nbsp;'.$err.'</div>';
        }
        $this->_displayForm();
        return $this->_html;
    }

    private function _displayForm() {
        //dani ?? había logo.gif
        $this->_html .= '<fieldset>
		<legend><img src="'.$this->_path.'asm_asm24.png" alt="" /> '.$this->l('Estado del módulo').'</legend>';

        $asm_guid = '';
        $asm_wsvc = '';

        $alert = array();
        if(!Configuration::get('ASM_GUID') || Configuration::get('ASM_GUID') == '') {
            $asm_guid = '15F9A8B5-82AC-4094-99F7-9FD58FD43E9E';
        } else  {
            $asm_guid = Tools::getValue('asm_guid', Configuration::get('ASM_GUID'));
        }
            //$alert['asm_guid'] = 1;
        if(!Configuration::get('ASM_URL') || Configuration::get('ASM_URL') == '') {
            $asm_wsvc = 'http://www.asmred.com/websrvs/ecm.asmx?wsdl';
        } else {
            $asm_wsvc = Tools::getValue('asm_url', Configuration::get('ASM_URL'));
        }
            //$alert['asm_url'] = 1;
        if (!count($alert))
            $this->_html .= '<img src="'._PS_IMG_.'admin/module_install.png" /><strong>'.$this->l('Módulo de ASM configurado y online').'</strong>';
        else {
            $this->_html .= '<img src="'._PS_IMG_.'admin/warn2.png" /><strong>'.$this->l('Módulo de ASM no está configurado, por favor:').'</strong>';
            $this->_html .= '<br />'.(isset($alert['asm_guid']) ? '<img src="'._PS_IMG_.'admin/warn2.png" />' : '<img src="'._PS_IMG_.'admin/module_install.png" />').' 1) '.$this->l('Configure el GUID');
            $this->_html .= '<br />'.(isset($alert['asm_url']) ? '<img src="'._PS_IMG_.'admin/warn2.png" />' : '<img src="'._PS_IMG_.'admin/module_install.png" />').' 4) '.$this->l('Configure la URL WS de conexión');
        }

        // 1 = No módulo, 4 = Módulo o no módulo
        $carriers = Carrier::getCarriers($cookie->id_lang, true, false, false, NULL, 1);

        $id_carrier_list = array();
        foreach($carriers as $carrier) {
            $id_carrier_list[] = array(
                'id'=> $carrier['id_carrier'],
                'name' => $carrier['name']
            );
        }

        $servicio_asm_seccionado_option_asm10 = '<option value="" title="Seleccione un servicio">Seleccione un servicio</option>';
        $servicio_asm_seccionado_option_asm14 = '<option value="" title="Seleccione un servicio">Seleccione un servicio</option>';
        $servicio_asm_seccionado_option_asm24 = '<option value="" title="Seleccione un servicio">Seleccione un servicio</option>';
        $servicio_asm_seccionado_option_asmeco = '<option value="" title="Seleccione un servicio">Seleccione un servicio</option>';
        $servicio_asm_seccionado_selected_asm10 = '';
        $servicio_asm_seccionado_selected_asm14 = '';
        $servicio_asm_seccionado_selected_asm24 = '';
        $servicio_asm_seccionado_selected_asmeco = '';

        foreach ($id_carrier_list as $key => $value) {
            if(Tools::getValue('asm_servicio_seleccionado_asm10', Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM10')) == $value['id']) {
                $servicio_asm_seccionado_selected_asm10 = 'selected="selected"';
            } else {
                $servicio_asm_seccionado_selected_asm10 = '';
            }

            if(Tools::getValue('asm_servicio_seleccionado_asm14', Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM14')) == $value['id']) {
                $servicio_asm_seccionado_selected_asm14 = 'selected="selected"';
            } else {
                $servicio_asm_seccionado_selected_asm14 = '';
            }

            if(Tools::getValue('asm_servicio_seleccionado_asm24', Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM24')) == $value['id']) {
                $servicio_asm_seccionado_selected_asm24 = 'selected="selected"';
            } else {
                $servicio_asm_seccionado_selected_asm24 = '';
            }

            if(Tools::getValue('asm_servicio_seleccionado_asmeco', Configuration::get('ASM_SERVICIO_SELECCIONADO_ASMECO')) == $value['id']) {
                $servicio_asm_seccionado_selected_asmeco = 'selected="selected"';
            } else {
                $servicio_asm_seccionado_selected_asmeco = '';
            }

            $servicio_asm_seccionado_option_asm10 .= '<option title="'.$value['name'].'" value="'.$value['id'].'" '.$servicio_asm_seccionado_selected_asm10.'>'.$value['name'].'</option>';
            $servicio_asm_seccionado_option_asm14 .= '<option title="'.$value['name'].'" value="'.$value['id'].'" '.$servicio_asm_seccionado_selected_asm14.'>'.$value['name'].'</option>';
            $servicio_asm_seccionado_option_asm24 .= '<option title="'.$value['name'].'" value="'.$value['id'].'" '.$servicio_asm_seccionado_selected_asm24.'>'.$value['name'].'</option>';
            $servicio_asm_seccionado_option_asmeco .= '<option title="'.$value['name'].'" value="'.$value['id'].'" '.$servicio_asm_seccionado_selected_asmeco.'>'.$value['name'].'</option>';
        }

        // Comprobamos selecciones anteriores
        // ENVIOS GRATUITOS
        $envio_gratuito_si = "";
        $envio_gratuito_no = "";
        $envio_gratuito_importe = "";
        $envio_gratuito_articulo = "";
        $servicio_gratuito_nada = "";
        $servicio_gratuito_asm10 = "";
        $servicio_gratuito_asm14 = "";
        $servicio_gratuito_asm24 = "";
        $servicio_gratuito_economy = "";
        $mostrar_todo_si = "";
        $mostrar_todo_no = "";
        $asm_como_modulo = "";

        if(Tools::getValue('asm_prestamodulo', Configuration::get('ASM_PRESTAMODULO')) == "1") {
            $asm_como_modulo = "checked=\"checked\"";
        }

        if(Tools::getValue('asm_envio_gratuito', Configuration::get('ASM_ENVIO_GRAT')) == "0") {
            $envio_gratuito_no = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_envio_gratuito', Configuration::get('ASM_ENVIO_GRAT')) == "1") {
            $envio_gratuito_si = "checked=\"checked\"";
        }

        if(Tools::getValue('asm_envio_gratuito_tipo', Configuration::get('ASM_ENVIO_GRAT_TIPO')) == "0") {
            $envio_gratuito_importe = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_envio_gratuito_tipo', Configuration::get('ASM_ENVIO_GRAT_TIPO')) == "1") {
            $envio_gratuito_articulo = "checked=\"checked\"";
        }

        if(Tools::getValue('asm_servicio_envio_gratuito', Configuration::get('ASM_SERVICIO_GRAT')) == "0") {
            $servicio_gratuito_nada = "selected=\"selected\"";
        }
        if(Tools::getValue('asm_servicio_envio_gratuito', Configuration::get('ASM_SERVICIO_GRAT')) == "ASM10") {
            $servicio_gratuito_asm10 = "selected=\"selected\"";
        }
        if(Tools::getValue('asm_servicio_envio_gratuito', Configuration::get('ASM_SERVICIO_GRAT')) == "ASM14") {
            $servicio_gratuito_asm14 = "selected=\"selected\"";
        }
        if(Tools::getValue('asm_servicio_envio_gratuito', Configuration::get('ASM_SERVICIO_GRAT')) == "ASM24") {
            $servicio_gratuito_asm24 = "selected=\"selected\"";
        }
        if(Tools::getValue('asm_servicio_envio_gratuito', Configuration::get('ASM_SERVICIO_GRAT')) == "ECONOMY") {
            $servicio_gratuito_economy = "selected=\"selected\"";
        }
        if(Tools::getValue('asm_mostrar_todo', Configuration::get('ASM_RESTO')) == "0") {
        	$mostrar_todo_no = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_mostrar_todo', Configuration::get('ASM_RESTO')) == "1") {
        	$mostrar_todo_si = "checked=\"checked\"";
        }
        $oldTaxRate = true;
        // TaxRules si la versión es superior a 1.5
        if(version_compare(_PS_VERSION_, '1.5') >= 0) {
            $oldTaxRate = false;
            $taxRules = TaxRulesGroup::getTaxRulesGroups(true);
            $taxHTML = '';
            $taxHelper = '';
            $taxSelected = '';

            foreach($taxRules as $key => $value) {
                ($value['id_tax_rules_group'] == Tools::getValue('asm_impuesto_agregado', Configuration::get('ASM_IMPUESTO'))) ? $taxSelected = " selected " : $taxSelected = '';
                //
                $taxHTML .= '<option value="'.$value['id_tax_rules_group'].'" title="'.$value['name'].'"'.$taxSelected.'>'.$value['name'].'</option>';
            }

            $taxHTML = '<select id="asm_impuesto_agregado" name="asm_impuesto_agregado" title="Seleccione impuesto...">'.$taxHTML.'</select>';
            $taxHelper = '<p class="tip">Seleccione el impuesto correspondiente para el servicio de transporte.</p>';
        } else {
            $taxHTML = '<input type="text" size="3" name="asm_impuesto_agregado" value="'.Tools::getValue('asm_impuesto_agregado', Configuration::get('ASM_IMPUESTO')).'" />';
            $taxHelper = '<p class="tip">Impuesto agregado. Modo de uso, si quiere poner un 21% deberá escribir 0.21</p>';
        }

        // ENVIOS NO GRATUITOS
        $envio_servicio_asm10_no = "";
        $envio_servicio_asm10_si = "";
        $envio_servicio_asm14_no = "";
        $envio_servicio_asm14_si = "";
        $envio_servicio_asm24_no = "";
        $envio_servicio_asm24_si = "";
        $envio_servicio_economy_no = "";
        $envio_servicio_economy_si = "";

        if(Tools::getValue('asm_servicio_asm10', Configuration::get('ASM_ASM10')) == "0") {
            $envio_servicio_asm10_no = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_servicio_asm10', Configuration::get('ASM_ASM10')) == "ASM10") {
            $envio_servicio_asm10_si = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_servicio_asm14', Configuration::get('ASM_ASM14')) == "0") {
            $envio_servicio_asm14_no = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_servicio_asm14', Configuration::get('ASM_ASM14')) == "ASM14") {
            $envio_servicio_asm14_si = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_servicio_asm24', Configuration::get('ASM_ASM24')) == "0") {
            $envio_servicio_asm24_no = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_servicio_asm24', Configuration::get('ASM_ASM24')) == "ASM24") {
            $envio_servicio_asm24_si = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_servicio_economy', Configuration::get('ASM_ECONOMY')) == "0") {
            $envio_servicio_economy_no = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_servicio_economy', Configuration::get('ASM_ECONOMY')) == "ECONOMY") {
            $envio_servicio_economy_si = "checked=\"checked\"";
        }

        // VARIOS
        $bultos_si = "";
        $bultos_no = "";
        if(Tools::getValue('asm_bultos', Configuration::get('ASM_BULTOS')) == "0") {
        	$bultos_no = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_bultos', Configuration::get('ASM_BULTOS')) == "1") {
        	$bultos_si = "checked=\"checked\"";
        }

        // CALCULAR PRECIO APARTIR DE: PESO, IMPORTE CARRITO O WEBSERVICE
        $precio_x_peso = "";
        $precio_x_importe = "";
        $precio_x_webservice = "";
        if(Tools::getValue('asm_precio_por', Configuration::get('ASM_CALCULAR_PRECIO')) == "0") {
        	$precio_x_peso = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_precio_por', Configuration::get('ASM_CALCULAR_PRECIO')) == "1") {
        	$precio_x_importe = "checked=\"checked\"";
        }
        if(Tools::getValue('asm_precio_por', Configuration::get('ASM_CALCULAR_PRECIO')) == "2") {
        	$precio_x_webservice = "checked=\"checked\"";
        }


        $asm_manipulacion_fijo = "";
        $asm_manipulacion_variable = "";
        if(Tools::getValue('asm_manipulacion', Configuration::get('ASM_MANIPULACION')) == "F") {
            $asm_manipulacion_fijo = "selected=\"selected\"";
        }
        if(Tools::getValue('asm_manipulacion', Configuration::get('ASM_MANIPULACION')) == "V") {
            $asm_manipulacion_variable = "selected=\"selected\"";
        }

        $asm_enviar_mail_si = "";
        $asm_enviar_mail_no = "";
        if(Tools::getValue('asm_enviar_mail', Configuration::get('ASM_ENVIAR_MAIL')) == "S") {
            $asm_enviar_mail_si = "selected=\"selected\"";
        }
        if(Tools::getValue('asm_enviar_mail', Configuration::get('ASM_ENVIAR_MAIL')) == "N") {
            $asm_enviar_mail_no = "selected=\"selected\"";
        }

        $ruta_csv = _PS_MODULE_DIR_.'asmcarrier/asm.tarifas.peso.csv';
        $ruta_csv2 = _PS_MODULE_DIR_.'asmcarrier/asm.tarifas.importe.csv';


        $this->_html .= '</fieldset><div class="clear">&nbsp;</div>
			<style>
				#tabList { clear: left; }
				.tabItem { display: block; background: #FFFFF0; border: 1px solid #CCCCCC; padding: 10px; padding-top: 20px; }
                                .columna1 { width:320px;text-align:right;font-weight:bold;padding-bottom:15px;display:table-cell;vertical-align:top; }
                                .columna2 { text-align:left;padding-left:20px; }
                                .tip {color: #7F7F7F;font-size: 0.85em;}
                #asm_prestatistas { width:300px; }
			</style>
			<script language="javascript">
			    <!--//
			    $(document).ready(function(event) {
			        if ($("#asm_prestamodulo").is(":checked")) {
                        $("#configuracionASM tr").each(function( i, e ) {
                            if(this.className != "asm_module_off") {
                              $(e).css("display","none");
                            }
                        });

                        $("#asm_service_1").css("display","");
                        $("#asm_service_2").css("display","");
                        $("#asm_service_3").css("display","");
                        $("#asm_service_4").css("display","");
			        }

			        $("#asm_prestamodulo").click(function (event) {
			            var styleStatus = "";
			            var styleTwoStatus = "";
                        if($(this).prop("checked")) {
                            styleStatus = "none";
                        } else {
                            styleTwoStatus = "none";
                        }

                        $("#asm_service_1").css("display",styleTwoStatus);
                        $("#asm_service_2").css("display",styleTwoStatus);
                        $("#asm_service_3").css("display",styleTwoStatus);
                        $("#asm_service_4").css("display",styleTwoStatus);

                       $("#configuracionASM tr").each(function( i, e ) {
                            if(this.className != "asm_module_off") {
                                $(e).css("display",styleStatus);
                            }
                        });
			        });
			    });
			    //-->
            </script>
			<div id="tabList">
				<div class="tabItem">
					<form action="index.php?tab='.Tools::getValue('tab').'&configure='.Tools::getValue('configure').'&token='.Tools::getValue('token').'&tab_module='.Tools::getValue('tab_module').'&module_name='.Tools::getValue('module_name').'&id_tab=1&section=general" method="post" class="form" id="configForm">

					<fieldset style="border: 0px;">
						<h4>'.$this->l('General configuration').' :</h4>
                                                <table style="border: 0px;" id="configuracionASM">
                                                    <tr class="asm_module_off">
                                                        <td class="columna1">'.$this->l('Manual').' : </td>
                                                        <td class="columna2">
                                                            <input type="checkbox" size="45" name="asm_prestamodulo"  id="asm_prestamodulo" '.$asm_como_modulo.' value="1" />
                                                            <p class="tip">Seleccionando esta opción podrá trabajar con los transportistas que haya configurado<br /> para usarse como ASM en el apartado "Transportistas" de Prestashop.</p>
                                                        </td>
                                                    </tr>
                                                    <tr class="asm_module_off" id="asm_service_1">
                                                        <td class="columna1">'.$this->l('Seleccione transportista(s)').' : </td>
                                                        <td class="columna2">
                                                            '.$this->l('Servicio 10 horas').':
                                                            <select id="asm_servicio_seleccionado_asm10" name="asm_servicio_seleccionado_asm10">
                                                                '.$servicio_asm_seccionado_option_asm10.'
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="asm_module_off" id="asm_service_2">
                                                        <td class="columna1"> </td>
                                                        <td class="columna2">
                                                            '.$this->l('Servicio 14 horas').' :
                                                            <select id="asm_servicio_seleccionado_asm14" name="asm_servicio_seleccionado_asm14">
                                                                '.$servicio_asm_seccionado_option_asm14.'
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="asm_module_off"  id="asm_service_3">
                                                        <td class="columna1"></td>
                                                        <td class="columna2">
                                                            '.$this->l('Servicio 24 horas').' :
                                                            <select id="asm_servicio_seleccionado_asm24" name="asm_servicio_seleccionado_asm24">
                                                                '.$servicio_asm_seccionado_option_asm24.'
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="asm_module_off" id="asm_service_4">
                                                        <td class="columna1"></td>
                                                        <td class="columna2">
                                                            '.$this->l('Servicio Economy').' :
                                                            <select id="asm_servicio_seleccionado_asmeco" name="asm_servicio_seleccionado_asmeco">
                                                                '.$servicio_asm_seccionado_option_asmeco.'
                                                            </select>
                                                            <p class="tip">Seleccione los transportistas que ha configurado para trabajar con ASM en su Prestashop.<br />Si no desea utilizar alguno de ellos deje la opción en blanco.</p>
                                                        </td>
                                                    </tr>
                                                    <tr class="asm_module_off">
                                                        <td class="columna1">'.$this->l('GUID').' : </td>
                                                        <td class="columna2">
                                                            <input type="text" size="45" name="asm_guid" value="'.$asm_guid.'" />
                                                            <p class="tip">El GUID por defecto (15F9A8B5-82AC-4094-99F7-9FD58FD43E9E) es para hacer pruebas. <br />Cuando tenga el módulo y sus opciones corretamente configurado y testado <strong>solicite su GUID a su Agencia ASM.</strong></p>
                                                        </td>
                                                    </tr>
                                                    <tr class="asm_module_off">
                                                        <td class="columna1">'.$this->l('URL WS').' : </td>
                                                        <td class="columna2">
                                                            <input type="text" size="49" name="asm_url" value="'.$asm_wsvc.'" />
                                                            <p class="tip">URL del servicio web de ASM. Por defecto: http://www.asmred.com/websrvs/ecm.asmx?wsdl</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td colspan="2"><hr/></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Habilitar envio Gratuito').' :</td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_envio_gratuito" value="0" '.$envio_gratuito_no.'/>No&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_envio_gratuito" value="1" '.$envio_gratuito_si.'/>Si
                                                            <p class="tip">Habilita el envío gratuito</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                  		<td class="columna1">'.$this->l('Servicio para envio Gratuito').' : </td>
                                                        <td class="columna2">
                                                            <select name="asm_servicio_envio_gratuito">
                                                              <option '.$servicio_gratuito_nada.' value="0"> - elija un servicio - </option>
                                                              <option '.$servicio_gratuito_asm10.' value="ASM10">ASM10</option>
                                                              <option '.$servicio_gratuito_asm14.' value="ASM14">ASM14</option>
                                                              <option '.$servicio_gratuito_asm24.' value="ASM24">ASM24</option>
                                                              <option '.$servicio_gratuito_economy.' value="ECONOMY">ECONOMY</option>
                                                            </select>
                                                            <p class="tip">Servicio para envío gratuito</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Tipo').' :</td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_envio_gratuito_tipo" value="0" '.$envio_gratuito_importe.'/>Por importe&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_envio_gratuito_tipo" value="1" '.$envio_gratuito_articulo.'/>Por número de artículos
                                                            <p class="tip">Define si el calculo del envío gratuito será por precio de carrito o por número de artículos</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Importe/Número de artículos mínimo/s para envío gratuito').' : </td>
                                                        <td class="columna2">
                                                            <input type="text" size="3" name="asm_importe_minimo_envio_gratuito" value="'.Tools::getValue('asm_importe_minimo_envio_gratuito', Configuration::get('ASM_IMP_MIN_ENVIO_GRA')).'" />
                                                            <p class="tip">Importe/Número de artículos mínimo/s para envío gratuito</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td colspan="2"><hr/></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Habilitar servicio ASM10').' : </td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_servicio_asm10" value="0" '.$envio_servicio_asm10_no.'/>No&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_servicio_asm10" value="ASM10" '.$envio_servicio_asm10_si.'/>Si
                                                            <p class="tip">Habilita como método de envío el servicio ASM10</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Habilitar servicio ASM14').' : </td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_servicio_asm14" value="0" '.$envio_servicio_asm14_no.'/>No&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_servicio_asm14" value="ASM14" '.$envio_servicio_asm14_si.'/>Si
                                                            <p class="tip">Habilita como método de envío el servicio ASM14</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Habilitar servicio ASM24').' : </td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_servicio_asm24" value="0" '.$envio_servicio_asm24_no.'/>No&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_servicio_asm24" value="ASM24" '.$envio_servicio_asm24_si.'/>Si
                                                            <p class="tip">Habilita como método de envío el servicio ASM24</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Habilitar servicio ECONOMY').' : </td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_servicio_economy" value="0" '.$envio_servicio_economy_no.'/>No&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_servicio_economy" value="ECONOMY" '.$envio_servicio_economy_si.'/>Si
                                                            <p class="tip">Habilita como método de envío el servicio ECONOMY</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td colspan="2"><hr/></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Bultos por envío').' :</td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_bultos" value="0" '.$bultos_no.'/>Fijo&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_bultos" value="1" '.$bultos_si.'/>Variable
                                                            <p class="tip">Configuración de bultos por envío fijo o variable según numero de artículos.</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Número de artículos por bultos fijo').' : </td>
                                                        <td class="columna2">
                                                            <input type="text" size="3" name="asm_num_fijo_bultos" default="1" value="'.Tools::getValue('asm_num_fijo_bultos', Configuration::get('ASM_FIJO_BULTOS')).'" />
                                                            <p class="tip">Indique el número de artículos por bulto.</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Número de artículos por bultos variable').' : </td>
                                                        <td class="columna2">
                                                            <input type="text" size="3" name="asm_num_articulos" value="'.Tools::getValue('asm_num_articulos', Configuration::get('ASM_NUM_BULTOS')).'" />
                                                            <p class="tip">Indique el número de artículos por bulto.</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td colspan="2"><hr/></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="columna1">'.$this->l('Calcular precio de envio por').' :</td>
                                                        <td class="columna2">
                                                            <input type="radio" name="asm_precio_por" value="0" '.$precio_x_peso.'/>&nbsp;Peso&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="asm_precio_por" value="1" '.$precio_x_importe.'/>&nbsp;Precio Carrito

                                                            <p class="tip">Calcula el precio del envio por el peso total o por el precio del carrito.</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td colspan="2"><hr/></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Impuesto agregado').' : </td>
                                                        <td class="columna2">
                                                            '.$taxHTML.$taxHelper.'
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Coste fijo de envío').' : </td>
                                                        <td class="columna2">
                                                            <input type="text" size="3" name="asm_coste_fijo_envio" value="'.Tools::getValue('asm_coste_fijo_envio', Configuration::get('ASM_COSTE_FIJO_ENVIO')).'" />
                                                            <p class="tip">Precio fijo por envío</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Manipulación').' : </td>
                                                        <td class="columna2">
                                                            <select name="asm_manipulacion">
                                                              <option '.$asm_manipulacion_fijo.' value="F">Fijo</option>
                                                              <option '.$asm_manipulacion_variable.' value="V">Variable</option>
                                                            </select>
                                                            <p class="tip">Sistema de cálculo para el cargo de manipulación</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">'.$this->l('Coste de manipulación').' : </td>
                                                        <td class="columna2">
                                                            <input type="text" size="5" name="asm_coste_manipulacion" value="'.Tools::getValue('asm_coste_manipulacion', Configuration::get('ASM_COSTE_MANIPULACION')).'" />
                                                            <p class="tip">Coste manipulación. (0 – sin coste)</p>
                                                        </td>
                                                    </tr>
                                                     <tr class="asm_module_off">
                                                        <td class="columna1">'.$this->l('Envíar mail').' : </td>
                                                        <td class="columna2">
                                                            <select name="asm_enviar_mail">
                                                              <option '.$asm_enviar_mail_si.' value="S">SI</option>
                                                              <option '.$asm_enviar_mail_no.' value="N">NO</option>
                                                            </select>
                                                            <p class="tip">Envíar mail al comprador. Solo funciona para versiones de prestasho 1.5.x en adelante</p>
                                                        </td>
                                                    </tr>
													<tr class="asm_module_off">
                                                        <td class="columna1">'.$this->l('Mensaje').' : </td>
                                                        <td class="columna2">
                                                            <textarea name="asm_email" id="asm_email" cols="60" rows="12">'.Tools::getValue('asm_email', Configuration::get('ASM_EMAIL')).'</textarea>
                                                            <p class="tip">Descripción del mesaje que le llegará al comprador por correo electrónico</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td colspan="2"><hr/></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="columna1">&nbsp; </td>
                                                        <td class="columna2">
                                                            <p class="columna1"><strong>Solamente se admiten envíos a España y Portugal</strong></p>
                                                        </td>
                                                    </tr>
                                                </table>
					<br /><br />
				</fieldset>
				<div class="margin-form"><input class="button" name="submitSave" type="submit"></div>
			</form>
		</div></div>';
    }

    private function _postValidation() {
    	// Check configuration values
        if(Tools::getValue('asm_guid') == '' &&
           Tools::getValue('asm_url') == '')
            $this->_postErrors[]  = $this->l('Necesita configurar correctamente: GUID y URL del Webservice.');

        if(Tools::getValue('asm_prestamodulo') && Tools::getValue('asm_prestamodulo') == "1" ) {
            if(Tools::getValue('asm_servicio_seleccionado_asm10') == '' && Tools::getValue('asm_servicio_seleccionado_asm14') == '' && Tools::getValue('asm_servicio_seleccionado_asm24') == '' && Tools::getValue('asm_servicio_seleccionado_asmeco') == '') {
                echo "perro malo";
               $this->_postErrors[]  = $this->l('Si configura el módulo para que actue con los transportistas de Prestashop deberá indicar al menos uno de ellos para que trabaje con ASM.');
            }
        }
		
		
		//Actualización del texto del mensaje en el correo electrónico
		$query = "UPDATE "._DB_PREFIX_."asm_email SET
							mensaje = '".Tools::getValue('asm_email')."'
						WHERE id = 1";
		
		if(!Db::getInstance()->Execute($query)){
        	AsmLog::error('Imposible actualizar registro de la tabla '._DB_PREFIX_.'asm_email usando el ENGINE='._MYSQL_ENGINE_."\n\r");
	        // do rollback
	        $this->tablesRollback();
	        return false;
		}		
    }

    private function _postProcess() {
        // Saving new configurations
        if (Configuration::updateValue('ASM_PRESTAMODULO', Tools::getValue('asm_prestamodulo')) &&
            Configuration::updateValue('ASM_SERVICIO_SELECCIONADO_ASM10', Tools::getValue('asm_servicio_seleccionado_asm10')) &&
            Configuration::updateValue('ASM_SERVICIO_SELECCIONADO_ASM14', Tools::getValue('asm_servicio_seleccionado_asm14')) &&
            Configuration::updateValue('ASM_SERVICIO_SELECCIONADO_ASM24', Tools::getValue('asm_servicio_seleccionado_asm24')) &&
            Configuration::updateValue('ASM_SERVICIO_SELECCIONADO_ASMECO', Tools::getValue('asm_servicio_seleccionado_asmeco')) &&
            Configuration::updateValue('ASM_GUID', Tools::getValue('asm_guid')) &&
            Configuration::updateValue('ASM_URL', Tools::getValue('asm_url')) &&

            Configuration::updateValue('ASM_ENVIO_GRAT', Tools::getValue('asm_envio_gratuito')) &&
            Configuration::updateValue('ASM_ENVIO_GRAT_TIPO', Tools::getValue('asm_envio_gratuito_tipo')) &&
            Configuration::updateValue('ASM_SERVICIO_GRAT', Tools::getValue('asm_servicio_envio_gratuito')) &&
            Configuration::updateValue('ASM_IMP_MIN_ENVIO_GRA', Tools::getValue('asm_importe_minimo_envio_gratuito')) &&
        	Configuration::updateValue('ASM_RESTO', Tools::getValue('asm_mostrar_todo')) &&

            Configuration::updateValue('ASM_ASM10', Tools::getValue('asm_servicio_asm10')) &&
            Configuration::updateValue('ASM_ASM14', Tools::getValue('asm_servicio_asm14')) &&
            Configuration::updateValue('ASM_ASM24', Tools::getValue('asm_servicio_asm24')) &&
            Configuration::updateValue('ASM_ECONOMY', Tools::getValue('asm_servicio_economy')) &&

        	Configuration::updateValue('ASM_BULTOS', Tools::getValue('asm_bultos')) &&
        	Configuration::updateValue('ASM_FIJO_BULTOS', Tools::getValue('asm_num_fijo_bultos')) &&
        	Configuration::updateValue('ASM_NUM_BULTOS', Tools::getValue('asm_num_articulos')) &&

        	Configuration::updateValue('ASM_CALCULAR_PRECIO', Tools::getValue('asm_precio_por')) &&

            Configuration::updateValue('ASM_IMPUESTO', Tools::getValue('asm_impuesto_agregado')) &&
            Configuration::updateValue('ASM_COSTE_FIJO_ENVIO', Tools::getValue('asm_coste_fijo_envio')) &&
            Configuration::updateValue('ASM_MANIPULACION', Tools::getValue('asm_manipulacion')) &&
            Configuration::updateValue('ASM_COSTE_MANIPULACION', Tools::getValue('asm_coste_manipulacion')) &&
			Configuration::updateValue('ASM_ENVIAR_MAIL', Tools::getValue('asm_enviar_mail')) &&
			Configuration::updateValue('ASM_EMAIL', Tools::getValue('asm_email')) ) {
            //Configuration::updateValue('ASM_MARGEN_COSTE_ENVIO', Tools::getValue('asm_margen_coste_envio')) &&
            //Configuration::updateValue('ASM_SOBRE_CP', Tools::getValue('asm_sobreescribir_cp'))

            // TaxRules si la versión es superior a 1.5
            if((version_compare(_PS_VERSION_, '1.5') >= 0) && !$this->isASMModule()) {
                $asm_carrier = array(Configuration::get('MYCARRIER1_CARRIER_ID'), Configuration::get('MYCARRIER2_CARRIER_ID'), Configuration::get('MYCARRIER3_CARRIER_ID'), Configuration::get('MYCARRIER4_CARRIER_ID'));
                foreach ($asm_carrier as $key => $value) {
                    $this->id_carrier = $value;
                    $this->setTaxRulesGroup(Tools::getValue('asm_impuesto_agregado', Configuration::get('ASM_IMPUESTO')));
                }

            }

            $this->_html .= $this->displayConfirmation($this->l('Configuración actualizada'));
        } else {
            $this->_html .= $this->displayErrors($this->l('Error al actualizar la configuración'));
        }
    }

    public function setTaxRulesGroup($id_tax_rules_group, $all_shops = false)
    {
        if (!Validate::isUnsignedId($id_tax_rules_group))
            die(Tools::displayError());

        if (!$all_shops)
            $shops = Shop::getContextListShopID();
        else
            $shops = Shop::getShops(true, null, true);

        $this->deleteTaxRulesGroup($shops);

        $values = array();
        foreach ($shops as $id_shop)
            $values[] = array(
                'id_carrier' => (int)$this->id_carrier,
                'id_tax_rules_group' => (int)$id_tax_rules_group,
                'id_shop' => (int)$id_shop,
            );
        Cache::clean('carrier_id_tax_rules_group_'.(int)$this->id.'_'.(int)Context::getContext()->shop->id);
        return Db::getInstance()->insert('carrier_tax_rules_group_shop', $values);
    }

    public function deleteTaxRulesGroup(array $shops = null) {
        if (!$shops)
            $shops = Shop::getContextListShopID();

        $where = 'id_carrier = '.(int)$this->id_carrier;
        if ($shops)
            $where .= ' AND id_shop IN('.implode(', ', array_map('intval', $shops)).')';
        return Db::getInstance()->delete('carrier_tax_rules_group_shop', $where);
    }

	/*
	** Hook Admin Order
	** Esta función permite mostrar y llevar a cabo tareas en el administrador de pedidos del Backoffice de Prestashop.
	**
    */

	public function hookAdminOrder($params) {
        global $smarty;

		$id_order = $params['id_order']; //Tools::getValue('id_order');
		$regenerar = '';
		$generarEnvioHook = Tools::getValue('regenerar');
        $bultos = (int) Tools::getValue('asm_bultos_user');
        $bultos_error_msg = '';

		//Checkeamos que sea válido el pedido
		$valida = DB::getInstance()->ExecuteS('SELECT o.valid FROM '._DB_PREFIX_.'orders AS o WHERE id_order = "'.$id_order.'" LIMIT 1');
		$valida = $valida[0]['valid'];

        if(isset($generarEnvioHook) || !empty($generarEnvioHook)) {
            if(isset($bultos) && !empty($bultos)) {
                if(!is_int($bultos)) {
                    $bultos_error_msg = 'Solo debe introducir números enteros en el campo "Bultos".';
                    $smarty->assign('mensaje',$bultos_error_msg);
                    $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                    $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                    $smarty->assign('asm_state','1');

                    return $this->display(__FILE__, 'templates/adminErrorOrder.tpl');
                }
            }
        }

		// @generarEnvioHook permite comprobar si se realiza la llamada para generar la etiqueta desde el apartado de administración de pedidos
		if(!isset($generarEnvioHook) || empty($generarEnvioHook)) {
			// Comprobamos que sea ASM quien realice el envío
            $orderTemp = new Order($id_order);
            if($this->isCarrierASM($id_order) || ($this->isASMModule() && $this->isCarrierASMNonModule($orderTemp->id_carrier))) {
				if($valida) {
					$history = new OrderHistory();
					$items_order_state = $history->getLastOrderState($id_order);
					$query = 'SELECT * FROM  '._DB_PREFIX_.'asm_envios WHERE  id_envio_order = '.$id_order;
					$rowCarrier = Db::getInstance()->getRow($query);
					$path_myroot   = _PS_BASE_URL_.__PS_BASE_URI__;
					if($rowCarrier['codigo_envio']!=NULL) {
						//if(!file_exists(_PS_ROOT_DIR_."/modules/asmcarrier/PDF/etiqueta_".$rowCarrier['codigo_envio'].".pdf")) {}
						// Comprobacmos que exista el fichero PDF del envío, si existe mostramos por pantalla la infomración del mismo al Cliente
						if (file_exists(_PS_ROOT_DIR_."/modules/asmcarrier/PDF/etiqueta_".$rowCarrier['id_envio_order'].".pdf")) {
                            $path_download_pdf = $path_myroot."/modules/asmcarrier/PDF/etiqueta_".$rowCarrier['id_envio_order'].".pdf";
                            $path_download_html = 'http://www.asmred.com/Extranet/public/ecmLabel.aspx?codbarras='. $rowCarrier['codigo_envio'] . '&uid=' .Configuration::get('ASM_GUID');

                            $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                            $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                            $smarty->assign('asm_state','1');
                            $smarty->assign('asm_pdf_down', $path_download_pdf);
                            $smarty->assign('asm_html_down', $path_download_html);
                            $smarty->assign('asm_pedido', $id_order);
                            $smarty->assign('referencia', $orderTemp->reference);
                            $smarty->assign('asm_n_envio', $rowCarrier['id_envio_order']);
                            $smarty->assign('asm_codigo_envio', $rowCarrier['codigo_envio']);
                            $smarty->assign('asm_download', 'Pulse aqui descargar');
                            $smarty->assign('asm_pdf_txt', 'Etiqueta de transporte (PDF)');
                            $smarty->assign('asm_html_txt', 'Etiqueta de transporte (ventana nueva)');
                            $smarty->assign('asm_seguimiento_envio', 'Realizar el seguimiento del envío');
                            $smarty->assign('asm_seguimiento_envio_url', $rowCarrier['url_track']);
                            $smarty->assign('asm_state','3');

                            return $this->display(__FILE__, 'templates/adminOKOrder.tpl');
						} else {
                            $smarty->assign('mensaje','El pedido no tiene registros de etiquetas de envio.');
                            $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                            $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                            $smarty->assign('asm_state','1');

                            return $this->display(__FILE__, 'templates/adminErrorOrder.tpl');
						}
					} else {
                        $smarty->assign('mensaje','El pedido no dispone de etiquetas de envío');
                        $smarty->assign('bultos_message','Bultos');
                        $smarty->assign('bultos_input_txt', 'Indique el número de bultos');
                        $smarty->assign('bultos_btn', 'Generar etiqueta');

                        if(version_compare(_PS_VERSION_, '1.5') >= 0) {
                            $smarty->assign('bultos_controller',Tools::getValue('controller'));
                        } else {
                            $smarty->assign('bultos_controller',Tools::getValue('tab'));
                        }

                        $smarty->assign('bultos_id_order',Tools::getValue('id_order'));
                        $smarty->assign('bultos_regenerar','1');
                        $smarty->assign('bultos_token', Tools::getValue('token'));
                        $smarty->assign('bultos_info_b', 'Si desea emplear la <strong>configuración que ha predefinido</strong> en el módulo de ASM <strong>deje el campo "Bultos" vacío.</strong>');
                        $smarty->assign('bultos_info', 'En esta nueva versión del módulo puede <strong>cambiar los bultos por expedición</strong> en el mismo momento de generar la etiqueta; solo debe <strong>indicar el número de bultos para este pedido</strong> en el campo "Bultos".');
                        $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                        $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                        $smarty->assign('asm_state','2');

                        return $this->display(__FILE__, 'templates/adminOrder.tpl');
					}
				} else {
                        $smarty->assign('mensaje','Para generar una etiqueta debe cambiar el estado de su pedido.');
                        $smarty->assign('bultos_info', 'El caso más frecuente es que el pedido este pendiente la aprobación del pago debido a métodos de pago como transferencias bancarias, etc.');
                        $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                        $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                        $smarty->assign('asm_state','1');

                        return $this->display(__FILE__, 'templates/adminNoOrder.tpl');
				
			    } // ASM Carrier (if)
            }
			} else { // regenerar 1
				if($valida) {
					// Inicializamos envíos ASM
					$this->inicializarAsmEnvios();
					// Generamos etiquetas y enviamos email al Cliente con su código de seguimiento
                    $_SESSION["ultimoErrorASM"] = "";


					$this->imprimirEtiquetas(Tools::getValue('id_order'));
					
					$query = 'SELECT id_envio_order, codigo_envio, url_track FROM '._DB_PREFIX_.'asm_envios where id_envio_order = '.Tools::getValue('id_order');
                    //AsmLog::info('query: ' . $query."\n\r");
					$asm_track_value = Db::getInstance()->ExecuteS($query);
					
					$asm_track_value = $asm_track_value[0];
					$path_myroot   = _PS_BASE_URL_.__PS_BASE_URI__;
	
					$path_download_pdf = $path_myroot."/modules/asmcarrier/PDF/etiqueta_".$asm_track_value['id_envio_order'].".pdf";
                    $path_download_html = 'http://www.asmred.com/Extranet/public/ecmLabel.aspx?codbarras='. $asm_track_value['codigo_envio'] . '&uid=' .Configuration::get('ASM_GUID');

                    $orderTemp = new Order(Tools::getValue('id_order'));

                    //AsmLog::info('codigo envio: ' . $asm_track_value['codigo_envio']."\n\r");

                    if($_SESSION["ultimoErrorASM"] ==  "") {
                        $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                        $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                        $smarty->assign('asm_state','4');
                        $smarty->assign('asm_pdf_down', $path_download_pdf);
                        $smarty->assign('asm_html_down', $path_download_html);
                        $smarty->assign('asm_pedido', Tools::getValue('id_order'));
                        $smarty->assign('referencia', $orderTemp->reference);
                        $smarty->assign('asm_n_envio', $asm_track_value['id_envio_order']);
                        $smarty->assign('asm_codigo_envio', $asm_track_value['codigo_envio']);
                        $smarty->assign('asm_download', 'Pulse aqui descargar');
                        $smarty->assign('asm_pdf_txt', 'Etiqueta de transporte (PDF)');
                        $smarty->assign('asm_html_txt', 'Etiqueta de transporte (ventana nueva)');
                        $smarty->assign('asm_seguimiento_envio', 'Realizar el seguimiento del envío');
                        $smarty->assign('asm_seguimiento_envio_url', $asm_track_value['url_track']);
                        $smarty->assign('asm_success_msg', 'Se ha generado la etiqueta con éxito');

                        return $this->display(__FILE__, 'templates/adminOKOrder.tpl');

                    } else {
                        $smarty->assign('mensaje', $_SESSION["ultimoErrorASM"]);
                        $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                        $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                        $smarty->assign('asm_state','1');

                        return $this->display(__FILE__, 'templates/adminErrorOrder.tpl');
                    }
				} else {
                    $smarty->assign('mensaje','Para generar una etiqueta debe cambiar el estado de su pedido.');
                    $smarty->assign('bultos_info', 'El caso más frecuente es que el pedido este pendiente la aprobación del pago debido a métodos de pago como transferencias bancarias, etc.');
                    $smarty->assign('asm_lopeta', $this->displayName.' - Etiquetas de envio para clientes');
                    $smarty->assign('asm_version', $this->description.' v. '.$this->version);
                    $smarty->assign('asm_state','1');

                    return $this->display(__FILE__, 'templates/adminNoOrder.tpl');
				}
			}
	}
	
    /*
	** Función que permite comprobar si el transporte pertenece a ASM
	**
    */
	
	function isCarrierASM($id_order) {
		
		$stateTrans = Configuration::get('MYCARRIER1_CARRIER_ID');
		$stateTransA = Configuration::get('MYCARRIER2_CARRIER_ID');
		$stateTransB = Configuration::get('MYCARRIER3_CARRIER_ID');
		$stateTransC = Configuration::get('MYCARRIER4_CARRIER_ID');
		$arrST = explode(',',$stateTrans);
		$arrSTA = explode(',',$stateTransA);
		$arrSTB = explode(',',$stateTransB);
		$arrSTC = explode(',',$stateTransC);
		
		$query = 'SELECT id_carrier FROM  '._DB_PREFIX_.'orders WHERE  id_order = '.$id_order;
		$rowCarrier = Db::getInstance()->getRow($query);
		if(in_array($rowCarrier['id_carrier'], $arrST) 
			|| in_array($rowCarrier['id_carrier'], $arrSTA) 
			|| in_array($rowCarrier['id_carrier'], $arrSTB) 
			|| in_array($rowCarrier['id_carrier'], $arrSTC)) {
			return true;
		}
		else { return false; }
	}

    function isCarrierASMNonModule ($id_carrier) {
        if (Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM10') == $id_carrier || Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM14') == $id_carrier || Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM24') == $id_carrier || Configuration::get('ASM_SERVICIO_SELECCIONADO_ASMECO') == $id_carrier) {
            return true;
        } else {
            return false;
        }
    }

    function isASMModule () {
        $val = Configuration::get('ASM_PRESTAMODULO');

        if (!function_exists('boolval')) {
            return (bool) $val;
        } else {
            return boolval($val);
        }
    }



    /*
	** Hook update carrier
	**
    */

    public function hookupdateCarrier($params) {
        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER1_CARRIER_ID')))
            Configuration::updateValue('MYCARRIER1_CARRIER_ID', (int)($params['carrier']->id));
        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER2_CARRIER_ID')))
            Configuration::updateValue('MYCARRIER2_CARRIER_ID', (int)($params['carrier']->id));
        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER3_CARRIER_ID')))
            Configuration::updateValue('MYCARRIER3_CARRIER_ID', (int)($params['carrier']->id));
        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER4_CARRIER_ID')))
            Configuration::updateValue('MYCARRIER4_CARRIER_ID', (int)($params['carrier']->id));
    }




    /*
	** Front Methods
	**
	** If you set need_range at true when you created your carrier (in install method), the method called by the cart will be getOrderShippingCost
	** If not, the method called will be getOrderShippingCostExternal
	**
	** $params var contains the cart, the customer, the address
	** $shipping_cost var contains the price calculated by the range in carrier tab
	**
    */

    public function getOrderShippingCost($params, $shipping_cost) {

    	global $cart;
        global $smarty;

        if($this->isASMModule()) return false;

    	//obtenemos el tipo de tarifas 0=por peso 1=por carrito  2=webservice
    	$tipo_tarifa = Configuration::get('ASM_CALCULAR_PRECIO');
    	//obtenemos el costo total del carrito o el número de artículos

        if(Configuration::get('ASM_ENVIO_GRAT_TIPO')==0)
        {
            $total = $params->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
            if(Configuration::get('ASM_MANIPULACION') == 'F'){
                $coste_manipulacion = floatval(Configuration::get('ASM_COSTE_MANIPULACION'));
            }
            if(Configuration::get('ASM_MANIPULACION') == 'V'){
                $coste_manipulacion = floatval($total*(Configuration::get('ASM_COSTE_MANIPULACION')/100));
            }
        } else {
            $total = $params->nbProducts();
            //AsmLog::info("número de artículos: " . $total);
        }

    	$peso = $params->getTotalWeight();
    	if($peso<1){
    		$peso=1;
    	}

    	//obtenemos los datos del usuario
    	$usuario_direccion_id = $params->id_address_delivery;
    	$query = 'SELECT * FROM '._DB_PREFIX_.'address where id_address = "'.$usuario_direccion_id.'"';
    	$usuario_datos = Db::getInstance()->ExecuteS($query);
    	$query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$usuario_datos[0]['id_country'].'"';
    	$usuario_pais_id = Db::getInstance()->ExecuteS($query);
    	$usuario_pais = $usuario_pais_id[0]['iso_code'];
    	$usuario_cp =$usuario_datos[0]['postcode'];

        //AsmLog::info("usuario_cp antes: " . $usuario_cp);
        //AsmLog::info("usuario_pais antes: " . $usuario_pais);

        //Dani a 10/09/2014 Ñapa para detectar un código postal de portugal
        $pos = strpos($usuario_cp, "-");

        //AsmLog::info("pos: " . $pos);

        if ($pos === false) {
            $nohacernadadenada=1;
        } else {
           $usuario_pais = "PT";
        }

        $usuario_cp = str_replace("-", "", $usuario_cp);
        $usuario_cp = str_replace(" ", "", $usuario_cp);

        //AsmLog::info("usuario_cp despues: " . $usuario_cp);
        //AsmLog::info("usuario_pais despues: " . $usuario_pais);

    	//Hay que agregar el IVA???
    	$iva = 0;

        // TaxRules si la versión es superior a 1.5
        if((version_compare(_PS_VERSION_, '1.5') >= 0) && !$this->isASMModule()) {
        //echo $this->agregar_impuesto($usuario_pais);
    	    $iva = 0;
        } else {
            if($this->agregar_impuesto($usuario_pais)){
                $iva = Configuration::get('ASM_IMPUESTO');
            }
        }
    	//Cargamos las tarifas del CSV
    	$tarifas = $this->tarifas();
    	$tarifas2 = $this->tarifas2();

    	//Preparamos los demas parametros
        if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
        	$coste_fijo_envio = floatval(Configuration::get('ASM_COSTE_FIJO_ENVIO'));
        }

        /*
        if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
            $coste_margen = floatval(Configuration::get('ASM_MARGEN_COSTE_ENVIO'));
        }
        */

        // This example returns shipping cost with overcost set in the back-office, but you can call a webservice or calculate what you want before returning the final value to the Cart

        // Filtramos para ASM10

        //AsmLog::info ("idcarrier: " . $this->id_carrier . "\n\r");
        //AsmLog::info ("MYCARRIER1_CARRIER_ID: " . Configuration::get('MYCARRIER1_CARRIER_ID') . "\n\r");
        //AsmLog::info ("MYCARRIER2_CARRIER_ID: " . Configuration::get('MYCARRIER2_CARRIER_ID') . "\n\r");
        //AsmLog::info ("MYCARRIER3_CARRIER_ID: " . Configuration::get('MYCARRIER3_CARRIER_ID') . "\n\r");
        //AsmLog::info ("MYCARRIER4_CARRIER_ID: " . Configuration::get('MYCARRIER4_CARRIER_ID') . "\n\r");

        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER1_CARRIER_ID')) && ($usuario_pais=="ES" || $usuario_pais=="PT")){
            $servicio = 1;
            $horario  = 0;
            //AsmLog::info("Filtramos por ASM10\n\r");
        	// Es gratuito???

            if($tipo_tarifa == 0){
                $hayDestino = $this->existeDestino($tarifas, "ASM10", $usuario_pais, $usuario_cp);
          	} else if($tipo_tarifa == 1){
                $hayDestino = $this->existeDestino($tarifas2, "ASM10", $usuario_pais, $usuario_cp);
            }

            if($hayDestino == 0) return false;


        	if(Configuration::get('ASM_ENVIO_GRAT') && $total >= Configuration::get('ASM_IMP_MIN_ENVIO_GRA') && Configuration::get('ASM_IMP_MIN_ENVIO_GRA') != ""){

        		if(Configuration::get('ASM_SERVICIO_GRAT') == 'ASM10'){
        			return 0;
        		}
                /*
        		// Esta habilitado ver resto de servicios
        		if(Configuration::get('ASM_RESTO') && Configuration::get('ASM_SERVICIO_GRAT') == 'ASM24'){
        			$importe = 0;
        			$subimporte=0;
        			if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
        				$subimporte = $coste_fijo_envio;
        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
        				$importe = floatval($coste_envio + $coste_manipulacion);
        			}
        			else{
        				//Si no hay coste_fijo buscamos en el csv el precio
        				//necesitamos el tipo_servicio, cp_cliente, pais y peso
        				if($tipo_tarifa == 0){
                            $coste_envio = $this->dame_tarifa($tarifas, "ASM10", $usuario_pais, $usuario_cp, $peso);
        				} else if ($tipo_tarifa == 1){
                            $coste_envio = $this->dame_tarifa2($tarifas2, "ASM10", $usuario_pais, $usuario_cp, $total);
        				} else {
        				    //Dani Llamada al webservice de valoración
                            $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                            $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                        	$tienda_pais_id = Db::getInstance()->ExecuteS($query);
                        	$tienda_pais = $tienda_pais_id[0]['iso_code'];

                            $bultos   = 1;
                            $peso     = $peso;
                            $cpOrig   = $vendedor['PS_SHOP_CODE'];
                            $cpDest   = $usuario_cp;
                            $paisOrig = $tienda_pais;
                            $paisDest = $usuario_pais;

                            $errPais='';

                            if($paisOrig == 'ES') {
                              $paisOrig = '34';
                            } else if($paisOrig == 'PT'){
                              $paisOrig = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                                if($paisDest == 'ES') {
                                  $paisDest = '34';
                                } else if($paisDest == 'PT'){
                                  $paisDest = '351';
                                } else {
                                  $errPais = 'Solamente se admiten envíos a España y Portugal';
                                }

                                if($errPais=='') {
                                   $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                                } else {
                                  ?>
                                       <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                     <?php
                                  return false;
                                }
                            } else {
                              ?>
                                 <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                               <?php
                            return false;
                            }

        				}
        				$subimporte=$coste_envio;
        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO
        				if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
        					$coste_envio=$coste_envio+$coste_margen;
        				}
        				$coste_envio = $coste_envio +($coste_envio*$iva);
        				$importe = floatval($coste_envio+$coste_manipulacion);
        			}
        			//formateamos el importe
        			$importe = number_format($importe,2,".","");
        			return (float)$importe;
        		}
        		return false;
                */
        	}
            // No es gratuito
            //AsmLog:info("configuration: ".Configuration::get('ASM_ASM10')."\n\r");


            if(Configuration::get('ASM_ASM10')){
                //$totala = $params->getOrderTotal(true, Cart::ONLY_SHIPPING);
                $importe = 0;
                $subimporte=0;

                $total = $params->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
                if(Configuration::get('ASM_MANIPULACION') == 'F'){
                    $coste_manipulacion = floatval(Configuration::get('ASM_COSTE_MANIPULACION'));
                }
                if(Configuration::get('ASM_MANIPULACION') == 'V'){
                    $coste_manipulacion = floatval($total*(Configuration::get('ASM_COSTE_MANIPULACION')/100));
                }

                if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
                    $subimporte = $coste_fijo_envio;
                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
                    $importe = floatval($coste_envio + $coste_manipulacion);
                }
                else{
                    //Si no hay coste_fijo buscamos en el csv el precio
                    //necesitamos el tipo_servicio, cp_cliente, pais y peso
                	if($tipo_tarifa == 0){
                	    $coste_envio = $this->dame_tarifa($tarifas, "ASM10", $usuario_pais, $usuario_cp, $peso);
                	} else if($tipo_tarifa == 1){
                        $coste_envio = $this->dame_tarifa2($tarifas2, "ASM10", $usuario_pais, $usuario_cp, $total);
                	} else {
                        //Dani Llamada al webservice de valoración
                        $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                        $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                        $tienda_pais_id = Db::getInstance()->ExecuteS($query);
                        $tienda_pais = $tienda_pais_id[0]['iso_code'];

                        $bultos   = 1;
                        $peso     = $peso;
                        $cpOrig   = $vendedor['PS_SHOP_CODE'];
                        $cpDest   = $usuario_cp;
                        $paisOrig = $tienda_pais;
                        $paisDest = $usuario_pais;

                        $errPais='';

                        if($paisOrig == 'ES') {
                          $paisOrig = '34';
                        } else if($paisOrig == 'PT'){
                          $paisOrig = '351';
                        } else {
                          $errPais = 'Solamente se admiten envíos a España y Portugal';
                        }

                        if($errPais=='') {
                            if($paisDest == 'ES') {
                              $paisDest = '34';
                            } else if($paisDest == 'PT'){
                              $paisDest = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                               $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                            } else {
                              ?>
                                   <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                 <?php
                              return false;
                            }
                        } else {
                          ?>
                             <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                           <?php
                        return false;
                        }
                	}
                    $subimporte=$coste_envio;
                    /*
                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO
                    if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
                        $coste_envio=$coste_envio+$coste_margen;
                    }
                    */
                    $coste_envio = $coste_envio +($coste_envio*$iva);
                    $importe = floatval($coste_envio+$coste_manipulacion);
                }
                //formateamos el importe
                $importe = $importe + $shipping_cost;
                $importe = number_format($importe,2,".","");
                return (float)$importe;
            }
            return false;
        }
        // Filtramos para ASM14
        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER2_CARRIER_ID')) && ($usuario_pais=="ES" || $usuario_pais=="PT")){
            $servicio = 1;
            $horario  = 2;
            //AsmLog::info("Filtramos por ASM14\n\r");
        	// Es gratuito???

            if($tipo_tarifa == 0){
                $hayDestino = $this->existeDestino($tarifas, "ASM14", $usuario_pais, $usuario_cp);
          	} else if($tipo_tarifa == 1){
                $hayDestino = $this->existeDestino($tarifas2, "ASM14", $usuario_pais, $usuario_cp);
            }

            if($hayDestino == 0) return false;


        	if(Configuration::get('ASM_ENVIO_GRAT') && $total >= Configuration::get('ASM_IMP_MIN_ENVIO_GRA') && Configuration::get('ASM_IMP_MIN_ENVIO_GRA') != ""){
        		if(Configuration::get('ASM_SERVICIO_GRAT') == 'ASM14'){
        			return 0;
        		}
                /*
        		// Esta habilitado ver resto de servicios
        		if(Configuration::get('ASM_RESTO') && Configuration::get('ASM_SERVICIO_GRAT') == 'ASM10'){
        			$importe = 0;
        			$subimporte=0;
        			if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
        				$subimporte = $coste_fijo_envio;
        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
        				$importe = floatval($coste_envio + $coste_manipulacion);
        			}
        			else{
        				//Si no hay coste_fijo buscamos en el csv el precio
        				//necesitamos el tipo_servicio, cp_cliente, pais y peso
        				if($tipo_tarifa == 0){
        				    $coste_envio = $this->dame_tarifa($tarifas, "ASM24", $usuario_pais, $usuario_cp, $peso);
        				} else if($tipo_tarifa == 1){
                            $coste_envio = $this->dame_tarifa2($tarifas2, "ASM24", $usuario_pais, $usuario_cp, $total);
        				} else {
                            //Dani Llamada al webservice de valoración
                            $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                            $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                            $tienda_pais_id = Db::getInstance()->ExecuteS($query);
                            $tienda_pais = $tienda_pais_id[0]['iso_code'];

                            $bultos   = 1;
                            $peso     = $peso;
                            $cpOrig   = $vendedor['PS_SHOP_CODE'];
                            $cpDest   = $usuario_cp;
                            $paisOrig = $tienda_pais;
                            $paisDest = $usuario_pais;

                            $errPais='';

                            if($paisOrig == 'ES') {
                              $paisOrig = '34';
                            } else if($paisOrig == 'PT'){
                              $paisOrig = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                                if($paisDest == 'ES') {
                                  $paisDest = '34';
                                } else if($paisDest == 'PT'){
                                  $paisDest = '351';
                                } else {
                                  $errPais = 'Solamente se admiten envíos a España y Portugal';
                                }

                                if($errPais=='') {
                                   $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                                } else {
                                  ?>
                                       <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                     <?php
                                  return false;
                                }
                            } else {
                              ?>
                                 <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                               <?php
                            return false;
                            }
        				}
        				$subimporte=$coste_envio;
        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO
        				if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
        					$coste_envio=$coste_envio+$coste_margen;
        				}
        				$coste_envio = $coste_envio +($coste_envio*$iva);
        				$importe = floatval($coste_envio+$coste_manipulacion);
        			}
        			//formateamos el importe
        			$importe = number_format($importe,2,".","");
        			return (float)$importe;
        		}
        		return false;
                */
        	}
            // No es gratuito
            if(Configuration::get('ASM_ASM14')){
                $importe = 0;
                $subimporte=0;

                $total = $params->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
                if(Configuration::get('ASM_MANIPULACION') == 'F'){
                    $coste_manipulacion = floatval(Configuration::get('ASM_COSTE_MANIPULACION'));
                }
                if(Configuration::get('ASM_MANIPULACION') == 'V'){
                    $coste_manipulacion = floatval($total*(Configuration::get('ASM_COSTE_MANIPULACION')/100));
                }

                if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
                    $subimporte = $coste_fijo_envio;
                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
                    $importe = floatval($coste_envio + $coste_manipulacion);
                }
                else{
                    //Si no hay coste_fijo buscamos en el csv el precio
                    //necesitamos el tipo_servicio, cp_cliente, pais y peso
                	if($tipo_tarifa == 0){
                        $coste_envio = $this->dame_tarifa($tarifas, "ASM14", $usuario_pais, $usuario_cp, $peso);
                	} else if($tipo_tarifa == 1){
                        $coste_envio = $this->dame_tarifa2($tarifas2, "ASM14", $usuario_pais, $usuario_cp, $total);
                	} else {
                        //Dani Llamada al webservice de valoración
                        $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                        $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                        $tienda_pais_id = Db::getInstance()->ExecuteS($query);
                        $tienda_pais = $tienda_pais_id[0]['iso_code'];

                        $bultos   = 1;
                        $peso     = $peso;
                        $cpOrig   = $vendedor['PS_SHOP_CODE'];
                        $cpDest   = $usuario_cp;
                        $paisOrig = $tienda_pais;
                        $paisDest = $usuario_pais;
                        $errPais='';

                        if($paisOrig == 'ES') {
                          $paisOrig = '34';
                        } else if($paisOrig == 'PT'){
                          $paisOrig = '351';
                        } else {
                          $errPais = 'Solamente se admiten envíos a España y Portugal';
                        }

                        if($errPais=='') {
                            if($paisDest == 'ES') {
                              $paisDest = '34';
                            } else if($paisDest == 'PT'){
                              $paisDest = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                               $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                            } else {
                              ?>
                                   <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                 <?php
                              return false;
                            }
                        } else {
                          ?>
                             <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                           <?php
                        return false;
                        }
                	}
                	$subimporte=$coste_envio;
                    /*
                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO
                    if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
                        $coste_envio=$coste_envio+$coste_margen;
                    }
                    */
                    $coste_envio = $coste_envio +($coste_envio*$iva);
                    $importe = floatval($coste_envio+$coste_manipulacion);
                }
                //formateamos el importe
                $importe = $importe + $shipping_cost;
                $importe = number_format($importe,2,".","");
                return (float)$importe;
            }
            return false;
        }

        // Filtramos para ASM24
        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER3_CARRIER_ID')) && ($usuario_pais=="ES" || $usuario_pais=="PT")){
            $servicio = 1;
            $horario  = 3;
            //AsmLog::info("Filtramos por ASM24\n\r");
        	// Es gratuito???

            if($tipo_tarifa == 0){
                $hayDestino = $this->existeDestino($tarifas, "ASM24", $usuario_pais, $usuario_cp);
          	} else if($tipo_tarifa == 1){
                $hayDestino = $this->existeDestino($tarifas2, "ASM24", $usuario_pais, $usuario_cp);
            }

            if($hayDestino == 0) return false;


        	if(Configuration::get('ASM_ENVIO_GRAT') && $total >= Configuration::get('ASM_IMP_MIN_ENVIO_GRA') && Configuration::get('ASM_IMP_MIN_ENVIO_GRA') != ""){
        		if(Configuration::get('ASM_SERVICIO_GRAT') == 'ASM24'){
        			return 0;
        		}
                /*
        		// Esta habilitado ver resto de servicios
        		if(Configuration::get('ASM_RESTO') && Configuration::get('ASM_SERVICIO_GRAT') == 'ASM10'){
        			$importe = 0;
        			$subimporte=0;
        			if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
        				$subimporte = $coste_fijo_envio;
        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
        				$importe = floatval($coste_envio + $coste_manipulacion);
        			}
        			else{
        				//Si no hay coste_fijo buscamos en el csv el precio
        				//necesitamos el tipo_servicio, cp_cliente, pais y peso
        				if($tipo_tarifa == 0){
        				    $coste_envio = $this->dame_tarifa($tarifas, "ASM24", $usuario_pais, $usuario_cp, $peso);
        				} else if($tipo_tarifa == 1){
                            $coste_envio = $this->dame_tarifa2($tarifas2, "ASM24", $usuario_pais, $usuario_cp, $total);
        				} else {
                            //Dani Llamada al webservice de valoración
                            $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                            $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                            $tienda_pais_id = Db::getInstance()->ExecuteS($query);
                            $tienda_pais = $tienda_pais_id[0]['iso_code'];

                            $bultos   = 1;
                            $peso     = $peso;
                            $cpOrig   = $vendedor['PS_SHOP_CODE'];
                            $cpDest   = $usuario_cp;
                            $paisOrig = $tienda_pais;
                            $paisDest = $usuario_pais;

                            $errPais='';

                            if($paisOrig == 'ES') {
                              $paisOrig = '34';
                            } else if($paisOrig == 'PT'){
                              $paisOrig = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                                if($paisDest == 'ES') {
                                  $paisDest = '34';
                                } else if($paisDest == 'PT'){
                                  $paisDest = '351';
                                } else {
                                  $errPais = 'Solamente se admiten envíos a España y Portugal';
                                }

                                if($errPais=='') {
                                   $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                                } else {
                                  ?>
                                       <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                     <?php
                                  return false;
                                }
                            } else {
                              ?>
                                 <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                               <?php
                            return false;
                            }
        				}
        				$subimporte=$coste_envio;
        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO
        				if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
        					$coste_envio=$coste_envio+$coste_margen;
        				}
        				$coste_envio = $coste_envio +($coste_envio*$iva);
        				$importe = floatval($coste_envio+$coste_manipulacion);
        			}
        			//formateamos el importe
        			$importe = number_format($importe,2,".","");
        			return (float)$importe;
        		}
        		return false;
                */
        	}
            // No es gratuito
            if(Configuration::get('ASM_ASM24')){
                $importe = 0;
                $subimporte=0;

                $total = $params->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
                if(Configuration::get('ASM_MANIPULACION') == 'F'){
                    $coste_manipulacion = floatval(Configuration::get('ASM_COSTE_MANIPULACION'));
                }
                if(Configuration::get('ASM_MANIPULACION') == 'V'){
                    $coste_manipulacion = floatval($total*(Configuration::get('ASM_COSTE_MANIPULACION')/100));
                }

                if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
                    $subimporte = $coste_fijo_envio;
                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
                    $importe = floatval($coste_envio + $coste_manipulacion);
                }
                else{
                    //Si no hay coste_fijo buscamos en el csv el precio
                    //necesitamos el tipo_servicio, cp_cliente, pais y peso
                	if($tipo_tarifa == 0){
                        $coste_envio = $this->dame_tarifa($tarifas, "ASM24", $usuario_pais, $usuario_cp, $peso);
                	} else if($tipo_tarifa == 1){
                        $coste_envio = $this->dame_tarifa2($tarifas2, "ASM24", $usuario_pais, $usuario_cp, $total);
                	} else {
                        //Dani Llamada al webservice de valoración
                        $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                        $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                        $tienda_pais_id = Db::getInstance()->ExecuteS($query);
                        $tienda_pais = $tienda_pais_id[0]['iso_code'];

                        $bultos   = 1;
                        $peso     = $peso;
                        $cpOrig   = $vendedor['PS_SHOP_CODE'];
                        $cpDest   = $usuario_cp;
                        $paisOrig = $tienda_pais;
                        $paisDest = $usuario_pais;
                        $errPais='';

                        if($paisOrig == 'ES') {
                          $paisOrig = '34';
                        } else if($paisOrig == 'PT'){
                          $paisOrig = '351';
                        } else {
                          $errPais = 'Solamente se admiten envíos a España y Portugal';
                        }

                        if($errPais=='') {
                            if($paisDest == 'ES') {
                              $paisDest = '34';
                            } else if($paisDest == 'PT'){
                              $paisDest = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                               $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                            } else {
                              ?>
                                   <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                 <?php
                              return false;
                            }
                        } else {
                          ?>
                             <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                           <?php
                        return false;
                        }
                	}
                	$subimporte=$coste_envio;
                    /*
                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO
                    if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
                        $coste_envio=$coste_envio+$coste_margen;
                    }
                    */
                    $coste_envio = $coste_envio +($coste_envio*$iva);
                    $importe = floatval($coste_envio+$coste_manipulacion);
                }
                //formateamos el importe
                $importe = $importe + $shipping_cost;
                $importe = number_format($importe,2,".","");
                return (float)$importe;
            }
            return false;
        }

        // Filtramos para ECONOMY
        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER4_CARRIER_ID')) && ($usuario_pais=="ES" || $usuario_pais=="PT")){
            $servicio = 37;
            $horario  = 16;
            //AsmLog::info("Filtramos por ECONOMY\n\r");

            //Dani a 08/01/2013 No se permiten los envíos a baleares con el servicio ECONOMY
            //Dani a 15/02/203 Para las tarifas de 2013 este servicio está activo para baleares
            //if(substr($usuario_cp,0,2)=="07" && $usuario_pais=="ES")
            //{
            //    return false;
            //}

        	// Es gratuito???

            if($tipo_tarifa == 0){
                $hayDestino = $this->existeDestino($tarifas, "ECONOMY", $usuario_pais, $usuario_cp);
          	} else if($tipo_tarifa == 1){
                $hayDestino = $this->existeDestino($tarifas2, "ECONOMY", $usuario_pais, $usuario_cp);
            }

            if($hayDestino == 0) return false;


        	if(Configuration::get('ASM_ENVIO_GRAT') && $total >= Configuration::get('ASM_IMP_MIN_ENVIO_GRA') && Configuration::get('ASM_IMP_MIN_ENVIO_GRA') != ""){
        		if(Configuration::get('ASM_SERVICIO_GRAT') == 'ECONOMY'){
        			return 0;
        		}
                /*
        		// Esta habilitado ver resto de servicios
                //Dani hay que revisar esto. no lo acabo de pillar??
        		if(Configuration::get('ASM_RESTO') && Configuration::get('ASM_SERVICIO_GRAT') == 'ASM10'){
        			$importe = 0;
        			$subimporte=0;
        			if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
        				$subimporte = $coste_fijo_envio;
        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
        				$importe = floatval($coste_envio + $coste_manipulacion);
        			}
        			else{
        				//Si no hay coste_fijo buscamos en el csv el precio
        				//necesitamos el tipo_servicio, cp_cliente, pais y peso
        				if($tipo_tarifa == 0){
        				    $coste_envio = $this->dame_tarifa($tarifas, "ECONOMY", $usuario_pais, $usuario_cp, $peso);
        				} else if($tipo_tarifa == 1){
                            $coste_envio = $this->dame_tarifa2($tarifas2, "ECONOMY", $usuario_pais, $usuario_cp, $total);
        				} else {
                            //Dani Llamada al webservice de valoración
                            $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                            $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                            $tienda_pais_id = Db::getInstance()->ExecuteS($query);
                            $tienda_pais = $tienda_pais_id[0]['iso_code'];

                            $bultos   = 1;
                            $peso     = $peso;
                            $cpOrig   = $vendedor['PS_SHOP_CODE'];
                            $cpDest   = $usuario_cp;
                            $paisOrig = $tienda_pais;
                            $paisDest = $usuario_pais;
                            $errPais='';

                            if($paisOrig == 'ES') {
                              $paisOrig = '34';
                            } else if($paisOrig == 'PT'){
                              $paisOrig = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                                if($paisDest == 'ES') {
                                  $paisDest = '34';
                                } else if($paisDest == 'PT'){
                                  $paisDest = '351';
                                } else {
                                  $errPais = 'Solamente se admiten envíos a España y Portugal';
                                }

                                if($errPais=='') {
                                   $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                                } else {
                                  ?>
                                       <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                     <?php
                                  return false;
                                }
                            } else {
                              ?>
                                 <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                               <?php
                            return false;
                            }


        				}
        				$subimporte=$coste_envio;
        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO
        				if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
        					$coste_envio=$coste_envio+$coste_margen;
        				}
        				$coste_envio = $coste_envio +($coste_envio*$iva);
        				$importe = floatval($coste_envio+$coste_manipulacion);
        			}
        			//formateamos el importe
        			$importe = number_format($importe,2,".","");
        			return (float)$importe;
        		}
        		return false;
                */
        	}
            // No es gratuito
            if(Configuration::get('ASM_ECONOMY')){
                $importe = 0;
                $subimporte=0;

                $total = $params->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
                if(Configuration::get('ASM_MANIPULACION') == 'F'){
                    $coste_manipulacion = floatval(Configuration::get('ASM_COSTE_MANIPULACION'));
                }
                if(Configuration::get('ASM_MANIPULACION') == 'V'){
                    $coste_manipulacion = floatval($total*(Configuration::get('ASM_COSTE_MANIPULACION')/100));
                }

                if(Configuration::get('ASM_COSTE_FIJO_ENVIO')){
                    $subimporte = $coste_fijo_envio;
                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);
                    $importe = floatval($coste_envio + $coste_manipulacion);
                }
                else{
                    //Si no hay coste_fijo buscamos en el csv el precio
                    //necesitamos el tipo_servicio, cp_cliente, pais y peso
                	if($tipo_tarifa == 0){
                        $coste_envio = $this->dame_tarifa($tarifas, "ECONOMY", $usuario_pais, $usuario_cp, $peso);
                	} else if($tipo_tarifa == 1){
                        $coste_envio = $this->dame_tarifa2($tarifas2, "ECONOMY", $usuario_pais, $usuario_cp, $total);
                	} else {
                        //Dani Llamada al webservice de valoración
                        $vendedor = Configuration::getMultiple(array('PS_SHOP_COUNTRY_ID','PS_SHOP_CODE'));
                        $query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
                        $tienda_pais_id = Db::getInstance()->ExecuteS($query);
                        $tienda_pais = $tienda_pais_id[0]['iso_code'];

                        $bultos   = 1;
                        $peso     = $peso;
                        $cpOrig   = $vendedor['PS_SHOP_CODE'];
                        $cpDest   = $usuario_cp;
                        $paisOrig = $tienda_pais;
                        $paisDest = $usuario_pais;

                        $html_error='<p>Error</p>';

                        $errPais='';

                        if($paisOrig == 'ES') {
                          $paisOrig = '34';
                        } else if($paisOrig == 'PT'){
                          $paisOrig = '351';
                        } else {
                          $errPais = 'Solamente se admiten envíos a España y Portugal';
                        }

                        if($errPais=='') {
                            if($paisDest == 'ES') {
                              $paisDest = '34';
                            } else if($paisDest == 'PT'){
                              $paisDest = '351';
                            } else {
                              $errPais = 'Solamente se admiten envíos a España y Portugal';
                            }

                            if($errPais=='') {
                               $coste_envio = $this->valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest);
                            } else {
                              ?>
                                   <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                                 <?php
                              return false;
                            }
                        } else {
                          ?>
                             <script>alert("No se permiten envíos fuera de España y Portugal");</script>
                           <?php
                        return false;
                        }
                	}
                	$subimporte=$coste_envio;
                    /*
                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO
                    if(Configuration::get('ASM_MARGEN_COSTE_ENVIO')){
                        $coste_envio=$coste_envio+$coste_margen;
                    }
                    */
                    $coste_envio = $coste_envio +($coste_envio*$iva);
                    $importe = floatval($coste_envio+$coste_manipulacion);
                }
                //formateamos el importe
                $importe = $importe + $shipping_cost;
                $importe = number_format($importe,2,".","");
                return (float)$importe;
            }
            return false;
        }

        // If the carrier is not known, you can return false, the carrier won't appear in the order process
        return false;
    }

    public function getOrderShippingCostExternal($params) {}

    function agregar_impuesto($pais){

        $paises = Array("AT","BE","BG","CC","CY","CZ","DK","EE","FI",
                        "FR","DE","GR","HU","IE","IT","LV","LT","LU",
                        "MT","NL","PL","PT","RO","SK","SI","ES","SE","GB");
        $max=count($paises);
        for($i=0;$i<$max;$i++){
            if($pais == $paises[$i]){
                return true;
            }
        }
        return false;
    }
    
    // Esta funcion devuelve un array con las tarifas segun PESO pedido
    protected function tarifas(){
    	$archivo = _PS_MODULE_DIR_.'asmcarrier/asm.tarifas.peso.csv';

        $tarifas = Array();

        if($fp = fopen ( $archivo , "r" )){
            while (( $data = fgetcsv ( $fp , 1000 , ";" )) !== FALSE ) { // Mientras hay líneas que leer...
                $tarifas[] = Array( "servicio"      => $data[0],
                                    "pais"          => $data[1],
                                    "cp_origen"     => $data[2],
                                    "cp_destino"    => $data[3],
                                    "peso"          => $data[4],
                                    "importe"       => $data[5]);
            }
            fclose ( $fp );
            return $tarifas;
        }
        else{
            return false;
        }
    }

    // Esta funcion devuelve un array con las tarifas segun IMPORTE DEL CARRITO DE COMPRAS pedido
    protected function tarifas2(){
    	$archivo = _PS_MODULE_DIR_.'asmcarrier/asm.tarifas.importe.csv';

    	$tarifas = Array();

    	if($fp = fopen ( $archivo , "r" )){
    		while (( $data = fgetcsv ( $fp , 1000 , ";" )) !== FALSE ) { // Mientras hay líneas que leer...
    			$tarifas[] = Array( "servicio"      => $data[0],
    					"pais"          => $data[1],
    					"cp_origen"     => $data[2],
    					"cp_destino"    => $data[3],
    					"precio_carrito"=> $data[4],
    					"importe"       => $data[5]);
    		}
    		fclose ( $fp );
    		return $tarifas;
    	}
    	else{
    		return false;
    	}
    }
    // Devulve la tarifa segun el peso del pedido
    function dame_tarifa($tarifas,$servicio,$pais,$cp,$peso){
        $max=count($tarifas);
        $cp=intval($cp);
        $peso=ceil($peso); //redondeo para arriba
        $segmento = Array();

        for($i=1;$i<$max;$i++){
            // Si es un envio para ES-PT-AD
            //if($servicio == 'ASM10' || $servicio == 'ASM14' || $servicio == 'ASM24' || $servicio == 'ECONOMY'){
                if($tarifas[$i]['servicio'] == $servicio){
                    if($tarifas[$i]['pais'] == $pais){
                        $cp_origen=intval($tarifas[$i]['cp_origen']);
                        $cp_destino=intval($tarifas[$i]['cp_destino']);
                        if($cp >= $cp_origen){
                            if($cp <= $cp_destino){
                                $segmento[]=Array("peso" => floatval($tarifas[$i]['peso']),"precio" => floatval($tarifas[$i]['importe']));
                            }
                        }
                    }
                }
            //}
        }
        // ya tenemos el segmento

        // Metodo para ordenar arrays con arrays asociativos dentro
        if(!function_exists('ordenar')){
            function ordenar($x, $y){
                if ( $x['peso'] == $y['peso'] ){
                    return 0;
                }
                //ordenar de menor a mayor
                else if ( $x['peso'] < $y['peso'] ){
                    return -1;
                }
                else{
                    return 1;
                }
            }
        }

        // Ordenamos el segmento
        usort($segmento,'ordenar');
        // Preparamos los datos para el peso minimo y maximo
        $precio_envio = 0;
        $max=count($segmento);
        $peso_min = floatval($segmento[0]['peso']);
        $precio_min = floatval($segmento[0]['precio']);
        $peso_max = floatval($segmento[$max-2]['peso']);
        $precio_max = floatval($segmento[$max-2]['precio']);
        $precio_despues_max = floatval($segmento[$max-1]['precio']);

        if($peso <= $peso_min){
            $precio_envio = $precio_min;
        }
        else if($peso >= $peso_max){
            $peso_restante = $peso-$peso_max;
            $precio_restante = $peso_restante*$precio_despues_max;
            $precio_envio = $precio_max+$precio_restante;
        }
        else{
            for($i=0;$i<$max;$i++){
                if($peso != $segmento[$i]['peso']){
                    if($peso < $segmento[$i]['peso']){
                        $precio_envio = $segmento[$i]['precio'];
                        $i=$max;
                    }
                }
                else{ //es igual
                    $precio_envio = $segmento[$i]['precio'];
                    $i=$max;
                }
            }
        }
        return $precio_envio;
    }

    // Devulve la tarifa segun el importe del carrito
    function dame_tarifa2($tarifas,$servicio,$pais,$cp,$importe_carrito){
    	$max=count($tarifas);
    	$cp=intval($cp);
    	$importe_carrito=ceil($importe_carrito); //redondeo para arriba
    	$segmento = Array();

    	for($i=1;$i<$max;$i++){
    		// Si es un envio para ES-PT-AD
    		//if($servicio == 'ASM10' || $servicio == 'ASM14' || $servicio == 'ASM24' || $servicio == 'ECONOMY'){
    			if($tarifas[$i]['servicio'] == $servicio){
    				if($tarifas[$i]['pais'] == $pais){
    					$cp_origen=intval($tarifas[$i]['cp_origen']);
    					$cp_destino=intval($tarifas[$i]['cp_destino']);
    					if($cp >= $cp_origen){
    						if($cp <= $cp_destino){
    							$segmento[]=Array("carrito" => floatval($tarifas[$i]['precio_carrito']),"precio" => floatval($tarifas[$i]['importe']));
    						}
    					}
    				}
    			}
    		//}
    	}
    	// ya tenemos el segmento

    	// Metodo para ordenar arrays con arrays asociativos dentro
    	if(!function_exists('ordenar2')){
    		function ordenar2($x, $y){
    			if ( $x['carrito'] == $y['carrito'] ){
    				return 0;
    			}
    			//ordenar de menor a mayor
    			else if ( $x['carrito'] < $y['carrito'] ){
    				return -1;
    			}
    			else{
    				return 1;
    			}
    		}
    	}

    	// Ordenamos el segmento
    	usort($segmento,'ordenar2');
    	// Preparamos los datos para el peso minimo y maximo
    	$precio_envio = 0;
    	$max=count($segmento);
    	$carrito_min = floatval($segmento[0]['carrito']);
    	$precio_min = floatval($segmento[0]['precio']);
    	$carrito_max = floatval($segmento[$max-2]['carrito']);
    	$precio_max = floatval($segmento[$max-2]['precio']);

    	if($importe_carrito <= $carrito_min){
    		$precio_envio = $precio_min;
    	}
    	else{
    		for($i=0;$i<$max;$i++){
    			if($importe_carrito != $segmento[$i]['carrito']){
    				if($importe_carrito < $segmento[$i]['carrito']){
    					$precio_envio = $segmento[$i]['precio'];
    					$i=$max;
    				}
    			}
    			else{ //es igual
    				$precio_envio = $segmento[$i]['precio'];
    				$i=$max;
    			}
    		}
    	}
    	return $precio_envio;
    }

    function existeDestino($tarifas,$servicio,$pais,$cp)
    {
    	$max=count($tarifas);
    	$cp=intval($cp);
        $encontrado = 0;

    	for($i=1;$i<$max;$i++){
  			if($tarifas[$i]['servicio'] == $servicio){
  				if($tarifas[$i]['pais'] == $pais){
  					$cp_origen=intval($tarifas[$i]['cp_origen']);
  					$cp_destino=intval($tarifas[$i]['cp_destino']);
  					if($cp >= $cp_origen){
  						if($cp <= $cp_destino){
  							$encontrado = 1;
  						}
  					}
  				}
  			}
    	}

        return $encontrado;
    }

    function es_europeo($pais){
        $paises = Array("DE","AT","BE","BG","CC","DK","SK","SI","EE","FI","FR","GR","GG",
                        "NL","HU","IE","IT","LV","LI","LT","LU","MC","NO","PL","GB","CZ",
                        "RO","SM","SE","CH","VA");
        $max=count($paises);
        for($i=0;$i<$max;$i++){
            if($pais == $paises[$i]){
                return true;
            }
        }
        return false;
    }


    function pedidosTabla(){
	    global $cookie, $smarty;

	    // primero inicializamos la tabla de asm envios
	    $this->inicializarAsmEnvios();
	    // pasamos el token a la vista
	    $smarty->assign('tokenOrder', Tools::getAdminToken('AdminOrders'.(int)Tab::getIdFromClassName('AdminOrders').(int)$cookie->id_employee));
	    // preparamos el paginador
	    $countQuery = Db::getInstance()->ExecuteS('SELECT COUNT(o.id_order) AS allCmd FROM '._DB_PREFIX_.'orders o JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier WHERE c.external_module_name = "asmcarrier"');
		// Paginacion
        require_once(_PS_MODULE_DIR_.'asmcarrier/lib/paginator.class.2.php');

        $paginas= new Paginator;
        $paginas->items_total = $countQuery[0]['allCmd']; // items total
        //$paginas->items_per_page = 3;
        $paginas->mid_range = 10; // numero de enlaces

        $paginas->paginate();
        $paginacion_items_x_pag = $paginas->display_items_per_page();
        $paginacion_menu = $paginas->display_jump_menu();
        $paginacion = $paginas->display_pages(); // obtenemos la paginacion

        $smarty->assign('paginacion', $paginacion);
        $smarty->assign('paginacion_items_x_pag', $paginacion_items_x_pag);
        $smarty->assign('paginacion_menu', $paginacion_menu);

        $pedidosNoModule = '';

        if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM10') != '') $pedidosNoModule .= 'OR c.id_carrier = '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM10').' ';
        if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM14') != '') $pedidosNoModule .= 'OR c.id_carrier = '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM14').' ';
        if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM24') != '') $pedidosNoModule .= 'OR c.id_carrier = '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM24').' ';
        if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASMECO') != '') $pedidosNoModule .= 'OR c.id_carrier = '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASMECO').' ';

	    // obtenemos todos los pedidos relacionados con ASM
	    $pedidos = Db::getInstance()->ExecuteS('SELECT o.id_order,o.reference,o.module,o.total_paid_real,o.valid,o.date_add,c.name,e.*,
	       u.firstname,u.lastname FROM '._DB_PREFIX_.'orders o
	       JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier
	       JOIN '._DB_PREFIX_.'asm_envios e ON e.id_envio_order = o.id_order
	       JOIN '._DB_PREFIX_.'customer u ON u.id_customer = o.id_customer
	       WHERE c.external_module_name = "asmcarrier" '.$pedidosNoModule.'
	       ORDER BY o.id_order DESC '.$paginas->limit);

	    // creamos los diferentes enlaces para la vista

		
	    $pedidos2 = array();
	    $i=0;
	    foreach ($pedidos as $pedido){

	       if($pedido['valid']){
	           $pedidos[$i]['link_etiqueta'] = 'index.php?tab=AdminAsm&id_order_envio='.$pedido['id_envio_order'].'&option=etiqueta&token='.Tools::getValue('token');
	           if($pedido['codigo_envio']){
	               $pedidos[$i]['link_cancelar'] = 'index.php?tab=AdminAsm&id_order_envio='.$pedido['id_envio_order'].'&option=cancelar&token='.Tools::getValue('token');
	               $pedidos[$i]['link_envio_mail'] = 'index.php?tab=AdminAsm&id_order_envio='.$pedido['id_envio_order'].'&option=envio&token='.Tools::getValue('token');
	           }
	           else{
	               $pedidos[$i]['link_cancelar'] = '';
	               $pedidos[$i]['link_envio_mail'] = '';
	           }
	       }
	       else{
	           $pedidos[$i]['link_etiqueta'] = '';
	           $pedidos[$i]['link_cancelar'] = '';
	       }
	       $pedidos[$i]['num_pedido'] = sprintf('%06d', $pedido['id_order']);
            $pedidos[$i]['referencia'] = $pedido['reference'];

	       $i++;
	    }
		
	
	
		$activeTab = Tools::getValue('tab');
		$dateStart = Tools::getValue('date_0');
		$dateEnd = Tools::getValue('date_1');
		$mpedidos = array();
		if (!empty($activeTab) && $activeTab=='Manifest') {
			$mpedidos = Db::getInstance()->ExecuteS('SELECT o.id_order,o.reference,o.module,FORMAT(o.total_paid_real,2,\'es_ES\') as total_paid_real,o.valid,o.date_add,c.name,e.*,
			   a.address1,a.address2,a.postcode,a.phone_mobile,a.id_state,a.city,a.phone,s.name as statename,
			   u.firstname,u.lastname FROM '._DB_PREFIX_.'orders o
			   JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier
			   JOIN '._DB_PREFIX_.'asm_envios e ON e.id_envio_order = o.id_order
			   JOIN '._DB_PREFIX_.'customer u ON u.id_customer = o.id_customer
			   JOIN '._DB_PREFIX_.'address a ON a.id_address = o.id_address_delivery
			   JOIN '._DB_PREFIX_.'state s ON s.id_state = a.id_state
			   WHERE o.valid = 1 AND (c.external_module_name = "asmcarrier" '.$pedidosNoModule.')
			   AND o.date_add BETWEEN "'.$dateStart.' 00:00:00" AND "'.$dateEnd.' 23:59:59" AND e.codigo_envio != ""
			   ORDER BY o.id_order DESC ');
		}
		
	    // premaramos los path de los iconos
	    $smarty->assign('module_base', $this->_path);
	    $smarty->assign('path_img_logo', $this->_path.'img/logo_asm.png');
	    $smarty->assign('path_img_track', $this->_path.'img/track.gif');
	    $smarty->assign('path_img_email', $this->_path.'img/email.gif');
	    $smarty->assign('path_img_cod_barras', $this->_path.'img/cod_barras.gif');
	    $smarty->assign('path_img_cancelar', $this->_path.'img/cancelar.gif');
	    $smarty->assign('token', Tools::getValue('token'));
	    $smarty->assign('activetab', $activeTab);
	    $smarty->assign('date_0', $dateStart);
	    $smarty->assign('date_1', $dateEnd);
        $smarty->assign('pedidos', $pedidos);
        $smarty->assign('mpedidos', $mpedidos);
	    $smarty->assign('today',date('l jS \of F Y'));
	    $smarty->assign('asm_version',ASM_VERSION);

        if(version_compare(_PS_VERSION_, '1.6') >= 0) {
            $smarty->assign('pagerTemplate', _PS_MODULE_DIR_.'asmcarrier/templates/pagerTemplate.tpl');
            return $this->display(__FILE__, 'templates/OrdersTable.tpl');
        } else {
            $smarty->assign('pagerTemplate', _PS_MODULE_DIR_.'asmcarrier/pager_template2.tpl');
            return $this->display(__FILE__, 'pedidos4.tpl');
        }
    }

    function imprimirEtiquetas($id_pedido=0, $adminOrders=false) {
        global $smarty, $cookie, $currentIndex;

        // Antes de guardar verificamos que no este guardado este envio
        if($id_pedido){
            $resultado = Db::getInstance()->ExecuteS('SELECT codigo_envio FROM '._DB_PREFIX_.'asm_envios WHERE id_envio_order = "'.$id_pedido.'"');

            if($resultado[0]['codigo_envio'] == ""){
                $hay_track=false;
            }
            else{
            	$hay_track=true;
            }
        } else{
            // Si no llego por GET el id_order redireccionamos
            //Tools::redirectAdmin($currentIndex.'&token='.Tools::getValue('token'));
        }

        if(!$hay_track){
            // Ya tenemos el id_sesion
            // Vamos a por todos los datos necesarios para realizar el pedido
            //Dani a 23/04/2015 Para las versiones anteriores a la 1.5, el campo o.reference no existe
            if(version_compare(_PS_VERSION_, '1.5') >= 0) {
                $select = 'SELECT o.id_order,o.module,o.total_paid_real,c.name,u.email,a.firstname,
                a.lastname,a.address1,a.address2,a.postcode,a.other,a.city,a.phone,a.phone_mobile,z.iso_code, o.id_carrier, o.reference, a.company ';
            } else {
                $select = 'SELECT o.id_order,o.module,o.total_paid_real,c.name,u.email,a.firstname,
                a.lastname,a.address1,a.address2,a.postcode,a.other,a.city,a.phone,a.phone_mobile,z.iso_code, o.id_carrier, a.company ';
            }

            $datos = Db::getInstance()->ExecuteS($select .
                'FROM '._DB_PREFIX_.'orders AS o
                JOIN '._DB_PREFIX_.'carrier AS c
                JOIN '._DB_PREFIX_.'customer AS u
                JOIN '._DB_PREFIX_.'address a
                JOIN '._DB_PREFIX_.'country AS z
                WHERE o.id_order = "'.$id_pedido.'"
                AND c.id_carrier=o.id_carrier
                AND u.id_customer = o.id_customer
                AND a.id_address = o.id_address_delivery
                AND a.id_country = z.id_country');

            if(!$this->isASMModule()) {
                // Obtenemos el tipo de servicio
                switch($datos[0]['name']){
                      case 'ASM - Servicio ASM10':
                        $asm_tipo_servicio = 'ASM10';
                        $servicio =1;
                        $horario  =0;
                      break;
                      case 'ASM - Servicio ASM14':
                        $asm_tipo_servicio = 'ASM14';
                        $servicio =1;
                        $horario  =2;
                      break;
                      case 'ASM - Servicio ASM24':
                        $asm_tipo_servicio = 'ASM24';
                        $servicio =1;
                        $horario  =3;
                      break;
                      case 'ASM - Servicio ECONOMY':
                        $asm_tipo_servicio = 'ECONOMY';
                        $servicio =37;
                        $horario  =16;
                      break;
                }
            } else {
                if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM10') == $datos[0]['id_carrier']) {
                    $asm_tipo_servicio = 'ASM10';
                    $servicio =1;
                    $horario  =0;
                }
                if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM14') == $datos[0]['id_carrier']) {
                    $asm_tipo_servicio = 'ASM14';
                    $servicio =1;
                    $horario  =2;
                }
                if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM24') == $datos[0]['id_carrier']){
                    $asm_tipo_servicio = 'ASM24';
                    $servicio =1;
                    $horario  =3;
                }
                if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASMECO') == $datos[0]['id_carrier']){
                    $asm_tipo_servicio = 'ECONOMY';
                    $servicio =37;
                    $horario  =16;
                }
            }

            //Obtenemos el peso y numero de productos
            $productos = Db::getInstance()->ExecuteS(
	            'SELECT product_quantity, product_weight FROM '._DB_PREFIX_.'order_detail
	            where id_order = "'.$id_pedido.'"');
            $peso = 0;
            $num_productos = 0;
            foreach ($productos as $producto){
                $peso += floatval($producto['product_quantity'] * $producto['product_weight']);
                $num_productos += $producto['product_quantity'];
            }
            if($peso < 1){
                $peso=1;
            }
            $asm_peso_origen = $peso;

            // Calculamos el numero de paquetes para asm segun el num de articulos
            $asm_numero_paquetes = 1;
            $bultos = Configuration::get('ASM_BULTOS');
            //bultos fijos
            if($bultos == 0){
            	$num_articulos = Configuration::get('ASM_FIJO_BULTOS');
            	if($num_articulos == '' || $num_articulos == 0){
            		$num_articulos = 1;
            	}
            	$asm_numero_paquetes = intval($num_articulos);
            }

            //bultos variables
            if($bultos == 1){
            	$num_articulos = Configuration::get('ASM_NUM_BULTOS');
            	if($num_articulos == '' || $num_articulos == 0){
            		$num_articulos = 1;
            	}
            	$asm_numero_paquetes = ceil($num_productos / $num_articulos);
            }

            //bultos predefinidos
            $bultos_get = intval(Tools::getValue('asm_bultos_user'));
            AsmLog::info("\n\rGET Bultos usuario:\n\r".$bultos_get."\n\r");

            if(isset($bultos_get) && !empty($bultos_get) && is_int($bultos_get)) {

                AsmLog::info("\n\rBultos válidos\n\r");

                $num_articulos = $bultos_get;

                if($num_articulos == '' || $num_articulos == 0){
                    $num_articulos = 1;
                }
                $asm_numero_paquetes = intval($num_articulos);
            }

            // Obtenemos el num de pedido
            $asm_referencia = sprintf('%010d', $datos[0]['id_order']);

            if(version_compare(_PS_VERSION_, '1.5') >= 0) {
                 $asm_referencia3 = $datos[0]['reference'];
            } else {
                 $asm_referencia3 = '';
            }


            //Obtenemos el importe total del pedido
            $asm_importe_servicio = $datos[0]['total_paid_real'];

            //if(version_compare(_PS_VERSION_, '1.5') >= 0) {
            //AsmLog::info("\n\rVERSION: " . _PS_VERSION_ . "\n\r");
            //AsmLog::info("\n\rversion_compare(_PS_VERSION_, '1.4'): " . version_compare(_PS_VERSION_, '1.4') . "\n\r");
            //AsmLog::info("\n\rversion_compare(_PS_VERSION_, '1.5'): " . version_compare(_PS_VERSION_, '1.5') . "\n\r");
            //AsmLog::info("\n\rversion_compare(_PS_VERSION_, '1.6'): " . version_compare(_PS_VERSION_, '1.6') . "\n\r");

            //Datos del comprador
            $asm_nombre_destinatario       = $datos[0]['firstname'].' '.$datos[0]['lastname'];
            $asm_nombre_via_destinatario   = $datos[0]['address1'].' '.$datos[0]['address2'];;
            $asm_poblacion_destinatario    = $datos[0]['city'];
            $asm_CP_destinatario           = $datos[0]['postcode'];
            //$asm_cod_provincia_destinatario= $dir_pedido->getRegion();
            $asm_telefono_destinatario     = $datos[0]['phone'];
            $asm_movil_destinatario        = $datos[0]['phone_mobile'];
            $asm_email_destinatario        = $datos[0]['email'];
            $asm_pais                      = $datos[0]['iso_code'];
			//$observaciones                 = $datos[0]['other'];
            $asm_empresa                   = $datos[0]['company'];

            //13/11/2012 para las observaciones
            //04/06/2013 Solo hay que mostrar los mensajes que no sean privados, es decir, los que añade principalmente el usuario.
            $obs = Db::getInstance()->ExecuteS('SELECT message FROM '._DB_PREFIX_.'message where id_order = "'.$id_pedido.'" and private = "0"');

            $observaciones='';
            foreach ($obs as $obv){
                $observaciones = $observaciones . ' ' . $obv['message'];
            }

            //AsmLog::info("\n\rEMPRESA: ".$asm_empresa."\n\r");

            if($asm_empresa != '')
            {
                $observaciones = 'EMPRESA: ' . $asm_empresa . '. ' . $observaciones;
            }

            if($asm_pais == 'ES') {
               $asm_pais = '34';
            } else if($asm_pais == 'PT') {
               $asm_pais = '351';
               $asm_CP_destinatario = substr($asm_CP_destinatario,0,4) . '-' . $asm_CP_destinatario = substr($asm_CP_destinatario,-3);
            }

            //HAY QUE CONTROLAR SI EL COMPRADOR A ELEGIDO CONTRAREEMBOLSO Y PONERLO EN EL PARAMETRO
            $metodo_pago = $datos[0]['module'];
            if($metodo_pago == 'cashondelivery' || $metodo_pago == 'cashondeliverywithfee' || $metodo_pago == 'maofree_cashondeliveryfee' || $metodo_pago == 'codfee' || $metodo_pago == 'cashondeliveryplus' ){
                $asm_reembolso=floatval($asm_importe_servicio);
            }
            else{
                $asm_reembolso = 0;
            }

            // Datos del vendedor o la tienda
            $vendedor = Configuration::getMultiple(array('PS_SHOP_NAME','PS_SHOP_EMAIL','PS_SHOP_ADDR1','PS_SHOP_ADDR2','PS_SHOP_CODE','PS_SHOP_CITY','PS_SHOP_COUNTRY_ID','PS_SHOP_STATE_ID','PS_SHOP_PHONE','PS_SHOP_FAX'));
            $asm_nombre_remitente          = $vendedor['PS_SHOP_NAME'];
            $asm_nombre_via_remitente      = $vendedor['PS_SHOP_ADDR1'];
            $asm_poblacion_remitente       = $vendedor['PS_SHOP_CITY'];
            //$asm_cod_provincia_remitente   = $vendedor['PS_SHOP_STATE_ID'];
            $asm_telefono_remitente        = $vendedor['PS_SHOP_PHONE'];

            $asm_CP_remitente = $vendedor['PS_SHOP_CODE'];

        	//Realizamos el pedido

            $version = $this->version;
            $URL = Configuration::get('ASM_URL');
            $uidCliente = Configuration::get('ASM_GUID');


            $asm_telefono_destinatario = str_replace(" ", "", $asm_telefono_destinatario);
            $asm_movil_destinatario = str_replace(" ", "", $asm_movil_destinatario);

            // Referencia DEMO para saltarnos el error -70
            $asm_referencia2 = $asm_referencia + rand(10,100);

            $XML=
                '<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                  <soap12:Body>
                    <GrabaServicios  xmlns="http://www.asmred.com/">
                      <docIn>
                <Servicios uidcliente="' . $uidCliente . '">
                  <Envio>
                    <Portes>P</Portes>
                    <Servicio>' . $servicio . '</Servicio>
                    <Horario>' . $horario . '</Horario>
                    <Bultos>' . $asm_numero_paquetes . '</Bultos>
                    <Peso>' . $asm_peso_origen . '</Peso>';

                    if($asm_reembolso != 0)
                    {
                        $XML.='<Importes><Reembolso>'. $asm_reembolso .'</Reembolso></Importes>';
                    }

                    $XML.='<Remite>
                      <Nombre><![CDATA[' . $asm_nombre_remitente . ']]></Nombre>
                      <Direccion><![CDATA[' . $asm_nombre_via_remitente . ']]></Direccion>
                      <Poblacion><![CDATA[' . $asm_poblacion_remitente . ']]></Poblacion>
                      <Pais>34</Pais>
                      <CP>' . $asm_CP_remitente . '</CP>
                    </Remite>
                    <Destinatario>
                      <Nombre><![CDATA[' . $asm_nombre_destinatario . ']]></Nombre>
                      <Direccion><![CDATA[' . $asm_nombre_via_destinatario . ']]></Direccion>
                      <Poblacion><![CDATA[' . $asm_poblacion_destinatario . ']]></Poblacion>
                      <Pais>' . $asm_pais. '</Pais>
                      <CP>' . $asm_CP_destinatario . '</CP>
                      <Telefono>' . $asm_telefono_destinatario . '</Telefono>
                      <Movil>' . $asm_movil_destinatario . '</Movil>
                      <Observaciones><![CDATA[' . $observaciones . ']]></Observaciones>
                      <Email>' . $asm_email_destinatario . '</Email>
                    </Destinatario>
                    <Referencias>
                      <Referencia tipo="0">' . $asm_referencia . '</Referencia>';

                    if($asm_referencia3 != '')
                    {
                           $XML.='<Referencia tipo="C">' . $asm_referencia3 . '</Referencia>';
                    }

                    $XML.='</Referencias>
                  </Envio>
                  <Plataforma>Prestashop ' . $version . '</Plataforma>
                </Servicios></docIn>
                    </GrabaServicios>
                  </soap12:Body>
                </soap12:Envelope>';
				

            AsmLog::info("\n\rPETICION WEBSERVICE:\n\r".$XML."\n\r");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
            curl_setopt($ch, CURLOPT_URL, $URL );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );
            //curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml; charset=UTF-8"));

            $postResult = curl_exec($ch);
            AsmLog::info("\n\rWS RESPUESTA REALIZAR PEDIDO\n\r".$postResult."\n\r");



            if (curl_errno($ch)) {
				AsmLog::error('No se pudo llamar al ws de ASM.'."\n\r");
            }
            $xml = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");
            $xml->registerXPathNamespace('asm', 'http://www.asmred.com/');
            $arr = $xml->xpath("//asm:GrabaServiciosResponse/asm:GrabaServiciosResult");
            $ret = $arr[0]->xpath("//Servicios/Envio");
            //return $ret[0];
            $return = $ret[0]->xpath("//Servicios/Envio/Resultado/@return");

            $_SESSION["ultimoErrorASM"] = "";

            if ($return[0]!="0") {
                  $error = $arr[0]->xpath("//Servicios/Envio/Errores/Error");
                  AsmLog::error('No se pudo grabar en ASM. Retorno: ' . $return[0]. ". " . $error[0] . "\n\r");
                  $_SESSION["ultimoErrorASM"] = 'No se pudo grabar en ASM. Retorno: ' . $return[0] . ". " . $error[0];
                  return false;
            } else {
                //AsmLog::error(version_compare(_PS_VERSION_, '1.5') ."\n\r");
                $referencia = intval($asm_referencia);

                if (version_compare(_PS_VERSION_, '1.5') >= 0){
                    $id_order_state = (int)(4);
                    $objOrder = new Order($referencia);
                    $history = new OrderHistory();
                    $history->id_order = (int)$objOrder->id;
                    $history->changeIdOrderState((int)($id_order_state), (int)($objOrder->id));
                    $history->id_order_state = (int)($id_order_state);
                    $history->add(true);
                } else {
                    $id_order_state = (int)(4);
                    $objOrder = new Order($referencia);
                    $history = new OrderHistory();
                    $history->id_order = (int)($referencia);
                    $history->id_order_state = (int)(4);
                    $history->changeIdOrderState((int)(4), $objOrder);
                    $history->add(true);

                    /*$_SESSION["query"] = "";

                    $query = "UPDATE "._DB_PREFIX_."orders SET
                                        current_state = '4'
                                    WHERE id_order like $referencia";

                    $_SESSION["query"] = $query;

                    echo $query;

                    if(!Db::getInstance()->Execute($query)){
                        AsmLog::error('Imposible actualizar el registro de la tabla '._DB_PREFIX_.'prstshp_orders usando el ENGINE='._MYSQL_ENGINE_."\n\r");
                        // do rollback
                        $_SESSION["ultimoErrorASM"] = 'Imposible actualizar el registro de la tabla '._DB_PREFIX_.'prstshp_orders usando el ENGINE='._MYSQL_ENGINE_."\n\r";

                        //$this->tablesRollback();
                        return false;
                    } else {
                        $context = Context::getContext();
                        $empleado = $context->cookie->id_employee;
                        $query = "INSERT INTO "._DB_PREFIX_."order_history (id_employee, id_order, id_order_state, date_add) VALUES ($empleado,$referencia,4,NOW())";
                        $_SESSION['query'] = $query;
                        //echo $query;
                        if(!Db::getInstance()->Execute($query)) {
                            AsmLog::error('Imposible insertar el registro de la tabla '._DB_PREFIX_.'order_history usando el ENGINE='._MYSQL_ENGINE_."\n\r");
                            $_SESSION["ultimoErrorASM"] = 'Imposible insertar el registro de la tabla '._DB_PREFIX_.'order_history usando el ENGINE='._MYSQL_ENGINE_."\n\r";
                            // do rollback
                            //$this->tablesRollback();
                            return false;
                        }
                    }*/
                }
            }

            $cb = $ret[0]->xpath("//Servicios/Envio/@codbarras");


			AsmLog::info('NUMERO DE CODIGO DE BARRAS = '.$cb[0]["codbarras"]."\n\r");
            $codTracking = $cb[0]["codbarras"];
			
			//echo "Código tracking: ".$codTracking;

            //Ahora podemos obtener el codigo de barras en PDF codificado en base64
             $XML='<?xml version="1.0" encoding="utf-8"?>
                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                      <soap12:Body>
                          <EtiquetaEnvio xmlns="http://www.asmred.com/">
                              <codigo>'.$codTracking.'</codigo>
                              <tipoEtiqueta>PDF</tipoEtiqueta>
                          </EtiquetaEnvio>
                        </soap12:Body>
                    </soap12:Envelope>';



            AsmLog::info("\n\rWS PETICION DE ETIQUETA \n\r".$XML."\n\r");

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
            curl_setopt($ch, CURLOPT_URL, "http://www.asmred.com/websrvs/printserver.asmx");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml; charset=UTF-8"));

            $postResult = curl_exec($ch);

            $result = strpos($postResult, '<base64Binary>');
            if($result !== false){
               //Encontrado (quitamos todo el código pdf)
               $cadena1 = substr($postResult,0,$result+14);
               $result2 = strpos($postResult, '</base64Binary>');
               $cadena2 = substr($postResult,$result2);
               AsmLog::info("\n\rWS RESPUESTA CON ETIQUETA\n\r ".$cadena1 . $cadena2);
            } else {
              //No encontrado
              AsmLog::info("\n\rWS RESPUESTA CON ETIQUETA\n\r ".$postResult);
            }

            if (curl_errno($ch)) {
             	AsmLog::error('No se pudo llamar al WS de ASM');
            }
            $xml = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");
            $xml->registerXPathNamespace("asm","http://www.asmred.com/");
            $arr = $xml->xpath("//asm:EtiquetaEnvioResponse/asm:EtiquetaEnvioResult/asm:base64Binary");
            $asm_etiqueta = $arr[0];

			// Ya tenemos todos los datos necesarios para guardar en la tabla de envios
			
			if($ruta=$this->guardarEnvio2($id_pedido, $codTracking, "", $asm_etiqueta, $asm_CP_destinatario)){
				//despues enviamos pdf codigo barras
				$smarty->assign('download_pdf', $ruta);

                $url= 'http://www.asmred.com/Extranet/public/ecmLabel.aspx';
                $url .= '?codbarras='. $codTracking;
                $url .= '&uid=' . Configuration::get('ASM_GUID');
                $smarty->assign('ventana_etiqueta', $url);

			}
			else{
				$error = "";
				$ruta = "../modules/asmcarrier/PDF";
				// comprobamos si la carpeta existe
				$existe = file_exists($ruta);
				$error .= "<p>La carpeta modules/asmcarrier/PDF existe = $existe</p>";
				// comprobamos los permisos
				$permisos = substr(sprintf('%o', fileperms($ruta)), -4);
				$error .= "<p>La carpeta modules/asmcarrier/PDF permisos = $permisos</p>";

				$smarty->assign('errores',$error);
			}

            //if (version_compare(_PS_VERSION_, '1.5') >= 0){

            if(Configuration::get('ASM_ENVIAR_MAIL') == 'S'){
                AsmLog::info('Se procede a enviar el mail');
                //////////////////////////////////////////////////////////////////////////
    		    $error = false;
    		    $resultado = false;
    		    $mensaje = false;
                $mensaje_html = '';

                (!isset($_POST['mensaje']) || empty($_POST['mensaje'])) ? $mensaje = Configuration::get('ASM_EMAIL') : $mensaje = $_POST['mensaje'];

    			if($id_pedido){
    		    	//obtenemos los datos necesarios del usuario
    		            $datos = Db::getInstance()->ExecuteS(
    		            	'SELECT o.id_order,o.reference,u.firstname,u.lastname,u.email,e.url_track
    		            	FROM '._DB_PREFIX_.'orders AS o
    		            	JOIN '._DB_PREFIX_.'customer AS u
    		            	JOIN '._DB_PREFIX_.'asm_envios AS e
    		            	WHERE o.id_order = "'.$id_pedido.'" AND
    		            	u.id_customer = o.id_customer AND
    		            	e.id_envio_order = "'.$id_pedido.'"');

    				$usuario_nombre    = $datos[0]['firstname'];
    				$usuario_apellidos = $datos[0]['lastname'];
    				$usuario_email     = $datos[0]['email'];
    				//$orden_pedido      = sprintf('%06d', $id_pedido);
                    $orden_pedido      = $datos[0]['reference'];
    				$asunto            = "Codigo seguimiento del pedido num. ".$orden_pedido;
    				$enlace            = '<p><a href="'.$datos[0]['url_track'].'">Ver seguimiento</a></p>';
    				$mensaje .= '<p>'.$enlace.'</p>';
                    $followup = $datos[0]['url_track'];

    				if (Mail::Send(intval($cookie->id_lang),'in_transit',$asunto,array('{firstname}' => $usuario_nombre,'{lastname}' => $usuario_apellidos,'{order_name}' => $orden_pedido,'{message}' => $mensaje,'{followup}' => $followup,'{email}' => $usuario_email),$usuario_email)){
                        AsmLog::error('Mail enviado al destinatario correctamente');
                        // Guardamos el nuevo mensaje
    		        	Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'asm_email SET mensaje="'.$_POST['mensaje'].'" WHERE id = "1"');
    		        	$resultado = '<p>Se envio la URL de seguimiento del pedido <b>'.$orden_pedido.'</b> correctamente al siguiente destinatario <b>'.$usuario_nombre.' '.$usuario_apellidos.'</b> al email <b>'.$usuario_email.'</b></p>';
    		        }
    		        else{
    		            $error = Tools::displayError('Hubo un error al intentar enviar el mensaje a: '.$usuario_nombre.' '.$usuario_apellidos.' con el email: '.$usuario_email);
                        AsmLog::error('Error al enviar el mail al destinatario: ' . $error);
    		        }
    		        $smarty->assign('formulario', false);
    			//}
    		//}


            }
            else
            {
                AsmLog::info('No enviamos el mail por configuración');
            }
            }

        } else{
        	//obtenemos la url de la etiqueta PDF ya registrado
        	    $resultado = Db::getInstance()->ExecuteS('SELECT e.codigo_barras, e.codigo_envio FROM '._DB_PREFIX_.'asm_envios AS e  where e.id_envio_order = "'.$id_pedido.'"');
	            $smarty->assign('download_pdf', $resultado[0]['codigo_barras']);
                $url= 'http://www.asmred.com/Extranet/public/ecmLabel.aspx';
                $url .= '?codbarras='. $resultado[0]['codigo_envio'];
                $url .= '&uid=' . Configuration::get('ASM_GUID');

                $smarty->assign('ventana_etiqueta', $url);
        }

		$smarty->assign('error', $error);
		$smarty->assign('resultado', $resultado);
        $smarty->assign('volver', '<a href="index.php?tab=AdminAsm&token='.Tools::getValue('token').'"><strong>Volver</strong></a>');
        $smarty->assign('path_img_logo', $this->_path.'img/logo_asm.png');//.jpg');

        if(version_compare(_PS_VERSION_, '1.6') >= 0) {
            return $this->display(__FILE__, 'templates/TagASM.tpl');
        } else {
            return $this->display(__FILE__, 'etiqueta2.tpl');
        }
    }

    // Funcion encargada de insertar/actualizar el estado de un envio
    function guardarEnvio($id_order,$codigo_envio,$url_track,$num_albaran,$codigo_barras)
    {

    	// preparamos para guardar el archivo pdf
	    $nombre = "etiqueta_".$id_order.".pdf";
    	$ruta   = "../modules/asmcarrier/PDF/".$nombre;
    	$descodificar = base64_decode($codigo_barras);

		if(!$fp2 = fopen($ruta,"wb+")){
			AsmLog::error("IMPOSIBLE ABRIR EL ARCHIVO $ruta \n\r");
			return false;
		}
		if(!fwrite($fp2, trim($descodificar))){AsmLog::error("IMPOSIBLE escribir EL ARCHIVO $ruta \n\r");}
		fclose($fp2);

    	//preparamos la URL para el track
    	$fecha = date('d/m/y');
    	$cortar=split("\?",$url_track);
        $url_seguimiento=$cortar[0];
        $enlace=$url_seguimiento."?servicio=".$codigo_envio."&fecha=".$fecha;

        Db::getInstance()->ExecuteS(
        	'UPDATE '._DB_PREFIX_.'asm_envios SET
        	codigo_envio = "'.$codigo_envio.'",
        	url_track = "'.$enlace.'",
        	num_albaran = "'.$num_albaran.'",
        	codigo_barras = "'.$ruta.'",
        	fecha = "'.date('Y-m-d H:i:s').'"
        	WHERE id_envio_order = "'.$id_order.'"');

		Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'orders SET shipping_number="'.$this->comprimir_num_track($codigo_envio).'" WHERE id_order = "'.$id_order.'"');

        // Actualizar el tracking en la tabla order_carrier
        if((version_compare(_PS_VERSION_, '1.5') >= 0)) {
            Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'order_carrier SET tracking_number="'.$codigoBarras.'" WHERE id_order = "'.$id_order.'"');
        }

        return $ruta;
    }

     // Funcion encargada de insertar/actualizar el estado de un envio
    function guardarEnvio2($id_order,$codigoBarras,$num_albaran,$codigo_barras,$cp_destino)
    {
        AsmLog::info("Guardamos el envío en la BDD\n\r");
    	// preparamos para guardar el archivo pdf
	    $nombre = "etiqueta_".$id_order.".pdf";
    	$ruta   = "../modules/asmcarrier/PDF/".$nombre;
    	$descodificar = base64_decode($codigo_barras);

		if(!$fp2 = fopen($ruta,"wb+")){
			AsmLog::error("IMPOSIBLE ABRIR EL ARCHIVO $ruta \n\r");
			return false;
		}
		if(!fwrite($fp2, trim($descodificar))){AsmLog::error("IMPOSIBLE escribir EL ARCHIVO $ruta \n\r");}
		fclose($fp2);

        //$enlace="../modules/asmcarrier/tracking.php?codbarras=".$codigoBarras."&uid=".Configuration::get('ASM_GUID');
        $enlace='http://www.asmred.com/Extranet/Public/ExpedicionASM.aspx?cpDst='. $cp_destino .'&codigo='.$codigoBarras;


        Db::getInstance()->ExecuteS(
        	'UPDATE '._DB_PREFIX_.'asm_envios SET
        	codigo_envio = "'.$codigoBarras.'",
        	url_track = "'.$enlace.'",
        	num_albaran = "'.$num_albaran.'",
        	codigo_barras = "'.$ruta.'",
        	fecha = "'.date('Y-m-d H:i:s').'"
        	WHERE id_envio_order = "'.$id_order.'"');

		Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'orders SET shipping_number="'.$codigoBarras.'" WHERE id_order = "'.$id_order.'"');

        // Actualizar el tracking en la tabla order_carrier
        if((version_compare(_PS_VERSION_, '1.5') >= 0)) {
            Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'order_carrier SET tracking_number="'.$codigoBarras.'" WHERE id_order = "'.$id_order.'"');
        }


        return $ruta;
    }

    function comprimir_num_track($codigo)
    {
        $separar = split("-",$codigo);
        $comprimir="";
        foreach($separar as $linea){
            $comprimir.=$linea;
        }
        return $comprimir;
    }
    function inicializarAsmEnvios()
    {
    	// verificamos si hay pedidos sin registro de envio nuevo
        if(!$this->isASMModule()) {
            $envios = Db::getInstance()->ExecuteS('SELECT o.id_order FROM '._DB_PREFIX_.'orders o JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier WHERE c.external_module_name = "asmcarrier"');
        } else {
            $pedidosNoModule = '';

            if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM10') != '') $pedidosNoModule .= 'OR o.id_carrier LIKE '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM10').' ';
            if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM14') != '') $pedidosNoModule .= 'OR o.id_carrier LIKE '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM14').' ';
            if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM24') != '') $pedidosNoModule .= 'OR o.id_carrier LIKE '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASM24').' ';
            if(Configuration::get('ASM_SERVICIO_SELECCIONADO_ASMECO') != '') $pedidosNoModule .= 'OR o.id_carrier LIKE '.Configuration::get('ASM_SERVICIO_SELECCIONADO_ASMECO').' ';

            if($pedidosNoModule != '') $pedidosNoModule = substr($pedidosNoModule, 3);

            $envios = Db::getInstance()->ExecuteS('SELECT o.id_order FROM '._DB_PREFIX_.'orders AS o WHERE '.$pedidosNoModule);
        }
        if(!$envios){
            return false;
        }
        foreach ($envios as $envio){
        	if(!Db::getInstance()->ExecuteS('SELECT id_envio_order FROM '._DB_PREFIX_.'asm_envios where id_envio_order = "'.$envio['id_order'].'"')){
        	   Db::getInstance()->ExecuteS('INSERT INTO '._DB_PREFIX_.'asm_envios (id_envio_order,codigo_envio,url_track,num_albaran) VALUES ("'.$envio['id_order'].'","","","")');
        	}
        }
        return true;
    }
    function limpiarNumTrack($codigo)
    {
        if(!$codigo){
            return false;
        }
        $codigo = substr($codigo,1,36);
        return $codigo;
    }
    function enviarEmailTrack($id_pedido=false)
    {
    	global $smarty, $cookie;

    	$error = false;
		$resultado = false;
		$mensaje = false;

		if(!isset($_POST['mensaje'])){
			//cargamos mensaje anterior
            $datos = Db::getInstance()->ExecuteS('SELECT mensaje FROM '._DB_PREFIX_.'asm_email');
            $mensaje = $datos[0]['mensaje'];
            $url_form = 'index.php?tab=AdminAsm&id_order_envio='.$id_pedido.'&option=envio&token='.Tools::getValue('token');
	        $smarty->assign('mensaje', $mensaje);
			$smarty->assign('formulario', true);
			$smarty->assign('url_formulario', $url_form);
		}
		else{
			if($id_pedido){
		    	//obtenemos los datos necesarios del usuario
		            $datos = Db::getInstance()->ExecuteS(
		            	'SELECT o.id_order,u.firstname,u.lastname,u.email,e.url_track
		            	FROM '._DB_PREFIX_.'orders AS o
		            	JOIN '._DB_PREFIX_.'customer AS u
		            	JOIN '._DB_PREFIX_.'asm_envios AS e
		            	WHERE o.id_order = "'.$id_pedido.'" AND
		            	u.id_customer = o.id_customer AND
		            	e.id_envio_order = "'.$id_pedido.'"');

				$usuario_nombre    = $datos[0]['firstname'];
				$usuario_apellidos = $datos[0]['lastname'];
				$usuario_email     = $datos[0]['email'];
				$orden_pedido      = sprintf('%06d', $id_pedido);
				$asunto            = "Codigo seguimiento del pedido num. ".$orden_pedido;
				$enlace            = '<p><a href="'.$datos[0]['url_track'].'">Ver seguimiento</a></p>';
				$mensaje = $_POST['mensaje'].'<p>'.$enlace.'</p>';

		        if (Mail::Send(intval($cookie->id_lang),'order_customer_comment',$asunto,array('{firstname}' => $usuario_nombre,'{lastname}' => $usuario_apellidos,'{order_name}' => $orden_pedido,'{message}' => $mensaje),$usuario_email)){
		        	// Guardamos el nuevo mensaje
		        	Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'asm_email SET mensaje="'.$_POST['mensaje'].'" WHERE id = "1"');
		        	$resultado = '<p>Se envio la URL de seguimiento del pedido <b>'.$orden_pedido.'</b> correctamente al siguiente destinatario <b>'.$usuario_nombre.' '.$usuario_apellidos.'</b> al email <b>'.$usuario_email.'</b></p>';
		        }
		        else{
		            $error = Tools::displayError('Hubo un error al intentar enviar el mensaje a: '.$usuario_nombre.' '.$usuario_apellidos.' con el email: '.$usuario_email);
		        }
		        $smarty->assign('formulario', false);
			}
		}

		$smarty->assign('volver', '<a href="index.php?tab=AdminAsm&token='.Tools::getValue('token').'"><strong>Volver</strong></a>');
        $smarty->assign('error', $error);
		$smarty->assign('resultado', $resultado);
        //Dani ojo que pongo el fichero .png (había .jpg)
		$smarty->assign('path_img_logo', $this->_path.'img/logo_asm.png');

	    //return $this->display(__FILE__, 'seguimiento.tpl');
		return $this->display(__FILE__, 'etiqueta2.tpl');
    }

    /** Llama al servicio web de ASM para valorar el envio*/
    protected function valora($servicio,$horario,$bultos,$peso,$cpOrig,$cpDest,$paisOrig,$paisDest)
    {
            AsmLog::info("Entra en valoración. Servicio: ".$servicio. " horario: ".$horario."\n\r");
            //Dani ojo buscar como obtener versión del módulo
            $version = $this->version;
            $URL = Configuration::get('ASM_URL');
            $uidCliente = Configuration::get('ASM_GUID');

            $XML=
                '<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                  <soap12:Body>
                    <Valora xmlns="http://www.asmred.com/">
                      <docIn>
                        <Servicios uidcliente="' . $uidCliente . '">
                          <Envio>
                            <Servicio>' . $servicio  . '</Servicio>
                            <Horario>' . $horario . '</Horario>
                            <Bultos>' . $bultos . '</Bultos>
                            <Peso>' . $peso . '</Peso>
                            <Remite>
                              <Pais>' . $paisOrig . '</Pais>
                              <CP>' . $cpOrig . '</CP>
                            </Remite>
                            <Destinatario>
                              <Pais>' . $paisDest . '</Pais>
                              <CP>' . $cpDest . '</CP>
                            </Destinatario>
                          </Envio>
                          <Plataforma>Prestashop ' . $version . '</Plataforma>
                        </Servicios></docIn>
                    </Valora>
                  </soap12:Body>
                </soap12:Envelope>';

          AsmLog::info("WS VALORA: ".$XML."\n\r");

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
          curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
          curl_setopt($ch, CURLOPT_URL, $URL );
          curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );
          curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));

          $postResult = curl_exec($ch);
          AsmLog::info("RESULTADO VALORACION: ".$postResult."\n\r");

          if (curl_errno($ch)) { print curl_error($ch); }

          $x = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");
          $x->registerXPathNamespace('asm', 'http://www.asmred.com/');
          $arr = $x->xpath("//asm:ValoraResponse/asm:ValoraResult");

         // Mage::log($arr);

          return (float)$arr [0];
    }
}
