<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Đặng Minh Đạt',
                'email' => 'datdmgcc210147@fpt.edu.vn',
                'account_id' => 'GCC210147',
                'role' => '1',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Nguyễn Hoàng Kha',
                'email' => 'khanhgbc200062@fpt.edu.vn',
                'account_id' => 'GBC200062',
                'role' => '0',
                'password' => Hash::make('12345678'),
            ],
            // [
            //     'name' => 'Phạm Hoàng Nam',
            //     'email' => 'NamPHGBC210003@fpt.edu.vn',
            //     'account_id' => 'GBC210003',
            //     'role' => '2',
            //     'password' => Hash::make('12345678'),
            // ],
            // [
            //     'name' => 'Nguyễn Đăng Xuân Thiên',
            //     'email' => 'ThienNDXGBC210009@fpt.edu.vn',
            //     'account_id' => 'GBC210009',
            //     'role' => '2',
            //     'password' => Hash::make('12345678'),
            // ],
            // [
            //     'name' => 'Phan Thị Cẩm Ước',
            //     'email' => 'UocPTCGCC210016@fpt.edu.vn',
            //     'account_id' => 'GCC210016',
            //     'role' => '2',
            //     'password' => Hash::make('12345678'),
            // ],
            // [
            //     'name' => 'Đinh Nhật Trường',
            //     'email' => 'TruongDNGDC210020@fpt.edu.vn',
            //     'account_id' => 'GDC210020',
            //     'role' => '2',
            //     'password' => Hash::make('12345678'),
            // ],
            // [
            //     'name' => 'Dương Gia Huy',
            //     'email' => 'HuyDGGBC210018@fpt.edu.vn',
            //     'account_id' => 'GBC210018',
            //     'role' => '2',
            //     'password' => Hash::make('12345678'),
            // ]

            // Add more sample users as needed
        ];

        // Insert the sample data into the database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
