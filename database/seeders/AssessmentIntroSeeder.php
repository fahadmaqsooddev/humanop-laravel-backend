<?php

namespace Database\Seeders;

use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Models\Admin\Code\CodeDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentIntroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $codes = ['CI', 'PLI', 'EI', 'BI', 'MI', 'TI', 'CLI', 'MRI', 'SI'];

             // Get all relevant code_details rows
             $codeDetails = CodeDetail::
             whereIn('code', $codes)
             ->get();

             foreach($codeDetails as $data){
                
        AssessmentIntro::create([
            'name' => $data['name'],
            'public_name' => $data['public_name'],
            'code' => $data['code'],
            'number' => $data['number'],
            'type' => $data['type'],
            'text' => $data['text'],
        ]);
             }
    }
}
