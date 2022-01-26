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
    $('.modal').hide();
    $('.cell_link').click(function() {
        var day = $(this).data('id');
        $('.day').attr("value", day);
        var classVal = $(this).attr('class'); 
        var classVals = classVal.split(' ');  
        var year = classVals[1];
        var month = classVals[2];
        var url = "http://127.0.0.1:8000";
        
        $.ajax({
            url: "http://127.0.0.1:8000/api/test/" + day,
            type: 'POST',
            dataType : "text",
        })
        .then(
            function(param){
                var date_schedule = JSON.parse(param);
                if(date_schedule.length) {
                    $('#edit').fadeIn();
                    $('#textarea_edit').val(date_schedule[0].schedule);
                    $('.link_edit').click(function() {
                        $('#edit').hide();
                        $('#update_delete').show();
                        $('#textarea_update').val(date_schedule[0].schedule);
                        $('.link_update').click(function() {
                            $('#update_form').attr('action', ` ${url}/edit/${year}/${month}/${date_schedule[0].id}`);
                        });
                    })
                    $('.link_delete').click(function() {
                        $('#edit').hide();
                        $('#delete').show();
                        $('#textarea_delete').val(date_schedule[0].schedule);
                        $('.delete').click(function() {
                            $('#delete_form').attr('action', ` ${url}/delete/${year}/${month}/${date_schedule[0].id}`);
                        })
                    });
                } else {
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