<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\Admin\Admin;
use App\Models\User;

class DeleteAdminAndTheirPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldUsers = [
            ['email' => 'lisa@humanop.com', 'password' => 'lisa@humanop','first_name' => 'Lisa', 'last_name' => 'Nelson', 'gender' => Admin::IS_FEMALE, 'role' => Admin::IS_ADMIN],
            ['email' => 'wei@humanop.com', 'password' => 'wei@humanop','first_name' => 'Wei', 'last_name' => 'Houng', 'gender' => Admin::IS_MALE, 'role' => Admin::SUB_ADMIN],
            ['email' => 'zannah@humanop.com', 'password' => 'zannah@humanop','first_name' => 'Zannah', 'last_name' => 'Hackett', 'gender' => Admin::IS_MALE, 'role' => Admin::SUB_ADMIN],
            ['email' => 'brant@humanop.com', 'password' => 'Brant@humanop','first_name' => 'Brant', 'last_name' => 'Hindman', 'gender' => Admin::IS_MALE, 'role' => Admin::SUB_ADMIN],
            ['email' => 'admin@humanop.com', 'password' => '12345678','first_name' => 'Admin', 'last_name' => 'Developers', 'gender' => Admin::IS_MALE, 'role' => Admin::IS_ADMIN],
        ];

        DB::beginTransaction();

        try {

            foreach ($oldUsers as $oldUser){

                $findUsers = User::where('email', $oldUser['email'])->get();

                foreach ($findUsers as $findUser){

                    DB::table('model_has_roles')->where('model_id', $findUser->id)->delete();

                    DB::table('model_has_permissions')->where('model_id', $findUser->id)->delete();

                    $findUser->delete();
                }

            }

            DB::commit();

        }catch (\Exception $exception){

            DB::rollBack();

//            Log::info(['add admin seeder exception' => $exception->getMessage()]);
        }
    }
}
