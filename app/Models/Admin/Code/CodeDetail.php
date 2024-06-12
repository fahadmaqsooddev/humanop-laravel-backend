<?php

namespace App\Models\Admin\Code;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeDetail extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function allCodes()
    {
        return self::all();
    }

    public static function getSingleCode($id = null)
    {
        return self::find($id);
    }

    public static function updateCode($data = null, $id = null)
    {

        $code = self::find($id)->update($data);

        return $code;

    }
}
