<?php

namespace Database\Seeders;
use App\Models\User;
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
        User::create([
        'name' => 'Bob Smith',
        'email' => 'bob@example.com',
        'password' => bcrypt('password'),
    ]);
    User::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => bcrypt('password'),
    ]);
    User::create([
        'name' => 'Alice Smith',
        'email' => 'alice@example.com',
        'password' => bcrypt('password'),
    ]);
    User::create([
        'name' => 'Michael Johnson',
        'email' => 'michael@example.com',
        'password' => bcrypt('password'),
    ]);
    User::create([
        'name' => 'Emily Davis',
        'email' => 'emily@example.com',
        'password' => bcrypt('password'),
    ]);
    User::create([
        'name' => 'William Wilson',
        'email' => 'william@example.com',
        'password' => bcrypt('password'),
    ]);
    User::create([
        'name' => 'Olivia Taylor',
        'email' => 'olivia@example.com',
        'password' => bcrypt('password'),
    ]);
    User::create([
        'name' => 'James Brown',
        'email' => 'james@example.com',
        'password' => bcrypt('password'),
        ]);
    User::create([
        'name' => 'Sophia Lee',
        'email' => 'sophia@example.com',
        'password' => bcrypt('password'),
    ]);
}
}

