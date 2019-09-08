<?php
class Zend_View_Helper_Moeda extends Zend_View_Helper_Abstract
{
    private $helper = null;
    public function moeda($params){
        if (is_null($this->helper))
            $this->helper = new Zend_View_Helper_Currency(new Zend_Currency('pt_BR'));
        return $this->helper->currency($params);
    }

}