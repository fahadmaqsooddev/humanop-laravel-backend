<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            ['email' => 'lisa@humanoptech.com', 'password' => 'lisa@humanoptech','first_name' => 'Lisa', 'last_name' => 'Nelson', 'gender' => Admin::IS_FEMALE, 'role' => Admin::IS_ADMIN],
            ['email' => 'wei@humanoptech.com', 'password' => 'wei@humanoptech','first_name' => 'Wei', 'last_name' => 'Houng', 'gender' => Admin::IS_MALE, 'role' => Admin::SUB_ADMIN],
            ['email' => 'zannah@humanoptech.com', 'password' => 'zannah@humanoptech','first_name' => 'Zannah', 'last_name' => 'Hackett', 'gender' => Admin::IS_MALE, 'role' => Admin::SUB_ADMIN],
            ['email' => 'brant@humanop.ai', 'password' => 'brant@humanop','first_name' => 'Brant', 'last_name' => 'Hindman', 'gender' => Admin::IS_MALE, 'role' => Admin::SUB_ADMIN],
            ['email' => 'admin@humanoptech.com', 'password' => '12345678','first_name' => 'Admin', 'last_name' => 'Developers', 'gender' => Admin::IS_MALE, 'role' => Admin::IS_ADMIN],
        ];

        DB::beginTransaction();

        try {

            foreach ($users as $user){

                $findUsers = User::where('email', $user['email'])->get();

                foreach ($findUsers as $findUser){

                    DB::table('model_has_roles')->where('model_id', $findUser->id)->delete();

                    DB::table('model_has_permissions')->where('model_id', $findUser->id)->delete();

                    $findUser->delete();
                }

                $newUser = User::create([
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'password' => $user['password'],
                    'email' => $user['email'],
                    'gender' => $user['gender'],
                    'is_admin' => $user['role'],
                ]);

                if ($user['role'] === 3){

                    $role_id = Role::where('name', 'sub admin')->first()->id ?? null;

                }else if ($user['role'] === 1){

                    $role_id = Role::where('name', 'super admin')->first()->id ?? null;
                }

                DB::table('model_has_roles')->insert([
                    ['role_id' => $role_id ?? null, 'model_type' => 'App\Models\User','model_id' => $newUser->id],
                ]);

                $permissions = Permission::all();

                foreach ($permissions as $permission){

                    if ($user['role'] === 3){ // sub admin

                        if ($permission->name != 'approveQueries'){

                            DB::table('model_has_permissions')->insert([
                                ['permission_id' => $permission->id ?? null, 'model_type' => 'App\Models\User','model_id' => $newUser->id],
                            ]);

                        }

                    }elseif ($user['role'] === 1){ // super admin

                        DB::table('model_has_permissions')->insert([
                            ['permission_id' => $permission->id ?? null, 'model_type' => 'App\Models\User','model_id' => $newUser->id],
                        ]);

                    }

                }

            }

            DB::commit();


        }catch (\Exception $exception){

            DB::rollBack();

            return $exception->getMessage();
        }

    }
}
