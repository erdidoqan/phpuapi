<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class); //yayın için gerekli seed
        $this->call(SubscriptionTypeTableSeeder::class);
        /*$this->call(LaracastSeeder::class);
        $this->call(StudentSeeder::class);*/
    }
}
