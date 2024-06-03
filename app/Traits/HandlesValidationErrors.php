<?php
namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait HandlesValidationErrors
{
    public function customValidation($customRequestObj,$value)
    {

        $validator = Validator::make($value, $customRequestObj->rules(), $customRequestObj->messages());


        if ($validator->fails()) {

            foreach ($validator->errors()->messages() as $key => $errors) {
                foreach ($errors as $error) {
                    $this->addError($key, $error);
                }
            }
            return true;
        }
        return false;
    }
}
