<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin\Code\CodeDetail;

class Assessment extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function createAssessment($data = null)
    {
        return self::create($data);
    }

    public static function updateAssessment($data = null, $id = null)
    {
        return self::find($id)->update($data);
    }

    public static function getLastPage()
    {
        $page = self::where('user_id', Auth::user()->id)->select(['page'])->latest()->first();
        if ($page) {
            return $page->page;
        } else {
            return 0;
        }
    }

    public static function getGrid($id = null)
    {
        return self::whereId($id)->where('user_id', Auth::user()['id'])->first();
    }

    public static function getReport($id = null)
    {
        $assessment =  self::whereId($id)->where('user_id', Auth::user()['id'])->first();

        $style = [
            'sa' => $assessment['sa'],
            'ma' => $assessment['ma'],
            'jo' => $assessment['jo'],
            'lu' => $assessment['lu'],
            'ven' => $assessment['ven'],
            'mer' => $assessment['mer'],
            'so' => $assessment['so'],
        ];

        $second_row_sa = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];

        $third_row_sa = $assessment['sa'] * $second_row_sa;
        $third_row_ma = $assessment['ma'] * $second_row_ma;
        $third_row_jo = $assessment['jo'] * $second_row_jo;
        $third_row_lu = $assessment['lu'] * $second_row_lu;
        $third_row_ven = $assessment['ven'] * $second_row_ven;
        $third_row_mer = $assessment['mer'] * $second_row_mer;
        $third_row_so = 10 * $assessment['so'];

        $third_row_style = [
            'sa' => $third_row_sa,
            'ma' => $third_row_ma,
            'jo' => $third_row_jo,
            'lu' => $third_row_lu,
            'ven' => $third_row_ven,
            'mer' => $third_row_mer,
            'so' => $third_row_so,
        ];

        $highlightStyle = [];

        foreach ($style as $key => $value) {
            if ($value > 4) {
                $highlightStyle[$key] = $value;
            }
        }

        arsort($highlightStyle);

        $topTwoKeys = array_slice(array_keys($highlightStyle), 0, 2);

        $code_detail = CodeDetail::getCodeDeatil($topTwoKeys);
        
        return $code_detail;
    }

    public static function getAssessment()
    {
        return static::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    }

    public static function allAssessment()
    {
        return self::with('user')->where('page', 0)->get();
    }

    public static function abandonedAssessment()
    {
        return self::with('user')->where('page','>', 0)->get();
    }

}
