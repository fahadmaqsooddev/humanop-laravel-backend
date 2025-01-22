<!DOCTYPE html>
<html>
    <style>
        .mystyle p strong {
    font-size: 1.5em;
    font-weight: bolder;
  }
    </style>
<body>
<div class="row">

    <div class="col-lg-12 position-relative z-index-2">
        <div class="container-fluid">
            <section>
                <div class="row mt-lg-4 mt-2">
                    <div class="col-12">
                        <div class="card" style="text-align: center">
                            <div class="card-body p-3 ">
                                <div>

                                    <img
                                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/hai_chat_logo.png'))) }}"
                                        style="padding: 0px; max-width: 150px;">

                                </div>
                                <div>
                                    <img
                                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logos/HumanOp dark.png'))) }}"
                                        style="padding: 0px; max-width: 500px;">
                                </div>

                                <h3 class="text-white text-bold">“Advanced Human Assessment Technology for a Better
                                    World.”</h3>
                                <h1 class="text-white">HumanOp Summary Report</h1>
                                <h2 class="text-white text-bold">{{$user_name}}</h2>
                                <div class="text-white mt-4" style="text-align: justify">
                                  
                                    {!!$summary_static['description']!!}

                                </div>

                     
                               <div style="text-align: justify" class="mystyle">
                                {!!$trait_intro['description']!!}
                               </div>
                               


                                @foreach($allStyles as $style)
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $style['public_name'] }}</h2>
                                        <div style="text-align: justify">
                                            <p class="text-white" style="text-align: justify !important;">{!! $style['description'] !!}</p>
                                        </div>
                                @endforeach

                               
                                    <div style="text-align: justify"  class="mystyle">
                                      {!!$motivation_intro['description']!!}
                                    </div>

                                @foreach($topTwoFeatures as $index => $feature)
                                    <?php
                                    $featureHeading = '';
                                    $featureText = '';
                            


                                    ?>
                                    @switch($feature['public_name'])
                                        @case('Initiates Change')
                                        <?php
                                        $featureHeading = 'Initiating Change';
                                        $featureTextArray = [
                                      
                                            "0"=>$feature['description'],
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break
                                        @case('Creating Order')
                                        <?php
                                        $featureHeading = 'Creating Order';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Creates Protection')
                                        <?php
                                        $featureHeading = 'Creating Protection';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Monetary Discernment')
                                        <?php
                                        $featureHeading = 'Monetary Discernment';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Visionary')
                                        <?php
                                        $featureHeading = 'The Visionary';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Optimistic')
                                        <?php
                                        $featureHeading = 'Optimism';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Humility')
                                        <?php
                                        $featureHeading = 'Humility';
                                        $featureTextArray = [

"0"=>$feature['description']
];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Accomplishment')
                                        <?php
                                        $featureHeading = 'Accomplishment';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Compassion')
                                        <?php
                                        $featureHeading = 'Compassion';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('The Traveler')
                                        <?php
                                        $featureHeading = 'The Traveler';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); 

                                        $featureText = $featureTextArray[$randomKey]; 
                                        ?>
                                        @break

                                        @case('Aesthetic Sensibility')
                                        <?php
                                        $featureHeading = 'Aesthetic Sensibility';
                                        $featureTextArray = [

"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Perseverance')
                                        <?php
                                        $featureHeading = 'Perseverance';
                                        $featureTextArray = [
                                     
"0"=>$feature['description']
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break
                                    @endswitch

                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $index == 0 ? 'Pilot ' : 'Co-pilot ' }} {{$featureHeading}}</h2>
                                        <div style="text-align: justify">
                                            <p class="text-white" style="text-align: justify">{!! $featureText !!}</p>
                                        </div>
                                   
                                @endforeach

                               
                                 <div style="text-align: justify"  class="mystyle">
                                   {!!$intro_boundaries['description']!!}
                                 </div>
                                @if($boundary)
                                    <?php
                                    $boundaryHeading = '';
                                    $boundaryImage = '';
                                    $boundaryText = '';
                                    $randomKey = 0;
                                    ?>
                                    @switch($boundary['public_name'])
                                        @case('Gold')
                                        <?php
                                        $boundaryHeading = 'Gold';
                                        $boundaryImage = 'img/gold.png';

                                        $boundaryTextArray = [
            
            "0"=>$boundary['description']
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Gold-Silver')
                                        <?php
                                        $boundaryHeading = 'Gold-Silver';
                                        $boundaryImage = 'img/gold-silver.png';

                                        $boundaryTextArray = [

"0"=>$boundary['description']
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Silver-Gold')
                                        <?php
                                        $boundaryHeading = 'Silver-Gold';
                                        $boundaryImage = 'img/silver-gold.png';

                                        $boundaryTextArray = [

"0"=>$boundary['description']
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Silver')
                                        <?php
                                        $boundaryHeading = 'Silver';
                                        $boundaryImage = 'img/silver.png';

                                        $boundaryTextArray = [

"0"=>$boundary['description']
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Silver-Copper')
                                        <?php
                                        $boundaryHeading = 'Silver-Copper';
                                        $boundaryImage = 'img/silver-copper.png';

                                        $boundaryTextArray = [

"0"=>$boundary['description']
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Copper-Silver')
                                        <?php
                                        $boundaryHeading = 'Copper-Silver';
                                        $boundaryImage = 'img/copper-silver.png';

                                        $boundaryTextArray = [

"0"=>$boundary['description']
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Copper')
                                        <?php
                                        $boundaryHeading = 'Copper';
                                        $boundaryImage = 'img/copper.png';

                                        $boundaryTextArray = [

"0"=>$boundary['description']
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch

                                    <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOU HAVE
                                        A {{ $boundaryHeading }} Alchemy</h2>
                                    <div class="mt-4" style="border: 0px solid #ccc;">
                                        <img
                                            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/' . $boundaryImage))) }}"
                                            style="background:#351a0d; padding: 0px; max-width: 500px"/>
                                    </div>
                                    <div style="text-align: justify">
                                        <p class="text-white mt-4" style="text-align: justify">{!! $boundaryText !!}</p>
                                    </div>
                                    
                                    <!-- Render HTML tags -->
                                @endif

                              

                                    <div style="text-align:justify"  class="mystyle">
                                     {!!$intro_communication['description']!!}
                                    </div>

                                @foreach($topCommunication as $index => $communication)
                                    <?php
                                    $communicationHeading = '';
                                    $communicationText = '';
                                    $randomKey = 0;
                                    ?>
                                    @switch($communication['public_name'])
                                        @case('Emotional')
                                        <?php
                                        $communicationHeading = 'The Emotional Energy Center';
                                        $communicationTextArray = [

"0"=>$communication['description']
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break

                                        @case('Instinctual')
                                        <?php
                                        $communicationHeading = 'The Instinctual Energy Center';
                                        $communicationTextArray = [

"0"=>$communication['description']
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break

                                        @case('Intellectual')
                                        <?php
                                        $communicationHeading = 'The Intellectual Energy Center';
                                        $communicationTextArray = [
                                          

                                            "0"=>$communication['description']
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break

                                        @case('Moving')
                                        <?php
                                        $communicationHeading = 'The Moving Energy Center';
                                        $communicationTextArray = [


"0"=>$communication['description']
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify"> {{ $index == 0 ? 'Your First “Door” is ' : ($index == 1 ? 'Your Second “Door” is ' : ($index == 2 ? 'Your Third “Door” is ' : 'Your Fourth “Door” is ')) }}{{ $communicationHeading }}</h2>
                                        <div style="text-align: justify">
                                            <p class="text-white" style="text-align: justify">{!! $communicationText !!}</p>
                                        </div>
                                    
                                @endforeach

                          
                                 <div style="text-align: justify"  class="mystyle">
                                {!!$intro_perceptionlife['description']!!}
                                 </div>
                                @if($perception)
                                    <?php
                                    $perceptionHeading = '';
                                    $perceptionText = '';
                                    $randomKey = 0;
                                    ?>
                                    @switch($perception['public_name'])
                                        @case('Polarity Neutral')
                                        <?php
                                        $perceptionHeading = 'Neutral Perception of Life';
                                        $perceptionTextArray = [
                                        
                                            "0"=>$perception['description']
                                        ];
                                        $randomKey = array_rand($perceptionTextArray);
                                        $perceptionText = $perceptionTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Polarity Positive')
                                        <?php
                                        $perceptionHeading = 'Positive Perception of Life';
                                        $perceptionTextArray = [
                                        
                                            "0"=>$perception['description']
                                        ];
                                        $randomKey = array_rand($perceptionTextArray);
                                        $perceptionText = $perceptionTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Polarity Negative')
                                        <?php
                                        $perceptionHeading = 'Negative Perception of Life';
                                        $perceptionTextArray = [
                                         
                                            "0"=>$perception['description']
                                        ];
                                        $randomKey = array_rand($perceptionTextArray);
                                        $perceptionText = $perceptionTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $perceptionHeading }}</h2>
                                        <div style="text-align: justify">
                                            <p class="text-white" style="text-align: justify">{!! $perceptionText !!}</p> 
                                        </div>
                                   
                                @endif

                          

                                    <div style="text-align: justify"  class="mystyle">
                                    {!!$intro_energypool['description']!!}
                                    </div>
                                   

                                @if($energyPool)
                                
                                    <?php
                                    $energyPoolHeading = '';
                                    $energyPoolText = '';
                                    $randomKey = 0;
                                    
                                    ?>
                                    @switch($energyPool['public_name'])
                                        @case(' Excellent')
                                        <?php
                                        $energyPoolHeading = 'Energy - Excellent';
                                        $energyPoolTextArray = [
                                         
                                            "0"=>$energyPool['text']
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case(' Above Excellent')
                                        <?php
                                        $energyPoolHeading = 'Energy - Above Excellent';
                                        $energyPoolTextArray = [
                                        
                                            "0"=>$energyPool['text']
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case(' Average')
                                        <?php
                                        $energyPoolHeading = 'Energy - Average';
                                        $energyPoolTextArray = [
                                        
                                            "0"=>$energyPool['text']
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case(' Fair')
                                        <?php
                                        $energyPoolHeading = 'Energy - Fair';
                                        $energyPoolTextArray = [
                                         
                                            "0"=>$energyPool['text']
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $energyPoolHeading }}</h2>
                                        <div style="text-align: justify">
                                            <p class="text-white" style="text-align: justify">{!! $energyPoolText !!}</p>
                                        </div>
                                    
                                @endif

                                <div style="text-align: justify" class="text-white mt-4"
                                     style="text-align: justify">
                                     <?php
                                     $text=config('pdffooter.footer');
                                   
                                     ?>
                                   
                                    {!!$text!!}

                                </div>

                                <p class="text-white mt-4"
                                   style="text-align: justify; padding-bottom: 20px; border-bottom: 2px solid #f2661c">
                                    Your practitioner
                                    is N/A
                                    <br>{{\Illuminate\Support\Facades\Auth::user()['email']}}</p>
                                <p class="text-white mt-4" style="text-align: justify">For internal use only. <br>Compatibility
                                    values for
                                    BR {{\Illuminate\Support\Facades\Auth::user()['gender'] == 1 ? '(F)' : '(M)'}}
                                    Interval</p>

                                <p class="text-white mt-4" style="text-align: justify">S {{$style_position}}</p>
                                <p class="text-white mt-4" style="text-align: justify">F {{$feature_position}}</p>
                                <p class="text-white mt-4" style="text-align: justify">Alch {{$alchl_code}}</p>
                                <p class="text-white mt-4" style="text-align: justify">
                                    PV {{$pv > 0 ? '+' : ''}} {{$pv}} REP
                                    ARC {{$pv - $ep}} to +{{$pv + $ep}}</p>
                                <p class="text-white mt-4" style="text-align: justify">REP {{$ep}}</p>
                                <p class="text-white mt-4" style="text-align: justify">TEP {{$ep * 2}}</p>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
</body>
</html>
