<?php

/*
  Criado     : 18/09/2015
  Modificado : 18/09/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :
 
        Responsavel pela comunicação da view com o model
 */


namespace Conta\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Conta\Model\Tipo;


class TipoController extends AbstractActionController
{

    protected $tipoTabela;
    protected $tipoForm;
    
    
    
    /*
     * Retorna um serviço
     * @return Conta\Model\BdTabela\TipoTabela
     * 
    */
    public function getTipoTabela()
    {
        if(!$this->tipoTabela)
        {
            $this->tipoTabela = $this->getServiceLocator()->get('Conta\Model\TipoTabela');
        }
        return $this->tipoTabela;
    }
    
    
    
    
    
    /*
     * Retorna um serviço
     * @return Conta\Form\TipoForm
     * 
    */
    public function getTiipoForm()
    {
        if(!$this->tipoForm)
        {
            $this->tipoForm = $this->getServiceLocator()->get('Conta\Form\TipoForm');
        }
        return $this->tipoForm;
    }
    
    
    
    
    
    public function indexAction()
    {
        $paginator = $this->getTipoTabela()->buscarTodos(true);
        
        $paginaAtual = filter_var($this->params()->fromRoute('page', 1), FILTER_SANITIZE_NUMBER_INT);
        
        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(10);
        
        return new ViewModel(array(
            'tipos' => $paginator
        ));
    }
    
    
    
    
    
    public function vizualizarAction(){
        
        $id = filter_var($this->params()->fromRoute('id', 0), FILTER_SANITIZE_NUMBER_INT);
        
        $perfil = $this->getTipoTabela()->buscarUm($id);   
        if(!$perfil){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('perfil');
        }

        return new ViewModel(array(
            'perfil' => $perfil,
        ));
    }
    
    
    
    
    public function salvarAction()
    {        
        $form    = $this->getTiipoForm();
        $request = $this->getRequest();
        
        if($request->isPost())
        {      
            $form->setData($request->getPost());
            
            if($form->isValid())
            {                
                $tipo = new Tipo();               
                $tipo->exchangeArray($form->getData());
                $this->getTipoTabela()->salvar($tipo);
                
                $this->flashMessenger()->addSuccessMessage("Registro cadastrado com sucesso.");
                return $this->redirect()->toRoute('tipo');
            }
        }
        
        return array('form' => $form);
    }
    
    
    
    
    
    public function editarAction()
    {        
        $id = filter_var($this->params()->fromRoute('id', 0), FILTER_SANITIZE_NUMBER_INT);
        
        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('tipo');
        }
        
        $tipo = $this->getTipoTabela()->buscarUm($id);
        if(!$tipo){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('tipo');   
        }
                
        $form = $this->getTiipoForm();
        $form->bind($tipo);
        
        $request = $this->getRequest();
        if($request->isPost()){
            
            $form->setData($request->getPost());  
            
            if ($form->isValid()){
                $this->getTipoTabela()->salvar($tipo);
                $this->flashMessenger()->addSuccessMessage('Registro atualizado com sucesso');
                return $this->redirect()->toRoute('tipo');
            }
        }
        
        return array(
            'id'   => $id,
            'form' => $form,
        );
    }
    
    
    
    
    
    public function deletarAction()
    {
        $id = filter_var($this->params()->fromRoute('id', 0), FILTER_SANITIZE_NUMBER_INT);
        
        if (!$id) {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado.");
            return $this->redirect()->toRoute('cliente');            
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Voltar');

            if ($del == 'Deletar') {
                $id = (int) $request->getPost('id');
                $this->getClienteTabela()->deletar($id);
                $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
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
}
