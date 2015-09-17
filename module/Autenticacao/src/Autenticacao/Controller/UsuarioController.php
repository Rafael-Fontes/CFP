<?php


namespace Autenticacao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
