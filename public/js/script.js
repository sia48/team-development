$(function() { 
    const url = "http://127.0.0.1:8000";

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

    $('.this_month_schedules').click(function() {
        $('#this_month_schedule, .overlay').fadeIn();
        $('.close, .overlay').click(function() {
            $('#this_month_schedule, .overlay').fadeOut();
        });
    });

    $('.textarea_store').on('input', function() {
        let cnt = $(this).val().length;
        if(cnt > 100) {
            $('.validate_store').css('display', 'block').next().next().prop('disabled', true);
        } else {
            $('.validate_store').css('display', 'none').next().next().prop('disabled', false);
        }
    });

    $('.textarea_edit').on('input', function() {
        let cnt = $(this).val().length;
        if(cnt > 100) {
            $('.validate_edit').css('display', 'block');
            $('.link_update').prop('disabled', true);
        } else {
            $('.validate_edit').css('display', 'none');
            $('.link_update').prop('disabled', false);
        }
    });

    $('.textarea_edit_store').on('input', function() {
        let cnt = $(this).val().length;
        if(cnt > 100) {
            $('.validate_edit_store').css('display', 'block').next().next().prop('disabled', true);
        } else {
            $('.validate_edit_store').css('display', 'none').next().next().prop('disabled', false);
        }
    });

    //modal
    $('.cell_link').click(function() {
        $('.store_area').css({'position':'sticky', 'right':''});
        var day = $(this).data('id');
        var group_id = $(this).data('group-id');
        var user_id = $('#edit').data('user-id');
        $('.day').attr("value", day);
        var classVal = $(this).attr('class'); 
        var classVals = classVal.split(' ');  
        var year = classVals[1];
        var month = classVals[2];

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: url + "/api/test/" + day + "/" + group_id + "/" + user_id,
            type: 'POST',
            dataType : "text",
            cache: false
        })
        .then(
            function(param){
                if(param.length) {
                    var date_schedule = JSON.parse(param);
                    var count = Object.keys(date_schedule).length;
                } else {
                    var count = 0;
                }
                
                if(count > 0) {
                    $('#edit, .overlay').fadeIn();    
                    for(let i = 0; i < count; i++) {
                        $(`.detail${date_schedule[i].schedule_id}`).css('display', 'flex');
                        $(`.textarea_edit${date_schedule[i].schedule_id}`).val(date_schedule[i].schedule);
                        $(`.link_delete${date_schedule[i].schedule_id}`).click(function() {
                            $('.textarea_delete').val(date_schedule[i].schedule);
                            $('#delete .detail').css('display', 'block');
                            $('#edit').hide();
                            $('#delete').fadeIn();
                            $('.delete').click(function() {
                                $('#delete_form').attr('action', ` ${url}/delete/${year}/${month}/${date_schedule[i].schedule_id}`);
                            });
                        });
                        $(`.link_edit${date_schedule[i].schedule_id}`).click(function(){
                            $('.textarea_edit').each(function() {
                                $('.textarea_edit').prop('disabled', true);
                            });
                            $('.link_update').each(function() {
                                $('.link_update').css('display', 'none');
                            });
                            $('.link_edit').each(function() {
                                $('.link_edit').css('display', 'block');
                            });
                            $('.textarea_store').val('');
                            $('#edit .btn.submit').html('入力する').css('background', 'limegreen').attr('type', 'button');
                            $(this).css('display', 'none');
                            $(this).next('.link_update').css('display', 'block');
                            $(`.textarea_edit${date_schedule[i].schedule_id}`).prop('disabled', false).focus();
                            $('.textarea_edit_store').prop('disabled', true);
                            $('#edit_form').attr('action', ` ${url}/edit/${year}/${month}/${date_schedule[i].schedule_id}`);
                            $(`.link_update${date_schedule[i].schedule_id}`).click(function(){
                                $('#edit_form').submit();
                            });
                        });
                        $('#edit .btn.submit').click(function() {
                            $('.textarea_edit_store').prop('disabled', false).focus();
                            $('#edit_form').attr('action', ` ${url}/store/${year}/${month}`);
                            $('.textarea_edit').each(function() {
                                $('.textarea_edit').prop('disabled', true);
                            });
                            $('.link_update').each(function() {
                                $('.link_update').css('display', 'none');
                            });
                            $('.link_edit').each(function() {
                                $('.link_edit').css('display', 'block');
                            });
                            $(this).attr('type', 'submit').html('登録する').css('background', 'orange');
                            if($('.textarea_edit_store').val() == '') {
                                return false;
                            } 
                        });
                    }
                    
                    var modal_height = $('#edit.modal').height();
                    var pos_height = $('#edit_form').outerHeight();
                    var judge = modal_height - pos_height;
                    if(judge > 0) {
                        $('.store_area').css('position', 'absolute');
                        if($('#edit.modal').width() < 600) {
                            $('.store_area').css('right', '0');
                        }
                    }
                } else {
                    $('#create, .overlay').fadeIn();
                    $('#create .detail').css('display', 'block');
                }   

                $('.close, .overlay').each(function(){
                    $(this).on('click', function() {
                        $('.modal, .overlay').hide();
                        $('.textarea_edit').each(function() {
                            $(this).prop('disabled', true);
                        });                        
                        $('.textarea_store').prop('disabled', false);
                        $('.link_update').each(function() {
                            $(this).css('display', 'none');
                        });
                        $('.link_edit').each(function() {
                            $(this).css('display', 'block');
                        });
                        $('.detail').each(function(){                   
                            $(this).css("display", "none");
                        });
                        $('#edit_form').attr('action', ` ${url}/store/${year}/${month}`);
                        $('#edit .btn.submit').attr('type', 'submit').html('登録する').css('background', 'orange');
                    });   
                });  
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log(XMLHttpRequest); 
        });
    });

    $('.profile_edit').click(function() {
        $('#profile, .overlay').fadeIn();
        $('.close, .overlay').click(function() {
            $('#profile, .overlay').fadeOut();
        });
    });

    $('.group_invitation').click(function() {
        var count = $('.alert').data('count-id');
        $('#invitation, .overlay').fadeIn();
        $('.add').click(function() {
            if(count === 3) {
                $('.alert').css('display', 'block');
            } else {
                var num = $('.group_name').data('group-num-id');
                $('#form_invitation').attr('action', `${url}/group_invitation/${num}`);
                $('#form_invitation').submit();
            }
        });
        $('.rejection').click(function() {
            var num = 0;
            $('#form_invitation').attr('action', `${url}/group_invitation/${num}`);
            $('#form_invitation').submit();
        });
    });

    $('.inv_modal_close, .overlay').click(function() {
        $('#invitation, .overlay').fadeOut();
        $('.alert').css('display', 'none');
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
                    $("#user_image").attr("src", e.target.result);
                    $("#user_image").attr("title", file.name);
                };
            })(file);
          reader.readAsDataURL(file);
        });
    });

    $('.password, .password2').on('input', function() {
        if($('.password').val() != $('.password2').val()) {
            $('.password_error').css('display', 'block');
            $('.btn.edit').attr('type', 'button');
        } else {
            $('.password_error').css('display', 'none'); 
            $('.btn.edit').attr('type', 'submit');
        }
    });
});