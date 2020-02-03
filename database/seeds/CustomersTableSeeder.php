<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'user_id' => 1,
                'name' => ' new Test',
                'email' => 'new@gmail.com',
                'phone' => '987359873459',
                'website' => 'http://new.com',
            ],
            [
                'user_id' => 1,
                'name' => 'new Test-2',
                'email' => 'new2@gmail.com',
                'phone' => '987345983745',
                'website' => 'http://new2.com',
            ],
        ];

        DB::table('customers')->insert($data);
    }
}
