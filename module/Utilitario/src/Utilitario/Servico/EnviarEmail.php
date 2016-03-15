<?php

/*
  Criado     : 17/12/2015
  Modificado : 17/12/2015
  Autor      : Raphaell
  Contato    : 
  Descrição  :

        Classe responsavel pelo envio de e-mail.
 */


namespace Utilitario\Servico;


use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;




class EnviarEmail
{
    //@return Zend\Mail\Message
    private $objMessage;
    
    //@return Zend\Mail\Transport\Smtp
    private $transporte;
    
    
    
    public function __construct($configEmail)
    {
        $smtpOptions      = new SmtpOptions($configEmail);        
        $this->transporte = new Smtp($smtpOptions);
        
        $this->objMessage = new Message();
        $this->objMessage->setEncoding("UTF-8");
    }
    
    

    
    
    /*
     * Adiciona o remetente do email
     * 
     * @param String $email
     * @param String $nome
     */
    public function setRemetente($email, $nome = null)
    {
        $this->objMessage->setFrom($email, $nome);
        return $this;
    }

    

    
    
    /*
     * Adiciona o destinatário do email
     * 
     * @param String $email
     * @param String $nome
     */
    public function setDestinatario($email, $nome = null)
    {
        $this->objMessage->addTo($email, $nome);
        return $this;
    }

    
    
    
    
    /*
     * Adiciona o assunto do email
     * 
     * @param String $assunto
     */
    public function setAssunto($assunto)
    {
        $this->objMessage->setSubject($assunto);
        return $this;
    }

    
    

    
    /*
     * Adiciona a mensagem ao email, message encontrase no formato texto
     * 
     * @param String $mensagem
     */
    public function setMensagem($conteudo, $dados = null)
    {
        //Se o conteudo correspode a um template html
        if(file_exists($conteudo))
        { echo 'oiii';
            $viewModel = new ViewModel();
            $viewModel->setTemplate($conteudo)
                      ->setTerminal(TRUE)
                      ->setOption('has_parent', true)
                      ->setVariable('dados', $dados);
         
            $render = new PhpRenderer();
            
            
            var_dump($render->render($viewModel)); die();
        }
        
        $html = new MimePart($conteudo);
        $html->type = 'text/html';
        
        $body = new MimeMessage();
        $body->setParts([$html]);
      
        $this->objMessage->setBody($body);
        
        return $this;
    }

    

    
  
    
    /*
     * Envia o email no formato texto
    */
    public function enviarEmail()
    {
        $this->transporte->send($this->objMessage);
    }
    
    
    
    
    
    /*
     * Imprime na tela mensagem que foi enviada.
     * @return String
     */
    public function imprimirEmail()
    {
        echo $this->objMessage->toString();
    }
}
