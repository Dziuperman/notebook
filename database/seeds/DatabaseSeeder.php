<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);

        $faker = Faker::create();
        foreach (range(1,50) as $index) {
            DB::table('customers')->insert([
                'user_id' => rand(1, 2),
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->e164PhoneNumber,
                'website' => 'http://'.str_random(8).'.com',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
