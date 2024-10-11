<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddAdminAndTheirPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = [
            ['email' => 'lisa@humanop.com', 'password' => 'lisa@humanop', 'first_name' => 'Lisa', 'last_name' => 'Nelson', 'gender' => Admin::IS_FEMALE, 'role' => Admin::IS_ADMIN],
            ['email' => 'wei@humanop.com', 'password' => 'wei@humanop', 'first_name' => 'Wei', 'last_name' => 'Houng', 'gender' => Admin::IS_MALE, 'role' => Admin::IS_ADMIN],
            ['email' => 'zannah@humanop.com', 'password' => 'zannah@humanop', 'first_name' => 'Zannah', 'last_name' => 'Hackett', 'gender' => Admin::IS_MALE, 'role' => Admin::SUB_ADMIN],
            ['email' => 'brant@humanop.com', 'password' => 'Brant@humanop', 'first_name' => 'Brant', 'last_name' => 'Hindman', 'gender' => Admin::IS_MALE, 'role' => Admin::IS_ADMIN],
            ['email' => 'admin@humanop.com', 'password' => '12345678', 'first_name' => 'Admin', 'last_name' => 'Developers', 'gender' => Admin::IS_MALE, 'role' => Admin::IS_ADMIN],
        ];

        $clients = [
            ['email' => 'apple@humanop.com', 'password' => 'weloveapple@humanop.com', 'first_name' => 'Apple', 'last_name' => 'User', 'gender' => Admin::IS_MALE]
        ];

        DB::beginTransaction();

        try {

            foreach ($users as $user) {

//                $findUsers = User::where('email', $user['email'])->get();
//
//                foreach ($findUsers as $findUser) {
//
//                    DB::table('model_has_roles')->where('model_id', $findUser->id)->delete();
//
//                    DB::table('model_has_permissions')->where('model_id', $findUser->id)->delete();
//
//                    $findUser->delete();
//                }

                $newUser = User::create([
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'password' => $user['password'],
                    'email' => $user['email'],
                    'gender' => $user['gender'],
                    'is_admin' => $user['role'],
                    'status' => 1,
                ]);

                if ($user['role'] === 3) {

                    $role_id = Role::where('name', 'sub admin')->first()->id ?? null;

                } else if ($user['role'] === 1) {

                    $role_id = Role::where('name', 'super admin')->first()->id ?? null;
                }

                DB::table('model_has_roles')->insert([
                    ['role_id' => $role_id ?? null, 'model_type' => 'App\Models\User', 'model_id' => $newUser->id],
                ]);

                $permissions = Permission::all();

                foreach ($permissions as $permission) {

                    if ($user['role'] === 3) { // sub admin

                        if ($permission->name != 'approveQueries') {

                            DB::table('model_has_permissions')->insert([
                                ['permission_id' => $permission->id ?? null, 'model_type' => 'App\Models\User', 'model_id' => $newUser->id],
                            ]);

                        }

                    } elseif ($user['role'] === 1) { // super admin

                        DB::table('model_has_permissions')->insert([
                            ['permission_id' => $permission->id ?? null, 'model_type' => 'App\Models\User', 'model_id' => $newUser->id],
                        ]);

                    }

                }

            }

            foreach ($clients as $client) {

//                User::where('email', $client['email'])->delete();

                User::create([
                    'first_name' => $client['first_name'],
                    'last_name' => $client['last_name'],
                    'password' => $client['password'],
                    'email' => $client['email'],
                    'gender' => $client['gender'],
                    'is_admin' => Admin::IS_CUSTOMER,
                    'status' => 1,
                ]);
            }

            User::query()->update(['status' => 1]);

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            Log::info(['add admin seeder exception' => $exception->getMessage()]);
        }

    }
}
