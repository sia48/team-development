<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; //日付のライブラリ
use Yasumi\Yasumi; //祝日取得のライブラリ
use App\Models\Suito;

class SuitoController extends Controller
{
    public function showSuito($year, $month)
    {   
        /*googleAPI設定////////////////////////////////////////////////////////////////////////////////////////////////*/
        $key = 
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
            'singleEvents' => 'true'
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

        return view('Suito.suito', ['dates' => $dates, 'year' => $year, 'month' => $month, 'month_en' => $month_en, 'holidays' => $holidays, 'google_holidays' => $google_holidays]);
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

    public function destroy($year, $month, $id, Request $request)
    {   
        $content = Suito::find($id);
        $content->delete();
        return redirect()->route('suito', ['year' => $year, 'month' => $month]);
    }

    public function test($day)
    {   
        $contents = Suito::where('suito', '=', $day )->get();
        return $contents;
    }

   public function suitoStore(Request $request, $year, $month)
   {
       $content = new Suito();

       $d = str_replace('日', '', $request->suito_date);  // "日"を空文字に置換する
       $d = str_replace('年', '/', $d); // "年"を"/"に置換する
       $d = str_replace('月', '/', $d);

       $content->create([
           'category' => $request->category,
           'money' => $request->money,
           'flag' => $request->flag,
           'datetime' => $d
       ]);

       return redirect()->route('suito', ['year' => $year, 'month' => $month]);
   }
    
    public function suitoIncome()
    {
        $suitos = Suito::find('id', 1)->first();
        dd($suitos);
        return view('Suito.suito',['suitos' => $suitos]);
    }
}
