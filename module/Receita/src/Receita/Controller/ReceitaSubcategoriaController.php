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

use Receita\Model\ReceitaSubcategoria;


class ReceitaSubcategoriaController extends AbstractActionController
{

    protected $receitaSubTabela;
    protected $receitaSubForm;
    protected $categoriaTabela;







    /*
     * Retorna um serviço
     * @return Receita\Model\BdTabela\ReceitaSubcategoriaTabela
     *
    */
    private function getReceitaSubTabela()
    {
        if(!$this->receitaSubTabela)
        {
            $this->receitaSubTabela = $this->getServiceLocator()->get('Receita\Model\BdTabela\ReceitaSubcategoriaTabela');
        }
        return $this->receitaSubTabela;
    }





    /*
     * Retorna um serviço
     * @return Receita\Form\ReceitaSubcategoriaForm
     *
    */
    private function getReceitaSubForm()
    {
        if(!$this->receitaSubForm)
        {
            $this->receitaSubForm = $this->getServiceLocator()->get('Receita\Form\ReceitaSubcategoriaForm');
        }
        return $this->receitaSubForm;
    }





    /*
     * Retorna um serviço
     * @return Cliente\Model\BdTabela\ClienteTabela
     *
    */
    private function getCategoriaTabela()
    {
        if(!$this->categoriaTabela)
        {
            $this->categoriaTabela = $this->getServiceLocator()->get('Receita\Model\BdTabela\ReceitaCategorioriaTabela');
        }
        return $this->categoriaTabela;
    }





    public function indexAction()
    {
        $filtros     = $this->params()->fromQuery();
        $paginator   = $this->getReceitaSubTabela()->buscartodos(true, $filtros);
        $paginaAtual = filter_var($this->params()->fromRoute('page', 1), FILTER_SANITIZE_NUMBER_INT);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(2);

        return new ViewModel(array(
            'receitaSub' => $paginator,
        ));
    }





    public function salvarAction()
    {
        $form    = $this->getReceitaSubForm();
        $request = $this->getRequest();

        if($request->isPost())
        {
            $form->setData($request->getPost());

            if($form->isValid())
            {
                $receitaSub = new ReceitaSubcategoria();
                $receitaSub->exchangeArray($form->getData());
                $this->getReceitaSubTabela()->salvar($receitaSub);

                $this->flashMessenger()->addSuccessMessage("Registro cadastrado com sucesso.");
                return $this->redirect()->toRoute('receita-subcategoria');
            }
        }

        $arrayCategoria = $this->getCategoriaTabela()->buscarTodos(false);

        return array(
            'form'         => $form,
            'fk_categoria' => array_column($arrayCategoria->toArray(), 'nome', 'id')
        );
    }




    public function editarAction()
    {
        $id = filter_var($this->params()->fromRoute('id', 0), FILTER_SANITIZE_NUMBER_INT);

        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('receita-subcategoria');
        }

        $receitaSub = $this->getReceitaSubTabela()->buscarUm($id);
        if(!$receitaSub){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('receita-subcategoria');
        }

        $form = $this->getReceitaSubForm();
        $form->bind($receitaSub);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $this->getReceitaSubTabela()->salvar($receitaSub);
                $this->flashMessenger()->addSuccessMessage('Registro atualizado com sucesso');
                return $this->redirect()->toRoute('receita-subcategoria');
            }
        }

        $arrayCategoria = $this->getCategoriaTabela()->buscarTodos(false);
        $arrayCategoria = array_column($arrayCategoria->toArray(), 'nome', 'id');

        return array(
            'id'         => $id,
            'form'       => $form,
            'fk_categoria' => $arrayCategoria,
        );
    }




    public function deletarAction()
    {
        $id = filter_var($this->params()->fromRoute('id', 0), FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado.");
            return $this->redirect()->toRoute('receita-subcategoria');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Voltar');

            if ($del == 'Deletar') {
                $id = (int) $request->getPost('id');
                $this->getReceitaSubTabela()->deletar($id);
                $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
            }

            return $this->redirect()->toRoute('receita-categoria');
        }

        $objConta = $this->getReceitaSubTabela()->buscarUm($id);
        if(!$objConta){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('receita-categoria');
        }

        $viewModel = new ViewModel(array(
            'id'         => $id,
            'receitaSub' => $objConta
        ));

        return $viewModel->setTerminal(true);
    }
}