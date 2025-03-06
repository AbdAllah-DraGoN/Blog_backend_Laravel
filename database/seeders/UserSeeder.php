<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run()
    {
        $names = [
            "abdallah",
            "dragon",
            "abdallahdragon",
            "test",
            "developer",
        ];

        $emails = [
            "abdallah@gmail.com",
            "dragon@gmail.com",
            "abdallahdragon@gmail.com",
            "test@gmail.com",
            "developer@gmail.com",
        ];

        $images = [
            "storage/usersImages/person-1.png",
            "storage/usersImages/person-2.jpg",
            "storage/usersImages/person-3.jpg",
        ];

        foreach (range(0, 4) as $i) {
            User::create([
                'name' => $names[$i],
                'email' => $emails[$i],
                'password' => Hash::make('112233'),
                'image' => $images[array_rand($images)],
            ]);
        }
    }
}
