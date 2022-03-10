<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; //日付のライブラリ
use Yasumi\Yasumi; //祝日取得のライブラリ
use App\Models\Suito;
use DB;
use Auth;

class SuitoController extends Controller
{
    public function showSuito($year, $month)
    {   
        /*googleAPI設定////////////////////////////////////////////////////////////////////////////////////////////////*/
        $key = 'AIzaSyBHn2iBpie5DM5v80qC5M68-XfZn-TlG10';
        $calendar_id = urlencode('japanese__ja@holiday.calendar.google.com');  // Googleの提供する日本の祝日カレンダー
        $start = $year . '-' . '01' . '-' . '01' . 'T00:00:00Z'; //2022-01-01 の形にする
        $end = $year . '-' . '12' . '-' . '31' . 'T00:00:00Z'; //2022-12-31の形にする
        $url = "https://www.googleapis.com/calendar/v3/calendars/" . $calendar_id . "/events?"; //リクエスト先のURL
        $query = [
            'key' => $key,
            'timeMin' => $start,
            'timeMax' => $end,
            'maxResults' => 50,
            'orderBy' => 'startTime',
            'singleEvents' => 'true',
        ];
        $google_holidays = [];
        if ($data = file_get_contents($url. http_build_query($query), true)) {
            $data = json_decode($data);
            foreach ($data->items as $val) { // $data->itemは祝日を取得する
                $google_holidays[$val->start->date] = $val->summary; // [予定の日付 => 予定のタイトル]
            }
        }       
        /*googleAPI設定ここまで//////////////////////////////////////////////////////////////////////////////////////////*/

        $date = sprintf('%04d-%02d-01', $year, $month);
        $date = new Carbon($date);
        $holidays = Yasumi::create('Japan', $year, 'ja_JP'); //その年の祝日を取得

        $end_day_week = $date->copy()->endOfMonth()->dayOfWeek; //その月の最終日の曜日を取得
        if ($end_day_week != 6 ) {
            $next_month_day = 6 - $end_day_week; //はみ出す日数を計算
        } else {
            $next_month_day = 0;
        }

        //その月の始まりの曜日+はみ出す日数+その月の日数を足す = カレンダー1ページ分を取得
        $this_month_total_days = $date->copy()->firstOfMonth()->dayOfWeek + $next_month_day + $date->copy()->daysInMonth; 

        $date->subDays($date->dayOfWeek); //カレンダーの前月分の計算
        
        $dates = [];

        for ($i = 0; $i < $this_month_total_days; $i++, $date->addDay()) {
            $dates[] = $date->copy(); 
        }

        $user = Auth::user();
        //$contents = Suito::where('suito_date', 'like', );

        $month_en = [ //英語表記の為の配列
            0,
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // suitosテーブル検索 by K
        $first_day = date('Y-m-d',strtotime($year.'-'.$month.'-01')); // 月の初日 2022-02-01
        // mktimeの引数0,0,0は時間、分、秒
        // 月の最後の日付けはバラバラのため現在の月から+1して日付けを-1して取得する
        // 後ろの0は日付を表している。0は1日よりもう1つ前の日付を指している。これで月の最後の日を取得
        $last_day = date('Y-m-d',mktime(0,0,0,$month + 1,0,$year)); // 月の初日 2022-02-01

        $suitos = Suito::whereBetween('suito',[$first_day,$last_day])->orderBy('suito','asc')->get();
        // error_log("SuitoController::suito [".$first_day."],[".$last_day."]");
        // whereBetweenで今月の初日から期末までの出納テーブルを検索
        // flag = 2が収入,flag=1が出費
        $plus_total = Suito::where('flag',2)->whereBetween('suito',[$first_day,$last_day])->sum('money');
        $minus_total = Suito::where('flag',1)->whereBetween('suito',[$first_day,$last_day])->sum('money');
        $categories = Suito::where('category')->whereBetween('suito',[$first_day,$last_day]);
        
        // $p_daily_total = Suito::where('flag',2)->sum('money');
        // $m_daily_total = Suito::where('flag',1)->sum('money');
        $p_daily_total = Suito::select('suito')->selectRaw('SUM(money) as total')->where('flag', '2')->groupby('suito')->whereBetween('suito', [$first_day, $last_day])->get();
        $m_daily_total = Suito::select('suito')->selectRaw('SUM(money) as total')->where('flag', '1')->groupby('suito')->whereBetween('suito', [$first_day, $last_day])->get();

        /*
        select A.suito, (ifnull(A.p_total, 0) - ifnull(B.m_total, 0)) as total from
        (select suito, sum(money) as p_total from suitos where flag = '2' and suito between '2022-03-01' and '2022-03-31' group by suito, flag) as A
        left outer join (select suito, sum(money) as m_total from suitos where flag = '1' and suito between '2022-03-01' and '2022-03-31' group by suito, flag) as B
        on A.suito = B.suito
        */

        // 収入の日毎の合計を出すサブクエリー
        $subQuery_a = DB::table('suitos', 'A')
        ->selectRaw('suito, sum(money) as p_total')
        ->where('flag', '2')
        ->whereBetween('suito', [$first_day, $last_day])
        ->groupby('suito', 'flag');
    
        // 支出の日毎の合計を出すサブクエリー
        $subQuery_b = DB::table('suitos', 'B')
            ->selectRaw('suito, sum(money) as m_total')
            ->where('flag', '1')
            ->whereBetween('suito', [$first_day, $last_day])
            ->groupby('suito', 'flag');

        // 左：収入 右：支出
        // 収入 - 支出の合計値を出すサブクエリー
        // ただし収入があるレコードだけ（left outer join
        $query_f = DB::table($subQuery_a, 'A')
            ->select(['A.suito', DB::raw('ifnull(A.p_total, 0) - ifnull(B.m_total, 0) as total')])
            ->leftJoinSub($subQuery_b, "B", 'A.suito', '=', 'B.suito');

        // 収入 - 支出の合計を出すサブクエリー
        // ただし支出があるレコードだけ（right outer join
        $query_b = DB::table($subQuery_a, 'A')
            ->select(['B.suito', DB::raw('ifnull(A.p_total, 0) - ifnull(B.m_total, 0) as total')])
            ->rightJoinSub($subQuery_b, "B", 'A.suito', '=', 'B.suito');
        
        // $query_f と $query_b をunionで和集合を取る
        $daily_total = $query_f->union($query_b)->orderby('suito', 'asc')->get();

        $view = view('Suito.suito', [
            'dates' => $dates, 
            'year' => $year, 
            'month' => $month, 
            'month_en' => $month_en, 
            'holidays' => $holidays, 
            'google_holidays' => $google_holidays,
            'user' => $user,
            'suitos' => $suitos,
            'plus_total' => $plus_total,
            'minus_total' => $minus_total,
            // 'p_daily_total' =>$p_daily_total, 
            // 'm_daily_total' =>$m_daily_total, 
            'daily_total' => $daily_total,
            'categories' => $categories,
        ]);
        return $view;
    }

    public function requestCalendar(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
                
        return redirect()->route('suito', ['year' => $year, 'month' => $month]);
    }

    public function store(Request $request, $year, $month)
    {
        $content = new Suito();
        $content->user_id = 1;
        $content->suito = $request->suito;
        $content->suito_date = $request->suito_date;
        $content->save();
                
        return redirect()->route('suito', ['year' => $year, 'month' => $month]);
    }

    public function edit($year, $month, $id, Request $request)
    {   
        $content = Suito::find($id);
        $content->suito = $request->suito;
        $content->save();
        return redirect()->route('suito', ['year' => $year, 'month' => $month]);
    }

    public function suitoDestroy($year,$month,$id)
    {   
        $content = Suito::find($id);
        $content->delete();
        return redirect()->route('suito',['year' => $year, 'month' => $month]);
        // header('Content-Type: application/json; charset=uft-8');
        // echo json_encode($id);
    }

    public function test($key)
    {   
        $contents = Suito::where('suito', '=', $key )->get();
        return $contents;
    }

   public function suitoStore(Request $request, $year, $month)
   {
       $content = new Suito();

       $d = str_replace('日', '', $request->suito_date);  // "日"を空文字に置換する
       $d = str_replace('年', '-', $d); // "年"を"/"に置換する
       $d = str_replace('月', '-', $d);

       $content->create([
           'category' => $request->category,
           'money' => $request->money,
           'flag' => $request->flag,
           'suito' => $d,
           'datetime' => $d
       ]);

       return redirect()->route('suito', ['year' => $year, 'month' => $month]);
   }
    
    public function suitoIncome()
    {
        $content = Suito::find('money');
        return view('Suito.suito',['money' => $content]);
    }
}
