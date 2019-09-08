<?php
class Zend_View_Helper_Helpdesk extends Zend_View_Helper_Abstract
{
    private  $once = TRUE;
    private $template = '<a target="_blank" href="http://monitor.rmater.org.br/mantis/bug_report_page.php"><img class="helpdesk no-print" src="/img/helpdesk.png" /></a>';


    public function helpdesk(){
        if ($this->once === TRUE){
            $this->view->headLink()->appendStylesheet($this->view->baseUrl('/css/helpdesk.css'));
            $this->once = false;
        }
        
        return $this->template;
    }


}