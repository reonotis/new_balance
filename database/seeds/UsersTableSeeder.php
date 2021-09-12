<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '藤澤怜臣',
                'email' => 'fujisawa@reonotis.jp',
                'password' => Hash::make('reonotis'),
            ],[
                'name' => '平瀬 尚久',
                'email' => 'hirase@fluss.co.jp',
                'password' => Hash::make('fluss'),
            ]
        ]);
    }
}
