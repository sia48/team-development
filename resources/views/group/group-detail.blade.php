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
    <title>グループ詳細</title>
</head>
<body>
    <div class="modal" id="detail">
        <div class="title">
            <h2>{{ $group->group_name }}</h2>
            <span style="display:none" data-group-id="{{ $group->id }}">{{ $group->id }}</span>
        </div>
        <div class="detail">
            <div class="members">
                <h2>メンバー（5）</h2>
                <div class="member">
                    <div class="user_icon"> <!-- TODO : ここでログインユーザーの画像を取ってくる -->
                        <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a><!-- プロフィールに飛べるようにする -->
                    </div>
                    <h3>ユーザー名</h3>
                </div>
                <div class="member">
                    <div class="user_icon"> <!-- TODO : ここでログインユーザーの画像を取ってくる -->
                        <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a><!-- プロフィールに飛べるようにする -->
                    </div>
                    <h3>ユーザー名</h3>
                </div>
            </div>
            <div class="operations">
                <form action="{{ route('group_update', ['id' => $group->id]) }}" method="post" enctype="multipart/form-data" id="update">
                @csrf
                    <div class="operation">
                        <div class="group_icon">
                            <img src="{{ asset('storage/group-image/'.$group->group_image) }}" alt="グループのアイコン">
                        </div>
                        <button type="button" id="delete">グループを削除する</button>
                        <button type="button">グループを抜ける</button><!-- submitにする -->
                        <h4>グループ写真</h4>
                        <input type="file" name="group_image" value="{{ $group->group_image }}">
                        <input type="text" name="group_name" value="{{ $group->group_name }}">
                    </div>
                </form>
                <div class="ope_inv">
                    <h3>招待するメンバーを選択</h3>
                    <input type="text" name="user" class="user" placeholder="ユーザー名">
                    <button type="button" class="search">検索</button>
                    <div class="user_icon"> <!-- TODO : ここでログインユーザーの画像を取ってくる -->
                        <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a><!-- プロフィールに飛べるようにする -->
                    </div>
                    <h3 class="user_name">ユーザー名</h3>
                    <button type="button" class="invitation">招待する</button>
                </div>
                <div class="button_area">
                    <button type="button" class="close">キャンセル</button>
                    <button type="submit" class="update" form="update">保存する</button>
                </div>
            </div>
        </div>     
    </div>
    <script src="{{ asset('js/group.js') }}"></script>
</body>
</html>