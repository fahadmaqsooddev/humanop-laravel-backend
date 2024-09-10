<?php

namespace App\Http\Livewire\Admin\ClientQuery;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\HAIChai\ClientQuery;
use App\Models\HAIChai\QueryAnswer;
use App\Helpers\Helpers;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Admin\Code\CodeDetail;

class QueryAnswerForm extends Component
{

    public $queryId;
    public $answer;

    public function submitForm()
    {
        DB::beginTransaction();

        try {

            $queryData = ClientQuery::updateQuery($this->queryId);

            QueryAnswer::createAnswer($queryData['id'], $this->answer);

            DB::commit();

            session()->flash('success', "Answer submit successfully");

            $this->emitUp('refreshQuery');

            $this->emit('closeAnswerModal', ['id' =>$this->queryId]);


        }catch (\Exception $exception)
        {
            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        $user = Helpers::getWebUser();
        $query = ClientQuery::singleQuery($this->queryId);
        $user_age = User::getUserAge(Helpers::getWebUser()->age_group);
        $assessment = Assessment::getLatestAssessment($user['id']);
        $topThreeStyles = $assessment != null ? Assessment::getTopThreeStyles($assessment) : [];
        $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
        $boundary = $assessment != null ? Assessment::getAlchemyPublicName($assessment) : [];
        $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
        $preception = $assessment != null ? Assessment::getPreceptionReport($assessment) : [];
        $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
        $topCommunication = $communication != null ? CodeDetail::getCommunicationPublicName($communication) : [];
        $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : [];

        return view('livewire.admin.client-query.query-answer-form', compact('query','topThreeStyles', 'topTwoFeatures', 'boundary', 'topCommunication', 'assessment', 'preception','user_age','energyPool'));
    }
}
