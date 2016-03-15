<?php

/*
  Criado     : 23/09/2015
  Modificado : 23/09/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel pela comunicação da view com o model
 */


namespace Cliente\Controller;

use Cliente\Model\Cliente;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;



class ClienteController extends AbstractActionController{



    protected $clienteTabela;
    protected $clienteForm;
    protected $enviarEmail;



    /*
     * Metodo retorna o serviço que contem a instancia da classe
     * @return Cliente\Model\BdTabela\ClienteTabela
     *
     */
    public function getClienteTabela()
    {
        if(!$this->clienteTabela){
            $this->clienteTabela = $this->getServiceLocator()->get('Cliente\Model\BdTabela\ClienteTabela');
        }
        return $this->clienteTabela;
    }





    /*
     * Retorna o serviço que contem a instancia da classe
     * @return Utilitario\Servico\EnviarEmail
     */
    public function getEnviarEmail()
    {
        if(!$this->enviarEmail){
            $this->enviarEmail = $this->getServiceLocator()->get('Utilitario\Servico\EnviarEmail');
        }
        return $this->enviarEmail;
    }

    
    
    

    /*
     * Retorna o serviço que contem a instancia da classe
     * Cliente\Form\ClienteForm
     */
    public function getClienteForm()
    {
        if(!$this->clienteForm){
            $this->clienteForm = $this->getServiceLocator()->get('Cliente\Form\ClienteForm');
        }
        return $this->clienteForm;
    }




    public function indexAction()
    {
        $paginator   = $this->getClienteTabela()->buscarTodos(true);
        $paginaAtual = filter_var($this->params()->fromRoute('page', 1), FILTER_SANITIZE_NUMBER_INT);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(10);

        //var_dump($this->identity());
        
//        $this->getEnviarEmail()
//              ->setRemetente('remetente@live.com')
//              ->setDestinatario('destinatario@live.com')
//              ->setAssunto('Assunto email')
//              ->setMensagem('C:\wamp\www\Projetos\CFP\module\Cliente\view\cliente\cliente\apg.phtml')
//              ;//->enviarEmail();
        
        return new ViewModel(array(
            'clientes' => $paginator
        ));
    }





    public function vizualizarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        $cliente = $this->getClienteTabela()->buscarUm($id);
        if(!$cliente){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('cliente');
        }

        return new ViewModel(array(
            'cliente' => $cliente,
        ));
    }





    public function salvarAction()
    {
        $form    = $this->getClienteForm();
        $request = $this->getRequest();

        if($request->isPost()) { 
            $form->setData($request->getPost());

            if($form->isValid()) {
                $cliente   = new Cliente();
                $cliente->exchangeArray($form->getData());                
                $retorno = $this->getClienteTabela()->salvar($cliente);

                if($retorno){
                    $this->flashMessenger()->addSuccessMessage("Registro salvo com sucesso.");
                    return $this->redirect()->toRoute('cliente');
                }
                else{
                    $this->flashMessenger()->addErrorMessage("Erro ao salvar registro, tente novamentte.");
                }                
            }
        }

        return array('form' => $form);
    }





    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('cliente');
        }

        $cliente = $this->getClienteTabela()->buscarUm($id);
        if(!$cliente){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('cliente');
        }

        $form = $this->getClienteForm();
        $form->bind($cliente);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());

            //Remove a validação da senha
            if(empty($form->get('senha')->getValue()) && empty($form->get('senha_confirma')->getValue()))
            {
                $form->getInputFilter()->remove('senha');
                $form->getInputFilter()->remove('senha_confirma');
            }

            if ($form->isValid())
            {
                $retorno = $this->getClienteTabela()->salvar($cliente);
                
                if ($retorno) {
                    $this->flashMessenger()->addSuccessMessage('Registro atualizado com sucesso');
                    return $this->redirect()->toRoute('cliente');
                }
                else{
                    $this->flashMessenger()->addSuccessMessage('Erro ao atualizar registro, tente novamente.');
                }                
            }
        }

        return array(
            'id'   => $id,
            'form' => $form,
        );
    }





    public function deletarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado.");
            return $this->redirect()->toRoute('cliente');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            
            if ($id) {
                $retorno = $this->getClienteTabela()->deletar($id);
                if($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
                }
                else{
                    $this->flashMessenger()->addSuccessMessage('Erro ao deletar registro, tente novamente.');
                }                
            }

            return $this->redirect()->toRoute('cliente');
        }

        $objCliente = $this->getClienteTabela()->buscarUm($id);
        if(!$objCliente){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('cliente');
        }

        return array(
            'id'      => $id,
            'cliente' => $objCliente
        );
    }
    
    
    
    
    
    /*
     * Ativa cliente
     */
    public function ativarAction()
    {
        
    }

}
