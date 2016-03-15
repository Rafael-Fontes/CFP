<?php

/*
  Criado     : 22/09/2015
  Modificado : 17/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel pela comunicação da view com o model
 */

namespace Conta\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Conta\Model\Conta;


class ContaController extends AbstractActionController
{

    protected $contaTabela;
    protected $tipoTabela;
    protected $clienteTabela;
    protected $contaForm;




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
     * @return Conta\Model\BdTabela\TipoTabela
     *
    */
    public function getClienteTabela()
    {
        if(!$this->clienteTabela)
        {
            $this->clienteTabela = $this->getServiceLocator()->get('Cliente\Model\BdTabela\ClienteTabela');
        }
        return $this->clienteTabela;
    }





    /*
     * Retorna um serviço
     * @return Conta\Form\ContaForm
     *
    */
    private function getContaForm()
    {
        if(!$this->contaForm)
        {
            $this->contaForm = $this->getServiceLocator()->get('Conta\Form\ContaForm');
        }

        return $this->contaForm;
    }





    public function indexAction()
    {
        $filtros     = $this->params()->fromQuery();
        $paginator   = $this->getContaTabela()->buscartodos(true, $filtros);
        $paginaAtual = (int) $this->params()->fromRoute('page', 1);

        $paginator->setCurrentPageNumber($paginaAtual);
        $paginator->setItemCountPerPage(10);

//        $saldoFimMes = 0;
//        foreach($paginator as $valor){
//            $saldoFimMes += (($valor->saldo + $valor->receita_valor) - $valor->despesa_valor);
//        }
        
//        echo '<pre>';
//        print_r($paginator->getCurrentItems()); die;
        
        return new ViewModel(array(
            'contas'      => $paginator,
            //'saldoFimMes' => $saldoFimMes
        ));
    }





    public function visualizarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        $conta = $this->getContaTabela()->buscarUm($id);
        if(!$conta)
        {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('conta');
        }

        return new ViewModel(array(
            'conta' => $conta,
        ));
    }





    public function salvarAction()
    {
        $form    = $this->getContaForm();
        $request = $this->getRequest();

        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $conta = new Conta();
                $conta->exchangeArray($form->getData());
                $retorno = $this->getContaTabela()->salvar($conta);

                if($retorno){
                    $this->flashMessenger()->addSuccessMessage("Registro cadastrado com sucesso.");
                    return $this->redirect()->toRoute('conta');
                }else{
                    $this->flashMessenger()->addErrorMessage("Erro ao salvar registro, tente novamente.");
                }
            }
        }

        $arrayTipo    = $this->getTipoTabela()->buscarTodos(false);
        $arrayCliente = $this->getClienteTabela()->buscarTodos(false);

        return array(
            'form'       => $form,
            'fk_tipo'    => array_column($arrayTipo->toArray(), 'nome', 'id'),
            'fk_cliente' => array_column($arrayCliente->toArray(), 'nome', 'id')
        );
    }





    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(!$id){
            $this->flashMessenger()->addErrorMessage('Registro não encontrado');
            return $this->redirect()->toRoute('conta');
        }

        $conta = $this->getContaTabela()->buscarUm($id);
        if(!$conta)
        {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('conta');
        }

        $form = $this->getContaForm();
        $form->get('saldo')->setAttribute('disabled', 'disabled');
        $form->bind($conta);

        $request = $this->getRequest();
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $retorno = $this->getContaTabela()->salvar($conta);
                if($retorno){
                    $this->flashMessenger()->addSuccessMessage('Registro atualizado com sucesso');
                    return $this->redirect()->toRoute('conta');
                }else{
                    $this->flashMessenger()->addErrorMessage("Erro ao editar registro, tente novamente.");
                }
            }
        }

        $arrayTipo    = $this->getTipoTabela()->buscarTodos(false);
        $arrayCliente = $this->getClienteTabela()->buscarTodos(false);

        return array(
            'id'         => $id,
            'form'       => $form,
            'fk_tipo'    => array_column($arrayTipo->toArray(), 'nome', 'id'),
            'fk_cliente' => array_column($arrayCliente->toArray(), 'nome', 'id')
        );
    }





    public function deletarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id)
        {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado.");
            return $this->redirect()->toRoute('conta');
        }

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $id      = (int) $request->getPost('id');
            $retorno = $this->getContaTabela()->deletar($id);

            if($retorno){
                $this->flashMessenger()->addSuccessMessage('Registro deletado com sucesso.');
            }else{
                $this->flashMessenger()->addErrorMessage("Erro ao deletar registro, tente novamente.");
            }

            return $this->redirect()->toRoute('conta');
        }

        $objConta = $this->getContaTabela()->buscarUm($id);
        if (!$objConta)
        {
            $this->flashMessenger()->addErrorMessage("Registro não encontrado");
            return $this->redirect()->toRoute('conta');
        }

        $view = new ViewModel([
            'id'    => $id,
            'conta' => $objConta
        ]);
        
        $view->setTerminal(true);
        return $view;
    }

}