<?php

namespace Utilitario\Validacao;

use Zend\Validator\AbstractValidator;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\TableIdentifier;
use Zend\Validator\Exception;


class TesteUnicidade extends AbstractValidator
{    
    
    //Constante que contem a mensagem retornada     
    const MSG_REGISTRO_ENCONTRADO = 'msgRegistroEncontrado';    
    
    
    //@var \Zend\Db\Adapter\Adapter     
    protected $adapter = null;
    
    
    //tabela do BD a ser usada na consulta
    private $tabela = null;
    
    
    //campo do formulário que esta sendo validado
    private $campo = null;

    
    //campos que seram usados na comparação
    private $camposExtra = array();
    
    
    //Contem os campos e valores que serão negados no sql
    //ex: id != 1
    private $negar = array();    
    
    

    //Mensagem padrão
    protected $messageTemplates = array(
        self::MSG_REGISTRO_ENCONTRADO => "Registro já encontra-se cadastrado",
    );
    
    
    
    
    
    public function __construct($options = null) 
    {
        parent::__construct($options);
        
        if(is_array($options))
        {
            if(array_key_exists("adapter", $options) && !empty($options['adapter'])){
                $this->setAdapter($options['adapter']);
            }
            else{
                throw new Exception\InvalidArgumentException('Necessario informa Adapter.');
            }
            
            
            if(array_key_exists("tabela", $options) && !empty($options['tabela'])){
                $this->tabela = $options['tabela'];
            }
            else{
                throw new Exception\InvalidArgumentException('Necessario informa a tabela do BD.');
            }

            
            if(array_key_exists("campo", $options) && !empty($options['campo'])){
                $this->campo = $options['campo'];
            }
            else{
                throw new Exception\InvalidArgumentException('Necessario informa o campo a ser validado.');
            }
            
            
            if(array_key_exists('negar', $options) && count($options['negar']) > 0){
                $this->negar = $options['negar'];
            }
            
            if(array_key_exists("camposExtra", $options) && count($options['camposExtra']) > 0){
                $this->camposExtra = $options['camposExtra'];
            }
        }
    }
    
    
    
    
    
    /**
     * @return DbAdapter
     */
    protected function getAdapter()
    {
        return $this->adapter;
    }
    

    
    
    
    /**
     * @param  DbAdapter $adapter
     * @return self Provides a fluent interface
     */
    protected function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    
    

    
    /*
     * Monta um array contendo os campos e seus respectivos valores que
     * seram usados na consulta
     * 
     * @param  Array $arrayChave   nome dos campos da consulta sql
     * @param  Array $arrayValores valores dos campos da consulta sql
     * @return Array
     */
    private function montarArray(Array $arrayChave, Array $arrayValores)
    {
        $arrayDados = array();
        
        if(count($arrayChave) > 0 && count($arrayValores) > 0)
        {
            foreach ($arrayChave as $chave)
            {
                $arrayDados[$chave] = 
                            (array_key_exists($chave, $arrayValores)) ? $arrayValores[$chave] : null;
            }
        }
        
        return array_filter($arrayDados);
    }
    
    
    
    
    
    /*
     * Monta e execulta o sql necessário na consulta
     * 
     * @param String $valor
     * @param Array|null $valoresExtras
     * @return Array|Boolean
     */
    public function query($valor, $valoresExtras = null)
    {
        $select = new Select();
        $identificadorTabela = new TableIdentifier($this->tabela);
        $select->from($identificadorTabela)->columns([$this->campo]);
        
        $paramConsulta         = $this->montarArray($this->camposExtra, $valoresExtras);
        $paramConsultaIngnorar = $this->montarArray($this->negar, $valoresExtras);        
        $paramConsulta[$this->campo] = $valor;        
        
        
        foreach($paramConsulta as $chave => $valor){
            $select->where->equalTo($chave, $valor);
        }
        
        if(count($paramConsultaIngnorar) > 0){
            foreach($paramConsultaIngnorar as $chave => $valor){
                $select->where->notEqualTo($chave, $valor);
            }   
        }
    
        $sql       = new Sql($this->getAdapter());
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
 
        return $result->current();   
    }
    
    
    
    
    
    /*
        Validação é execultada

        @param  String $valor
        @param  Array $context
        @return Boolean
     */
    public function isValid($valor, $context = null)
    { 
        $this->setValue($valor);
       
        $validacao = true;
        $resultado = $this->query($valor, $context);

        if ($resultado) {
            $validacao = false;
            $this->error(self::MSG_REGISTRO_ENCONTRADO);
        }

        return $validacao;
    }
}