<?php
/**
 * Usuarios
 * 
 * @author Marcus
 * @version 
 */
class Application_Model_VendedorBarraca extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_vendedor_barraca';
    protected $_primary = array('id_barraca', 'id_vendedor');

}
