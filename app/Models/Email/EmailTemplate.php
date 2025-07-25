<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailTemplate extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

    }

    public static function getTemplate($data = null, $name = null){

        $data['{baseURL}'] = url('/');

        $content = self::where('tag', '=', $name)->pluck('format')->first();

        return strtr($content,$data);
    }

    public static function getEmailTemplateByTag($tag=null)
    {

        return DB::table('email_templates')->where('tag',$tag)->first();
    }

    public static function createTemplate($data = null)
    {
        $template = self::create($data);

        return $template;
    }

    public static function singleTemplate($id = null)
    {
        $template = self::whereId($id)->first();

        return $template;
    }

    public static function editTemplate($data = null, $id = null)
    {
        $template = self::whereId($id)->first();
        $template->update($data);

        return $template;
    }

    public static function deleteTemplate($id = null)
    {
        $template = self::whereId($id)->first();

        $template->delete();
    }

    public static function getTemplatesForB2C()
    {

        return DB::table('email_templates')->where('type', '1')->get();
    }
}
