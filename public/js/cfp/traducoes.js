/*
  Criado     : 10/11/2015
  Modificado : 10/11/2015
  Autor      : Raphaell
  Contato    : rafaelk-f@hotmail.com
  Descrição  :

        Comtem a tradução para algumas bibliotecas javascript;
 */

$(document).ready(function ()
{
   
    /*
     * Tradução do plugin datepicker bootstrap
     * @url https://github.com/eternicode/bootstrap-datepicker
    */
    $.fn.datepicker.dates['ptBr'] = {
        days        : ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
        daysShort   : ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        daysMin     : ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
        months      : ["Janeiro", "Fervereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort : ["Jan", "Fer", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today       : "Hoje",
        clear       : "Limpar",
        format      : "mm/dd/yyyy",
        titleFormat : "MM yyyy",
        weekStart   : 0
    };
    
    
    
    
    
    /*
     * Tradução do plugin moment.js
     * @url http://momentjs.com/
     * @url http://momentjs.com/docs/#/customization/
    */
    moment.locale('ptBr', {
        months        : ["Janeiro", "Fervereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort   : ["Jan", "Fer", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        weekdays      : ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
        weekdaysShort : ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        weekdaysMin   : ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
    });
});