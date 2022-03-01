<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; //日付のライブラリ
use Yasumi\Yasumi; //祝日取得のライブラリ

class ShowController extends Controller
{
    public function showCalendar($year, $month)
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

        $user = Auth::user();
        $date_key = $year . '年' . $month . '月';

        $schedules = Schedule::select('users.id', 'users.group_id', 'users.belongs_group', 'schedules.user_id', 'schedules.id as schedule_id', 'schedules.schedule_date', 'schedules.schedule')
                ->join('users', 'users.id', '=', 'schedules.user_id')
                ->where('schedules.schedule_date', 'like', "%$date_key%")
                ->get();

        foreach($schedules as $schedule) {
            if(isset($schedule->belongs_group)) {
                $belong_groups = explode(' ', $schedule->belongs_group );
                foreach($belong_groups as $belong_group) {
                    if($belong_group == $user->group_id) {
                        $view_schedules[] = $schedule;
                        break;
                    }
                }
            }
        }

        if(empty($view_schedules)) {
            $view_schedules = null;
        }

        $my_schedules = Schedule::select('id', 'user_id', 'schedule', 'schedule_date')
                    ->where('user_id', '=', $user->id)
                    ->where('schedule_date', 'like', "%$date_key%")
                    ->orderBy('schedule_date', 'asc')
                    ->get();

        if($user->invitation != 0) {
            $invitations = explode(' ', $user->invitation);
            foreach($invitations as $invitation) {
                $groups = Group::find($invitation);
                $author_id = $groups->created_user_id;
                $author = User::find($author_id);
                break;
            }
        } else {
            $groups = null;
            $author = null;
        }

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

        return view('index', 
            [
                'dates' => $dates, 
                'year' => $year, 
                'month' => $month, 
                'month_en' => $month_en, 
                'holidays' => $holidays, 
                'google_holidays' => $google_holidays, 
                'user' => $user, 
                'view_schedules' => $view_schedules, 
                'groups' => $groups, 
                'author' => $author, 
                'my_schedules' => $my_schedules
            ]
        );
    }

    public function requestCalendar(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
                
        return redirect()->route('calendar', ['year' => $year, 'month' => $month]);
    }

    public function store(Request $request, $year, $month)
    {   
        $request->validate([
            'schedule' => 'required|max:100'
        ],
        [
            'schedule.required' => '予定を入力して下さい',
            'schedule.max' => '最大100文字までです'
        ]);

        $content = new Schedule();
        $content->user_id = Auth::user()->id;
        $content->schedule = $request->schedule;
        $content->schedule_date = $request->schedule_date;
        $content->save();
                
        return redirect()->route('calendar', ['year' => $year, 'month' => $month]);
    }

    public function edit($year, $month, $id, Request $request)
    {   
        $request->validate([
            'schedule' => 'max:100',
            'schedule_edit' => 'required|max:100'
        ],
        [
            'schedule_edit.required' => '予定を入力して下さい',
            'schedule_edit.max' => '最大100文字までです',
            'schedule.max' => '最大100文字までです'
        ]);

        $content = Schedule::find($id);
        $content->schedule = $request->schedule_edit;
        $content->save();
        return redirect()->route('calendar', ['year' => $year, 'month' => $month]);
    }

    public function destroy($year, $month, $id, Request $request)
    {   
        $content = Schedule::find($id);
        $content->delete();
        return redirect()->route('calendar', ['year' => $year, 'month' => $month]);
    }

    public function profile(Request $request, $id) 
    {
        $user = User::find($id);
        $user->name = $request->name;
        if(isset($request->password)) {
            $rules = [
                'password' => 'confirmed|max:128|min:8'
            ];
            $this->validate($request, $rules);
            $user->password = Hash::make($request->password);
        }

        if(isset($request->user_image)) {
            $user->profile_photo_path = $request->user_image->store('public/user-image');
            $user->profile_photo_path = str_replace('public/user-image', '', $user->profile_photo_path);
        }
        $user->save();

        return redirect()->route('calendar', ['year' => date('Y'), 'month' => date('n')]);
    }

    public function test($day, $group_id, $user_id)
    {   
        $contents = Schedule::select('users.id', 'users.group_id', 'users.belongs_group', 'schedules.user_id', 'schedules.id as schedule_id', 'schedules.schedule_date', 'schedules.schedule')
            ->join('users', 'users.id', '=', 'schedules.user_id')
            ->where('schedules.schedule_date', '=', $day)
            ->get();

            $view_schedules = [];
            $my_schedules = [];

            foreach($contents as $content) {
                if(isset($content->belongs_group)) {
                    $belong_groups = explode(' ', $content->belongs_group );
                    foreach($belong_groups as $belong_group) {
                        if($belong_group == $group_id) {
                            $view_schedules[] = $content;
                            break;
                        }
                    }
                }
            }

            foreach($contents as $content) {
                if($content->user_id == $user_id) {
                    $my_schedules[] = $content;
                }
            }

            if(empty($view_schedules)) {
                return $my_schedules;
            } else {
                return $view_schedules;
            }
    }
}