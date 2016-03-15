$(document).ready(function ()
{
    /*
     * Retorna o valor do parametro passado pelo metodo GET
     * @param  String parametro
     * @return String | Array |
     */
    getParametroUrl = function (parametro)
    {
        var arrayUrl   = $(location).attr('search').replace('?', '').split('&');
        var arrayParam = [];

        for (var i = 0; i < arrayUrl.length; i++)
        {
            var valor = arrayUrl[i].split('=');
            if (valor[0] === parametro)
                return valor[1];

            if(valor[0] !== '' && valor[0] !== undefined)
                arrayParam[valor[0]] = decodeURIComponent(valor[1].replace(/\+/g, " "));
        }

        return arrayParam;
    };





    /*
     * Adiciona parametros do tipo GET na url
     * @param  Obj parametros
     */
    montarUrl = function(parametros)
    {
        var minhaUrl = $(location).attr('pathname');
        var pagina   = '/page/1?';

        if(minhaUrl.search('/page/') !== -1)
        {
            $(location).attr('href', minhaUrl + "?" + parametros);
        }
        else
        {
            if(minhaUrl.slice(-1) === '/')
                pagina = 'page/1?';

            $(location).attr('href', minhaUrl + pagina + parametros);
        }
    };





    /*
     * Responsavel pelo filtro das tabelas de listagem existente nas
     * páginas index.
     */
    filtroTabela = function ()
    {
        $('#filtro-tabela-submit').click(function ()
        {
            var meuObj     = '';
            var paramExtra = '';
            var arrayData  = '';
            var inputData  = $('input:text[name=data]');

            if(inputData.length && inputData.val() !== '' && inputData.val() !== undefined)
            {
                arrayData  = inputData.val().split('-');
                paramExtra = '&data=' + moment().year(arrayData[1])
                                                .month(arrayData[0])
                                                .day('01')
                                                .format("YYYY-MM-DD");
            }

            meuObj = $('table > tbody > tr:first-child').find('input, select').filter(function ()
            {
                return $(this).val();
            }).serialize().concat(paramExtra);

            montarUrl(decodeURIComponent(meuObj.replace(/\+/g, " ")));
        });

        //Populando o formulario de busca com seus respectivos valores
        var parametrosUrl     = getParametroUrl();
        var chaveParametroUrl = Object.keys(parametrosUrl);
        var elementoFiltro    = $('table > tbody > tr:first-child');

        for(var i = 0; i < chaveParametroUrl.length; i++)
        {
          elementoFiltro.find('input[name='+chaveParametroUrl[i]+']').attr('value', parametrosUrl[chaveParametroUrl[i]]);
          elementoFiltro.find('select[name='+chaveParametroUrl[i]+']')
                  .find('option[value='+parametrosUrl[chaveParametroUrl[i]]+']')
                  .prop('selected', true);
        }
    };





    /*
     * Função exibe um calendário no formato mês e ano,
     * envia um parametro GET para a url e recarrega a página
     * @Exemplo:  <input name='data' id='calendario-mes-ano' />
     */
    filtroMesAno = function()
    {
        //Inicializa o calendário e ativa o filtro pela data, toda vez que o valor
        //for alterado.
        $('#calendario-mes-ano').datepicker({
            autoclose       : true,
            language        : 'ptBr',
            format          : 'MM, yyyy',
            minViewMode     : 'months',
            todayHighlight  : true,
        })
        .on('changeDate', function ()
        {
            var dataFiltro = $('input[name=data]');
            if(dataFiltro.val() !== '')
            {
                var arrayData  = dataFiltro.val().split(',');
                //Segundo o plugin moment.js o mês de Janeiro 0 e Dezembro 11
                var mes        = (moment().month(arrayData[0]).format('MM') - 1);
                var parametros = 'data='+ moment([arrayData[1], mes, '01']).format('YYYY-MM-DD');

                montarUrl(parametros);
            }
        });

        var parametroUrl = moment(getParametroUrl('data')).format("MMMM - YYYY");
        $('#calendario-mes-ano').attr('value', parametroUrl);
    };



    //Inicianizar funções
    filtroMesAno();
    filtroTabela();


});