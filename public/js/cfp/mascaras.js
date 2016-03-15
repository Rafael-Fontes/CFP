$(document).ready(function()
{
    $('input:text[data-mascara=telefone]').mask('(99) 9999-9999?9');
    $('input:text[data-mascara=mascara-data-br1]').mask('00/00/0000');
    $('input:text[data-mascara=mascara-data-br2]').mask('00-00-0000');
    
    
    //R$ 1.000,00
    $('input:text[data-mascara=dinheiro-br1]').maskMoney({
        prefix       :'R$ ',
        allowNegative: true,
        thousands    :'.', 
        decimal      :',', 
        affixesStay  : false
    });
    
    
    //R$ 1000.00
    $('input:text[data-mascara=dinheiro-br2]').maskMoney({
        prefix       :'R$ ',
        allowNegative: true,
        thousands    :'', 
        decimal      :'.', 
        affixesStay  : false
    });


    //12/11/2015
    $('input[data-calendario=data-br1]').datepicker({
        language        : 'ptBr',
        autoclose       : true,
        format          : 'dd/mm/yyyy',
        todayHighlight  : true,
    });
    
});