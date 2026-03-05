<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ImpactContribution;
use Illuminate\Support\Facades\DB;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Helpers\ActivityLogs\ActivityLogger;

class ImpactProject extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

    /**
     * Create a new Impact Project
     *
     * @param array $data
     * @return self
     */
    public static function createProject(array $data)
    {
        return self::create($data);
    }

    /**
     * Fetch all Impact Projects
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function fetchAll()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function fetchForUser($user)
    {
    
        $userHpRecord = HumanOpPoints::getUserPoints($user);
        $userHp = $userHpRecord ? ($userHpRecord->points ?? 0) : 0;
        $projects = self::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($project) use ($userHp) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'description' => $project->description,
                    'hp_required' => $project->hp_required,
                    'status' => $userHp >= $project->hp_required ? 'available' : 'locked',
                    'verification_text' => $project->verification_text,
                ];
            });

        return [
            'user_hp' => $userHp,
            'projects' => $projects,
        ];
    }

    public function contributeByUser($user,$hpRequired)
    {


        $userHpRecord = HumanOpPoints::getUserPoints($user);       
        $userHp = $userHpRecord ? ($userHpRecord->points ?? 0) : 0;


         if ($userHp < $hpRequired) {
            return [
                'success' => false,
                'message' => 'Insufficient HP to contribute.',
            ];
        }

        DB::transaction(function () use ($user, $userHpRecord) {

            if ($userHpRecord) {
                $userHpRecord->points -= $this->hp_required;
                $userHpRecord->save();
            }

            ImpactContribution::createContribution($user->id, $this->id, $this->hp_required);
        });

        $remainingHp = $userHpRecord ? $userHpRecord->points : 0;

        $message = "Contributed {$this->hp_required} HP to Impact Project: '{$this->title}'";
        ActivityLogger::addLog('Impact Project', $message);

        return [
            'success' => true,
            'remaining_hp' => $remainingHp,
        ];
    }
}