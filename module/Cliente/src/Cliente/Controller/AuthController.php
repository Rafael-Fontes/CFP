<?php

/*
  Criado     : 23/09/2015
  Modificado : 17/12/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Responsavel pela autenticação do usuário.
 */


namespace Cliente\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\SessionManager;


class AuthController extends AbstractActionController
{


    private  $authForm;
    private  $authenticationService;




    /*
     * Metodo retorna o serviço que contem a instancia da classe
     * @return Zend\Authentication\AuthenticationService
     *
    */
    private function getAuthenticationService()
    {
        if(!$this->authenticationService)
        {
            $this->authenticationService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        }
        return $this->authenticationService;
    }





    /*
     * Metodo retorna o serviço que contem a instancia da classe
     * @return Cliente\Form\AuthForm
     *
    */
    private function getAuthForm()
    {
        if(!$this->authForm)
        {
            $this->authForm = $this->getServiceLocator()->get('Cliente\Form\AuthForm');
        }

        return $this->authForm;
    }





    public function loginAction()
    {
        $form    = $this->getAuthForm();
        $request = $this->getRequest();

        if($request->isPost())
        {
            $form->setData($request->getPost());

            if($form->isValid())
            {
                $authenticationService = $this->getAuthenticationService();
                $authAdapter           = $authenticationService->getAdapter();

                $authAdapter->setLogin($request->getPost('email'));
                $authAdapter->setSenha($request->getPost('senha'));

                $resultado = $authenticationService->authenticate()->isValid();
                if($resultado)
                {
                    $authenticationService->getStorage()->write($authenticationService->getIdentity()['cliente']);

                    if($request->getPost('lembrar') == 'sim')
                    {
                        $tempo = 2592000000; // 30 dias em milissegundos
                        $SessionManager = new SessionManager();
                        $SessionManager->rememberMe($tempo);
                    }

                    return $this->redirect()->toRoute('cliente', array('controller' => 'cliente', 'action' => 'index'));
                }
                else
                {
                    echo "Login ou senha incorreto";
                }
            }
        }

        $viewModel = new ViewModel([
            'form' => $form
        ]);

        return $viewModel->setTerminal(true);
    }





    public function logoutAction()
    {
        $auth = $this->getAuthenticationService();
        $auth->clearIdentity();

        $this->redirect()->toRoute('login');
    }

}
