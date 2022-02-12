$(function() { 
    $('.nav.sp').click(function () { 
        $(this).toggleClass('active'); 
        $(this).find('ul').toggleClass('active');
    }); 

    $("#submit_select_year").change(function(){
        $("#submit_form_year").submit();
    });

    $("#submit_select_month").change(function(){
        $("#submit_form_month").submit();
    });

    //modal
    // $('.modal').hide(); ->モーダルを最初から表示しないようにhide()で消しているがこのコードだとリロードしたときに一瞬モーダルが表示されてその後hide()で消えるように表示されるためよろしくない。そのためcssのほうでdisplay: none;とすることによって最初から表示されなくなる。
    $('.cell_link').click(function() {
        var day = $(this).data('id');
        $('.day').attr("value", day);
        console.log(day);
        var classVal = $(this).attr('class'); 
        var classVals = classVal.split(' ');  
        var year = classVals[1];
        var month = classVals[2];
        var url = "http://127.0.0.1:8000";
        
        $.ajax({
            url: "http://127.0.0.1:8000/api/suito/test/" + day,
            type: 'POST',
            dataType : "text",
        })
        .then(
            function(param){
                var suito_date = JSON.parse(param);
                if(suito_date.length) {
                    $('#edit').fadeIn();
                    $('#textarea_edit').val(suito_date[0].suito);
                    $('.link_edit').click(function() {
                        $('#edit').hide();
                        $('#update_delete').show();
                        $('#textarea_update').val(suito_date[0].suito);
                        $('.link_update').click(function() {
                            $('#update_form').attr('action', ` ${url}/edit/${year}/${month}/${suito_date[0].id}`);
                        });
                    })
                    $('.link_delete').click(function() {
                        $('#edit').hide();
                        $('#delete').show();
                        $('#textarea_delete').val(suito_date[0].suito);
                        $('.delete').click(function() {
                            $('#delete_form').attr('action', ` ${url}/delete/${year}/${month}/${suito_date[0].id}`);
                        })
                    });
                } else {
                    $('#edit').hide();
                    $('#create').fadeIn();
                }
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log(XMLHttpRequest); 
        });
    });
        
    $('.close').click(function() {
        $('.modal').fadeOut();
    });
});