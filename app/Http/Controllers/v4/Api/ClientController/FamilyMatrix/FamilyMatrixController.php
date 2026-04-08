<?php

namespace App\Http\Controllers\v4\Api\ClientController\FamilyMatrix;

use App\Enums\Admin\Admin;
use App\Helpers\Assessments\AssessmentHelper;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FamilyMatrix\AssignFamilymatrixRelationshipRequest;
use App\Http\Requests\Api\FamilyMatrix\FamilyMatrixNoteRelationRequest;
use App\Http\Requests\Api\FamilyMatrix\ConsentRequest;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Assessment;
use App\Models\FamilyMatrix\AssignFamilyMatrixRelationship;
use App\Models\FamilyMatrix\FamilyMatrixRelationship;
use App\Models\FamilyMatrix\FamilyMatrixResponse;
use App\Models\FamilyMatrix\FamilyMatrixNote;
use Illuminate\Http\Request;
use App\Http\Requests\Api\FamilyMatrix\FamilyMatrixNoteRequest;

class FamilyMatrixController extends Controller
{

    protected $assignRelationship;

    public $user = null;

    public function __construct(AssignFamilyMatrixRelationship $assignRelationship)
    {
        $this->middleware('auth:api');

        $this->assignRelationship = $assignRelationship;

        $this->user = Helpers::getUser();

    }


    public function familyMatrixAnalyze(Request $request)
    {

        $userId = Helpers::getUser()->id;

        $userPlan = Helpers::getUser()->plan_name;

        if ($userPlan != Admin::PREMIUM_PLAN_NAME) {

            return Helpers::validationResponse('This feature is available for Premium users only. Please upgrade your plan to continue.');

        }

        $targetId = $request->input('target_id');

        $getFamilyMatrix = FamilyMatrixResponse::getFamilyMatrix($userId, $targetId);

        if (!empty($getFamilyMatrix)) {

            return Helpers::successResponse('family matrix', $getFamilyMatrix);

        }

        $assessments = AssessmentHelper::getUserAssessments([$userId, $targetId]);

        if (count($assessments) !== 2) {

            return response()->json(['error' => 'Assessment data incomplete'], 404);

        }

        $userAssessment = $assessments[$userId];

        $targetAssessment = $assessments[$targetId];

        $data = [
            'request_meta' => [
                'relationship_label' => 'Father',
                'user_id' => 'usr_' . $userId,
                'target_id' => 'usr_' . $targetId,
            ],
            'profiles' => [
                'user_self' => $this->buildProfile($userAssessment),
                'target_member' => $this->buildProfile($targetAssessment),
            ],
            'compatibility_matrix' => AssessmentHelper::buildCompatibilityMatrix($userAssessment, $targetAssessment),
            'validationErrors' => []
        ];

        $response = GuzzleHelpers::sendRequestFromGuzzleForNewHai('post', 'family-matrix/analyze', $data);

        $validationErrors = $this->validateHaiAnalysisResponse($response);

        if (!empty($validationErrors)) {

            $error['validationErrors'] = $validationErrors;

            GuzzleHelpers::sendRequestFromGuzzleForNewHai('post', 'family-matrix/analyze', $error);

            return Helpers::serverErrorResponse('Family matrix analysis response invalid.');

        }

        $familyMatrix = FamilyMatrixResponse::createFamilyMatrixResponse($userId, $targetId, $response);

        return Helpers::successResponse('family matrix', $familyMatrix);

    }

    private function validateHaiAnalysisResponse(array $response): array
    {

        $errors = [];

        if (!isset($response['data']['content']) || !is_array($response['data']['content'])) {

            $errors[] = 'Missing content block';

            return $errors;

        }

        $content = $response['data']['content'];

        $requiredTop = ['vibe_check', 'the_physics', 'system_hack'];

        foreach ($requiredTop as $key) {

            if (!isset($content[$key]) || !is_array($content[$key])) {

                $errors[] = "Missing {$key} section";

            }

        }

        if (!empty($errors)) {

            return $errors;

        }

        if (empty($content['vibe_check']['text']) || !is_string($content['vibe_check']['text'])) {

            $errors[] = 'vibe_check.text missing or invalid';

        }

        if (empty($content['the_physics']['friction_analysis']) || !is_string($content['the_physics']['friction_analysis'])) {

            $errors[] = 'the_physics.friction_analysis missing or invalid';

        }

        if (empty($content['the_physics']['flow_analysis']) || !is_string($content['the_physics']['flow_analysis'])) {

            $errors[] = 'the_physics.flow_analysis missing or invalid';

        }

        if (empty($content['system_hack']['title']) || !is_string($content['system_hack']['title'])) {

            $errors[] = 'system_hack.title missing or invalid';

        }

        if (empty($content['system_hack']['actionable_step']) || !is_string($content['system_hack']['actionable_step'])) {

            $errors[] = 'system_hack.actionable_step missing or invalid';

        }

        return $errors;

    }

    private function buildProfile($assessment): array
    {

        $features = Assessment::getFeatures($assessment);

        $drivers = Assessment::getTopTwoFeatures($features['top_two_keys'], $assessment);

        $traits = Assessment::topThreeTraits($assessment);

        return [
            'pilot_driver' => $drivers[0]['public_name'] ?? null,
            'co_pilot_driver' => $drivers[1]['public_name'] ?? null,
            'primary_trait' => CodeDetail::getPublicNames($traits)[0][1] ?? null,
            'alchemy' => Assessment::getAlchemyDetail($assessment)['public_name'] ?? null,
            'perception_of_life' => Assessment::getPerceptionReportDetail($assessment)['public_name'] ?? null,
            'energy_center' => CodeDetail::getPublicNames(Assessment::getEnergyCenter($assessment))[0][1] ?? null,
        ];

    }

    public function allFamilyMatrixRelationship()
    {

        try {

            $relationships = FamilyMatrixRelationship::getRelationships();

            return Helpers::successResponse('All Family Matrix relationship', $relationships);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function assignFamilyMatrixRelationship(AssignFamilymatrixRelationshipRequest $request)
    {

        try {

            $userId = Helpers::getUser()->id;

            $dataArray = $request->only($this->assignRelationship->getFillable());

            if (AssignFamilyMatrixRelationship::checkRelationship($userId, $dataArray)) {

                return Helpers::validationResponse('Relationship already added.');

            }

            $relationship = AssignFamilyMatrixRelationship::createAssignRelationships($userId, $dataArray);

            HaiChatHelpers::syncUserRecordWithHAi();

            return Helpers::successResponse('Relationship added successfully.', $relationship);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function giveConsent(ConsentRequest $request)
    {

        $validated = $request->validated();

        $relation = AssignFamilyMatrixRelationship::updateConsent($this->user->id, $validated['target_id'], $validated['consent']);

        if (!$relation) {

            return Helpers::notFoundResponse('Relationship not found');

        }

        HaiChatHelpers::syncUserRecordWithHAi();

        return Helpers::successResponse('Permission updated', ['consent' => $relation->consent]);

    }


    public function allAssignFamilyMatrixRelationship()
    {

        try {

            $userId = Helpers::getUser()->id;

            $relationships = AssignFamilyMatrixRelationship::getRelationships($userId);

            return Helpers::successResponse('All Assign Family Matrix relationship', $relationships);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function deleteAssignFamilyMatrixRelationship(Request $request)
    {

        try {

            $targetId = $request->input('target_id');

            $userId = Helpers::getUser()->id;

            AssignFamilyMatrixRelationship::deleteRelationship($targetId, $userId);

            HaiChatHelpers::syncUserRecordWithHAi();

            return Helpers::successResponse('Delete Assign Family Matrix relationship Successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function addFamilyMatrixNotes(FamilyMatrixNoteRequest $request)
    {

        $validated = $request->validated();

        $note = FamilyMatrixNote::addFamilyMatrixNote(
            $this->user->id,
            $validated['assign_relation_id'],
            $validated['note'] ?? null
        );

        if (!$note) {
            return Helpers::validationResponse('Family Matrix Note Already Added.');
        }

        HaiChatHelpers::syncUserRecordWithHAi();

        return Helpers::successResponse('Family Matrix Note Added Successfully', $note);
    }

    public function updateFamilyMatrixNotes(FamilyMatrixNoteRequest $request)
    {
        try {

            $validated = $request->validated();

            $userId = $this->user->id;

            $assignRelationId = $validated['assign_relation_id'];

            $note = FamilyMatrixNote::updateFamilyMatrixNote($assignRelationId, $userId, $validated['note'] ?? null);

            if (!$note) {

                return Helpers::notFoundResponse('Note not found or not owned by you');

            }

            HaiChatHelpers::syncUserRecordWithHAi();

            return Helpers::successResponse('Family Matrix Note Updated Successfully', $note);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function deleteFamilyMatrixNotes(FamilyMatrixNoteRelationRequest $request)
    {
        try {

            $relationId = $request->input('assign_relation_id');

            $userId = $this->user->id;

            $deleted = FamilyMatrixNote::deleteFamilyMatrixNote($relationId, $userId);

            if (!$deleted) {

                return Helpers::notFoundResponse('Note not found');

            }

            HaiChatHelpers::syncUserRecordWithHAi();

            return Helpers::successResponse('Family Matrix Note Deleted Successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function showFamilyMatrixNote(FamilyMatrixNoteRelationRequest $request)
    {
        try {

            $relationId = $request->input('assign_relation_id');

            $userId = $this->user->id;

            $note = FamilyMatrixNote::getNoteByRelationId($relationId, $userId);

            if (!$note) {

                return Helpers::notFoundResponse('Note not found or not owned by you');

            }

            return Helpers::successResponse('Family Matrix Note retrieved successfully', $note);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }


    public function viewFamilyMatrixNotes()
    {
        try {

            $notes = FamilyMatrixNote::getAllNotes($this->user->id);

            if ($notes->isEmpty()) {

                return Helpers::notFoundResponse('No family matrix notes found');

            }

            return Helpers::successResponse('Family Matrix Notes retrieved successfully', $notes);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
