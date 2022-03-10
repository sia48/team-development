<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('ScheduleTableSeeder::class');
        $start = Carbon::create("2022", "2", "1");
        $end = Carbon::create("2022", "2", "28");
 
        $min = strtotime($start);
        $max = strtotime($end);
 
        for($i = 0; $i < 30; $i++) {
             $date = rand($min, $max);
             $timestamp = date('Y-m-d h:m:s', $date);
             $date = date('Y-m-d', $date);            
             DB::table('suitos')->insert([
                 'money' => rand(1000, 30000),
                 'category' => Str::random(15),
                 'suito' => "$date",
                 'datetime' => "$timestamp",
                 'flag' => rand(1,2),
             ]);
        }
    }
}
