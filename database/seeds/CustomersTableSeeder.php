<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'name' => 'Leanne Graham',
                'email' => 'Sincere@april.biz',
                'phone' => '1-770-736-8031',
                'website' => 'http://hildegard.org',
            ],
            [
                'user_id' => 1,
                'name' => 'Ervin Howell',
                'email' => 'Shanna@melissa.tv',
                'phone' => '010-692-6593',
                'website' => 'http://anastasia.net',
            ],
            [
                'user_id' => 1,
                'name' => 'Clementine Bauch',
                'email' => 'Nathan@yesenia.net',
                'phone' => '1-463-123-4447',
                'website' => 'http://ramiro.info',
            ],
            [
                'user_id' => 1,
                'name' => 'Patricia Lebsack',
                'email' => 'Julianne.OConner@kory.org',
                'phone' => '493-170-9623',
                'website' => 'http://kale.biz',
            ],

        ];

        for ($i = 1; $i <= 50; $i++) {
            $cName = 'Категория #'.$i;
            $user_id = rand(1, 2);

            $customers[] = [
                'user_id' => $user_id,
                'name' => str_random(10),
                'email' => str_random(8).'@gmail.com',
                'phone' => rand(8),
                'slug' => Str::slug($cName),
            ];
        }

        DB::table('customers')->insert($data);
    }
}
