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
        $this->call(UserTypesTableSeeder::class);
        $this->call(ModuleTableSeeder::class);
        $this->call(BranchTableSeeder::class);
        $this->call(AccessTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(KeywordsTableSeeder::class);
    }
}
