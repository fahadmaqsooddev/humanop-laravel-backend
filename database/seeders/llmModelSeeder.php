<?php

namespace Database\Seeders;

use App\Models\HAIChai\LlmModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class llmModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('llm_models')->truncate();

        $llmModels = [
            ['model_name' => 'Deepseek', 'model_value' => 'deepseek/deepseek-chat'],
            ['model_name' => 'Qwen', 'model_value' => 'qwen/qvq-72b-preview'],
            ['model_name' => 'Deepseek R1-Qwen', 'model_value' => 'deepseek/deepseek-r1-distill-qwen-1.5b'],
            ['model_name' => 'OpenAI', 'model_value' => 'openai/gpt-3.5-turbo'],
            ['model_name' => 'Anthropic Claude', 'model_value' => 'anthropic/claude-3-haiku'],
            ['model_name' => 'Google Gemini', 'model_value' => 'google/gemini-2.0-flash-001'],
        ];

        try {

            foreach ($llmModels as $model) {

                LlmModel::createModel($model);

            }

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

        }
    }
}
