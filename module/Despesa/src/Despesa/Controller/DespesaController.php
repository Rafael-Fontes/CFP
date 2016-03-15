<?php

/*
  Criado     : 23/11/2015
  Modificado : 23/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel pela comunicação da view com o model
 */

namespace Despesa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Despesa\Model\Despesa;


class DespesaController extends AbstractActionController
{

    protected $despesaTabela;
    protected $despesaForm;
    protected $clienteTabela;
    protected $despesaCategoriaTabela;
    protected $despesaSubcategoriaTabela;
    protected $contaTabela;




    /*
     * Retorna um serviço
     * @return Despesa\Model\BdTabela\DespesaTabela
     *
    */
    private function getDespesaTabela()
    {
        if(!$this->despesaTabela)
        {
            $this->despesaTabela = $this->getServiceLocator()->get('Despesa\Model\BdTabela\DespesaTabela');
        }
        return $this->despesaTabela;
    }





    /*
     * Retorna um serviço
     * @return Despesa\Form\DespesaForm
     *
    */
    private function getDespesaForm()
    {
        if(!$this->despesaForm)
        {
            $this->despesaForm = $this->getServiceLocator()->get('Despesa\Form\DespesaForm');
        }
        return $this->despesaForm;
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





    /*
     * Retorna um serviço
     * @return Despesa\Model\BdTabela\DespesaCategoriaTabela
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
     * @return Despesa\Model\BdTabela\DespesaSubcategoriaTabela
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
     * @return Conta\Model\BdTabela\ContaTabela
     *
    */
    private function getContaTabela()
    {
        if(!$this->contaTabela)
        {
            $this->contaTabela = $this->getServiceLocator()->get('Conta\Model\BdTabela\ContaTabela');
        }
        return $this->contaTabela;
    }





    public function indexAction()
    {
        $filtros     = $this->params()->fromQuery();
        $paginator   = $this->getDespesaTabela()->buscartodos(true, $filtros);
        $paginaAtual = (int) $this->params()->fromRoute('page', 1);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(10);

        $total = (isset($paginator->getCurrentItems()[0])) ? $paginator->getCurrentItems()[0]->total : '0.00';
        $totalPendente = (isset($paginator->getCurrentItems()[0])) ? $paginator->getCurrentItems()[0]->totalPendente : '0.00';
        $totalRecebido = (isset($paginator->getCurrentItems()[0])) ? $paginator->getCurrentItems()[0]->totalRecebido : '0.00';

        return new ViewModel(array(
            'despesas'      => $paginator,
            'total'         => $total,
            'totalPendente' => $totalPendente,
            'totalRecebido' => $totalRecebido,
        ));
    }





    public function visualizarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado.');
            return $this->redirect()->toRoute('despesa');
        }

        $objDespesa = $this->getDespesaTabela()->buscarUm($id);
        if(!$objDespesa){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado.');
            return $this->redirect()->toRoute('despesa');
        }


        return new ViewModel([
            'objDespesa' => $objDespesa
        ]);
    }





    public function salvarAction()
    {
        $form    = $this->getDespesaForm();
        $request = $this->getRequest();

        if($request->isPost())
        {
            $postData = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $form->setData($postData);

            if($form->isValid())
            {
                $objDespesa = new Despesa();
                $objDespesa->exchangeArray($form->getData());

                $retorno = $this->getDespesaTabela()->salvar($objDespesa);
                if($retorno){
                    $this->flashMessenger()->addSuccessMessage("Registro cadastrado com sucesso.");
                    return $this->redirect()->toRoute('despesa');
                }else{
                    $this->flashMessenger()->addErrorMessage("Erro ao salvar registro, tente novamente.");
                }
            }
        }

        $arrayCliente      = $this->getClienteTabela()->buscarTodos(false);
        $arrayCategoria    = $this->getDespesaCategoriaTabela()->buscarTodos(false);
        $arraySubcategoria = $this->getDespesaSubcategoriaTabela()->buscarTodos(false);
        $arrayContas       = $this->getContaTabela()->buscarTodos(false);

        return array(
            'form'          => $form,
            'clientes'      => array_column($arrayCliente->toArray(), 'nome', 'id'),
            'categorias'    => array_column($arrayCategoria->toArray(), 'nome', 'id'),
            'subcategorias' => array_column($arraySubcategoria->toArray(), 'nome', 'id'),
            'contas'        => array_column($arrayContas->toArray(), 'nome', 'id'),
        );
    }





    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('receita-categoria');
        }

        $objDespesa = $this->getDespesaTabela()->buscarUm($id);
        if(!$objDespesa){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('despesa');
        }

        $form = $this->getDespesaForm();
        $form->bind($objDespesa);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $postData = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $form->setData($postData);
            if ($form->isValid())
            {
                $resultado = $this->getDespesaTabela()->editar($objDespesa);
                if($resultado){
                    $this->flashMessenger()->addSuccessMessage('Registro atualizado com sucesso');
                    return $this->redirect()->toRoute('despesa');
                }else{
                    $this->flashMessenger()->addErrorMessage('Erro ao editar registro, tente novamente.');
                }
            }
        }

        $arrayCliente      = $this->getClienteTabela()->buscarTodos(false);
        $arrayCategoria    = $this->getDespesaCategoriaTabela()->buscarTodos(false);
        $arraySubcategoria = $this->getDespesaSubcategoriaTabela()->buscarTodos(false);
        $arrayContas       = $this->getContaTabela()->buscarTodos(false);

        return array(
            'id'            => $id,
            'form'          => $form,
            'clientes'      => array_column($arrayCliente->toArray(), 'nome', 'id'),
            'categorias'    => array_column($arrayCategoria->toArray(), 'nome', 'id'),
            'subcategorias' => array_column($arraySubcategoria->toArray(), 'nome', 'id'),
            'contas'        => array_column($arrayContas->toArray(), 'nome', 'id'),
        );
    }





    public function deletarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado.");
            return $this->redirect()->toRoute('despesa');
        }

        $objDespesa = $this->getDespesaTabela()->buscarUm($id);
        if(!$objDespesa){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('despesa');
        }

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $arrayId[]      = $id;
            $despAssociadas = $request->getPost('despesasAssociadas', null);

            if(!empty($objDespesa->getGrupo())){
                if($despAssociadas == 'todas'){
                    $arrayId = $this->getDespesaTabela()->buscarAssociados($objDespesa);
                }
                elseif($despAssociadas == 'estaEDemais'){
                    $arrayId = $this->getDespesaTabela()->buscarAssociados($objDespesa, ['dataVen' => true]);
                }
            }

            $retorno = $this->getDespesaTabela()->deletar($arrayId);
            if($retorno){
                $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
            }else{
                $this->flashMessenger()->addErrorMessage('Erro ao deletar registro, tente novamente.');
            }

            return $this->redirect()->toRoute('despesa');
        }

        $view =  new ViewModel([
            'id'      => $id,
            'despesa' => $objDespesa,
        ]);

        $view->setTerminal(true);
        return $view;
    }
}