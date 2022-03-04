<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('app.env') === 'production')
        <link rel="stylesheet" href="{{ secure_asset('css/group.css') }}">
        <link rel="stylesheet" href="{{ secure_asset('css/group-responsive.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/group.css') }}">
        <link rel="stylesheet" href="{{ asset('css/group-responsive.css') }}">
    @endif
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>グループ作成</title>
</head>
<body class="body_store">
    <div class="store" id="store">
        <div class="title">
            <h2>グループ設定</h2>
            <a href="/"><span class="close">x</span></a>
        </div>
        <form action="#" method="post" id="form_store" enctype="multipart/form-data">
            @csrf
            <div class="group_image">
                <img src="{{ asset('img/icon-default-user.svg') }}" alt="グループアイコン" id="group_image">
                <p class="default">デフォルト画像</p>
            </div>
            <div class="flex">
                <div class="group_store">
                    <div class="group_icon">
                        <label>
                            <input type="file" name="group_image" id="image">イメージ画像（省略可）
                        </label>
                    </div>
                    <div class="group_name">
                        <input type="text" name="group_name" placeholder="グループ名（最大50文字）" maxlength="50">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <li style="color:red">{{ $error }}</li>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </form>
        <div class="details">
            <div class="member_list">
                <h2>メンバー</h2>
            </div>
            <div class="invitation">
                <h2>招待するメンバーを選択</h2>
                <input type="text" name="user" class="user" placeholder="ユーザー名">
                <button type="button" class="search">検索</button>
                <div class="searched">
                    <img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン" class="search_user_icon">
                    <h3 class="search_user_name">ユーザー名</h3>
                </div>
                <button type="button" class="inv_btn">招待する</button>
            </div>
        </div>
        <div class="button_area">
            <?php 
                $array = explode(' ', $user->belongs_group);
                $count = count($array);
            ?>
            <strong class="alert" style="display:none" data-count-id="{{ $count }}">
                所属できるグループは3つまでです。<br>
                新しく作成するにはグループを1つ抜けて下さい。
            </strong>
            <a href="/" class="close">キャンセル</a>
            <button type="submit" class="form_store">作成する</button>
        </div>
    </div>

    <div class="modal" id="searched"> <!-- 検索用モーダル -->
        <div class="top">
            <h2>検索対象のユーザーが複数ヒットしました<br>招待するユーザーを選択して下さい</h2>
            <span class="close">x</span>
        </div>
        <div class="contents">
        </div>
        <div class="button_area">
            <button type="button" class="close">キャンセル</button>
            <button type="button" class="button">選択する</button>
        </div>
    </div>

    @if(config('app.env') === 'production')
        <script src="{{ secure_asset('js/group.js') }}"></script>
    @else
        <script src="{{ asset('js/group.js') }}"></script>
    @endif
</body>
</html>