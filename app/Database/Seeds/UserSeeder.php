<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        //
        helper("fn");
        $userModel = new UserModel();

        $password = "Admin@123";
        $admin = [
            "email" => "admin@gmail.com",
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "city" => 191,
            "is_admin" => 1,
            "first_name" => "Jemish",
            "last_name" => "Dhameliya",
            "contact_number" => "9512183895",
            "gender" => 0,
            "hobbies" => "Gaming,Research"
        ];
        
        $userModel->save($admin);


        for ($i=0; $i < 50; $i++) { 

            $index = $i+1;
            $contact = generateNumeric(10);
            $user = [
                "email" => "user$index@gmail.com",
                "password" => password_hash("User@1234", PASSWORD_DEFAULT),
                "city" => $index,
                "is_admin" => 0,
                "first_name" => "User$index",
                "last_name" => "Demo",
                "contact_number" => $contact,
                "gender" => 0,
            ];

            $userModel->save($user);

        }
    }
}
