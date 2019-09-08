<?php
class DenarioController extends Zend_Controller_Action
{
public function preDispatch() {
		if (Zend_Auth::getInstance()->getIdentity()->nivel != 1)
			$this->_redirect ('/');
	
	}
    public function indexAction ()
    {
        $this->view->form = $form = new MinhaBiblioteca_Forms_Denario();
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())){
            $valor = (float) $form->valor->getValue();
            $cartoes = explode("\n", trim($form->cartoes->getValue()));
            $creditados = array();
            $table = new Application_Model_Creditos();
            $modelCartao = new Application_Model_Cartao();
            $count = 0;
            foreach ($cartoes as $cartao) {
                $cartao = (integer) substr(trim($cartao), 0, -1);
                if (!in_array($cartao, $creditados)){
                    $creditados[] = $cartao;
                    $where = array();
                    $where[] = $modelCartao->getAdapter()->quoteInto('id_cartao = ?', $cartao);
                    $where[] = $modelCartao->getAdapter()->quoteInto('status = ?', 0);
                    $where = implode(' AND ', $where);
                    $found = $modelCartao->update(
                        array(
                            'descricao' => 'Denário',
                            'status' => 1
                        ),
                        $where
                    );
                    if ($found){
                        $table->insert(
                            array(
                                'id_cartao' => $cartao,
                                'valor' => $valor,
                                'id_forma' => 5,
                                'id_caixa' => 0,
                                'id_vendedor' => Zend_Auth::getInstance()->getIdentity()->id_vendedor,
                                'status' => 1,
                                'id_estorno' => 0,
                                'id_fechamento_caixa' => null
                            )
                        );
                        $count++;
                    }
                }
            }
            $form->reset();
            $this->getHelper('flashMessenger')
                 ->addMessage(array('success'=>"Foram criados $count cartão(ões) de denário, com ". $valor ." reais."));
        }
    }
}

