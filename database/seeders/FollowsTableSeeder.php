<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Follow::truncate();

        $userIds = DB::table('users')->pluck('id')->toArray();
        $statusArr = ['accept', 'pending'];
        $i = 0;
        foreach($userIds as $id){
            shuffle($userIds);
            foreach($userIds as $followedId){
                shuffle($userIds);
                if($id != $followedId && $i < 30){

                    try{
                        \App\Models\Follow::create([
                            'user_id'=>$id,
                            'followed_user_id'=>$followedId,
                            'status'=>$statusArr[array_rand($statusArr, 1)]
                        ]);
                        $i++ ;
                    }catch(\Exception $e){
                        dd($e);
                    }
                }
            }

        }
    }
}
