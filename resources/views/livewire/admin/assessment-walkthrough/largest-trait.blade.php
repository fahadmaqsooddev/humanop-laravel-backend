
    @push('css')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #0f1534; /* Example: Change this to your desired background color */
        }

        .ck-editor__editable {
            background-color: #0f1534 !important;
        }

        .ck-editor {
            border-radius: 0 !important;
            width: 100% !important;
        }

        .card {
            background-color: #1C365E !important;
        }

        .ck.ck-balloon-panel {
            z-index: 1050 !important;
        }

        .ck > p > a{
            color: blue !important;
        }


        
            .setting-options:hover {
                background-color: white !important;
            }

            .text-color-dark {
                color: #0f1534 !important;
            }

            input::placeholder {
                color: #0f1534 !important;
            }

            .setting-form-heading {
                font-size: 15px;
                font-weight: bold;
            }

            .new-orange-button{
            background-color: #F95520 !important;
            padding: 10px 20px 10px 20px;
            border-radius: 8px;
            color: white;
            border-color: transparent;
            cursor: pointer;
            font-weight: 800;
        }

        .new-orange-button:hover{
            color: white;
        }
        .card{
            background-color: #fff !important;
        }
        .text-orange {
    color: #f2661c !important;
}
.card{
    border: 2px solid #F95520 !important;
    border-radius: 10px;
}

.teaxt{
    background-color: #F4E3C7 !important;
}
.thead-light{
    color: #f2661c !important;
}
.border-style{
    border: 2px solid #F95520 !important;
}

        </style>
@endpush
<div>

    @if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


            <div class="table-responsive m-2">
                <table class="table table-flush " style="border-collapse: separate">
                    <thead class="thead-light ">
                    <tr class="">
                        @foreach(['SA', 'MA', 'JO', 'LU'] as $select_code)

                            <th class="text-center  border-style cursor-pointer {{ !empty($code) && in_array($select_code,$code) ? 'bg-success' : '' }}"
                                wire:click="selectCode('{{ $select_code }}')">
                                {{ strtoupper($select_code) }}
                            </th>
                        @endforeach
                    </tr>
                    <tr class="">
                        @foreach([ 'VEN', 'MER', 'SO'] as $select_code)

                            <th class="text-center  border-style cursor-pointer {{ !empty($code) && in_array($select_code,$code) ? 'bg-success' : '' }}"
                                wire:click="selectCode('{{ $select_code }}')">
                                {{ strtoupper($select_code) }}
                            </th>
                        @endforeach
                    </tr>
                  

                    

                  
                 
                    </thead>
                </table>
        </div>
        
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


        <div class="card-header">
            <h6 class="text-orange setting-form-heading py-2">Overview</h6>
            <textarea class="form-control teaxt" id="overview" wire:model="overview" rows="6" placeholder=""></textarea>
        </div>
        
        <div class="card-header">
            <h6 class="text-orange setting-form-heading py-2">Highest and Optimal Expression</h6>
            <textarea class="form-control teaxt" id="expression" wire:model="optimal" rows="6" placeholder=""></textarea>
        </div>
        
        <div class="card-header">
            <h6 class="text-orange setting-form-heading py-2">Optimization Hot Spots And Things To Recognize As Natural Triggers</h6>
            <textarea class="form-control teaxt" id="optimization" wire:model="optimization" rows="6" placeholder=""></textarea>
        </div>


        <div class="card-body d-sm-flex pt-0 justify-content-end">
            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="update"
                    class="mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end new-orange-button navButtonResponsive">
                update
                <span wire:loading wire:target="update" style="font-size: 8px;" class="swal2-loader">
                </span>
            </button>
        </div>


       

    </div> 
    
    
</div>


    @push('javascript')

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
        
            function initializeEditor(id) {
                const editorElement = document.getElementById(id);
                
                // Check if the editor is already initialized
                if (!editorElement || window[`editor_${id}`]) {
                    return;
                }
        
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
                        console.log(`CKEditor initialized for #${id}`);
                        window[`editor_${id}`] = editor; // Store editor instance
        
                        // Update Livewire model on change
                        editor.model.document.on('change:data', () => {
                            window.livewire.find('@this').set(id, editor.getData());
                        });
                    })
                    .catch(error => {
                        console.error(`Error initializing CKEditor for #${id}:`, error);
                    });
            }
        
            document.addEventListener("DOMContentLoaded", function () {
                initializeEditor('overview');
                initializeEditor('expression');
                initializeEditor('optimization');
            });
        
            document.addEventListener("livewire:load", function () {
                Livewire.hook('message.processed', (message, component) => {
                    initializeEditor('overview');
                    initializeEditor('expression');
                    initializeEditor('optimization');
                });
            });
        </script> --}}

        @endpush
        
        
