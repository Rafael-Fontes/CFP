<?php

/*
  Criado     : 20/11/2015
  Modificado : 20/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel pela comunicação da view com o model
 */


namespace Despesa\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Despesa\Model\DespesaSubcategoria;


class DespesaSubcategoriaController extends AbstractActionController
{
    
    protected $despesaSubcategoriaTabela;
    protected $despesaSubcategoriaForm;
    protected $despesaCategoriaTabela;
    
    
    
    
    /*
     * Retorna um serviço
     * @return Despesa\Model\BdTabela\DespesaCategorioriaTabela
     *
    */
    private function getDespesaSubcategoriaTabela()
    {
        if(!$this->despesaSubcategoriaTabela)
        {
            $this->despesaSubcategoriaTabela = $this->getServiceLocator()->get('Despesa\Model\BdTabela\DespesaSubcategoriaTabela');
        }
        return $this->despesaSubcategoriaTabela;
    }





    /*
     * Retorna um serviço
     * @return Despesa\Form\DespesaCategoriaForm
     *
    */
    private function getDespesaSubcategoriaForm()
    {
        if(!$this->despesaSubcategoriaForm)
        {
            $this->despesaSubcategoriaForm = $this->getServiceLocator()->get('Despesa\Form\despesaSubcategoriaForm');
        }
        return $this->despesaSubcategoriaForm;
    }





    /*
     * Retorna um serviço
     * @return Cliente\Model\BdTabela\ClienteTabela
     *
    */
    private function getDespesaCategoriaTabela()
    {
        if(!$this->despesaCategoriaTabela)
        {
            $this->despesaCategoriaTabela = $this->getServiceLocator()->get('Despesa\Model\BdTabela\DespesaCategoriaTabela');
        }
        return $this->despesaCategoriaTabela;
    }
    
    
    
    
    public function indexAction()
    {
        $filtros     = $this->params()->fromQuery();
        $paginator   = $this->getDespesaSubcategoriaTabela()->buscartodos(true, $filtros);
        $paginaAtual = filter_var($this->params()->fromRoute('page', 1), FILTER_SANITIZE_NUMBER_INT);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(10);
      
        return new ViewModel([
            'despesaSubcategoria' => $paginator
        ]);
    }
    
    
    
    
    public function salvarAction()
    {               
        $form    = $this->getDespesaSubcategoriaForm();
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $despesaSubcategoria = new DespesaSubcategoria();
                $despesaSubcategoria->exchangeArray($form->getData());

                $retorno = $this->getDespesaSubcategoriaTabela()->salvar($despesaSubcategoria);
                if ($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro salvo com sucesso.');
                    return $this->redirect()->toRoute('despesa-subcategoria');
                } else {
                    $this->flashMessenger()->addErrorMessage('Erro ao salvar registro, tente novamente.');
                }
            }
        }
        
        $arrayCategoria = $this->getDespesaCategoriaTabela()->buscarTodos(false);
        
        return array(
            'form'       => $form,
            'categorias' => array_column($arrayCategoria->toArray(), 'nome', 'id')
        );
    }

    
    
    
    
    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if(!$id)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado.');
            $this->redirect()->toRoute('despesa-subcategoria');
        }
        
        $objDespesaSubc = $this->getDespesaSubcategoriaTabela()->buscarUm($id);
        if(!$objDespesaSubc)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('despesa-subcategoria');
        }

        $form     = $this->getDespesaSubcategoriaForm();        
        $form->bind($objDespesaSubc);
        
        $resquest = $this->getRequest();
        if($resquest->isPost())
        {
            $form->setData($resquest->getPost());
            if($form->isValid())
            {
                $retorno = $this->getDespesaSubcategoriaTabela()->salvar($objDespesaSubc);
                if($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro editado com sucesso.');
                    return $this->redirect()->toRoute('despesa-subcategoria');
                }else{                    
                    $this->flashMessenger()->addErrorMessage('Erro ao editar registro, tente novamente.');
                }
            }
        }
        
        $arrayCategoria = $this->getDespesaCategoriaTabela()->buscarTodos(false);
    
        return array(
            'id'           => $id,
            'form'         => $form,
            'categorias' => array_column($arrayCategoria->toArray(), 'nome', 'id'),
        );
        
    }
    
    
    
    
    
    public function deletarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if(!$id)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            $this->redirect()->toRoute('despesa-subcategoria');
        }
        
        $request = $this->getRequest();
        if($request->isPost())
        {
            $apagar = $request->getPost('apagar', 'Cancelar');
            if($apagar == 'Deletar')
            {
                $id = (int) $request->getPost('id');
                $retorno = $this->getDespesaSubcategoriaTabela()->deletar($id);
                
                if($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
                    return $this->redirect()->toRoute('desepesa-subcategoria');
                } else{
                     $this->flashMessenger()->addErrorMessage('Erro ao deletado registro, tente novamente.');
                }
            }
        }
        
        $objDespesaSub = $this->getDespesaSubcategoriaTabela()->buscarUm($id);
        if(!$objDespesaSub)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado.');
            return $this->redirect()->toRoute('despesa-categoria');
        }
        
        $view =  new ViewModel(array(
            'id'               => $id,
            'despesaSubcategoria' => $objDespesaSub
        ));

        return $view->setTerminal(true);
    }
    
}
