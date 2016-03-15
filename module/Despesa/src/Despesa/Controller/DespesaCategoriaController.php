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

use Despesa\Model\DespesaCategoria;


class DespesaCategoriaController extends AbstractActionController
{

    protected $despesaCategoriaTabela;
    protected $despesaCategoriaForm;
    protected $clienteTabela;




    /*
     * Retorna um serviço
     * @return Despesa\Model\BdTabela\DespesaCategorioriaTabela
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





    /*
     * Retorna um serviço
     * @return Despesa\Form\DespesaCategoriaForm
     *
    */
    private function getDespesaCategoriaForm()
    {
        if(!$this->despesaCategoriaForm)
        {
            $this->despesaCategoriaForm = $this->getServiceLocator()->get('Despesa\Form\DespesaCategoriaForm');
        }
        return $this->despesaCategoriaForm;
    }





    /*
     * Retorna um serviço
     * @return Cliente\Model\BdTabela\ClienteTabela
     *
    */
    private function getClienteTabela()
    {
        if(!$this->clienteTabela)
        {
            $this->clienteTabela = $this->getServiceLocator()->get('Cliente\Model\BdTabela\ClienteTabela');
        }
        return $this->clienteTabela;
    }




    public function indexAction()
    {
        $filtros     = $this->params()->fromQuery();
        $paginator   = $this->getDespesaCategoriaTabela()->buscartodos(true, $filtros);
        $paginaAtual = filter_var($this->params()->fromRoute('page', 1), FILTER_SANITIZE_NUMBER_INT);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(10);

        return new ViewModel([
            'despesasCategorias' => $paginator
        ]);
    }




    public function salvarAction()
    {
        $form    = $this->getDespesaCategoriaForm();
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $despesaCategoria = new DespesaCategoria();
                $despesaCategoria->exchangeArray($form->getData());

                $retorno = $this->getDespesaCategoriaTabela()->salvar($despesaCategoria);
                if ($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro salvo com sucesso.');
                    return $this->redirect()->toRoute('despesa-categoria');
                } else {
                    $this->flashMessenger()->addErrorMessage('Erro ao salvar registro, tente novamente.');
                }
            }
        }

        $arrayCliente = $this->getClienteTabela()->buscarTodos(false);

        return array(
            'form'       => $form,
            'fk_cliente' => array_column($arrayCliente->toArray(), 'nome', 'id')
        );
    }





    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(!$id)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado.');
            $this->redirect()->toRoute('despesa-categoria');
        }

        $objDespesaCat = $this->getDespesaCategoriaTabela()->buscarUm($id);
        if(!$objDespesaCat)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('despesa-categoria');
        }

        $form = $this->getDespesaCategoriaForm();
        $form->bind($objDespesaCat);

        $resquest = $this->getRequest();
        if($resquest->isPost())
        {
            $form->setData($resquest->getPost());
            if($form->isValid())
            {
                $retorno = $this->getDespesaCategoriaTabela()->salvar($objDespesaCat);
                if($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro editado com sucesso.');
                    return $this->redirect()->toRoute('despesa-categoria');
                }else{
                    $this->flashMessenger()->addErrorMessage('Erro ao editar registro, tente novamente.');
                }
            }
        }

        $arrayCliente = $this->getClienteTabela()->buscarTodos(false);

        return array(
            'id'         => $id,
            'form'       => $form,
            'fk_cliente' => array_column($arrayCliente->toArray(), 'nome', 'id'),
        );
    }





    public function deletarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(!$id)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            $this->redirect()->toRoute('despesa-categoria');
        }

        $request = $this->getRequest();
        if($request->isPost())
        {
            $deletar = $request->getPost('deletar', 'cancelar');
            if($deletar == 'sim')
            {
                $id = (int) $request->getPost('id');
                $retorno = $this->getDespesaCategoriaTabela()->deletar($id);

                if($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
                    return $this->redirect()->toRoute('despesa-categoria');
                } else{
                     $this->flashMessenger()->addSuccessMessage('Erro ao deletado registro, tente novamente.');
                }
            }
        }

        $objDespesaCat = $this->getDespesaCategoriaTabela()->buscarUm($id);
        if(!$objDespesaCat)
        {
            $this->flashMessenger()->addErrorMessage('Registro não encontrado.');
            return $this->redirect()->toRoute('despesa-categoria');
        }

        $view =  new ViewModel(array(
            'id'               => $id,
            'despesaCategoria' => $objDespesaCat
        ));

        return $view->setTerminal(true);
    }

}
