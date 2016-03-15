<?php

/*
  Criado     : 09/10/2015
  Modificado : 09/10/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel pela comunicação da view com o model
 */

namespace Receita\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Receita\Model\Receita;


class ReceitaController extends AbstractActionController
{

    protected $receitaTabela;
    protected $receitaForm;
    protected $clienteTabela;
    protected $categoriaTabela;
    protected $receitaSubcateTabela;
    protected $contaTabela;







    /*
     * Retorna um serviço
     * @return Receita\Model\BdTabela\ReceitaTabela
     *
    */
    private function getReceitaTabela()
    {
        if(!$this->receitaTabela)
        {
            $this->receitaTabela = $this->getServiceLocator()->get('Receita\Model\BdTabela\ReceitaTabela');
        }
        return $this->receitaTabela;
    }





    /*
     * Retorna um serviço
     * @return Receita\Form\ReceitaForm
     *
    */
    private function getReceitaForm()
    {
        if(!$this->receitaForm)
        {
            $this->receitaForm = $this->getServiceLocator()->get('Receita\Form\ReceitaForm');
        }
        return $this->receitaForm;
    }





    /*
     * Retorna um serviço
     * @return Receita\Model\BdTabela\CategoriaTabela
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




    /*
     * Retorna um serviço
     * @return Receita\Model\BdTabela\ReceitaSubcategoriaTabela
    */
    private function ReceitaSubcategoriaTabela()
    {
        if(!$this->receitaSubcateTabela)
        {
            $this->receitaSubcateTabela = $this->getServiceLocator()->get('Receita\Model\BdTabela\ReceitaSubcategoriaTabela');
        }
        return $this->receitaSubcateTabela;
    }




    /*
     * Retorna um serviço
     * @return Conta\Model\BdTabela\contaTabela
    */
    private function getContaTabela()
    {
        if(!$this->contaTabela)
        {
            $this->contaTabela = $this->getServiceLocator()->get('Conta\Model\BdTabela\contaTabela');
        }
        return $this->contaTabela;
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
            $this->clienteTabela = $this->getServiceLocator()->get('Cliente\Model\BdTabela\clienteTabela');
        }
        return $this->clienteTabela;
    }




    public function indexAction()
    {
        $filtros     = $this->params()->fromQuery();
        $paginator   = $this->getReceitaTabela()->buscartodos(true, $filtros);
        $paginaAtual = filter_var($this->params()->fromRoute('page', 1), FILTER_SANITIZE_NUMBER_INT);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(10);

        $total = (isset($paginator->getCurrentItems()[0])) ? $paginator->getCurrentItems()[0]->total : '0.00';
        $totalPendente = (isset($paginator->getCurrentItems()[0])) ? $paginator->getCurrentItems()[0]->total_pendente : '0.00';
        $totalRecebido = (isset($paginator->getCurrentItems()[0])) ? $paginator->getCurrentItems()[0]->total_recebido : '0.00';

        return new ViewModel(array(
            'receita' => $paginator,
            'total'         => $total,
            'totalPendente' => $totalPendente,
            'totalRecebido' => $totalRecebido
        ));
    }





    public function salvarAction()
    {
        $form    = $this->getReceitaForm();
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
                $receita = new Receita();
                $receita->exchangeArray($form->getData());
                
                $retorno = $this->getReceitaTabela()->salvar($receita);

                if($retorno){
                    $this->flashMessenger()->addSuccessMessage("Registro cadastrado com sucesso.");
                    return $this->redirect()->toRoute('receita');
                }else{
                    $this->flashMessenger()->addErrorMessage("Erro ao salvar registro, tente novamente.");
                }
            }
        }

        $arrayCliente   = $this->getClienteTabela()->buscarTodos(false);
        $arrayCategoria = $this->getCategoriaTabela()->buscarTodos(false);
        $arraySubcat    = $this->ReceitaSubcategoriaTabela()->buscarTodos(false);
        $arrayConta     = $this->getContaTabela()->buscarTodos(false);

        return array(
            'form'            => $form,
            'fk_cliente'      => array_column($arrayCliente->toArray(),   'nome', 'id'),
            'fk_categoria'    => array_column($arrayCategoria->toArray(), 'nome', 'id'),
            'fk_subcategoria' => array_column($arraySubcat->toArray(),    'nome', 'id'),
            'fk_conta'        => array_column($arrayConta->toArray(),     'nome', 'id'),
        );
    }




    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('receita');
        }

        $receita = $this->getReceitaTabela()->buscarUm($id);
        if(!$receita){
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('receita');
        }

        $form = $this->getReceitaForm();
        $form->bind($receita);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());

            if ($form->isValid())
            {
            
                $ok = $this->getReceitaTabela()->editar($receita);
                if($ok){
                    $this->flashMessenger()->addSuccessMessage('Registro atualizado com sucesso');
                    return $this->redirect()->toRoute('receita');
                }
                
                $this->flashMessenger()->addSuccessMessage('Erro ao editar registro, tende novamente');
            }
        }

        $arrayCliente   = $this->getClienteTabela()->buscarTodos(false);
        $arrayCategoria = $this->getCategoriaTabela()->buscarTodos(false);
        $arraySubcat    = $this->ReceitaSubcategoriaTabela()->buscarTodos(false);
        $arrayConta     = $this->getContaTabela()->buscarTodos(false);

        return array(
            'id'              => $id,
            'form'            => $form,
            'fk_cliente'      => array_column($arrayCliente->toArray(),   'nome', 'id'),
            'fk_categoria'    => array_column($arrayCategoria->toArray(), 'nome', 'id'),
            'fk_subcategoria' => array_column($arraySubcat->toArray(),    'nome', 'id'),
            'fk_conta'        => array_column($arrayConta->toArray(),     'nome', 'id')
        );
    }




    
    public function deletarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado.");
            return $this->redirect()->toRoute('receita');
        }

        $objReceita = $this->getReceitaTabela()->buscarUm($id);
        if (!$objReceita) {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('receita');
        }
        
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $arrayId[]      = $id;
            $receAssociadas = $request->getPost('receitasAssociadas', null);
            
            if(!empty($objReceita->getGrupo())){
                if($receAssociadas == 'todas'){
                    $arrayId = $this->getReceitaTabela()->buscarAssociados($objReceita);
                }
                elseif($receAssociadas == 'estaEDemais'){
                    $arrayId = $this->getReceitaTabela()->buscarAssociados($objReceita, ['dataVen' => true]);
                }
            }
            
            $retorno = $this->getReceitaTabela()->deletar($arrayId);
            if($retorno){
                $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
            }else{
                $this->flashMessenger()->addErrorMessage('Erro ao deletar registro, tente novamente.');
            }
            
            return $this->redirect()->toRoute('receita');
        }       

        $viewModel = new ViewModel([
            'id'      => $id,
            'receita' => $objReceita
        ]);

        $viewModel->setTerminal(true);
        return $viewModel;
    }

}