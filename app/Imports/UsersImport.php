<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row): ?User
    {
        return new User([
            'name'              => $row['name'],
            'email'             => $row['email'],
            'email_verified_at' => now(),
            'password'          => bcrypt($row['password']),
            'type'              => 'agency',
            'balance'           => $row['balance'],
            'is_approved'       => 1,
            'active'            => 1,
            'agency_id'         => 1,
            'api'               => 0,
        ]);
    }
}
