<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/group.css') }}">
    <link rel="stylesheet" href="{{ asset('css/group-responsive.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>グループ詳細</title>
</head>
<body>
    <div id="detail">
        <div class="title">
            <h2>{{ $group->group_name }}</h2>
            <span style="display:none" data-group-id="{{ $group->id }}" class="group_id">{{ $group->id }}</span>
            <a href="/"><span class="close">x</span></a>
        </div>
        <div class="detail">
            <div class="members">
                <h2>メンバー（{{ count($belongsTo_users) }}）</h2>
                @foreach($belongsTo_users as $belongsTo_user)
                    <div class="member">
                        <div class="user_icon">
                            <img src="{{ asset('storage/user-image/'.$belongsTo_user->profile_photo_path) }}" alt="自分のアイコン">
                        </div>
                        <h3>{{ $belongsTo_user->name }}</h3>
                        @if($group->created_user_id === $user->id && $belongsTo_user->id !== $user->id) 
                            <form action="{{ route('member_delete', ['id' => $group->id, 'user_id' => $belongsTo_user->id]) }}" method="post" id="delete{{ $belongsTo_user->id }}">
                                @csrf
                                <button type="submit" form="delete{{ $belongsTo_user->id }}">除名する</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="operations">
                <form action="{{ route('group_update', ['id' => $group->id]) }}" method="post" enctype="multipart/form-data" id="update">
                @csrf
                    <div class="operation">
                        <div class="group_icon">
                            <img src="{{ asset('storage/group-image/'.$group->group_image) }}" alt="グループのアイコン" id="group_image">
                            <div class="button">
                                @if($group->created_user_id === $user->id)
                                    <button type="button" id="delete">グループを削除する</button>
                                @endif
                                <button type="button" class="exit">グループを抜ける</button>
                            </div>
                        </div>
                        <div class="group_detail">
                            <h4 class="file">グループ写真</h4>
                            <label>
                                <input type="file" name="group_image" value="{{ $group->group_image }}" id="image">変更する際は選択して下さい
                            </label>
                            <h4 class="group_name">グループ名</h4>
                            <input type="text" name="group_name" value="{{ $group->group_name }}">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li style="color:red">{{ $error }}</li>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </form>
                <div class="invitation">
                    <div class="search_area">
                        <h3>招待するメンバーを選択</h3>
                        <input type="text" name="user" class="user" placeholder="ユーザー名">
                        <button type="button" class="search">検索</button>
                    </div>
                    <div class="searched">
                        <img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン" class="search_user_icon">
                        <h3 class="search_user_name">ユーザー名</h3>
                    </div>
                    <strong class="invd" style="display:none"></strong>
                    <button type="button" class="inv_btn btn">招待する</button>
                </div>
                <div class="button_area">
                    <a href="{{ route('group_show') }}" class="close">キャンセル</a>    
                    <button type="submit" class="update" form="update">保存する</button>
                </div>
            </div> 
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

    <script src="{{ asset('js/group.js') }}"></script>
</body>
</html>