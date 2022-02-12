$(function() { 
    const url = "http://127.0.0.1:8000";

    $('#delete').click(function() {
        var id = $('.group_id').text();
        $('#update').attr('action', `${url}/group/delete/${id}`);
        $('#update').submit();
    })

    $('.exit').click(function() {
        var id = $('.group_id').text();
        $('#update').attr('action', `${url}/group/exit/${id}`);
        $('#update').submit();
    });

    $('.search').click(function() {
        var user_name = $('.user').val();
        $('.invd').css('display', 'none');

        $.ajax({
            type: 'POST',
            url: url + "/api/search_user/" + user_name, 
            datatype: "text",  
            cache: false
        })
        .then(
            function(param) {
                var user = param;
                console.log(user);
                if(user.length) {
                    $('.search_user_name').html(user[0].name);
                    $('.search_user_icon').attr('src', `${url}/storage/user-image/${user[0].profile_photo_path}`);
                } else {
                    $('.search_user_name').html('検索条件に一致するユーザーがいませんでした');
                }

                //detail用の招待ボタン
                $('.inv_btn.btn').click(function() {
                    var group_id = $('.group_id').data('group-id');

                    $.ajax({
                        type: 'POST',
                        url: url + "/api/inv_user/" + user[0].id + "/" + group_id, 
                        datatype: "text",  
                        cache: false
                    })
                    .then(
                        function(param) {
                            console.log(param);
                            if(param == 'error') {
                                $('.invd').css('display', 'block').css('color', 'red').html('既に加入済みです')
                            } else {
                                $('.invd').css('display', 'block').html(`${user[0].name}さんを招待しました。`)
                            }
                            return;
                        },
                        function(XMLHttpRequest, textStatus, errorThrown){ 
                            console.log(XMLHttpRequest);
                        }
                    );
                }); 

                //store用の招待ボタン
                $('.inv_btn').click(function() {
                    $('.member_list').append
                    (
                        `<div class="user_icon">
                            <a class="user_image" href=""><img src="${url}/storage/user-image/${user[0].profile_photo_path}" alt="他人のアイコン"></a>
                            <h3 class="user_name">${user[0].name}</h3>
                        </div>`
                    );
                        
                    if(typeof num === 'undefined') {
                        num = user[0].id;
                    } else {
                        num += "," + user[0].id;
                    }
                    console.log(num);
                    $('.form_store').click(function() {
                        var count = $('.alert').data('count-id');
                        if(count === 3) {
                            $('.alert').css('display', 'block');
                        } else {
                            $('#form_store').attr('action', `${url}/group_store/${num}`);
                            $('#form_store').submit();
                        }
                    });
                });
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log(XMLHttpRequest);
            }
        );
    });

    if (typeof num === 'undefined') {
        $('.form_store').click(function() {
            var num = 0;
            var count = $('.alert').data('count-id');
            if(count === 3) {
                $('.alert').css('display', 'block');
            } else {
                $('#form_store').attr('action', `${url}/group_store/${num}`);
                $('#form_store').submit();
            }
        });
    } 

    $('.submit').each(function() {
        $(this).click(function() {
            var group_id = $(this).data('belong-id');
            $('#form_select').attr('action', `${url}/group_select/${group_id}`);
            $('#form_select').submit();
        });
    });  
    
    $(function(){
        $('#image').change(function(e){
            var file = e.target.files[0];
            var reader = new FileReader();
       
            if(file.type.indexOf("image") < 0){
                alert("画像ファイルを指定してください。");
                return false;
            }
       
          reader.onload = (function(file){
                return function(e){
                    $("#group_image").attr("src", e.target.result);
                    $("#group_image").attr("title", file.name);
                    $(".default").html(file.name);
                };
            })(file);
          reader.readAsDataURL(file);
        });
    });
});