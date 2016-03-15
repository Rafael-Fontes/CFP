$(document).ready(function ()
{
    $('#btn_crud > a').on('click', function (evn)
    {
        if($(this).attr('data-acao') !== 'deletar')
            return;
        
        evn.preventDefault();
        
        $.ajax({
            type    : 'get',
            dataType: 'html',
            url     : $(this).attr('href'),
            success: function (data) 
            {
                alertify.alert().setting({
                    'message' : data,
                    'modal'   : false,
                    'basic'   : true,
                    'padding' :false
                }).show();
                
                $('.ajs-footer').remove();
            },
            error: function (jqXHR, textStatus, errorThrown) 
            {
                alert("Um erro ocorreu, tente novamente.");
            }
        });
    });
});