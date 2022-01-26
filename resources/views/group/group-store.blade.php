<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/group.css') }}">
    <link rel="stylesheet" href="{{ asset('css/group-responsive.css') }}">
    <!-- <link rel="icon" href=""> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>グループ作成</title>
</head>
<body>
    <div class="modal" id="create"> <!-- 新規登録のモーダル（遷移するからモーダルじゃなくてOK） -->
        <div class="title">
            <h2>グループ設定</h2>
            <span class="close">x</span>
        </div>
        <form action="{{route('group_store')}}" method="post" id="group_store" enctype="multipart/form-data"> <!-- 自分のIDを渡す -->
            @csrf
            <div class="group_store">
                <div class="group_store_icon">
                    <input type="file" name="group_image">
                </div>
                <div class="group_name">
                    <input type="text" name="group_name" placeholder="グループ名">
                </div>
            </div>
        </form>
        <div class="details">
            <div class="member_list">
                <h2>メンバー</h2>
                <div class="user_icon">
                    <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="他人のアイコン"></a>
                </div>
                <h3>ユーザー名</h3>
            </div>
            <div class="inv">
                <h2>招待するメンバーを選択</h2>
                <form action="" method="post">
                    @csrf
                    <input type="text" name="inv_user" placeholder="ユーザー名">
                    <div class="inv_user_icon">
                        <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="招待したい人のアイコン"></a>
                    </div>
                    <h3>ユーザー名</h3>
                    <button type="button" class="ajax_submit">招待する</button><!-- ボタンを押したら非同期で左のエリアに追加 -->
                </form>
            </div>
        </div>
        <div class="button_area">
            <button type="button" class="close">キャンセル</button>
            <button type="submit" class="store" form="group_store">作成する</button>
        </div>
    </div>
    <script src="{{ asset('js/group.js') }}"></script>
</body>
</html>