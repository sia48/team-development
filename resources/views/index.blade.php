<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <!-- <link rel="icon" href=""> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>ホ-ム</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="me">
                <div class="my-icon">
                    <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a>
                </div>
                <div class="my-user">
                    <div class="my-name">
                        <a href="">チーム太郎</a>
                    </div>
                    <div class="my-group">
                        <a href="">所属グループ</a>
                    </div>
                </div>
            </div>  
            <nav class="nav pc">
                <ul>
                    <li class="group"><a href="">グループ<br>作成</a></li>
                    <li class="group"><a href="">グループ<br>切り替え</a></li>
                    <li class="mf"><a href="">MF画面へ</a></li>
                    <li class="logout"><a href="">ログアウト</a></li>
                </ul>
            </nav> 
            <nav class="nav sp">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul>
                    <li><a href=""><i class="fas fa-users-cog"></i>グループ作成</a></li>
                    <li><a href=""><i class="fas fa-users"></i>グループ切り替え</a></li>
                    <li class="mf"><a href=""><i class="fas fa-money-check-alt"></i>MF画面へ</a></li>
                    <li class="logout"><a href=""><i class="fas fa-sign-out-alt"></i>ログアウト</a></li>
                    <li><a href=""><i class="far fa-calendar-alt"></i>今月の予定</a></li>
                </ul>
            </nav> 
        </div>
    </header>

    <div class="main-container">
        <aside class="aside">
            <div class="menu">
                <p>メニューバー</p>
                <ul>
                    <li><a href="">今月の予定</a></li>
                    <li><a href="">他に</a></li>
                    <li><a href="">何か</a></li>
                    <li><a href="">あれば</a></li>
                    <li><a href="">追加</a></li>
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
                    <div class="calendar-head-right"> <!-- 100vhのレイアウトだと厳しい･･ -->
                        <div class="next-month-head">
                            <h3>1</h3>
                            <p>2022</p>
                        </div>
                        <table class="next-month"> 
                            <tr> 
                                <th>日</th> 
                                <th>月</th> 
                                <th>火</th> 
                                <th>水</th> 
                                <th>木</th> 
                                <th>金</th> 
                                <th>土</th>
                            </tr> 
                            <tr> 
                                <td></td> 
                                <td></td> 
                                <td></td> 
                                <td>1</td> 
                                <td>2</td> 
                                <td>3</td> 
                                <td>4</td>
                            </tr> 
                            <tr> 
                                <td>5</td> 
                                <td>6</td> 
                                <td>7</td> 
                                <td>8</td> 
                                <td>9</td> 
                                <td>10</td> 
                                <td>11</td> 
                            </tr> 
                            <tr> 
                                <td>12</td> 
                                <td>13</td> 
                                <td>14</td> 
                                <td>15</td> 
                                <td>16</td> 
                                <td>17</td> 
                                <td>18</td> 
                            </tr>
                            <tr> 
                                <td>19</td> 
                                <td>20</td> 
                                <td>21</td> 
                                <td>22</td> 
                                <td>23</td> 
                                <td>24</td> 
                                <td>25</td> 
                            </tr>
                            <tr> 
                                <td>26</td> 
                                <td>27</td> 
                                <td>28</td> 
                                <td>29</td> 
                                <td>30</td> 
                                <td>31</td> 
                                <td></td> 
                            </tr>
                        </table>
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
                        @if($date->dayOfWeek == 0)
                            <tr>
                        @endif
                        <!-- if $date->month != $month → その日がその月に属さない場合はクラス付与 -->
                        
                            <td
                                @if($date->month != $month) 
                                    class="not_this_month"
                                @endif

                                @if($date->isToday()) 
                                    class="today"
                                @endif
                            >
                                <?php $date_modal = $date->year . '年' . $date->month . '月' . $date->day . '日'; ?>
                                <?php $js_year = $date->year;?>
                                <?php $js_month = $date->month;?>
                                <a class="cell_link {{ $js_year }} {{ $js_month }}" 
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
                            </td>
                            
                        @if($date->dayOfWeek == 6)
                            </tr>
                        @endif
                    @endforeach               
                </table>
            </div>
        </main>
    </div>

    <div class="modal" id="create"> <!-- 新規登録のモーダル -->
        <form action="{{ route('store', ['year' => $year, 'month' => $month]) }}" method="post">
            @csrf
            <input type="text" name="schedule_date" value="" class="day">
            <span class="close">x</span>
            <div class="contents">
                <div class="user_icon">
                    <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a>
                </div>
                <div class="details">
                    <div class="detail">
                        <textarea name="schedule"></textarea>
                    </div>
                </div>
            </div>
            <div class="button">
                <button type="button" class="close">一覧に戻る</button>
                <button type="submit" class="submit">登録する</button>
            </div>
        </form>
    </div>

    <div class="modal" id="edit"> <!-- 編集用モーダル -->
        <input type="text" name="schedule_date" value="" class="day">
        <span class="close">x</span>
        <div class="contents">
            <div class="user_icon"> <!-- TODO : ここでログインユーザーの画像を取ってくる -->
                <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a>
            </div>
            <div class="details">
                <div class="detail">
                    <textarea class="textarea_edit" id="textarea_edit" name="schedule" placeholder="" disabled></textarea>
                    <!-- 自分のIDなら編集削除ボタンを表示 -->
                        <div class="links">
                            <button type="text" class="link_edit">編集する</button>
                            <button type="text" class="link_delete">削除する</button>
                        </div>
                    <!-- ここまで -->
                </div>
            </div>
        </div>
        <div class="button">
            <button type="button" class="close">一覧に戻る</button>
        </div>
    </div>

    <div class="modal" id="update_delete"> <!-- 更新用モーダル -->
        <input type="text" name="schedule_date" value="" class="day">
        <span class="close">x</span>
        <div class="contents">
            <div class="user_icon"> <!-- TODO : ここでログインユーザーの画像を取ってくる -->
                <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a>
            </div>
            <div class="details">
                <div class="detail">
                    <form action="#" method="post" id="update_form">
                        @csrf
                        <textarea class="textarea_update" id="textarea_update" name="schedule" placeholder=""></textarea>
                        <div class="links">
                            <button type="submit" class="link_update">更新する</button>
                            <button type="button" class="close">一覧に戻る</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="delete"> <!-- 削除用モーダル -->
        <input type="text" name="schedule_date" value="" class="day">
        <span class="close">x</span>
        <div class="contents">
            <div class="user_icon"> <!-- TODO : ここでログインユーザーの画像を取ってくる -->
                <a href=""><img src="{{ asset('img/icon-default-user.svg') }}" alt="自分のアイコン"></a>
            </div>
            <div class="details">
                <div class="detail">
                    <form action="#" method="post" id="delete_form"><!-- 削除用ルート -->
                        @csrf
                        <textarea class="textarea_delete" id="textarea_delete" name="schedule" placeholder="" disabled></textarea>
                        <h2>上記の予定を削除してよろしいですか？</h2>
                        <button type="submit" class="delete">はい</button>
                        <button type="button" class="close">いいえ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>