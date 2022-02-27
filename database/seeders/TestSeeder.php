<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //ユーザーのテストデータ
        $timestamp = date('Y-m-d');

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'テストユーザー1',
            'email' => 'test@test',
            'password' => Hash::make('passwordtest'),
            'profile_photo_path' => 'icon-default-user.svg',
            'group_id' => 1,
            'belongs_group' => 1,
            'invitation' => 0,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'テストユーザー2',
            'email' => 'test2@test',
            'password' => Hash::make('passwordtest2'),
            'profile_photo_path' => 'icon-default-user.svg',
            'group_id' => 2,
            'belongs_group' => 2,
            'invitation' => 0,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'name' => 'テストユーザー3',
            'email' => 'test3@test',
            'password' => Hash::make('passwordtest3'),
            'profile_photo_path' => 'icon-default-user.svg',
            'group_id' => 3,
            'belongs_group' => 3,
            'invitation' => 0,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'name' => '二郎',
            'email' => 'jiro@jiro',
            'password' => Hash::make('passwordjiro'),
            'profile_photo_path' => 'icon-default-user.svg',
            'group_id' => 2,
            'belongs_group' => 2,
            'invitation' => 0,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'name' => '三郎',
            'email' => 'saburo@saburo',
            'password' => Hash::make('passwordsaburo'),
            'profile_photo_path' => 'icon-default-user.svg',
            'group_id' => 3,
            'belongs_group' => 3,
            'invitation' => 0,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);


        //グループのテストデータ
        DB::table('groups')->insert([
            'id' => 1,
            'group_name' => 'テストグループ1',
            'group_image' => 'icon-default-user.svg',
            'created_user_id' => '1',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        DB::table('groups')->insert([
            'id' => 2,
            'group_name' => 'テストグループ2',
            'group_image' => 'icon-default-user.svg',
            'created_user_id' => '2',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        DB::table('groups')->insert([
            'id' => 3,
            'group_name' => 'テストグループ3',
            'group_image' => 'icon-default-user.svg',
            'created_user_id' => '3',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        DB::table('groups')->insert([
            'id' => 4,
            'group_name' => '脱退テストグループ',
            'group_image' => 'icon-default-user.svg',
            'created_user_id' => '1',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);


        //スケジュールのテストデータ
        $start = Carbon::create("2022", "2", "1");
        $end = Carbon::create("2022", "2", "28");
 
        $min = strtotime($start);
        $max = strtotime($end);
 
        for($i = 1; $i < 11; $i++) {
             $date = rand($min, $max);
             $timestamp = date('Y-m-d', $date);
             $date = date('Y年n月j日', $date);            
             DB::table('schedules')->insert([
                 'user_id' => 1,
                 'schedule' => 'テストユーザー1の投稿：' . $i . '個目です',
                 'schedule_date' => $date,
                 'created_at' => $timestamp,
                 'updated_at' => $timestamp
             ]);
        }

        for($i = 1; $i < 11; $i++) {
            $date = rand($min, $max);
            $timestamp = date('Y-m-d', $date);
            $date = date('Y年n月j日', $date);            
            DB::table('schedules')->insert([
                'user_id' => 2,
                'schedule' => 'テストユーザー2の投稿：' . $i . '個目です',
                'schedule_date' => $date,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
       }

       for($i = 1; $i < 11; $i++) {
            $date = rand($min, $max);
            $timestamp = date('Y-m-d', $date);
            $date = date('Y年n月j日', $date);            
            DB::table('schedules')->insert([
                'user_id' => 3,
                'schedule' => 'テストユーザー3の投稿：' . $i . '個目です',
                'schedule_date' => $date,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
        }
    }
}
