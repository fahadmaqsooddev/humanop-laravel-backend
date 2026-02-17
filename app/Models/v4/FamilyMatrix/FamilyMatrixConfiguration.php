<?php

namespace App\Models\v4\FamilyMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMatrixConfiguration extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getConfigurations()
    {
        return self::orderBy('created_at', 'Desc')->get();
    }

    public static function updateConfigurationText(int $id, string $text): bool
    {
        $config = self::find($id);
        if (!$config) {
            return false;
        }

        $config->text = $text;

        if ($config->isDirty('text')) {
            return $config->save();
        }

        return true;
    }


}
