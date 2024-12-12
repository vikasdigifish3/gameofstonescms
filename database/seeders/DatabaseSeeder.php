<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // create admin user
        DB::table('users')->insert([
            ['name' => 'Admin','email' => 'parveen@digifish3.com', 'password'  =>  Hash::make('password@123#'), 'email_verified_at' => date('Y-m-d H:i:s')],
        ]);
        $this->call(ContentTagTableSeeder::class);

    }
}
