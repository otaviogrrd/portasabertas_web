<?php
/**
 * Arquivo models/validate/Cpf.php
 */

class MinhaBiblioteca_Acl extends Zend_Acl
{
	public function __construct(){
		$acl = Zend_Registry::get('acl');
		foreach ($acl->resources as $key => $value) 
			$this->add(new MinhaBiblioteca_Resource($key, $value));

		foreach ($acl->roles as $key => $value) {
			$this->addRole(new Zend_Acl_Role($value->id));
			foreach ($value->permissoes as $perm)
				$this->allow($value->id, $perm);
		}
	}
	public function getItensMenu($perfil){
		$resources = $this->getResources();
		$retorno = array();
		foreach ($resources as $key => $value)
			if ($this->isAllowed($perfil, $value))
				$retorno[] = $this->get($value);
		return $retorno;
	}
}