<?php

namespace ClienteTest\Controller;


use Zend\Http\Request as HttpRequest;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


class ClienteControllerTest extends AbstractHttpControllerTestCase
{

    protected $traceError = true;



    public function setUp()
    {
        $this->setApplicationConfig(
            include '/config/application.config.php'
        );

        parent::setUp();
    }




    public function testAcessoAPaginaIndex()
    {
        $this->dispatch('/cliente');
        $this->assertResponseStatusCode(200);
    }

}
