<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('app.env') === 'production')
        <link rel="stylesheet" href="{{ secure_asset('team-developmet/public/css/group.css') }}">
        <link rel="stylesheet" href="{{ secure_asset('team-developmet/public/css/group-responsive.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/group.css') }}">
        <link rel="stylesheet" href="{{ asset('css/group-responsive.css') }}">
    @endif
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>グループ一覧</title>
</head>
<body>
    <div class="show" id="show">
        <div class="title">
            @if($user->group_id === 0)
                <h2>グループに所属していません</h2>
            @else
                <h2>表示するグループを選択</h2>
            @endif
            <a href="/"><span class="close">x</span></a>
        </div>
        <div class="belongs_groups">
            <form action="" method="post" id="form_select">
                @csrf
                @foreach($groups as $group)
                    <div class="belong_group"
                        @if($user->group_id === $group->id)
                            id="selected"
                        @endif
                    >
                        <div class="group_icon">
                            <a href="{{ route('group_detail', ['id' => $group->id]) }}"><img src="{{ asset('storage/group-image/'.$group->group_image) }}" alt="グループのアイコン"></a>
                        </div>
                        <h3>
                            <a href="{{ route('group_detail', ['id' => $group->id]) }}">{{ $group->group_name }}</a>
                        </h3>
                        <div class="button_choice">
                            @if($user->group_id === $group->id)
                                <button type="button" class="button">表示中</button>
                            @else
                                <button type="button" class="submit" data-belong-id="{{ $group->id }}">選択</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </form>
        </div>
        <div class="button_area">
            <?php 
                $array = explode(' ', $user->belongs_group);
                $count = count($array);
            ?>
            <a href="{{ route('calendar', ['year' => date('Y'), 'month' => date('n')]) }}" class="close">戻る</a>
            @if($count !== 3)
                <a href="{{ route('group') }}" class="create">グループを作成する</a>
            @endif
        </div>
    </div>
    @if(config('app.env') === 'production')
        <script src="{{ secure_asset('team-development/public/js/group.js') }}"></script>
    @else
        <script src="{{ asset('js/group.js') }}"></script>
    @endif
</body>
</html>