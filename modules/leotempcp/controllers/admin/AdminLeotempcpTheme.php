<?php

/* * ****************************************************
 * @package Leo Prestashop Theme Framework for Prestashop 1.5.x
 * @version 2.0
 * @author http://www.leotheme.com
 * @copyright   Copyright (C) December 2013 LeoThemes.com <@emai:leotheme@gmail.com>.All rights reserved.
 * @license     GNU General Public License version 2
 * ***************************************************** */

require_once(_PS_MODULE_DIR_ . 'leotempcp/libs/helper.php');

class AdminLeotempcpThemeController extends ModuleAdminControllerCore {

    /**
     * @var String $name
     *
     * @access protected
     */
    public $name = 'LiveThemeEditor';

    /**
     * @var String $name
     *
     * @access public
     */
    public $themeName = '';

    /**
     * @var String $themeCustomizePath
     *
     * @access public 
     */
    public $themeCustomizePath = '';

    /**
     * @var String $customizeFolderURL
     *
     * @access public 
     */
    public $customizeFolderURL = '';

    /**
     * Constructor
     */
    public function __construct() {
        $this->table = 'leohook';
        $this->className = 'LeotempcpPanel';
        $this->bootstrap = true;
        $this->lang = true;
        $this->context = Context::getContext();
        parent::__construct();
        $this->display_key = (int) Tools::getValue('show_modules');

        $this->ownPositions = array(
            'displayHeaderRight',
            'displaySlideshow',
            'topNavigation',
            'displayBottom'
        );
        $this->hookspos = array(
            'displayTop',
            'displayHeaderRight',
            'displaySlideshow',
            'topNavigation',
            'displayTopColumn',
            'displayRightColumn',
            'displayLeftColumn',
            'displayHome',
            'displayFooter',
            'displayBottom',
            'displayContentBottom',
            'displayFootNav'
        );
        $this->themeName = Context::getContext()->shop->getTheme();
        $this->themeCustomizePath = _PS_ALL_THEMES_DIR_ . $this->themeName . '/css/customize/';
        $this->themeCustomizeURL = $this->context->shop->getBaseURL() . '/themes/' . $this->themeName . '/css/customize/';
    }

    public function renderList() {

        $tpl = $this->createTemplate('themeeditor.tpl');
        

        return $tpl->fetch();
    }

}

?>