<?php

/*
  Criado     : 06/10/2015
  Modificado : 06/10/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel pela comunicação da view com o model
 */

namespace Receita\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Receita\Model\ReceitaCategorioria;


class ReceitaCategoriaController extends AbstractActionController
{

    protected $receitaCategoriaTabela;
    protected $receitaCategoriaForm;
    protected $clienteTabela;







    /*
     * Retorna um serviço
     * @return Receita\Model\BdTabela\ReceitaCategorioriaTabela
     *
    */
    private function getReceitaCategoriaTabela()
    {
        if(!$this->receitaCategoriaTabela)
        {
            $this->receitaCategoriaTabela = $this->getServiceLocator()->get('Receita\Model\BdTabela\ReceitaCategorioriaTabela');
        }
        return $this->receitaCategoriaTabela;
    }





    /*
     * Retorna um serviço
     * @return Receita\Form\ReceitaCategoriaForm
     *
    */
    private function getReceitaCategoriaForm()
    {
        if(!$this->receitaCategoriaForm)
        {
            $this->receitaCategoriaForm = $this->getServiceLocator()->get('Receita\Form\ReceitaCategoriaForm');
        }
        return $this->receitaCategoriaForm;
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
        $paginator   = $this->getReceitaCategoriaTabela()->buscartodos(true, $filtros);
        $paginaAtual = filter_var($this->params()->fromRoute('page', 1), FILTER_SANITIZE_NUMBER_INT);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(2);

        return new ViewModel(array(
            'receitaCategorias' => $paginator,
        ));
    }





    public function salvarAction()
    {
        $form    = $this->getReceitaCategoriaForm();
        $request = $this->getRequest();

        if($request->isPost())
        {
            $form->setData($request->getPost());

            if($form->isValid())
            {
                $receitaCat = new ReceitaCategorioria();
                $receitaCat->exchangeArray($form->getData());
                $this->getReceitaCategoriaTabela()->salvar($receitaCat);

                $this->flashMessenger()->addSuccessMessage("Registro cadastrado com sucesso.");
                return $this->redirect()->toRoute('receita-categoria');
            }
        }

        $arrayCliente = $this->getClienteTabela()->buscarTodos(false);
        $arrayCliente = array_column($arrayCliente->toArray(), 'nome', 'id');

        return array(
            'form'       => $form,
            'fk_cliente' => $arrayCliente
        );
    }




    public function editarAction()
    {
        $id = filter_var($this->params()->fromRoute('id', 0), FILTER_SANITIZE_NUMBER_INT);

        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('receita-categoria');
        }

        $receitaCat = $this->getReceitaCategoriaTabela()->buscarUm($id);
        if(!$receitaCat){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('receita-categoria');
        }

        $form = $this->getReceitaCategoriaForm();
        $form->bind($receitaCat);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $this->getReceitaCategoriaTabela()->salvar($receitaCat);
                $this->flashMessenger()->addSuccessMessage('Registro atualizado com sucesso');
                return $this->redirect()->toRoute('receita-categoria');
            }
        }

        $arrayCliente = $this->getClienteTabela()->buscarTodos(false);
        $arrayCliente = array_column($arrayCliente->toArray(), 'nome', 'id');

        return array(
            'id'         => $id,
            'form'       => $form,
            'fk_cliente' => $arrayCliente,
        );
    }




    public function deletarAction()
    {
        $id = filter_var($this->params()->fromRoute('id', 0), FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado.");
            return $this->redirect()->toRoute('receita-categoria');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Voltar');

            if ($del == 'Deletar') {
                $id = (int) $request->getPost('id');
                $this->getReceitaCategoriaTabela()->deletar($id);
                $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
            }

            return $this->redirect()->toRoute('receita-categoria');
        }

        $objConta = $this->getReceitaCategoriaTabela()->buscarUm($id);
        if(!$objConta){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('receita-categoria');
        }

        $view =  new ViewModel(array(
            'id'               => $id,
            'receitaCategoria' => $objConta
        ));

        return $view->setTerminal(true);
    }
}