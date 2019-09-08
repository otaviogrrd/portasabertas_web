<?php
class Zend_View_Helper_ItemMenu extends Zend_View_Helper_Abstract
{
    public function itemMenu($params){
        $link = $this->view->url(
            array(
                'action'=>$params->action,
                'controller'=>$params->controller
            ),
            null,
            1
        );
        $label = $params->label;
        $key = $params->key;
        $labelKey = ucfirst($key);
        return "<a href='{$link}'>\n
\t<div class='opcaoMenu'> {$label} <div class='atalho' data-key='{$key}'>({$labelKey})</div></div>\n
</a>\n";
    }

}