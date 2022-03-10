$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
    }
  });

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
        var key = $(this).data('key-id');
        console.log(key);
        $('.day').attr("value", day);
        console.log(day);
        var classVal = $(this).attr('class'); 
        var classVals = classVal.split(' ');  
        var year = classVals[1];
        var month = classVals[2];
        var url = "http://127.0.0.1:8000";
        
        $.ajax({
            url: "http://127.0.0.1:8000/api/suito/test/" + key,
            type: 'POST',
            dataType : "text",
        })
        .then(
            function(param){
                var suito_date = JSON.parse(param);
                console.log(suito_date);
                var count = Object.keys(suito_date).length;
                console.log(count);
                    $('#create').hide();
                    $('#create').fadeIn();
                    for(let i = 0; i < count; i++){
                        $('.key').append
                        (
                            `<tr class="suito_detail">
                                <td class="category">
                                    <p class="${suito_date[i].flag == 1 ? 'minus':'plus'}">${suito_date[i].category}</p>
                                </td>
                                <td class="money">
                                    <p class="${suito_date[i].flag == 1 ? 'minus':'plus' }">${'¥' + suito_date[i].money}</p>
                                </td>
                                <td class="btn-delete">
                                    <a href="/suito_delete/${year}/${month}/${suito_date[i].id}" class="del">削除</a>
                                </td>
                            </tr>`
                        );
                                // <form action="route('suito_delete',['month' => $month,'year' => $year,'id' => $suito->id])" method="post" >
                                //     @csrf
                                //     <input type="submit" value="削除" class="btn btn-outline-danger" onclick='return confirm("削除しますか？")'>
                                // </form>
                    }
                    $(function(){
                        $('.del').click(function(e){
                            var daleteConfirm = window.confirm('削除してよろしいでしょうか?');
                
                            if(daleteConfirm == true){
                                // varclick = $(this)
                                clickEle = $(this)
                                //  削除ボタンにユーザIDをカスタムデータとして埋め込んでます。
                                // var key = clickEle.attr('data-delete-id');

                                // $.ajax({
                                //     url: "http://127.0.0.1:8000/suito_delete/" + year + '/' + month + '/' + key,
                                //     type: 'POST',
                                //     data: {'id': key,
                                //             '_method': 'DELETE'}   DELETE リクエストだよ！と教えてあげる。
                                // })
                
                                // .done(function(){
                                //       通信が成功した場合、クリックした要素の親要素の<tr>を削除
                                //     clickEle.parents('td').remove();
                                // })
                
                                // .fail(function(){
                                //     alert('エラー');
                                // });
                            }else {
                                e.preventDefault();
                                // (function(e){
                                    
                                // });
                            }
                        })
                    })
                    // $('#textarea_edit').val(suito_date[0].suito);
                    // $('.link_edit').click(function() {
                    //     $('#edit').hide();
                    //     $('#update_delete').show();
                    //     $('#textarea_update').val(suito_date[0].suito);
                    //     $('.link_update').click(function() {
                    //         $('#update_form').attr('action', ` ${url}/edit/${year}/${month}/${suito_date[0].id}`);
                    //     });
                    // })
                    // $('.link_delete').click(function() {
                    //    $('#edit').hide();
                    //     $('#delete').show();
                    //     $('#textarea_delete').val(suito_date[0].suito);
                    //     $('.delete').click(function() {
                    //         $('#delete_form').attr('action', ` ${url}/delete/${year}/${month}/${suito_date[0].id}`);
                    //     })
                    // });
            },
            function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log(XMLHttpRequest); 
            });
    });
        
    $('.close').click(function() {
        $('.suito_detail').remove();
        $('.modal').fadeOut();
    });
});