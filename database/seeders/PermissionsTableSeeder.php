<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'auth_profile_edit',
            ],
            [
                'id'    => 2,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 3,
                'title' => 'permission_create',
            ],
            [
                'id'    => 4,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 5,
                'title' => 'permission_show',
            ],
            [
                'id'    => 6,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 7,
                'title' => 'permission_access',
            ],
            [
                'id'    => 8,
                'title' => 'role_create',
            ],
            [
                'id'    => 9,
                'title' => 'role_edit',
            ],
            [
                'id'    => 10,
                'title' => 'role_show',
            ],
            [
                'id'    => 11,
                'title' => 'role_delete',
            ],
            [
                'id'    => 12,
                'title' => 'role_access',
            ],
            [
                'id'    => 13,
                'title' => 'user_create',
            ],
            [
                'id'    => 14,
                'title' => 'user_edit',
            ],
            [
                'id'    => 15,
                'title' => 'user_show',
            ],
            [
                'id'    => 16,
                'title' => 'user_delete',
            ],
            [
                'id'    => 17,
                'title' => 'user_access',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 19,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 20,
                'title' => 'airline_create',
            ],
            [
                'id'    => 21,
                'title' => 'airline_edit',
            ],
            [
                'id'    => 22,
                'title' => 'airline_delete',
            ],
            [
                'id'    => 23,
                'title' => 'airline_access',
            ],
            [
                'id'    => 24,
                'title' => 'setting_access',
            ],
            [
                'id'    => 25,
                'title' => 'category_create',
            ],
            [
                'id'    => 26,
                'title' => 'category_edit',
            ],
            [
                'id'    => 27,
                'title' => 'category_show',
            ],
            [
                'id'    => 28,
                'title' => 'category_delete',
            ],
            [
                'id'    => 29,
                'title' => 'category_access',
            ],
            [
                'id'    => 30,
                'title' => 'update_create',
            ],
            [
                'id'    => 31,
                'title' => 'update_edit',
            ],
            [
                'id'    => 32,
                'title' => 'update_show',
            ],
            [
                'id'    => 33,
                'title' => 'update_delete',
            ],
            [
                'id'    => 34,
                'title' => 'update_access',
            ],
            [
                'id'    => 35,
                'title' => 'contract_create',
            ],
            [
                'id'    => 36,
                'title' => 'contract_edit',
            ],
            [
                'id'    => 37,
                'title' => 'contract_show',
            ],
            [
                'id'    => 38,
                'title' => 'contract_delete',
            ],
            [
                'id'    => 39,
                'title' => 'contract_access',
            ],
            [
                'id'    => 40,
                'title' => 'flight_create',
            ],
            [
                'id'    => 41,
                'title' => 'flight_edit',
            ],
            [
                'id'    => 42,
                'title' => 'flight_show',
            ],
            [
                'id'    => 43,
                'title' => 'flight_delete',
            ],
            [
                'id'    => 44,
                'title' => 'flight_access',
            ],
            [
                'id'    => 45,
                'title' => 'location_create',
            ],
            [
                'id'    => 46,
                'title' => 'location_edit',
            ],
            [
                'id'    => 47,
                'title' => 'location_show',
            ],
            [
                'id'    => 48,
                'title' => 'location_delete',
            ],
            [
                'id'    => 49,
                'title' => 'location_access',
            ],
            [
                'id'    => 50,
                'title' => 'price_create',
            ],
            [
                'id'    => 51,
                'title' => 'price_edit',
            ],
            [
                'id'    => 52,
                'title' => 'price_show',
            ],
            [
                'id'    => 53,
                'title' => 'price_delete',
            ],
            [
                'id'    => 54,
                'title' => 'price_access',
            ],
            [
                'id'    => 55,
                'title' => 'booking_create',
            ],
            [
                'id'    => 56,
                'title' => 'booking_edit',
            ],
            [
                'id'    => 57,
                'title' => 'booking_show',
            ],
            [
                'id'    => 58,
                'title' => 'booking_delete',
            ],
            [
                'id'    => 59,
                'title' => 'booking_access',
            ],
            [
                'id'    => 60,
                'title' => 'ticket_create',
            ],
            [
                'id'    => 61,
                'title' => 'ticket_edit',
            ],
            [
                'id'    => 62,
                'title' => 'ticket_show',
            ],
            [
                'id'    => 63,
                'title' => 'ticket_delete',
            ],
            [
                'id'    => 64,
                'title' => 'ticket_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
