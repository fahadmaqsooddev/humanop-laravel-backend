<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Question;
use App\Models\Assessment;
use App\Models\AssessmentDetail;
use App\Models\AssessmentColorCode;
use App\Models\Admin\Answer\Answer;
use App\Models\Admin\AnswerCode\AnswerCode;

class generateUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all users data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables
        DB::table('users')->truncate();
        DB::table('assessments')->truncate();
        DB::table('assessment_details')->truncate();
        DB::table('assessment_color_code')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Open the users CSV file
        if (($csvFile = fopen(base_path("public/users.csv"), "r")) !== FALSE) {
            $firstline = true;
            while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

                // Skip the header line
                if ($firstline) {
                    $firstline = false;
                    continue;
                }

                // Check if the user with the given email already exists
                $checkUser = User::where('email', $data[3])->first();

                if (!$checkUser) {
                    $user = User::create([
                        'first_name' => $data[1],
                        'last_name' => $data[2],
                        'email' => $data[3],
                        'password' => '12345678',
                        'phone' => $data[6],
                        'age_min' => $data[7],
                        'age_max' => $data[8],
                        'gender' => $data[9],
                        'signup_date' => Carbon::today(),
                        'last_login' => Carbon::today(),
                        'status' => 1,
                        'is_admin' => 2,
                        'password_set' => 1,
                        'is_feedback' => 3,
                        'created_at' => Carbon::today(),
                        'updated_at' => Carbon::today(),
                    ]);

                    $userId = $user['id'];

                    Assessment::create([
                        'user_id' => $userId
                    ]);


                    // Now open the records CSV file
                    if (($recordsFile = fopen(base_path("public/records.csv"), "r")) !== FALSE) {
                        $recordsFirstline = true;
                        while (($recordData = fgetcsv($recordsFile, 2000, ",")) !== FALSE) {

                            // Skip the header line of the records file
                            if ($recordsFirstline) {
                                $recordsFirstline = false;
                                continue;
                            }

                            // Check if the user_id matches the record
                            if ($data[0] == $recordData[1]) {
                                $answer_id = $recordData[3];

                                if (($ansCodesFile = fopen(base_path("public/ans_codes.csv"), "r")) !== FALSE) {
                                    $ansCodesFirstline = true;
                                    while (($ansCodeData = fgetcsv($ansCodesFile, 2000, ",")) !== FALSE) {

                                        // Skip the header line of the ans_codes file
                                        if ($ansCodesFirstline) {
                                            $ansCodesFirstline = false;
                                            continue;
                                        }

                                        // Check if the answer_id matches the ansCodeData
                                        if ($answer_id == $ansCodeData[1]) {

                                            $assessmentResult = [];
                                            $resultArray = [];

                                            $assessment = Assessment::singleAssessment($userId);
                                            $oldResult = $assessment->toArray();
                                            $assessmentResult[strtolower($ansCodeData[3])] = $ansCodeData[4];

                                            if ($recordData[6] > 0) {

                                                switch ($recordData[6] > 0) {
                                                    case $recordData[6] == 1:
                                                        $resultArray[strtolower($ansCodeData[3])] = $oldResult[strtolower($ansCodeData[3])] + 4;
                                                        break;
                                                    case $recordData[6] == 2:
                                                        $resultArray[strtolower($ansCodeData[3])] = $oldResult[strtolower($ansCodeData[3])] + 3;
                                                        break;
                                                    case $recordData[6] == 3:
                                                        $resultArray[strtolower($ansCodeData[3])] = $oldResult[strtolower($ansCodeData[3])] + 2;
                                                        break;
                                                    case $recordData[6] == 4:
                                                        $resultArray[strtolower($ansCodeData[3])] = $oldResult[strtolower($ansCodeData[3])] + 1;
                                                        break;
                                                }

                                            } elseif ($recordData[6] == 0) {
                                                foreach ($assessmentResult as $key => $value) {
                                                    if ($value !== '') {
                                                        $resultArray[$key] = (isset($oldResult[$key]) ? $oldResult[$key] : 0) + $value;
                                                    } else {
                                                        $resultArray[$key] = isset($oldResult[$key]) ? $oldResult[$key] : 0;
                                                    }
                                                }
                                            }
                                            $resultArray['page'] = 0;
                                            $assessment->update($resultArray);

                                            AssessmentColorCode::deleteAssessemntColorCodeData($assessment);
                                            AssessmentColorCode::createStylesCodeAndColor($assessment);
                                            AssessmentColorCode::createFeaturesCodeAndColor($assessment);
                                        }
                                    }
                                    fclose($ansCodesFile); // Close the ans_codes file after processing
                                }
                                $question = Question::singleQuestion($recordData[2]);
                                $answer = Answer::singleAnswer($recordData[3]);

<<<<<<< Updated upstream
=======
                                dd($question, $answer);
>>>>>>> Stashed changes
                                if (!empty($question))
                                {
                                    AssessmentDetail::create([
                                        'user_id' => $userId,
                                        'assessment_id' => $assessment['id'],
<<<<<<< Updated upstream
//                                        'question' => $question['question'],
=======
                                        'question' => $question['question'],
>>>>>>> Stashed changes
                                        'answer' => $answer['answer'],
                                    ]);
                                }
                            }
                        }
                        fclose($recordsFile); // Close the records file after processing
                    }
                }
            }
            fclose($csvFile); // Close the users file after processing
        }
    }
}
