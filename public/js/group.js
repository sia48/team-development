$(function() { 
    const url = "http://127.0.0.1:8000";

    $('#delete').click(function() {
        var id = $('.group_id').text();
        if(!confirm('本当に削除しますか？')){
            return false;
        } else {
            alert('削除しました');
            $('#update').attr('action', `${url}/group/delete/${id}`);
            $('#update').submit();    
        }
    });

    $('.exit').click(function() {
        var id = $('.group_id').text();
        if(!confirm('本当に脱退しますか？')) {
            return false;
        } else {
            alert('脱退しました');
            $('#update').attr('action', `${url}/group/exit/${id}`);
            $('#update').submit();
        }
    });

    $('.search').click(function() {
        $('.inv_btn').off();
        $('.inv_btn.btn').off();
        $('.form_store').off();
        $('.button').off();
        $('.contents .user_icon').remove();
        var user_name = $('.user').val();
        $('.invd').css({'display':'none', 'color':'black'});

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

                if(user.length == 1) {
                    $('.search_user_name').html(user[0].name);
                    $('.search_user_icon').attr('src', `${url}/storage/user-image/${user[0].profile_photo_path}`);

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
                                    $('.invd').css('display', 'block').css('color', 'red').html('既に加入済みか招待済みです')
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
                    });
                } else if(user.length == 0){
                    $('.search_user_name').html('検索条件に一致するユーザーがいませんでした');
                } else if(user.length > 1) {
                    $('.store, #detail').hide();
                    $('#searched.modal').show();
                    for(let i = 0; i < user.length; i++) {
                        $('.contents').append
                        (
                            `<div class="user_icon">
                                <label>
                                    <input type="radio" value="${user[i].id},${user[i].name},${user[i].profile_photo_path}" class="user" name="user">
                                    <img src="${url}/storage/user-image/${user[i].profile_photo_path}" alt="アイコン" id="user_image">
                                    ${user[i].name}
                                </label>
                            </div>`        
                        );
                    }
                    $('input[name="user"]').change(function() {
                        $('input[name="user"]').each(function() {
                            $(this).prop('checked', false)
                        });
                        $(this).prop('checked', true);
                        var result = $(this).val().split(',');

                        if(result.length) {
                            $('.button').click(function() {
                                $('.search_user_name').html(result[1]);
                                $('.search_user_icon').attr('src', `${url}/storage/user-image/${result[2]}`);      
                                $('#searched.modal').hide();  
                                $('.store, #detail').show(); 
                            });
                        }

                        //detail用の招待ボタン
                        $('.inv_btn.btn').click(function() {
                            var group_id = $('.group_id').data('group-id');

                            $.ajax({
                                type: 'POST',
                                url: url + "/api/inv_user/" + result[0] + "/" + group_id, 
                                datatype: "text",  
                                cache: false
                            })
                            .then(
                                function(param) {
                                    console.log(param);
                                    if(param == 'error') {
                                        $('.invd').css('display', 'block').css('color', 'red').html('既に加入済みか招待済みです')
                                    } else {
                                        $('.invd').css('display', 'block').html(`${result[1]}さんを招待しました。`)
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
                                    <a class="user_image" href=""><img src="${url}/storage/user-image/${result[2]}" alt="他人のアイコン"></a>
                                    <h3 class="user_name">${result[1]}</h3>
                                </div>`
                            );
                                
                            if(typeof arr === 'undefined') {
                                arr = "," + result[0];
                            } else {
                                arr += "," + result[0];
                            }
                            console.log(arr);
                        });
                    });
                }

                $('.form_store').click(function() {
                    var count = $('.alert').data('count-id');
                    if(typeof num === 'undefined') {
                        num = 0;
                    }
                    if(typeof arr === 'undefined') {
                        arr = ',' + 0;
                    }
                    num += arr;
                    console.log(num);
                    if(count === 3) {
                        $('.alert').css('display', 'block');
                    } else {
                        $('#form_store').attr('action', `${url}/group_store/${num}`);
                        $('#form_store').submit();
                    }
                    return
                });            
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log(XMLHttpRequest);
            }
        );
    });

    $('.form_store').click(function() {
        var count = $('.alert').data('count-id');
        if(typeof num === 'undefined') {
            num = 0;
        }
        if(typeof arr === 'undefined') {
            arr = ',' + 0;
        }
        num += arr;
        console.log(num);
        if(count === 3) {
            $('.alert').css('display', 'block');
        } else {
            $('#form_store').attr('action', `${url}/group_store/${num}`);
            $('#form_store').submit();
        }
    });            


    $('.submit').each(function() {
        $(this).click(function() {
            var group_id = $(this).data('belong-id');
            $('#form_select').attr('action', `${url}/group_select/${group_id}`);
            $('#form_select').submit();
        });
    });  

    $('.close').click(function() {
        $('#searched.modal').hide();
        $('.store, #detail').show(); 
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