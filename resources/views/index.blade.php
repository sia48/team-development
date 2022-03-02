<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>ホ-ム</title>
</head>
<body>
    <div class="overlay"></div>
    <header class="header">
        <div class="container">
            <div class="me">
                <div class="my-icon">
                    <a class="profile_edit"><img src="{{ asset('storage/user-image/'.$user->profile_photo_path) }}" alt="自分のアイコン"></a>
                </div>
                <div class="my-user">
                    <div class="my-name">
                        <a class="profile_edit">{{ $user->name }}</a>
                    </div>
                    <div class="my-group">
                        @if($user->group_id !== 0)
                            <a href="{{ route('group_detail', ['id' => $user->group_id]) }}">{{ $user->group->group_name }}</a>
                        @endif
                    </div>
                </div>
                @if($user->invitation > 0)
                    <div class="group_invitation">
                        <li class="inv_modal"><a>グループに<br>招待されています</a></li>
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li style="color:red" class="error">{{ $error }}</li>
                    @endforeach
                @endif
            </div>  
            <nav class="nav pc">
                <ul>
                    <li class="group"><a href="{{ route('group') }}">グループ<br>作成</a></li>
                    <li class="group"><a href="{{ route('group_show') }}">グループ<br>切り替え</a></li>
                    <li class="mf"><a href="{{ route('suito',['year' => $year,'month' => $month]) }}">MF画面へ</a></li>
                    <li class="logout">
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </nav> 
            <nav class="nav sp">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul>
                    <li><a href="{{ route('group') }}"><i class="fas fa-users-cog"></i>グループ作成</a></li>
                    <li><a href="{{ route('group_show') }}"><i class="fas fa-users"></i>グループ切り替え</a></li>
                    <li class="mf"><a href="{{ route('suito',['year' => $year,'month' => $month]) }}"><i class="fas fa-money-check-alt"></i>MF画面へ</a></li>
                    <li class="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit">ログアウト</button>
                        </form>
                    </li>
                    <li><a><i class="far fa-calendar-alt this_month_schedules"></i>今月の予定</a></li>
                </ul>
            </nav> 
        </div>
    </header>

    <div class="main-container">
        <aside class="aside">
            <div class="menu">
                <p>メニューバー</p>
                <ul>
                    <li><a class="this_month_schedules">今月の予定</a></li>
                    <!-- 追加したらスマホ用のナビゲーション、カレンダーヘッドにも追加 -->
                </ul>
            </div>
        </aside>

        <main class="main">
            <div class="nav-calendar">
                <div class="nav-calendar-left">
                    <div class="years">
                        <p>年</p>
                        <span>
                            @for($i = $year - 2; $i < $year + 3; $i++)
                                <a href="{{ route('calendar', ['year' => $i, 'month' => $month]) }}"
                                @if($year == $i)
                                    class="this_year_color"
                                @endif
                                >{{ $i }}</a>
                            @endfor
                            @if($month != date('n') || $year != date('Y'))
                                <a href="{{ route('calendar', ['year' => date('Y'), 'month' => date('n')]) }}" class="today">今月へ</a>
                            @endif
                        </span>
                    </div>
                    <div class="months">
                        <span>
                            <p>月</p>
                            @for ($i = 1; $i < 13; $i++)
                                @if (strlen($i) === 1)
                                    <a href="{{ route('calendar', ['year' => $year, 'month' => $i]) }}"
                                    @if ($month == $i)
                                        class="this_month_color"
                                    @endif
                                    >{{ '0' . $i }}</a>
                                @else
                                    <a href="{{ route('calendar', ['year' => $year, 'month' => $i]) }}"
                                    @if ($month == $i)
                                        class="this_month_color"
                                    @endif
                                    >{{ $i }}</a>
                                @endif
                            @endfor
                            @if ($month == 1)
                                <a href="{{ route('calendar', ['year' => $year - 1, 'month' => 12]) }}" class="prev">←前月へ</a>
                            @else
                                <a href="{{ route('calendar', ['year' => $year, 'month' => $month - 1]) }}" class="prev">←前月へ</a>
                            @endif

                            @if ($month == 12)
                                <a href="{{ route('calendar', ['year' => $year + 1, 'month' => 1]) }}" class="next">次月へ→</a>
                            @else
                                <a href="{{ route('calendar', ['year' => $year, 'month' => $month + 1]) }}" class="next">次月へ→</a>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- スマホ用 -->
                <div class="nav-calendar-left sp">
                    <div class="years">
                        <p>年:</p>
                        <form action="{{ route('request', ['month' => $month]) }}" method="post" id="submit_form_year">
                        @csrf
                            <select id="submit_select_year" name="year">
                                @for($i = $year - 2; $i < $year + 3; $i++)
                                    @if($i == $year)
                                        <option value={{ $i }} selected>{{ $i }}</option>
                                    @else
                                        <option value={{ $i }}>{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </form>
                        @if($month != date('n') || $year != date('Y'))
                            <a href="{{ route('calendar', ['year' => date('Y'), 'month' => date('n')]) }}" class="today">今月へ</a>
                        @endif
                    </div>
                    <div class="months">
                        <span>
                            <p>月:</p>
                            <form action="{{ route('request', ['year' => $year]) }}" method="post" id="submit_form_month">
                            @csrf
                                <select id="submit_select_month" name="month">
                                    @for ($i = 1; $i < 13; $i++)
                                        @if($i == $month)
                                            <option value= {{ $i }} selected>{{ $i }}</option>
                                        @else
                                            <option value= {{ $i }} >{{ $i }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </form>                    
                            @if ($month == 1)
                                <a href="{{ route('calendar', ['year' => $year - 1, 'month' => 12]) }}" class="prev">←前月へ</a>
                            @else
                                <a href="{{ route('calendar', ['year' => $year, 'month' => $month - 1]) }}" class="prev">←前月へ</a>
                            @endif

                            @if ($month == 12)
                                <a href="{{ route('calendar', ['year' => $year + 1, 'month' => 1]) }}" class="next">次月へ→</a>
                            @else
                                <a href="{{ route('calendar', ['year' => $year, 'month' => $month + 1]) }}" class="next">次月へ→</a>
                            @endif
                        </span>
                    </div>
                </div>
                <!-- スマホ用ここまで -->

                <div class="nav-calendar-right">
                    <div class="income">
                        <p><a href="">今月の収入</a></p>
                        <span>￥123,456</span>
                    </div>
                    <div class="spending">
                        <p><a href="">今月の支出</a></p>
                        <span>￥234,567</span>
                    </div>
                    <div class="total">
                        <p><a href="">今月の増減</a></p>
                        <span>￥345,678</span>
                    </div>
                </div>
            </div>

            <div class="calendar">
                <div class="calendar-head">
                    <div class="calendar-head-left">
                        <h1>{{ $month }}</h1>
                        <div class="calendar-en">
                            <h2>{{ $year }}</h2>
                            <h2>{{ $month_en[$month] }}</h2>
                        </div>
                    </div>
                </div>
                <table class="this-month"> 
                    <tr> 
                        <th>日</th> 
                        <th>月</th> 
                        <th>火</th> 
                        <th>水</th> 
                        <th>木</th> 
                        <th>金</th> 
                        <th>土</th>
                    </tr> 
                    @foreach($dates as $date)
                        <?php $date_modal = $date->year . '年' . $date->month . '月' . $date->day . '日'; ?>
                        <?php $js_year = $date->year;?>
                        <?php $js_month = $date->month;?>

                        @if($date->dayOfWeek == 0)
                            <tr>
                        @endif                        
                            <td
                                @if($date->month != $month) 
                                    class="not_this_month"
                                @endif

                                @if($date->isToday()) 
                                    class="today"
                                @endif
                            >
                                <a data-group-id="{{ $user->group_id }}" class="cell_link {{ $js_year }} {{ $js_month }}" 
                                    @if($holidays->isHoliday($date))
                                        id="holiday"
                                    @endif
                                    data-id='{{ $date_modal }}'
                                >
                                    <p>{{ $date->day }}</p> 
                                    @if($holidays->isHoliday($date))
                                        <?php $date_key = $date->year . '-' . sprintf('%02d',$date->month) . '-' . sprintf('%02d', $date->day); ?>
                                        @if (date('Y') <= $date->year && $date->year - date('Y') < 2) 
                                            {{-- 2年先の取得はエラーになるので、過去ではないかつ2年以内の年の指定なら実行 --}} 
                                            <div class="td">{{ $google_holidays[$date_key] }}</div>
                                        @elseif (date('Y') > $date->year || $date->year - date('Y') > 2) 
                                            {{-- 2年先の取得もしくは過去の取得はエラーになる(ただし色塗りは別のライブラリを使用しているから対応できる) --}}
                                        @endif
                                    @endif
                                </a>
                                @if(isset($view_schedules))
                                    @foreach($view_schedules as $schedule)
                                        @if($schedule->schedule_date == $date_modal)
                                            <p class="has_schedule">●</p>
                                            @break
                                        @endif
                                    @endforeach
                                @endif
                                @if(empty($view_schedules))
                                    @foreach($my_schedules as $my_schedule)
                                        @if($my_schedule->schedule_date == $date_modal)
                                            <p class="has_schedule">●</p>
                                            @break
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            
                        @if($date->dayOfWeek == 6)
                            </tr>
                        @endif
                    @endforeach               
                </table>
            </div>
        </main>
    </div>

    <div class="modal" id="this_month_schedule"> <!-- 今月の予定表示用のモーダル -->
        <div class="top">
            <h2>今月の予定</h2>
            <span class="close">x</span>
        </div>
        <div class="this_month_schedules_area">
            @if(count($my_schedules) == 0) 
                <div class="no_schedule">
                    <h3>今月の予定は登録されていません</h3>
                </div>
            @endif
            @foreach($my_schedules as $my_schedule)
                <div class="this_month_schedule">
                    <h3>{{ $my_schedule->schedule_date }}</h3>
                    <p>{{ $my_schedule->schedule }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal" id="create"> <!-- 新規登録のモーダル -->
        <form action="{{ route('store', ['year' => $year, 'month' => $month]) }}" method="post">
            @csrf
            <div class="top">
                <input type="text" name="schedule_date" value="" class="day">
                <span class="close">x</span>
            </div>
            <div class="contents">
                <div class="user_icon">
                    <img src="{{ asset('storage/user-image/'.$user->profile_photo_path) }}" alt="自分のアイコン" class="my_schedule">
                    <p>{{ $user->name }}</p>
                </div>
                <div class="details">
                    <div class="detail">
                        <textarea class="textarea_store" name="schedule" required maxlength="100"></textarea>
                    </div>
                </div>
            </div>
            <div class="button">
                <p class="validate_store" style="display:none; color:red">入力できる文字数は100文字までです</p>
                <button type="button" class="btn close">一覧に戻る</button>
                <button type="submit" class="btn submit">登録する</button>
            </div>
        </form>
    </div>

    <div class="modal" id="edit" data-user-id="{{ $user->id }}"> <!-- 編集用モーダル -->
        <form action="{{ route('store', ['year' => $year, 'month' => $month]) }}" method="post" id="edit_form">
            @csrf
            <div class="top">
                <input type="text" name="schedule_date" value="" class="day">
                <span class="close">x</span>
            </div>
            <div class="contents">
                <div class="details">
                    @foreach($my_schedules as $my_schedule)
                        <div class="detail detail{{ $my_schedule->id }}" style="display:none"> 
                            <div class="user_icon">
                                <img src="{{ asset('storage/user-image/'.$user->profile_photo_path) }}" alt="自分のアイコン" class="my_schedule">
                                <p class="my_name">{{ $user->name }}</p>
                                <p class="validate_edit" style="display:none; color:red">入力できる文字数は100文字までです</p>
                            </div>
                            <textarea class="textarea_edit textarea_edit{{ $my_schedule->id }}" name="schedule_edit" placeholder="" disabled maxlength="100">{{ $my_schedule->schedule }}</textarea>
                            <div class="links">
                                <button type="button" class="link_btn link_edit link_edit{{ $my_schedule->id }}">編集する</button>
                                <button type="button" class="link_btn link_update link_update{{ $my_schedule->id }}" style="display:none">更新する</button>
                                <button type="button" class="link_btn link_delete link_delete{{ $my_schedule->id }}">削除する</button>
                            </div>
                        </div>
                    @endforeach
                    @if(isset($view_schedules))
                        @foreach($view_schedules as $schedule)
                            @if($schedule->user_id !== $user->id)
                                <div class="other detail detail{{ $schedule->schedule_id }}" style="display:none"> 
                                    <div class="user_icon">
                                        <img src="{{ asset('storage/user-image/'.$schedule->user->profile_photo_path) }}" alt="ユーザーのアイコン">
                                        <p class="schedule_user_name">{{ $schedule->user->name }}</p>
                                    </div>
                                    <p class="same_group_users_schedule">{{ $schedule->schedule }}</p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="store_area">
                <div class="store">
                    <textarea name="schedule" class="textarea_edit_store" placeholder="新しい予定を登録する場合はこちら" required maxlength="100"></textarea>
                </div>
                <div class="button">
                    <p class="validate_edit_store" style="display:none; color:red">入力できる文字数は100文字までです</p>
                    <button type="button" class="btn close">一覧に戻る</button>
                    <button type="button" class="btn submit">登録する</button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="delete"> <!-- 削除用モーダル -->
        <div class="top">
            <input type="text" name="schedule_date" value="" class="day">
            <span class="close">x</span>
        </div>
        <div class="contents">
            <div class="user_icon">
                <img src="{{ asset('storage/user-image/'.$user->profile_photo_path) }}" alt="自分のアイコン" class="my_schedule">
                <p class="schedule_user_name">{{ $user->name }}</p>
            </div>
            <div class="details">
                <div class="detail">
                    <form action="#" method="post" id="delete_form">
                        @csrf
                        <textarea class="textarea_delete" id="textarea_delete" name="schedule" placeholder="" disabled></textarea>
                        <h2>上記の予定を削除してよろしいですか？</h2>
                        <div class="button">
                            <button type="submit" class="btn delete">はい</button>
                            <button type="button" class="btn close">いいえ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($groups !== null)
        <?php 
            $array = explode(' ', $user->belongs_group);
            $count = count($array);
         ?>
        <div class="modal" id="invitation"> <!-- グループ招待用モーダル -->
        <span class="inv_modal_close">x</span>
            <div class="group">
                <div class="group_detail">
                    <div class="group_image">
                        <img src="{{ asset('storage/group-image/'.$groups->group_image) }}" alt="グループのアイコン">
                        <h2 data-group-num-id="{{ $groups->id }}" class="group_name">{{ $groups->group_name }}</h2>
                    </div>
                    <p>作成者: <a href="">{{ $author->name }}</a></p>
                </div>
                <h3>上記グループに招待されています。</h3>
                <strong class="alert" style="display:none" data-count-id="{{ $count }}">
                    所属できるグループは3つまでです。<br>
                    拒否ボタンを押下して招待をお断りするか、他のグループから脱退して下さい。
                </strong>
                <form action="#" method="post" id="form_invitation">
                    @csrf
                    <button type="button" class="add">参加する</button>
                    <button type="button" class="rejection">拒否する</button>
                </form>
            </div>
        </div>
    @endif

    <div class="modal" id="profile"> <!-- プロフィール用モーダル -->
        <div class="top">
            <h2>プロフィール編集</h2>
            <span class="close">x</span>
        </div>
        <div class="contents">
            <div class="user_icon">
                <a href=""><img src="{{ asset('storage/user-image/'.$user->profile_photo_path) }}" alt="自分のアイコン" id="user_image"></a>
            </div>
            <div class="profile">
                <form action="{{ route('profile', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <p>プロフィール写真</p>
                    <label>
                        <input type="file" name="user_image" value="{{ $user->profile_photo_path }}" id="image">変更する際は選択して下さい
                    </label>
                    <p>ニックネーム</p>
                    <input type="text" name="name" value="{{ $user->name }}" required maxlength="50">
                    <p>パスワード</p>
                    <input type="password" name="password" placeholder="パスワード変更する場合ご入力下さい" minlength="8" maxlength="128" class="password">
                    <p>パスワード確認</p>
                    <input type="password" name="password_confirmation" placeholder="もう一度ご入力下さい" minlength="8" maxlength="128" class="password2">
                    <li style="display:none; color:red" class="password_error">パスワードとパスワード確認が一致していません</li>
                    <div class="button">
                        <button type="button" class="btn close">キャンセル</button>
                        <button type="submit" class="btn edit">保存する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>