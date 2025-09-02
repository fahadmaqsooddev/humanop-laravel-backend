<?php

namespace App\Models;

use App\Enums\Admin\Admin;
use App\Events\Assessment\SubmitAssessment;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Client\Gamification\GamificationBadgesAchievement;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\GenerateFile\PdfGenerate;
use App\Models\Videos\VideoProgress;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\Alchemy\AlchemyCode;

class Assessment extends Model
{
    use HasFactory;

    protected $appends = ['assessment_status'];

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assessmentColorCodes()
    {
        return $this->hasMany(AssessmentColorCode::class, 'assessment_id', 'id');
    }

    public function getAssessmentStatusAttribute()
    {

        return ($this->page > 0 || $this->page === null ? "Incomplete" : "Complete");
    }

    public function getUpdatedAtAttribute($value)
    {
        $formattedTimestamp = str_replace('T', ' ', $value);

        $formattedTimestamp = explode('.', $formattedTimestamp)[0];

        $timezone = Helpers::getWebUser()['timezone'] ?? Helpers::getUser()['timezone'] ?? '';

        $minutes = Helpers::explodeTimezoneWithHours($timezone);

        return Carbon::parse($formattedTimestamp)->addMinutes($minutes * 60)->format('m/d/Y h:i A');
    }

    public function getAfterResetAssessmentUpdatedAtAttribute($value)
    {
        $formattedTimestamp = str_replace('T', ' ', $value);

        $formattedTimestamp = explode('.', $formattedTimestamp)[0];

        $timezone = Helpers::getWebUser()['timezone'] ?? Helpers::getUser()['timezone'] ?? '';

        $minutes = Helpers::explodeTimezoneWithHours($timezone);

        return Carbon::parse($formattedTimestamp)->addMinutes($minutes * 60)->format('m/d/Y h:i A');
    }

    public function scopeSelection($query)
    {

        return $query->select(['id', 'user_id', 'sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so', 'de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil', 'g', 's', 'c', 'em', 'ins', 'int', 'mov', 'page', 'type', 'created_at', 'updated_at']);
    }

    public static function createAssessmentData($userId = null, $type = 0)
    {

        return self::create(['user_id' => $userId, 'type' => $type]);
    }

    public static function getGrid($id = null)
    {
        return self::whereId($id)->with('users')->first();
    }

    public static function getAllRowGrid($id = null)
    {
        $grid = self::whereId($id)->first();

        $gridColor = AssessmentColorCode::getAssessmentCodeAndColor($grid['id']);

        $second_row_sa = $grid['sa'] + $grid['ma'] + $grid['mer'];
        $second_row_ma = $grid['sa'] + $grid['ma'] + $grid['jo'];
        $second_row_jo = $grid['ma'] + $grid['jo'] + $grid['lu'];
        $second_row_lu = $grid['jo'] + $grid['lu'] + $grid['ven'];
        $second_row_ven = $grid['lu'] + $grid['ven'] + $grid['mer'];
        $second_row_mer = $grid['ven'] + $grid['mer'] + $grid['sa'];

        $second_row_de = $grid['ma'];
        $second_row_dom = $grid['sa'] + $grid['ma'];
        $second_row_fe = $grid['ma'] + $grid['lu'] + $grid['ven'];
        $second_row_gre = $grid['jo'] > 6 ? $grid['jo'] + $grid['mer'] : $grid['mer'];
        $second_row_lun = $grid['lu'];
        $second_row_nai = $grid['so'];
        $second_row_ne = $grid['sa'] + $grid['lu'] + $grid['ven'];
        $second_row_pow = $grid['jo'] + $grid['mer'];
        $second_row_sp = $grid['jo'];
        $second_row_tra = $grid['jo'] + $grid['ven'];
        $second_row_van = $grid['jo'] + $grid['ven'] + $grid['mer'] + $grid['so'];
        $second_row_wil = $grid['ma'] + $grid['lu'];

        $second_row_em = $grid['jo'] + $grid['ven'] + $grid['lu'];
        $second_row_ins = $grid['ma'] + $grid['ven'] + $grid['mer'];
        $second_row_int = $grid['jo'] + $grid['sa'] + $grid['mer'];
        $second_row_mov = $grid['ma'] + $grid['so'] + $grid['mer'];

        $firstRowGrid = [
            'sa' => $grid['sa'],
            'ma' => $grid['ma'],
            'jo' => $grid['jo'],
            'lu' => $grid['lu'],
            'ven' => $grid['ven'],
            'mer' => $grid['mer'],
            'so' => $grid['so'],
            'de' => $grid['de'],
            'dom' => $grid['dom'],
            'sp' => $grid['sp'],
            'fe' => $grid['fe'],
            'gre' => $grid['gre'],
            'lun' => $grid['lun'],
            'nai' => $grid['nai'],
            'ne' => $grid['ne'],
            'pow' => $grid['pow'],
            'tra' => $grid['tra'],
            'van' => $grid['van'],
            'wil' => $grid['wil'],
            'em' => $grid['em'],
            'ins' => $grid['ins'],
            'int' => $grid['int'],
            'mov' => $grid['mov'],
            '+' => $grid['sa'] + $grid['jo'] + $grid['ven'] + $grid['so'],
            '-' => $grid['ma'] + $grid['lu'] + $grid['mer'],
            'pv' => ($grid['sa'] + $grid['jo'] + $grid['ven'] + $grid['so']) - ($grid['ma'] + $grid['lu'] + $grid['mer']),
            'ep' => ($grid['sa'] + $grid['jo'] + $grid['ven'] + $grid['so']) + ($grid['ma'] + $grid['lu'] + $grid['mer']),
        ];

        $secondRowGrid = [
            'sa' => $grid['sa'] + $grid['ma'] + $grid['mer'],
            'ma' => $grid['sa'] + $grid['ma'] + $grid['jo'],
            'jo' => $grid['ma'] + $grid['jo'] + $grid['lu'],
            'lu' => $grid['jo'] + $grid['lu'] + $grid['ven'],
            'ven' => $grid['lu'] + $grid['ven'] + $grid['mer'],
            'mer' => $grid['ven'] + $grid['mer'] + $grid['sa'],
            'so' => 0,
            'de' => $grid['ma'],
            'dom' => $grid['sa'] + $grid['ma'],
            'fe' => $grid['ma'] + $grid['lu'] + $grid['ven'],
            'gre' => $grid['jo'] > 6 ? $grid['jo'] + $grid['mer'] : $grid['mer'],
            'lun' => $grid['lu'],
            'nai' => $grid['so'],
            'ne' => $grid['sa'] + $grid['lu'] + $grid['ven'],
            'pow' => $grid['jo'] + $grid['mer'],
            'sp' => $grid['jo'],
            'tra' => $grid['jo'] + $grid['ven'],
            'van' => $grid['jo'] + $grid['ven'] + $grid['mer'] + $grid['so'],
            'wil' => $grid['ma'] + $grid['lu'],
            'em' => $grid['jo'] + $grid['ven'] + $grid['lu'],
            'ins' => $grid['ma'] + $grid['ven'] + $grid['mer'],
            'int' => $grid['jo'] + $grid['sa'] + $grid['mer'],
            'mov' => $grid['ma'] + $grid['so'] + $grid['mer']
        ];

        $thirdRowGrid = [
            'sa' => $grid['sa'] * $second_row_sa,
            'ma' => $grid['ma'] * $second_row_ma,
            'jo' => $grid['jo'] * $second_row_jo,
            'lu' => $grid['lu'] * $second_row_lu,
            'ven' => $grid['ven'] * $second_row_ven,
            'mer' => $grid['mer'] * $second_row_mer,
            'so' => 0,
            'de' => $grid['de'] * $second_row_de,
            'dom' => $grid['dom'] * $second_row_dom,
            'fe' => $grid['fe'] * $second_row_fe,
            'gre' => $grid['gre'] * $second_row_gre,
            'lun' => $grid['lun'] * $second_row_lun,
            'nai' => $grid['nai'] * $second_row_nai,
            'ne' => $grid['ne'] * $second_row_ne,
            'pow' => $grid['pow'] * $second_row_pow,
            'sp' => $grid['sp'] * $second_row_sp,
            'tra' => $grid['tra'] * $second_row_tra,
            'van' => $grid['van'] * $second_row_van,
            'wil' => $grid['wil'] * $second_row_wil,
            'em' => $grid['em'] * $second_row_em,
            'ins' => $grid['ins'] * $second_row_ins,
            'int' => $grid['int'] * $second_row_int,
            'mov' => $grid['mov'] * $second_row_mov
        ];

        return $data = [
            'firstRow' => $firstRowGrid,
            'secondRow' => $secondRowGrid,
            'thirdRow' => $thirdRowGrid,
            'gridColor' => $gridColor,
            'alchemy' => $grid['g'] . '' . $grid['s'] . '' . $grid['c'],
        ];
    }

    public static function getAssessment()
    {
        return static::with(['assessmentColorCodes' => function ($query) {
            $query->selection();
        }])
            ->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))
            ->where('page', 0)
            ->orderBy('created_at', 'desc')
            ->selection()
            ->get();
    }

    public static function singleAssessment($user_id = null)
    {
        return self::where('user_id', $user_id)->latest()->first();
    }

    public static function getLatestAssessment($user_id = null)
    {

        return self::where('user_id', $user_id)->where('page', 0)->latest()->first();
    }

    public static function getSingleAssessment($assessmentId = null)
    {

        return self::whereId($assessmentId)->where('page', 0)->latest()->first();
    }

    public static function getAllAssessmentCount($user_id = null)
    {

        return self::where('user_id', $user_id)->where('page', 0)->count();
    }

    public static function getAllUser()
    {
        return self::where('page', 0)->orderBy('updated_at', 'desc')->get()->unique('user_id')->pluck('user_id');
    }

    public static function getAssessmentIds()
    {
        return static::where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))->where('page', 0)->orderBy('created_at', 'desc')->pluck('id')->toArray();
    }

    public static function checkAssessment($userId = null)
    {
        $currentDate = \Illuminate\Support\Carbon::now();

        $singleAssessment = self::singleAssessment($userId);

        if (!empty($singleAssessment)) {

            if ($singleAssessment['page'] === 0) {

                $freeAssessment = self::where('user_id', $userId)->where('page', 0)->where('type', 1)->latest()->first();

                if ($freeAssessment !== null) {

                    $createdAt = \Illuminate\Support\Carbon::parse($freeAssessment->created_at)->addDays(90);

                    if ($currentDate->greaterThan($createdAt)) {

                        return 'free';
                    } else {

                        return 'paid';
                    }
                }

                if ($singleAssessment['type'] === 0) {

                    return 'free';
                }
            } elseif ($singleAssessment['page'] !== 0) {

                return 'play';
            }
        }

        return 'free';
    }

    public static function allAssessment($name = null, $email = null, $age_range = null, $style_code = null, $style_code_color = null, $style_number = null, $feature_code = null, $feature_code_color = null, $feature_number = null, $perPage = 10)
    {

        $userId = Helpers::getWebUser()['id'];

        $isAdminLevel = Helpers::getWebUser()['is_admin'];

        $query = self::has('users')->where('page', 0);

        if ($isAdminLevel == 4) {

            $query->whereHas('users', function ($query) use ($userId) {

                $query->where('practitioner_id', $userId);
            });
        }

        if ($name) {

            $query->whereHas('users', function ($query) use ($name) {

                $query->whereRaw("concat(first_name, ' ', last_name) like ?", ["%{$name}%"])
                    ->orWhereRaw("concat(last_name, ' ', first_name) like ?", ["%{$name}%"]);
            });
        }

        if ($email) {

            $query->whereHas('users', function ($query) use ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            });
        }

        if ($age_range) {

            $data['age_range'] = $age_range;

            $data = Helpers::explodeAgeRangeIntoAge($data);

            $min_date = Carbon::now()->subYears((int)$data['age_max'] ?? 0)->toDateString();

            $max_date = Carbon::now()->subYears((int)$data['age_min'] ?? 0)->toDateString();

            $query->whereHas('users', function ($query) use ($min_date, $max_date) {

                $query->whereBetween('date_of_birth', [$min_date, $max_date]);
            });
        }

        if ($style_code && $style_code_color) {

            $query->whereHas('assessmentColorCodes', function ($query) use ($style_code, $style_code_color) {

                $query->where('code', $style_code)
                    ->where('code_color', $style_code_color);
            });
        }

        if ($style_number) {

            $parts = explode('-', $style_number);

            if (isset($parts[1])) {

                $style_num = $parts[1];

                $query->whereHas('assessmentColorCodes', function ($query) use ($style_num, $style_code, $style_code_color) {

                    $query->where('code', $style_code)
                        ->where('code_color', $style_code_color)
                        ->where('code_number', $style_num);
                });
            }
        }

        if ($feature_code && $feature_code_color) {

            $query->whereHas('assessmentColorCodes', function ($query) use ($feature_code, $feature_code_color) {

                $query->where('code', $feature_code)
                    ->where('code_color', $feature_code_color);
            });
        }

        if ($feature_number) {

            $parts = explode('-', $feature_number);

            if (isset($parts[1])) {

                $feature_num = $parts[1];

                $query->whereHas('assessmentColorCodes', function ($query) use ($feature_num, $feature_code, $feature_code_color) {

                    $query->where('code', $feature_code)
                        ->where('code_color', $feature_code_color)
                        ->where('code_number', $feature_num);
                });
            }
        }

        return $query->orderBy('updated_at', 'desc')->paginate($perPage);
    }

    public static function abandonedAssessment()
    {

        $userId = Helpers::getWebUser()['id'];

        $isAdminLevel = Helpers::getWebUser()['is_admin'];

        $query = self::with('users')->has('users');

        if ($isAdminLevel == 4) {

            $query->whereHas('users', function ($query) use ($userId) {

                $query->where('practitioner_id', $userId);
            });
        }

        $query->where(function ($q) {

            $q->whereNull('page')->orWhere('page', '!=', 0);
        })
            ->orderBy('created_at', 'DESC');

        return $query->get();
    }

    public static function getReport($id = null)
    {
        $assessment = self::whereId($id)->with(['users' => function ($q) {

            $q->select(['id', 'first_name', 'last_name', 'gender']);
        }])->first();

        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];


        $ep = $positive + $negative;

        $pv = $positive - $negative;

        $topTwoKeysStyle = self::getAllStyles($assessment);

        $topTwoKeysFeature = self::getFeatures($assessment);

        $alchemyCodeDetail = self::getAlchemy($assessment);

        $communication_keys = self::getEnergy($assessment);

        $energy_code = self::getEnergyPool($assessment);

        $polarity_code = self::getPolarity($assessment);

        $code_detail = CodeDetail::getCodeDeatil($topTwoKeysStyle, $topTwoKeysFeature, $alchemyCodeDetail, $communication_keys, $polarity_code, $energy_code, $pv, $ep, $assessment['users']);

        return $code_detail;
    }

    public static function getEnergyPool($assessment = null)
    {

        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

        $ep = $positive + $negative;


        if ($ep < 25) {

            $energy_code = 21;
        } elseif ($ep >= 25 and $ep <= 30) {

            $energy_code = 18;
        } elseif ($ep >= 31 and $ep <= 35) {

            $energy_code = 20;
        } elseif ($ep >= 36) {

            $energy_code = 16;
        }

        return $energy_code;
    }

    public static function GetEP($assessment = null)
    {

        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

        $ep = $positive + $negative;

        if ($ep < 25) {

            $energy_code = 21;
        } elseif ($ep >= 25 and $ep <= 30) {

            $energy_code = 18;
        } elseif ($ep >= 31 and $ep <= 35) {

            $energy_code = 20;
        } elseif ($ep >= 36) {

            $energy_code = 16;
        }

        return $data = ['energy_pool' => $ep, 'energy_code' => $energy_code];
    }

    public static function getPolarity($assessment = null)
    {

        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

        $pv = $positive - $negative;

        if ($pv <= -8) {

            $polarity_code = 40;
        } elseif ($pv >= -7 and $pv <= 7) {

            $polarity_code = 41;
        } elseif ($pv >= 8) {

            $polarity_code = 42;
        }

        return $polarity_code;
    }

    public static function getEnergyPoolPublicName($assessment = null)
    {

        $energy_code = self::getEP($assessment);

        $publicName = '';

        if ($energy_code['energy_code'] == 16) {

            $publicName = "Above Excellent [{$energy_code['energy_pool']}]";
        } elseif ($energy_code['energy_code'] == 18) {

            $publicName = "Average [{$energy_code['energy_pool']}]";
        } elseif ($energy_code['energy_code'] == 20) {

            $publicName = "Excellent [{$energy_code['energy_pool']}]";
        } elseif ($energy_code['energy_code'] == 21) {

            $publicName = "Fair [{$energy_code['energy_pool']}]";
        }

        $record = CodeDetail::whereId($energy_code['energy_code'])->with('video')->first();


        $video = $record['video'];

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessment['id'], $record['name']);

        $data = [
            'name' => $record['name'],
            'public_name' => $publicName,
            'code_name' => $record['code'],
            'description' => $record['text'],
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];

        return $data;
    }

    public static function getPreceptionReport($assessment = null)
    {

        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

        $pv = $positive - $negative;

        if ($pv <= -8) {

            $polarity_code = 40;
        } elseif ($pv >= -7 and $pv <= 7) {

            $polarity_code = 41;
        } elseif ($pv >= 8) {

            $polarity_code = 42;
        }

        $record = CodeDetail::whereId($polarity_code)->first();

        $data = [
            'polarity_code' => $polarity_code,
            'pv' => $pv > 0 ? '+' . $pv : $pv,
            'video_url' => $record['video_url'] ?? null
        ];

        return $data;
    }

    public static function getStyles($assessment = null)
    {

        $second_row_sa = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];
        $second_row_so = 10;

        $third_row_sa = $assessment['sa'] * $second_row_sa;
        $third_row_ma = $assessment['ma'] * $second_row_ma;
        $third_row_jo = $assessment['jo'] * $second_row_jo;
        $third_row_lu = $assessment['lu'] * $second_row_lu;
        $third_row_ven = $assessment['ven'] * $second_row_ven;
        $third_row_mer = $assessment['mer'] * $second_row_mer;
        $third_row_so = $assessment['so'] * $second_row_so;

        $third_row_style = [
            'sa' => $third_row_sa,
            'ma' => $third_row_ma,
            'jo' => $third_row_jo,
            'lu' => $third_row_lu,
            'ven' => $third_row_ven,
            'mer' => $third_row_mer,
            'so' => $third_row_so,
        ];

        arsort($third_row_style);

        $top_two = array_slice($third_row_style, 0, 2, true);

        $topKeysStyle = [

            'top_two_keys' => array_keys($top_two),

        ];

        return $topKeysStyle;
    }

    public static function highLightStyle($assessment = null)
    {
        $allStyles = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so', 'dom', 'fe', 'de', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil', 'g', 's', 'c', 'int', 'mov', 'ins', 'em'];

        $highLightStyles = [];

        if ($assessment) {

            foreach ($allStyles as $style) {

                if (isset($assessment[$style]) && $assessment[$style] > 4) {

                    $highLightStyles[] = $style;

                }

            }

        }

        return $highLightStyles;

    }

    public static function topThreeTraits($assessment = null)
    {

        $second_row_sa  = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma  = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo  = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu  = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];

        $third_row = [
            'sa'  => $assessment['sa'] * $second_row_sa,
            'ma'  => $assessment['ma'] * $second_row_ma,
            'jo'  => $assessment['jo'] * $second_row_jo,
            'lu'  => $assessment['lu'] * $second_row_lu,
            'ven' => $assessment['ven'] * $second_row_ven,
            'mer' => $assessment['mer'] * $second_row_mer,
            'so'  => 10 * $assessment['so']
        ];

        $getResult = AssessmentColorCode::getHighlightCodeColor($assessment['id']);

        $style = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];

        $getStyle = array_intersect_key($getResult, array_flip($style));

        arsort($getStyle);

        $remainingStyles = self::getTemporaryStyles($assessment);

        $data = array_merge($getStyle, $remainingStyles);

        uksort($data, function ($a, $b) use ($getStyle, $third_row, $style) {
            $scoreA = $getStyle[$a] ?? 0;
            $scoreB = $getStyle[$b] ?? 0;

            if ($scoreA != $scoreB) {
                return $scoreB <=> $scoreA;
            }

            $a_third = $third_row[$a] ?? 0;
            $b_third = $third_row[$b] ?? 0;

            if ($a_third != $b_third) {
                if ($a_third > 30 && $b_third <= 30) return -1;
                if ($b_third > 30 && $a_third <= 30) return 1;
                return $b_third <=> $a_third;
            }

            return array_search($a, $style) <=> array_search($b, $style);
        });

        $topThreeStyles = array_slice($data, 0, 3, true);

        $topThreeTraits = $topThreeStyles;

        $topThreeWithWeight = [];

        foreach ($topThreeTraits as $key => $val) {
            if (isset($third_row[$key])) {
                $topThreeWithWeight[$key] = [
                    'score' => $val,
                    'weight' => $third_row[$key],
                ];
            }
        }

        uasort($topThreeWithWeight, function ($a, $b) {
            if ($a['score'] === $b['score']) {
                return $b['weight'] <=> $a['weight'];
            }
            return $b['score'] <=> $a['score'];
        });

        // Build final result
        $getTraitWeight = [];
        foreach ($topThreeWithWeight as $key => $data) {
            $getTraitWeight[$key] = $data['weight'];
        }

        $getTraitWeight = array_slice($getTraitWeight, 0, 3);

        return $getTraitWeight;

    }

    public static function getTopThreeTraitWeight($assessment = null)
    {

        $topThreeTrait = self::topThreeTraits($assessment);

        if (!empty($topThreeTrait) && count($topThreeTrait) > 2) {

            $result = [];

            foreach ($topThreeTrait as $trait => $value) {

                $result[$trait] = $value;

            }

            $countTraitWeight = array_sum(array_values($result));

            $firstWeight = round((array_values(array_slice($result, 0, 1))[0] / $countTraitWeight) * 100, 2);

            $secondWeight = round((array_values(array_slice($result, 1, 1))[0] / $countTraitWeight) * 100, 2);

            $thirdWeight = round((array_values(array_slice($result, 2, 1))[0] / $countTraitWeight) * 100, 2);

            return [
                'first_weight' => $firstWeight,
                'second_weight' => $secondWeight,
                'third_weight' => $thirdWeight
            ];

        } else {

            return null;

        }

    }

    public static function getAllStyles($assessment = null)
    {

        $second_row_sa = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];
        $second_row_so = 10;

        $third_row = [
            'sa' => $assessment['sa'] * $second_row_sa,
            'ma' => $assessment['ma'] * $second_row_ma,
            'jo' => $assessment['jo'] * $second_row_jo,
            'lu' => $assessment['lu'] * $second_row_lu,
            'ven' => $assessment['ven'] * $second_row_ven,
            'mer' => $assessment['mer'] * $second_row_mer,
            'so' => $assessment['so'] * $second_row_so
        ];

        $getResult = AssessmentColorCode::getHighlightCodeColor($assessment['id']);

        $style = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];

        $getStyle = array_intersect_key($getResult, array_flip($style));

        arsort($getStyle);

        $remainingStyles = self::getTemporaryStyles($assessment);

        $data = array_merge($getStyle, $remainingStyles);

        uksort($data, function ($a, $b) use ($getStyle, $third_row, $style) {
            $scoreA = $getStyle[$a] ?? 0;
            $scoreB = $getStyle[$b] ?? 0;

            if ($scoreA != $scoreB) {
                return $scoreB <=> $scoreA;
            }

            $a_third = $third_row[$a] ?? 0;
            $b_third = $third_row[$b] ?? 0;

            if ($a_third != $b_third) {
                if ($a_third > 30 && $b_third <= 30) return -1;
                if ($b_third > 30 && $a_third <= 30) return 1;
                return $b_third <=> $a_third;
            }

            return array_search($a, $style) <=> array_search($b, $style);
        });

        $topFour = array_slice($data, 0, 3, true);

        $styleCodes = CodeDetail::getStylePublicNames($topFour);

        $allStyles = PdfGenerate::createGenerateFile($assessment['id'], $assessment['users']['id'], $styleCodes, $topFour);

        $data = [];

        foreach ($allStyles as $style) {

            $codeDetails = $style['codeDetails'][0] ?? null;

            if ($codeDetails) {
                $video = $codeDetails['video'] ?? [];

                $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
                    ? $video['video_upload_url']['path']
                    : ($video['video_url'] ?? null);

                $progress = VideoProgress::checkVideoProgress($assessment['id'], $codeDetails['name']);

                $data[] = [
                    'code_number' => $style['code_number'] ?? null,
                    'code_name' => $codeDetails['code'] ?? null,
                    'public_name' => $codeDetails['public_name'] ?? null,
                    'name' => $codeDetails['name'] ?? null,
                    'description' => $codeDetails['text'] ?? null,
                    'video_url' => $videoUrl,
                    'video_progress' => $progress['video_progress'],
                    'video_time' => $progress['video_time']
                ];
            }
        }


        return $data;
    }

    public static function authenticTraits($assessment = null)
    {

        $second_row_sa = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];
        $second_row_so = 10;

        $third_row = [
            'sa' => $assessment['sa'] * $second_row_sa,
            'ma' => $assessment['ma'] * $second_row_ma,
            'jo' => $assessment['jo'] * $second_row_jo,
            'lu' => $assessment['lu'] * $second_row_lu,
            'ven' => $assessment['ven'] * $second_row_ven,
            'mer' => $assessment['mer'] * $second_row_mer,
            'so' => $assessment['so'] * $second_row_so
        ];

        $getResult = AssessmentColorCode::getHighlightCodeColor($assessment['id']);

        $style = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];

        $getStyle = array_intersect_key($getResult, array_flip($style));

        arsort($getStyle);

        $data = $getStyle;

        uksort($data, function ($a, $b) use ($getStyle, $third_row, $style) {
            $scoreA = $getStyle[$a] ?? 0;
            $scoreB = $getStyle[$b] ?? 0;

            if ($scoreA != $scoreB) {
                return $scoreB <=> $scoreA;
            }

            $a_third = $third_row[$a] ?? 0;
            $b_third = $third_row[$b] ?? 0;

            if ($a_third != $b_third) {
                if ($a_third > 30 && $b_third <= 30) return -1;
                if ($b_third > 30 && $a_third <= 30) return 1;
                return $b_third <=> $a_third;
            }

            return array_search($a, $style) <=> array_search($b, $style);
        });

        dd($data);
        $styleCodes = CodeDetail::getStylePublicNames($data);

        $allStyles = PdfGenerate::createGenerateFile($assessment['id'], $assessment['users']['id'], $styleCodes, $data);

        return $styleCodes;
    }

    public static function getTemporaryStyles($assessment = null)
    {

        $getResult = AssessmentColorCode::getHighlightCodeColor($assessment['id']);

        $style = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];

        $matchedStyles = array_map('strtolower', array_keys($getResult));

        $remainingStyles = array_diff($style, $matchedStyles);


        $remainingValues = [];

        foreach ($remainingStyles as $styleCode) {
            if (isset($assessment[$styleCode])) {
                $remainingValues[$styleCode] = $assessment[$styleCode];
            }
        }

        arsort($remainingValues);

        return $remainingValues;
    }

    public static function UserTraits($userId = null)
    {
        $getAssessment = self::getLatestAssessment($userId);

        $style = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];

        $getStyle = [];

        $assessmentArray = $getAssessment?->toArray();

        foreach ($assessmentArray ?? [] as $key => $result) {

            if (in_array($key, $style)) {

                $getStyle[$key] = $result;
            }
        }

        arsort($getStyle);

        $styleCodes = CodeDetail::getStylePublicNames($getStyle);

        $data = [];

        foreach ($styleCodes as $style) {

            $codeKey = strtolower($style['code']);

            $data[] = [

                'code_name' => $style['code'],
                'public_name' => $style['public_name'],
                'code_number' => $getStyle[$codeKey],
                'code_color' => AssessmentColorCode::getSingleCodeColor($getAssessment['id'], $codeKey)['code_color'] ?? null
            ];
        }

        return $data;
    }

    public static function getFeatures($assessment = null, $isCode = true)
    {
        $second_row_sa = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];
        $second_row_so = 10;

        $third_row_ma = $assessment['ma'] * $second_row_ma;
        $third_row_jo = $assessment['jo'] * $second_row_jo;
        $third_row_lu = $assessment['lu'] * $second_row_lu;
        $third_row_mer = $assessment['mer'] * $second_row_mer;
        $third_row_so = $assessment['so'] * $second_row_so;

        $features = [
            'de' => $assessment['de'],
            'dom' => $assessment['dom'],
            'fe' => $assessment['fe'],
            'gre' => $assessment['gre'],
            'lun' => $assessment['lun'],
            'nai' => $assessment['nai'],
            'ne' => $assessment['ne'],
            'pow' => $assessment['pow'],
            'sp' => $assessment['sp'],
            'tra' => $assessment['tra'],
            'van' => $assessment['van'],
            'wil' => $assessment['wil'],
        ];

        $second_row_de = $assessment['ma'];
        $second_row_dom = $assessment['sa'] + $assessment['ma'];
        $second_row_fe = $assessment['ma'] + $assessment['lu'] + $assessment['ven'];
        $second_row_gre = $assessment['jo'] > 6 ? $assessment['jo'] + $assessment['mer'] : $assessment['mer'];
        $second_row_lun = $assessment['lu'];
        $second_row_nai = $assessment['so'];
        $second_row_ne = $assessment['sa'] + $assessment['lu'] + $assessment['ven'];
        $second_row_pow = $assessment['jo'] + $assessment['mer'];
        $second_row_sp = $assessment['jo'];
        $second_row_tra = $assessment['jo'] + $assessment['ven'];
        $second_row_van = $assessment['jo'] + $assessment['ven'] + $assessment['mer'] + $assessment['so'];
        $second_row_wil = $assessment['ma'] + $assessment['lu'];

        $third_row_feature = [
            'de' => $assessment['de'] * $second_row_de,
            'dom' => $assessment['dom'] * $second_row_dom,
            'fe' => $assessment['fe'] * $second_row_fe,
            'gre' => $assessment['gre'] * $second_row_gre,
            'lun' => $assessment['lun'] * $second_row_lun,
            'nai' => $assessment['nai'] * $second_row_nai,
            'ne' => $assessment['ne'] * $second_row_ne,
            'pow' => $assessment['pow'] * $second_row_pow,
            'sp' => $assessment['sp'] * $second_row_sp,
            'tra' => $assessment['tra'] * $second_row_tra,
            'van' => $assessment['van'] * $second_row_van,
            'wil' => $assessment['wil'] * $second_row_wil,
        ];

        arsort($features);

        $filtered_keys = [];

        $filtered_keys_red = [];

        foreach ($features as $key => $value) {

            switch ($key) {

                case 'de':

                    if (($assessment['de'] > 2 && $assessment['ma'] > 4) || ($assessment['de'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4 && $third_row_ma > 30)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['de'] > 2 && $assessment['ma'] < 5)) {

                        if ($third_row_ma > 30) {

                            if ($assessment['sa'] < 5 || $assessment['jo'] < 5) {

                                $filtered_keys_red[$key] = $value;
                            }
                        } elseif ($third_row_ma <= 30) {

                            $filtered_keys_red[$key] = $value;
                        }
                    }

                    break;

                case 'dom':

                    if (($assessment['dom'] > 2 && ($assessment['sa'] > 4 || $assessment['ma'] > 4)) || ($assessment['dom'] > 2 && $assessment['ma'] > 4 && $assessment['mer'] > 4) || ($assessment['dom'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['dom'] > 2 && ($assessment['sa'] < 5 && $assessment['ma'] < 5)) && ($assessment['ma'] < 5 || $assessment['mer'] < 5 || $assessment['sa'] < 5 || $assessment['jo'] < 5)) {

                        $filtered_keys_red[$key] = $value;
                    }

                    break;

                case 'fe':

                    if (($assessment['fe'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['fe'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4) || ($assessment['fe'] > 2 && $assessment['jo'] > 4 && $assessment['ven'] > 4) || ($assessment['fe'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['fe'] > 2 && ($assessment['ma'] < 5 && $assessment['lu'] < 5 && $assessment['ven'] < 5)) && ($assessment['sa'] < 5 || $assessment['jo'] < 5 || $assessment['ven'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5)) {

                        $filtered_keys_red[$key] = $value;
                    }

                    break;

                case 'gre':

                    if (($assessment['gre'] > 2 && ($assessment['jo'] > 6 || $assessment['mer'] > 4)) || ($assessment['gre'] > 2 && $assessment['ven'] > 4 && $assessment['sa'] > 4 && $third_row_mer > 30)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['gre'] > 2 && $assessment['jo'] < 7 && $assessment['mer'] < 5) || ($assessment['gre'] > 2 && ($assessment['ven'] < 5 || $assessment['sa'] < 5))) {

                        $filtered_keys_red[$key] = $value;
                    }

                    break;

                case 'lun':

                    if (($assessment['lun'] > 2 && $assessment['lu'] > 4) || ($assessment['lun'] > 2 && $assessment['ven'] > 4 && $assessment['jo'] > 4 && $third_row_lu > 30)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['lun'] > 2 && $assessment['lu'] < 5)) {

                        if ($third_row_lu > 30) {

                            if ($assessment['ven'] < 5 || $assessment['jo'] < 5) {

                                $filtered_keys_red[$key] = $value;
                            }
                        } elseif ($third_row_lu <= 30) {

                            $filtered_keys_red[$key] = $value;
                        }
                    }

                    break;

                case 'nai':

                    if (($assessment['nai'] > 2 && $assessment['so'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['nai'] > 2 && $assessment['so'] < 5)) {

                        $filtered_keys_red[$key] = $value;
                    }

                    break;

                case 'ne':

                    if (($assessment['ne'] > 2 && ($assessment['sa'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['ne'] > 2 && $assessment['ma'] > 4 && $assessment['mer'] > 4) || ($assessment['ne'] > 2 && $assessment['ven'] > 4 && $assessment['jo'] > 4) || ($assessment['ne'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['ne'] > 2 && ($assessment['sa'] < 5 && $assessment['lu'] < 5 && $assessment['ven'] < 5)) && ($assessment['ne'] < 5 || $assessment['ma'] < 5 || $assessment['mer'] < 5 || $assessment['ven'] < 5 || $assessment['jo'] < 5 || $assessment['lu'] < 5)) {

                        $filtered_keys_red[$key] = $value;
                    }
                    break;

                case 'pow':

                    if (($assessment['pow'] > 2 && ($assessment['jo'] > 4 || $assessment['mer'] > 4)) || ($assessment['pow'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4) || ($assessment['pow'] > 2 && $assessment['ven'] > 4 && $assessment['sa'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['pow'] > 2 && ($assessment['jo'] < 5 && $assessment['mer'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['ven'] < 5 || $assessment['sa'] < 5)) {

                        $filtered_keys_red[$key] = $value;
                    }

                    break;

                case 'sp':

                    if (($assessment['sp'] > 2 && $assessment['jo'] > 4) || ($assessment['sp'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4 && $third_row_jo > 30)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['sp'] > 2 && $assessment['jo'] < 5)) {

                        if ($third_row_jo > 30) {

                            if ($assessment['ma'] < 5 || $assessment['lu'] < 5) {

                                $filtered_keys_red[$key] = $value;
                            }
                        } elseif ($third_row_jo <= 30) {

                            $filtered_keys_red[$key] = $value;
                        }
                    }

                    break;

                case 'tra':

                    if (($assessment['tra'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4)) || ($assessment['tra'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4) || ($assessment['tra'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['tra'] > 2 && ($assessment['jo'] < 5 && $assessment['ven'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5)) {

                        $filtered_keys_red[$key] = $value;
                    }

                    break;

                case 'van':

                    if (($assessment['van'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4 || $assessment['mer'] > 4 || $assessment['so'] > 4)) || ($assessment['van'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4) || ($assessment['van'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4) || ($assessment['van'] > 2 && $assessment['ven'] > 4 && $assessment['sa'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['van'] > 2 && ($assessment['jo'] < 5 && $assessment['ven'] < 5 && $assessment['mer'] < 5 && $assessment['so'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5 || $assessment['ven'] < 5 || $assessment['sa'] < 5)) {

                        $filtered_keys_red[$key] = $value;
                    }

                    break;

                case 'wil':

                    if (($assessment['wil'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4)) || ($assessment['wil'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4) || ($assessment['wil'] > 2 && $assessment['jo'] > 4 && $assessment['ven'] > 4)) {

                        $filtered_keys[$key] = $value;
                    } elseif (($assessment['wil'] > 2 && ($assessment['ma'] < 5 && $assessment['lu'] < 5))) {

                        if ($third_row_ma > 30 || $third_row_lu > 30) {

                            if ($assessment['sa'] < 5 || $assessment['jo'] < 5 || $assessment['ven'] < 5) {

                                $filtered_keys_red[$key] = $value;
                            }
                        } elseif ($third_row_ma <= 30 || $third_row_lu <= 30) {

                            $filtered_keys_red[$key] = $value;
                        }
                    }

                    break;
            }
        }

        $redKeys = array_keys($filtered_keys_red);

        if (count($filtered_keys) < 2) {

            $matchingKeys = array_intersect_key($third_row_feature, array_flip(array_keys($filtered_keys)));

            arsort($matchingKeys);

            $all_values_are_2 = [];

            foreach ($features as $key => $value) {

                if ($value == 2) {

                    $all_values_are_2[$key] = $value;
                }
            }

            $matchingKeysLessThanTwo = array_intersect_key($third_row_feature, array_flip(array_keys($all_values_are_2)));

            arsort($matchingKeysLessThanTwo);

            $topAllKeys = array_merge($matchingKeys, $matchingKeysLessThanTwo);

            $topTwoKeys = array_slice(array_keys($topAllKeys), 0, 2);

            $nextTwoKeys = [];

            $topKeysFeature = [
                'top_two_keys' => $topTwoKeys,
                'next_two_keys' => $nextTwoKeys,
            ];
        } else {

            $topKeysFeature = self::getGridKeys($filtered_keys, $third_row_feature);
        }

        if ($isCode) {

            return $topKeysFeature;
        } else {

            return CodeDetail::getPublicNames($topKeysFeature['top_two_keys']);
        }
    }

    public static function getTopTwoFeatures($featureKeys = null, $assessment = null)
    {
        $features = [
            'de' => $assessment['de'],
            'dom' => $assessment['dom'],
            'fe' => $assessment['fe'],
            'gre' => $assessment['gre'],
            'lun' => $assessment['lun'],
            'nai' => $assessment['nai'],
            'ne' => $assessment['ne'],
            'pow' => $assessment['pow'],
            'sp' => $assessment['sp'],
            'tra' => $assessment['tra'],
            'van' => $assessment['van'],
            'wil' => $assessment['wil'],
        ];

        $topFeatures = [];

        foreach ($featureKeys as $key) {

            if (isset($features[$key])) {

                $topFeatures[$key] = $features[$key]; // Match key and get value from $style

            }
        }

        $topfeaturesdata = CodeDetail::getPublicNames($topFeatures);

        $newtopfeaturesdata = array_map(function ($item) use ($assessment) {

            $progress = VideoProgress::checkVideoProgress($assessment['id'], $item[5]);

            return [
                'code_number' => $item[0],
                'public_name' => $item[1],
                'description' => $item[2],
                'video_url' => $item[3],
                'code_name' => $item[4],
                'name' => $item[5],
                'video_progress' => $progress['video_progress'],
                'video_time' => $progress['video_time']
            ];
        }, $topfeaturesdata);

        return $newtopfeaturesdata;
    }

    public static function getAlchemy($assessment = null)
    {
        $gold = $assessment['g'];
        $silver = $assessment['s'];
        $copper = $assessment['c'];
        $alchemy = $gold . '' . $silver . '' . $copper;
        $alchemyCodeDetail = AlchemyCode::getCodeDeatil($alchemy);

        return $alchemyCodeDetail;
    }

    public static function getAlchlCode($assessment_id = null)
    {
        $assessment = self::whereId($assessment_id)->first(['g', 's', 'c']);

        $gold = $assessment['g'];
        $silver = $assessment['s'];
        $copper = $assessment['c'];
        $alchemy = $gold . '' . $silver . '' . $copper;

        return $alchemy;
    }

    public static function getEnergy($assessment = null)
    {
        $communications = [
            'em' => $assessment['em'],
            'ins' => $assessment['ins'],
            'int' => $assessment['int'],
            'mov' => $assessment['mov'],
        ];

        $second_row_em = $assessment['jo'] + $assessment['ven'] + $assessment['lu'];
        $second_row_ins = $assessment['ma'] + $assessment['ven'] + $assessment['mer'];
        $second_row_int = $assessment['jo'] + $assessment['sa'] + $assessment['mer'];
        $second_row_mov = $assessment['ma'] + $assessment['so'] + $assessment['mer'];

        $communication_third_row = [
            'em' => $assessment['em'] * $second_row_em,
            'ins' => $assessment['ins'] * $second_row_ins,
            'int' => $assessment['int'] * $second_row_int,
            'mov' => $assessment['mov'] * $second_row_mov,
        ];

        arsort($communications);

        $communication_array = array_filter($communications);

        uksort($communication_array, function ($a, $b) use ($communications, $communication_third_row) {

            if ($communications[$a] == $communications[$b]) {

                $a_third = isset($communication_third_row[$a]) ? $communication_third_row[$a] : -1;

                $b_third = isset($communication_third_row[$b]) ? $communication_third_row[$b] : -1;

                if ($a_third == $b_third) {

                    return array_search($b, array_keys(array_reverse($communications))) - array_search($a, array_keys(array_reverse($communications)));
                }

                return $a_third < $b_third ? 1 : -1; // Compare $styles_third values

            }

            return $communications[$a] < $communications[$b] ? 1 : -1;
        });

        $communication_keys = array_keys($communication_array);

        return $communication_keys;
    }

    public static function getEnergyCenter($assessment = null)
    {
        $communications = [
            'em' => $assessment['em'],
            'ins' => $assessment['ins'],
            'int' => $assessment['int'],
            'mov' => $assessment['mov'],
        ];

        $second_row_em = $assessment['jo'] + $assessment['ven'] + $assessment['lu'];
        $second_row_ins = $assessment['ma'] + $assessment['ven'] + $assessment['mer'];
        $second_row_int = $assessment['jo'] + $assessment['sa'] + $assessment['mer'];
        $second_row_mov = $assessment['ma'] + $assessment['so'] + $assessment['mer'];

        $communication_third_row = [
            'em' => $assessment['em'] * $second_row_em,
            'ins' => $assessment['ins'] * $second_row_ins,
            'int' => $assessment['int'] * $second_row_int,
            'mov' => $assessment['mov'] * $second_row_mov,
        ];

        arsort($communications);

        $communication_array = array_filter($communications);

        uksort($communication_array, function ($a, $b) use ($communications, $communication_third_row) {

            if ($communications[$a] == $communications[$b]) {

                $a_third = isset($communication_third_row[$a]) ? $communication_third_row[$a] : -1;

                $b_third = isset($communication_third_row[$b]) ? $communication_third_row[$b] : -1;

                if ($a_third == $b_third) {

                    return array_search($b, array_keys(array_reverse($communications))) - array_search($a, array_keys(array_reverse($communications)));
                }

                return $a_third < $b_third ? 1 : -1; // Compare $styles_third values

            }

            return $communications[$a] < $communications[$b] ? 1 : -1;
        });

        return $communication_array;
    }

    public static function getGridKeys($filtered_keys = null, $third_row_feature = null)
    {

        $greater_than_three_filtered_keys = [];

        foreach ($filtered_keys as $key => $value) {

            if ($value > 3) { // Check if the value is greater than 3

                $greater_than_three_filtered_keys[$key] = $value;
            }
        }

        $remainingFilterKeys = array_diff_key($filtered_keys, $greater_than_three_filtered_keys);

        $firstHighestArrayValue = [];

        $remainingHighestArrayValue = [];

        if (count($greater_than_three_filtered_keys) > 1 || count($greater_than_three_filtered_keys) == 1) {

            $firstHighestArrayValue = array_intersect_key($third_row_feature, array_flip(array_keys($greater_than_three_filtered_keys)));

            arsort($firstHighestArrayValue);
        }

        if (count($remainingFilterKeys) != 0) {

            $remainingHighestArrayValue = array_intersect_key($third_row_feature, array_flip(array_keys($remainingFilterKeys)));

            arsort($remainingHighestArrayValue);
        }

        $allValuesGets = array_merge($firstHighestArrayValue, $remainingHighestArrayValue);

        $topTwoKeys = array_slice(array_keys($allValuesGets), 0, 2);

        $nextTwoKeys = array_slice(array_keys($allValuesGets), 2);

        $topKeys = [
            'top_two_keys' => $topTwoKeys,
            'next_two_keys' => $nextTwoKeys ?? [],
        ];

        return $topKeys;
    }

    public static function assessmentsPaginated($request = null)
    {

        $order_by = isset($request['order_by']) ? $request['order_by'] : "created_at";

        $order = isset($request['order']) ? $request['order'] : "DESC";

        $assessments = self::where('user_id', Helpers::getUser()->id)->where('page', 0)->select(['id', 'page', 'updated_at', 'reset_assessment', 'after_reset_assessment_updated_at'])->orderBy($order_by, $order);

        return Helpers::pagination($assessments, $request->input('pagination'), $request->input('per_page'));
    }

    public static function getGridForApi($id = null)
    {
        return self::with('assessmentColorCodes')->whereId($id)->where('user_id', Helpers::getUser()->id)->first();
    }

    public static function createNewAssessment()
    {
        $assessment = Assessment::createAssessmentData(Helpers::getUser()->id, 1);

        $assessment_data = Assessment::where('id', $assessment['id'])->first();

        AssessmentColorCode::createStylesCodeAndColor($assessment_data);

        AssessmentColorCode::createFeaturesCodeAndColor($assessment_data);

        return 0;
    }

    public static function assessmentStatusForApi()
    {

        $user = Helpers::getUser();

        $assessment = self::where('user_id', Helpers::getUser()->id)->select(['page', 'type', 'updated_at', 'reset_assessment'])->latest()->first();

        if ($assessment) {

            if ($assessment['page'] === 0) {

                if ($assessment['reset_assessment'] == 1) {

                    return self::createNewAssessment();
                }

                $minutes = Helpers::explodeTimezoneWithHours($user['timezone']);

                $userTime = \Carbon\Carbon::parse($assessment['updated_at'])->addMinutes($minutes * 60)->toDateTimeString();

                $difference = \Carbon\Carbon::now()->diffInDays($userTime);

                if ($difference > 90) {

                    return self::createNewAssessment();
                } else {

                    return false;
                }
            } else {

                return ($assessment['page'] === null ? 0 : $assessment['page']);
            }
        } else {

            return self::createNewAssessment();
        }
    }

    public static function submitQuestionAnswers($answer_ids = [])
    {

        $multipleAnswersArray = [];

        $codeA = [];

        $codeArray = [];

        if (!empty($answer_ids)) {

            foreach ($answer_ids as $answer_id) {

                if (is_array($answer_id)) {

                    $i = 3;

                    foreach ($answer_id as $answer) {

                        $answerCode = AnswerCode::where('answer_id', $answer)->select(['code', 'number'])->first();

                        if ($answerCode) {

                            $number = (int)$answerCode->number + $i;

                            $code = strtolower($answerCode->code);

                            if (array_key_exists($code, $multipleAnswersArray)) {

                                $multipleAnswersArray[$code] += $number;
                            } else {

                                $multipleAnswersArray[$code] = $number;
                            }

                            $i--;
                        }
                    }
                } else {

                    $codes = AnswerCode::where('answer_id', $answer_id)->get();

                    foreach ($codes as $code) {

                        if (array_key_exists($code['code'], $codeA)) {

                            $codeA[$code['code']] += $code['number'];
                        } else {

                            $codeA[$code['code']] = $code['number'];
                        }
                    }
                }
            }
        }

        $userId = Helpers::getUser()->id;

        foreach ($codeA as $code => $value) {

            $lowercaseCode = strtolower($code);

            if (!isset($codeArray[$lowercaseCode])) {

                $codeArray[$lowercaseCode] = 0;
            }

            if ($value !== '') {

                $codeArray[$lowercaseCode] += $value;
            }
        }

        $existingAssessment = Assessment::where('user_id', $userId)->latest()->first();

        if ($existingAssessment) {

            $oldResult = $existingAssessment->toArray();

            $resultArray = [];

            if (!empty($multipleAnswersArray)) {

                $codeArray = array_merge($multipleAnswersArray, $codeArray);
            }

            foreach ($codeArray as $key => $value) {

                if ($value !== '') {

                    $resultArray[$key] = (isset($oldResult[$key]) ? $oldResult[$key] : 0) + $value;
                } else {

                    $resultArray[$key] = isset($oldResult[$key]) ? $oldResult[$key] : 0;
                }
            }

            $totalPages = ceil(Question::whereNull('question_id')->whereIn('gender', [Helpers::getUser()->gender, 2])->where('active', 1)->count() / 3) ?? 0;

            $current_page = $existingAssessment->page + 1;

            if ($totalPages == $current_page) {

                $resultArray['page'] = 0;

                $existingAssessment->update($resultArray);

                event(new SubmitAssessment(Helpers::getUser()['id'], 0));

                $latestAssessment = Assessment::getLatestAssessment($userId);

                $user = Helpers::getWebUser() ?? Helpers::getUser();

                if (!empty($latestAssessment)) {

                    $userDailyTip = UserDailyTip::getLatestTip();

                    if (empty($userDailyTip)) {

                        if ($latestAssessment) {

                            $codeColor = AssessmentColorCode::getGreenCodes($latestAssessment['id']);

                            $alchemy = Assessment::getAlchemy($latestAssessment);

                            if ($alchemy) {

                                $codeAlchemy = $alchemy['code'];
                            }

                            $communication = Assessment::getEnergy($latestAssessment);

                            if ($communication) {

                                $codeCommunication = $communication[0];
                            }

                            $selectedCodeList = [
                                $codeColor['code'] ?? '',
                                $codeAlchemy ?? '',
                                $codeCommunication ?? ''
                            ];

                            $randomCode = $selectedCodeList[array_rand($selectedCodeList)];

                            if ($randomCode) {

                                $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                                if ($newDailyTip) {

                                    $latestTip = UserDailyTip::where('user_id', $user['id'])->where('daily_tip_id', $newDailyTip['id'])->latest()->first();

                                    $alreadyExist = $latestTip && $latestTip->created_at >= Carbon::now()->subDays(365);

                                    if ($alreadyExist) {

                                        self::getTodayTip();
                                    }

                                    UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $latestAssessment['id']);

                                    $message = 'Your New Daily Tip';

                                    $deviceToken = $user['device_token'];

                                    event(new NewDailyTip($user['id'], 'new daily tip', $message));

                                    Helpers::OneSignalApiUsed($user['id'], 'new daily tip', $message);

//                                    Notification::createNotification('Daily Tip', $message, $deviceToken, $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION);
                                }
                            }
                        }
                    }
                }

                HaiChatHelpers::syncUserRecordWithHAi();

                HumanOpPoints::addPointsAfterCompleteAssessment($user);

                GamificationBadgesAchievement::addBadgeAfterCompleteAssessment($user['id']);

                if (\App\Models\Assessment::where('user_id', Helpers::getUser()->id)->count() === 1) {

                    $message = "Congratulations on finishing your first assessment!  Remember to come back next season (90 days) to take it again for free.";

                } else {

                    $message = "Congratulations on finishing your assessment!";
                }
            } else {

                $resultArray['page'] = $current_page;

                $existingAssessment->update($resultArray);

                event(new SubmitAssessment(Helpers::getUser()['id'], $current_page + 1));
            }

            AssessmentColorCode::deleteAssessemntColorCodeData($existingAssessment);

            AssessmentColorCode::createStylesCodeAndColor($existingAssessment);

            AssessmentColorCode::createFeaturesCodeAndColor($existingAssessment);
        }

        if ($existingAssessment['page'] == 0) {

            ActionPlan::storeUserActionPlan($existingAssessment);

        }

        foreach ($answer_ids as $answer_id) {


            $data['user_id'] = $userId;

            $data['assessment_id'] = $existingAssessment->id;


            if (is_array($answer_id)) {

                foreach ($answer_id as $ansId) {

                    $answer = Answer::where(function ($q) use ($ansId) {

                        $q->where('id', $ansId)->orWhere('answer_id', $ansId);

                    })->first();

                    $data['answer'] = $answer->answer ?? null;

                    $data['question'] = $answer->question->question ?? null;

                    AssessmentDetail::createAssessmentDetail($data);
                }
            } else {

                $answer = Answer::where(function ($q) use ($answer_id) {

                    $q->where('id', $answer_id)->orWhere('answer_id', $answer_id);

                })->first();

                $data['answer'] = $answer->answer ?? null;

                $data['question'] = $answer->question->question ?? null;

                AssessmentDetail::createAssessmentDetail($data);
            }
        }

        return ($message ?? "");
    }

    public static function singleAssessmentFromId($assessment_id)
    {

        $user = Helpers::getWebUser() ?? Helpers::getUser();

        return self::with('users')->where(function ($q) use ($assessment_id, $user) {

            if ($assessment_id) {

                $q->where('id', $assessment_id);

            } else {

                $q->where('user_id', $user->id);
            }

        })
            ->where('page', 0)->latest()->first();

    }

    public static function getEnergyPoolDetail($assessment = null)
    {
        $energy_code = self::GetEP($assessment);


        $code_detail = CodeDetail::whereId($energy_code['energy_code'])->first();

        if ($code_detail) {

            $code_detail['public_name'] = str_replace('Energy', '', $code_detail['public_name']);
        }


        return $code_detail;
    }

    public static function getAlchemyDetail($assessment = null)
    {
        $gold = $assessment['g'];
        $silver = $assessment['s'];
        $copper = $assessment['c'];
        $alchemy = $gold . '' . $silver . '' . $copper;
        $alchemyCodeDetail = AlchemyCode::getCodeDeatil($alchemy);

        if (!empty($alchemyCodeDetail)) {

            $publicName = CodeDetail::getSinglePublicName($alchemyCodeDetail['code']);

            $video = $publicName['video'];

            $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
                ? $video['video_upload_url']['path']
                : ($video['video_url'] ?? null);


            $progress = VideoProgress::checkVideoProgress($assessment['id'], $publicName['name']);

            return [
                'name' => $publicName['name'],
                'public_name' => $publicName['public_name'],
                'code_number' => $gold . $silver . $copper,
                'description' => $publicName['text'],
                'video_url' => $videoUrl,
                'img_url' => $alchemyCodeDetail['image_url'],
                'video_progress' => $progress['video_progress'],
                'video_time' => $progress['video_time']
            ];
        } else {

            return [
                'name' => '',
                'public_name' => "",
                'code_number' => $gold . $silver . $copper,
                'description' => "",
                'video_url' => "",
                'img_url' => "",
            ];
        }
    }

    public static function getPv($assessment = null)
    {
        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

        return $positive - $negative;

    }

    public static function getPreceptionReportDetail($assessment = null)
    {

        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

        $pv = $positive - $negative;

        if ($pv <= -8) {

            $polarity_code = 40;
        } elseif ($pv >= -7 and $pv <= 7) {

            $polarity_code = 41;
        } elseif ($pv >= 8) {

            $polarity_code = 42;
        }

        $record = CodeDetail::with('video')->whereId($polarity_code)->first();

        $record['pv'] = $pv > 0 ? '+' . $pv : $pv;


        $video = $record['video'];

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessment['id'], $record['name']);

        return $data = [
            'code_number' => $record['id'],
            'name' => $record['name'],
            'public_name' => $record['public_name'],
            'code_name' => $record['code'],
            'description' => $record['text'],
            'video' => $record['video'] ? $record['video']['video'] : null,
            'video_url' => $videoUrl,
            'pv' => $record['pv'],
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];
    }

    public static function resetAssessmentStatus($assessmentId = null)
    {

        $assessment = self::whereId($assessmentId)->first();

        if ($assessment) {

            if ($assessment['reset_assessment'] == Admin::NOT_RESET_ASSESSMENT) {

                $assessment->update([
                    'after_reset_assessment_updated_at' => Carbon::parse($assessment->updated_at)->format('Y-m-d H:i:s'),
                ]);

                $assessment->update(['reset_assessment' => Admin::RESET_ASSESSMENT]);
            } else {

                $assessment->update(['reset_assessment' => Admin::NOT_RESET_ASSESSMENT]);
            }
        }

        return $assessment;
    }

    public static function getAssessmentFromUserId($user_id)
    {
        return static::with(['assessmentColorCodes' => function ($query) {
            $query->selection();
        }])
            ->where('user_id', ($user_id))
            ->where('page', 0)
            ->orderBy('created_at', 'desc')
            ->selection()
            ->get();
    }

    public static function getCoreState($assessment = null, $dateOfBirth = null)
    {

        $interval_of_life = User::getUserAge($dateOfBirth, $assessment);

        $topThreeStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];

        $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];

        $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : null;

        $communication = $assessment != null ? Assessment::getEnergy($assessment) : null;

        $perception_life = AssessmentIntro::getPerceptionStaticText();

        $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : null;

        $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];

        $nextTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['next_two_keys'], $assessment) : [];

        $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication, $assessment) : [];

        $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : null;

        $data = [
            'assessment' => $assessment,
            'topThreeStyles' => $topThreeStyles,
            'topTwoFeatures' => $topTwoFeatures,
            'tertiaryFeatures' => $nextTwoFeatures ?? '',
            'boundary' => $boundary,
            'topCommunication' => $topCommunication,
            'energyPool' => $energyPool,
            'your_perception' => $perception_life,
            'perception' => $perception,
            'interval_of_life' => $interval_of_life
        ];

        return $data;
    }
}
