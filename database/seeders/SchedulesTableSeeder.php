<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SchedulesTableSeeder extends Seeder
{   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $start = Carbon::create("2018", "1", "1");
       $end = Carbon::create("2023", "12", "31");

       $min = strtotime($start);
       $max = strtotime($end);

       for($i = 0; $i < 30; $i++) {
            $date = rand($min, $max);
            $timestamp = date('Y-m-d', $date);
            $date = date('Y年n月j日', $date);            
            DB::table('schedules')->insert([
                'user_id' => rand(1, 3),
                'schedule' => Str::random(15),
                'schedule_date' => $date,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
       }
    }
}
