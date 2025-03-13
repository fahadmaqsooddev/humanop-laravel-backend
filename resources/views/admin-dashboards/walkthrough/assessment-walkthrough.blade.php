@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

<link href="{{ URL::asset('assets/css/cropper.min.css') }}" rel="stylesheet" />

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">


@section('content')
   
    <div class="container-fluid my-3 py-3">
        @include('layouts.message')
        <div class="row mb-5">
            <div class="col-lg-3">
                <div class="card position-sticky top-1">
                    <ul class="nav  flex-column border-radius-lg p-3">
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".largest-trait">
                                <span class="text-sm custom-text-dark">Largest Trait Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".second-trait">
                                <span class="text-sm custom-text-dark">Second Trait Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".third-trait">
                                <span class="text-sm custom-text-dark">Third Trait Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".pilot">
                                <span class="text-sm custom-text-dark">Pilot Driver Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".co-pilot">
                                <span class="text-sm custom-text-dark">Co-pilot Driver Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".alchemy">
                                <span class="text-sm custom-text-dark">Alchemy Boundaries Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".communication">
                                <span class="text-sm custom-text-dark">Communication Style Sequence</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trait-link setting-options text-body" href=""  data-target=".polarity">
                                <span class="text-sm custom-text-dark">Polarity and Energy Pool Sequence</span>
                            </a>
                        </li>
                        


                        <li class="nav-item">
                          
                            <a class="nav-link setting-options text-body" href="" data-scroll="" >
                     
                              
                                <span class="text-sm custom-text-dark">Hai Tautorial Sequence</span>
                            </a>
                        </li>
                      
                    
                    </ul>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card card-bg-white-orange-border mt-4" >
               

                    <div class="trait-container">
                        <div class="largest-trait">
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
                  
                </div>
            </div>
          
        @include('layouts/footers/auth/footer')
    </div>
@endsection


<script>

document.addEventListener("DOMContentLoaded", function () {
    // Get all trait links
    const traitLinks = document.querySelectorAll(".trait-link");

    traitLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // Get the target element class from data-target
            const targetSelector = this.getAttribute("data-target");
 

            console.log(targetSelector);
            
            // First, hide ALL trait components
            const allTraitComponents = document.querySelectorAll(".trait-container > div");
            allTraitComponents.forEach(function (component) {
                component.classList.add("d-none");
            });

            // Then show ONLY the target component
            const targetElement = document.querySelector(targetSelector);
            if (targetElement) {
                targetElement.classList.remove("d-none");
            }
        });
    });
});


    </script>


{{-- <script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
        }
    }




    </script> --}}

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


