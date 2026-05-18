<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\MikrotikRouter;
use App\Models\InternetPackage;
use App\Models\Customer;
use App\Enums\CustomerStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Super Admin Operator
        User::query()->updateOrCreate(
            ['email' => 'admin@interboard.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Default MikroTik Router NAS Configuration
        $router = MikrotikRouter::query()->updateOrCreate(
            ['ip_address' => '192.168.88.1'],
            [
                'name' => 'Pabna Main NAS',
                'api_port' => 8728,
                'api_username' => 'admin',
                'api_password' => 'password', // Decrypted automatically by Model casts
                'status' => true,
            ]
        );

        // 3. Create Default Broadband Internet Packages
        $starterPackage = InternetPackage::query()->updateOrCreate(
            ['pppoe_profile_name' => '5mbps_profile'],
            [
                'name' => 'Starter 5Mbps',
                'price' => 500.00,
                'status' => true,
            ]
        );

        $standardPackage = InternetPackage::query()->updateOrCreate(
            ['pppoe_profile_name' => '10mbps_profile'],
            [
                'name' => 'Standard 10Mbps',
                'price' => 800.00,
                'status' => true,
            ]
        );

        $premiumPackage = InternetPackage::query()->updateOrCreate(
            ['pppoe_profile_name' => '20mbps_profile'],
            [
                'name' => 'Premium 20Mbps',
                'price' => 1200.00,
                'status' => true,
            ]
        );

        // Array of packages to choose from during customer loop seeding
        $packages = [$starterPackage, $standardPackage, $premiumPackage];

        // 4. Create 5 Dummy Subscribers with mixed statuses and packages
        $dummyCustomers = [
            [
                'name' => 'Kazi Mamun',
                'phone' => '01712345678',
                'address' => 'Pabna Sadar, Pabna',
                'pppoe_username' => 'mamun',
                'pppoe_password' => 'password123',
                'status' => CustomerStatus::ACTIVE,
                'billing_day' => 10,
                'connection_date' => Carbon::now()->subMonths(3)->toDateString(),
            ],
            [
                'name' => 'Sabbir Rahman',
                'phone' => '01812345678',
                'address' => 'Ishwardi, Pabna',
                'pppoe_username' => 'sabbir',
                'pppoe_password' => 'password123',
                'status' => CustomerStatus::ACTIVE,
                'billing_day' => 15,
                'connection_date' => Carbon::now()->subMonths(2)->toDateString(),
            ],
            [
                'name' => 'Tariqul Islam',
                'phone' => '01912345678',
                'address' => 'Sujanagar, Pabna',
                'pppoe_username' => 'tariq',
                'pppoe_password' => 'password123',
                'status' => CustomerStatus::SUSPENDED,
                'billing_day' => 5,
                'connection_date' => Carbon::now()->subMonths(6)->toDateString(),
            ],
            [
                'name' => 'Afsana Mimi',
                'phone' => '01512345678',
                'address' => 'Chatmohar, Pabna',
                'pppoe_username' => 'mimi',
                'pppoe_password' => 'password123',
                'status' => CustomerStatus::PENDING,
                'billing_day' => 1,
                'connection_date' => Carbon::now()->subDays(5)->toDateString(),
            ],
            [
                'name' => 'Rakibul Hasan',
                'phone' => '01612345678',
                'address' => 'Bhangura, Pabna',
                'pppoe_username' => 'rakib',
                'pppoe_password' => 'password123',
                'status' => CustomerStatus::ACTIVE,
                'billing_day' => 25,
                'connection_date' => Carbon::now()->subMonths(1)->toDateString(),
            ],
        ];

        foreach ($dummyCustomers as $index => $customerData) {
            // Assign packages in a round-robin format
            $assignedPackage = $packages[$index % count($packages)];

            Customer::query()->updateOrCreate(
                ['phone' => $customerData['phone']],
                [
                    'name' => $customerData['name'],
                    'address' => $customerData['address'],
                    'pppoe_username' => $customerData['pppoe_username'],
                    'pppoe_password' => $customerData['pppoe_password'],
                    'mikrotik_router_id' => $router->id,
                    'internet_package_id' => $assignedPackage->id,
                    'status' => $customerData['status'],
                    'monthly_bill' => $assignedPackage->price,
                    'billing_day' => $customerData['billing_day'],
                    'connection_date' => $customerData['connection_date'],
                ]
            );
        }
    }
}
