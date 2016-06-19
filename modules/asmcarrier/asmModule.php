<?php
abstract class CarrierModuleCore extends Module
{
    abstract function getOrderShippingCost($params,$shipping_cost);
    abstract function getOrderShippingCostExternal($params);
}

abstract class asmModule extends CarrierModuleCore {
    public function __construct(){
        parent::__construct();
    }
}
?>