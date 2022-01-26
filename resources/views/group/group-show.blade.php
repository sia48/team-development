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
    <title>グループ一覧</title>
</head>
<body>
    <div class="modal" id="show">
        <div class="title">
            <h2>表示するグループを選択</h2>
        </div>
        <div class="belongs_group">
            <form action="" method="post">
                @foreach($groups as $group)
                    <div class="belong_group">
                        <div class="group_icon">
                            <a href="{{ route('group_detail', ['id' => $group->id]) }}"><img src="{{ asset('storage/group-image/'.$group->group_image) }}" alt="グループのアイコン"></a><!-- 詳細画面に飛べるようにする -->
                        </div>
                        <h3>
                            <a href="{{ route('group_detail', ['id' => $group->id]) }}">{{ $group->group_name }}</a>
                        </h3>
                        <button type="submit">選択</button>
                    </div>
                @endforeach
            </form>
        </div>
    </div>
    <script src="{{ asset('js/group.js') }}"></script>
</body>
</html>