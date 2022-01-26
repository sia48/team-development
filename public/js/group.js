$(function() { 
    const url = "http://127.0.0.1:8000";

    $('#delete').click(function() {
        var url = "http://127.0.0.1:8000";
        var id = $('span').text();
        $('#update').attr('action', `${url}/group/delete/${id}`);
        $('#update').submit();
    })

    $('.search').click(function() {
        var userName = $('.user').val();

        $.ajax({
            type: 'POST',
            url: url + "/api/search_user/" + userName, 
            datatype: "text",  
        })
        .then(
            function(param) {
                if(param.length) {
                    var userData = param;
                    $('.user_name').html(userData[0].name);
                } else {
                    $('.user_name').html('検索条件に一致するユーザーがいませんでした');
                }
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log(XMLHttpRequest);
            }
        );
    });

    $('.invitation').click(function(){
        var group_id = $('span').data('group-id');
        var user_id = 1;
        $.ajax({
            type: 'POST',
            url: url + "/api/register/" + user_id, 
            datatype: "text",  
            data: {
                'group_id' : group_id
            },
        })
        .then(
            function(data) {
                console.log(data);
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log(XMLHttpRequest);
            }
        );
    });

});