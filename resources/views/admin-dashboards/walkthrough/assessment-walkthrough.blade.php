@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    @push('css')
        <link href="{{ URL::asset('assets/css/cropper.min.css') }}" rel="stylesheet" />
   
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">

    @endpush
    <div class="container-fluid my-3 py-3">
        @include('layouts.message')
        <div class="row mb-5">
            <div class="col-lg-3">
                <div class="card position-sticky top-1">
                    <ul class="nav  flex-column border-radius-lg p-3">
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".largest-trait">
                                <span class="text-sm custom-text-dark">Largest Trait Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".second-trait">
                                <span class="text-sm custom-text-dark">Second Trait Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".third-trait">
                                <span class="text-sm custom-text-dark">Third Trait Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".pilot">
                                <span class="text-sm custom-text-dark">Pilot Driver Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".co-pilot">
                                <span class="text-sm custom-text-dark">Co-pilot Driver Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".alchemy">
                                <span class="text-sm custom-text-dark">Alchemy Boundaries Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".communication">
                                <span class="text-sm custom-text-dark">Communication Style Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setting-options text-body" href="#walkthrough" data-target=".polarity">
                                <span class="text-sm custom-text-dark">Polarity and Energy Pool Sequence</span>
                            </a>
                        </li>
                        


                        <li class="nav-item">
                            {{-- <a class="nav-link setting-options text-body" data-scroll="" href="#profile"> --}}
                            <a class="nav-link setting-options text-body" data-scroll="" href="#walkthrough">
                     
                              
                                <span class="text-sm custom-text-dark">Hai Tautorial Sequence</span>
                            </a>
                        </li>
                      
                    
                    </ul>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card card-bg-white-orange-border mt-4" >
               

                    <div class="trait-container" >
                        <div class="largest-trait d-none">
                            @livewire('admin.assessment-walkthrough.largest-trait')
                        </div>
                        <div class="second-trait d-none">
                            @livewire('admin.assessment-walkthrough.second-trait')
                        </div>
                        <div class="third-trait d-none">
                            @livewire('admin.assessment-walkthrough.third-trait')
                        </div>
                        <div class="pilot d-none">
                            @livewire('admin.assessment-walkthrough.pilot-trait')
                        </div>
                        <div class="co-pilot d-none">
                            @livewire('admin.assessment-walkthrough.co-pilot-trait')
                        </div>
                        <div class="alchemy d-none">
                            @livewire('admin.assessment-walkthrough.alchemy-trait')
                        </div>
                        <div class="communication d-none">
                            @livewire('admin.assessment-walkthrough.communication-trait')
                        </div>
                        <div class="polarity d-none">
                            @livewire('admin.assessment-walkthrough.polarity-trait')
                        </div>
                    </div>
                    
                  
                    @include('layouts.message')
                    {{-- <div class="card-header">
                        <h6 class="text-orange setting-form-heading py-2">Overview</h6>
                        <textarea class="form-control  teaxt" id="chatDescription" wire:model="prompt"
                                  rows="6" placeholder="">
                                        </textarea>
                    </div>
            
          
                    <div class="card-header">
                        <h6 class="text-orange setting-form-heading py-2">Highest and Optimal Expression</h6>
                        <textarea class="form-control  teaxt" id="chatDescription" wire:model="restriction"
                                  rows="6" placeholder=""></textarea>
                    </div>

                    <div class="card-header">
                        <h6 class="text-orange setting-form-heading py-2">Optimization Hot Spots And
Things To Recognize Aa Natural
Triggers</h6>
                        <textarea class="form-control teaxt" id="chatDescription" wire:model="restriction"
                                  rows="6" placeholder=""></textarea>
                    </div> --}}
            

                    {{-- <div class="card-header">
                        <h6 class="text-orange setting-form-heading py-2">Overview</h6>
                        <textarea class="form-control teaxt" id="overview" wire:model="prompt" rows="6" placeholder=""></textarea>
                    </div>
                    
                    <div class="card-header">
                        <h6 class="text-orange setting-form-heading py-2">Highest and Optimal Expression</h6>
                        <textarea class="form-control teaxt" id="expression" wire:model="restriction" rows="6" placeholder=""></textarea>
                    </div>
                    
                    <div class="card-header">
                        <h6 class="text-orange setting-form-heading py-2">Optimization Hot Spots And Things To Recognize As Natural Triggers</h6>
                        <textarea class="form-control teaxt" id="optimization" wire:model="restriction" rows="6" placeholder=""></textarea>
                    </div>
            --}}
{{--             
                    <div class="card-body d-sm-flex pt-0 justify-content-end">
                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="update"
                                class="mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end new-orange-button navButtonResponsive">
                            update
                            <span wire:loading wire:target="update" style="font-size: 8px;" class="swal2-loader">
                            </span>
                        </button>
                    </div> --}}
                </div>
            </div>
          
        @include('layouts/footers/auth/footer')
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".nav-link").forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();

                // Get the target class from the `data-target` attribute
                let targetClass = this.getAttribute("data-target");

                // Hide all Livewire component divs
                document.querySelectorAll(".trait-container > div").forEach(div => {
                    div.classList.add("d-none");
                });

                // Show the selected Livewire component
                let targetDiv = document.querySelector(targetClass);
                if (targetDiv) {
                    targetDiv.classList.remove("d-none");
                }
            });
        });
    });
</script>


<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
        }
    }




    </script>

    {{-- <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font,
            List,
            Link,
            AutoLink
        } from 'ckeditor5';

        // Function to initialize CKEditor for a specific textarea by ID
        let editorInstance;
        const editorElement = document.getElementById('editor');
        if (editorElement && !editorElement.classList.contains('ck-editor')) { // Check if not already initialized
            ClassicEditor
                .create(editorElement, {
                    plugins: [Essentials, Paragraph, Bold, Italic, Font, List, Link, AutoLink],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', 'link'  // Add list options to toolbar
                    ]
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                    @this.set('select_code.text', editor.getData());
                    })
                    Livewire.on('contentUpdated', content => {
                        editor.setData(content); // Set new content into CKEditor
                    });
                    editorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });

        }
        $('.createForm').on('click', function () {
            if (editorInstance) {
                editorInstance.setData('');
            }
        });

       
    </script> --}}



    {{-- livewire wla  --}}
    {{-- <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
            }
        }
        </script>
        
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Paragraph,
                Bold,
                Italic,
                Font,
                List,
                Link,
                AutoLink
            } from 'ckeditor5';
        
            // Function to initialize CKEditor for multiple textareas
            function initializeEditor(id, livewireProperty) {
                const editorElement = document.getElementById(id);
                if (editorElement && !editorElement.classList.contains('ck-editor')) {
                    ClassicEditor
                        .create(editorElement, {
                            plugins: [Essentials, Paragraph, Bold, Italic, Font, List, Link, AutoLink],
                            toolbar: [
                                'undo', 'redo', '|', 'bold', 'italic', '|',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                                'bulletedList', 'numberedList', 'link'
                            ]
                        })
                        .then(editor => {
                            editor.model.document.on('change:data', () => {
                                @this.set(livewireProperty, editor.getData()); // Sync with Livewire model
                            });
                            Livewire.on('contentUpdated', content => {
                                editor.setData(content); // Set updated content
                            });
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            }
        
            // Initialize CKEditor for all 3 textareas
            document.addEventListener("DOMContentLoaded", function () {
                initializeEditor('overview', 'prompt');
                initializeEditor('expression', 'restriction');
                initializeEditor('optimization', 'restriction');
            });
        
        </script> --}}


