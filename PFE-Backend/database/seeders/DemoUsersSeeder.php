<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Marie',
                'last_name' => 'Dupont',
                'email' => 'marie.dupont@entreprise.fr',
                'role' => 'client',
                'account_status' => 'active',
                'company_name' => 'Dupont SAS',
                'phone' => '06 12 34 56 78',
                'wants_newsletter' => true,
                'newsletter_category' => 'loi25',
            ],
            [
                'first_name' => 'Jean',
                'last_name' => 'Martin',
                'email' => 'jean.martin@client.com',
                'role' => 'client',
                'account_status' => 'pending',
                'company_name' => 'Martin Consulting',
                'phone' => '06 98 76 54 32',
                'wants_newsletter' => false,
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Bernard',
                'email' => 'sophie.bernard@client.com',
                'role' => 'client',
                'account_status' => 'inactive',
                'company_name' => 'Bernard Tech',
                'phone' => null,
                'wants_newsletter' => false,
            ],
            [
                'first_name' => 'Lucas',
                'last_name' => 'Petit',
                'email' => 'lucas.petit@piqueou.com',
                'role' => 'analyst',
                'account_status' => 'active',
                'company_name' => 'PIQUÉOU Conseil Inc.',
                'phone' => '07 11 22 33 44',
                'wants_newsletter' => true,
                'newsletter_category' => 'cmmc',
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Leroy',
                'email' => 'emma.leroy@piqueou.com',
                'role' => 'analyst',
                'account_status' => 'pending',
                'company_name' => 'PIQUÉOU Conseil Inc.',
                'phone' => '07 55 66 77 88',
                'wants_newsletter' => false,
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password123'),
                    'email_verified_at' => $data['account_status'] === 'active' ? now() : null,
                    'activated_at' => $data['account_status'] === 'active' ? now()->subDays(3) : null,
                ])
            );

            $user->roles()->sync(
                [Role::where('name', $role)->firstOrFail()->id]
            );
        }
    }
}
