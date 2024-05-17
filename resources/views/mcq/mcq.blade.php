@extends('user_type.auth', ['parentFolder' => 'mcq', 'childFolder' => 'none'])

@section('content')

    <div class="container mt-5" style="margin-left: 200px;"> 
        <!-- Question 1 -->
        <div class="mb-4 text-white text-bold">
            <p>1. What is the capital of France?</p>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q1a1">
                <label class="form-check-label text-white" for="q1a1">A) Berlin</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q1a2">
                <label class="form-check-label text-white" for="q1a2">B) Madrid</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q1a3">
                <label class="form-check-label text-white" for="q1a3">C) Paris</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q1a4">
                <label class="form-check-label text-white" for="q1a4">D) Rome</label>
            </div>
        </div>

        <!-- Question 2 -->
        <div class="mb-4 text-white text-bold">
            <p>2. Which planet is known as the Red Planet?</p>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q2a1">
                <label class="form-check-label text-white" for="q2a1">A) Earth</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q2a2">
                <label class="form-check-label text-white" for="q2a2">B) Mars</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q2a3">
                <label class="form-check-label text-white" for="q2a3">C) Jupiter</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q2a4">
                <label class="form-check-label text-white" for="q2a4">D) Saturn</label>
            </div>
        </div>

        <!-- Question 3 -->
        <div class="mb-4 text-white text-bold">
            <p>3. Who wrote 'Romeo and Juliet'?</p>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q3a1">
                <label class="form-check-label text-white" for="q3a1">A) Charles Dickens</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q3a2">
                <label class="form-check-label text-white" for="q3a2">B) Jane Austen</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q3a3">
                <label class="form-check-label text-white" for="q3a3">C) William Shakespeare</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q3a4">
                <label class="form-check-label text-white" for="q3a4">D) Mark Twain</label>
            </div>
        </div>

        <!-- Question 4 -->
        <div class="mb-4 text-white text-bold">
            <p>4. What is the chemical symbol for water?</p>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q4a1">
                <label class="form-check-label text-white" for="q4a1">A) O2</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q4a2">
                <label class="form-check-label text-white" for="q4a2">B) H2O</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q4a3">
                <label class="form-check-label text-white" for="q4a3">C) CO2</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q4a4">
                <label class="form-check-label text-white" for="q4a4">D) NaCl</label>
            </div>
        </div>

        <!-- Question 5 -->
        <div class="mb-4 text-white text-bold">
            <p>5. Which country is known as the Land of the Rising Sun?</p>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q5a1">
                <label class="form-check-label text-white" for="q5a1">A) China</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q5a2">
                <label class="form-check-label text-white" for="q5a2">B) Japan</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q5a3">
                <label class="form-check-label text-white" for="q5a3">C) South Korea</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="q5a4">
                <label class="form-check-label text-white" for="q5a4">D) Thailand</label>
            </div>

            <a href="{{url('pricing-page')}}" class="btn btn-icon bg-gradient-primary mt-4">
                Submit
                <i class="fas fa-arrow-right ms-1"></i>
              </a>
        </div>
    </div>


@endsection