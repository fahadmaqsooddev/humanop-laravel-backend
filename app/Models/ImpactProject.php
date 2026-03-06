<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ImpactContribution;
use Illuminate\Support\Facades\DB;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Models\Activity;
use App\Enums\Admin\Admin;

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

    public function contributions()
    {
        return $this->hasMany(ImpactContribution::class,'impact_project_id');
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

    public function contributeByUser($user)
    {
        $hpRequired = $this->hp_required;

        try {
            $remainingHp = DB::transaction(function () use ($user, $hpRequired) {

                // Lock the user's HP row
                $userHpRecord = HumanOpPoints::where('user_id', $user->id)
                    ->lockForUpdate()
                    ->first();

                if (!$userHpRecord || $userHpRecord->points < $hpRequired) {
                    throw new \Exception('Insufficient HP to contribute.');
                }

                // Deduct points
                $userHpRecord->decrement('points', $hpRequired);

                // Reload model to get accurate DB value
                $userHpRecord->refresh();

                // Record contribution
                ImpactContribution::createContribution(
                    $user->id,
                    $this->id,
                    $hpRequired
                );

                return $userHpRecord->points;
            });

            $message = "{$user->name} contributed {$hpRequired} HP to '{$this->title}'";
            ActivityLogger::addLog('Impact Project', $message);

            return [
                'success' => true,
                'remaining_hp' => $remainingHp,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function getLogs($userId){
        return Activity::select('action_title','action_description')
            ->where('subject_id', $userId)
            ->where('event',Admin::IMPACT_PROJECT)
            ->latest('created_at')
            ->get();
    }

     public static function findOrFailById($id)
    {
        return self::findOrFail($id);
    }

    // Update project
    public function updateProject(array $data)
    {
        $this->update($data);
    }

    // Delete project
    public function deleteProject()
    {
        $this->delete();
    }
}
