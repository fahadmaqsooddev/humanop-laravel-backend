@push('css')
        <link rel="stylesheet" href="{{asset('js/rangerover/src/jquery.rangerover.css')}}">
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">

    <style>
            .ck-editor__editable_inline {
    background-color: #0f1534; /* Example: Change this to your desired background color */
    }
    .ck-editor__editable{
        background-color: #0f1534 !important;
    }
    .ck-editor{
        border-radius: 0 !important;
        width: 100% !important;
    }
            #ep_slider {
                width: 1000px;
                margin: 0 auto;
            }
            #pv_slider {
                width: 1000px;
                margin: 0 auto;
            }
            @media (min-width: 992px) and (max-width: 1200px) {
                #ep_slider {
                    width: 700px;
                    margin: 0 auto;
                }
                #pv_slider {
                    width: 700px;
                    margin: 0 auto;
                }
                #interval_of_life{
                    width: 50% !important;
                }
            }

            @media (min-width: 500px) and (max-width: 992px) {
                #ep_slider {
                    width: 400px;
                    margin: 0 auto;
                }
                #pv_slider {
                    width: 400px;
                    margin: 0 auto;
                }
                #interval_of_life{
                    width: 100% !important;
                }
            }

            @media (max-width: 500px)  {
                #ep_slider {
                    width: 300px;
                    margin: 0 auto;
                }
                #interval_of_life{
                    width: 100% !important;
                }
                #pv_slider {
                    width: 300px;
                    margin: 0 auto;
                }
            }

            .card{
             background-color: #1C365E !important;
           }

            .ck.ck-balloon-panel {
                z-index: 1050 !important;
            }

            .ck > p > a{
                color: blue !important;
            }
 .tags-container {
    display: flex;
    flex-wrap: wrap; 
    gap: 10px; 
    margin-bottom: -15px;
}

.tag {
 
    position: relative; 
    background-color: #1C365E; 
    color: white; 
    /* top: 1px; */
    padding: 10px 15px; 
    border-radius: 20px;
    display: inline-flex; 
    align-items: center; 
    font-size: 14px; 
    white-space: nowrap; 
    border: 1px solid #f2661c;
    box-shadow: 4px 4px 6px rgba(0, 0, 0, 0.4); 
}

.delete-btn {
    position: absolute; 
    top: -10px; 
    right: -10px; 
    background: #f2661c; 
    color: white;
    border: none; 
    border-radius: 50%; 
    width: 20px; 
    height: 20px; 
    font-size: 14px;
    cursor: pointer; 
    display: flex; 
    justify-content: center; 
    align-items: center; 
}

.delete-btn:hover {
    background: #f2661c; 
}

.tag span {
    margin-right: 5px; 
    
}


    </style>
@endpush
<div wire:ignore.self class="modal fade" id="roleModel" data-bs-focus="false"
     role="dialog"
     aria-labelledby="roleModel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                @include('layouts.message')
                <div class="card-body pt-0">
                    <label class="form-label fs-4 text-white"><span>Role template</span>
                        {{-- <span style="margin-left: 10px">
                            <input type="radio" name="subscription_type" value="Freemium" wire:model="subscription_type" wire:change="changeSubscriptionType"> Freemium
                        </span>
                        <span style="margin-left: 10px">
                              <input type="radio" name="subscription_type" value="Core" wire:model="subscription_type" wire:change="changeSubscriptionType"> Core
                        </span>
                        <span style="margin-left: 10px">
                              <input type="radio" name="subscription_type" value="Premium" wire:model="subscription_type" wire:change="changeSubscriptionType"> Premium
                        </span> --}}
                    </label>

                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                            aria-label="Close"  wire:click="resetForm">
                        <span aria-hidden="true">&times;</span>
                    </button>


{{-- 
<div class="tags-container">
    @foreach ($tags as $index => $tag)
        <div class="tag">
            <button type="button" class="delete-btn" wire:click="removeTag({{ $index }})">&times;</button>
            <span>{{ $tag }}</span>
        </div>
    @endforeach
</div>

<br>
<div class="col-md-6">
    <label for=""  class="form-label text-white">Add tasks</label>
    <input
    type="text"
    wire:model.defer="tagInput"
    wire:keydown.enter.prevent="addTag"
    class="form-control"
    style="background-color: #1C365E;color:white;"
    />
</div> --}}


                    <form wire:submit.prevent="updateTemplate">

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="col-md-6 mb-3" id="interval_of_life">
                                        
                                        <label for="" class="form-label text-white"> Role Name</label>
                                        <input type="text" name="role_name"  wire:model="role_name" class="form-control"
                                         style="background-color: #1C365E;color:white;" required>
                                    </div>
                                    
<div class="tags-container">
    @foreach ($tags as $index => $tag)
        <div class="tag">
            <button type="button" class="delete-btn" wire:click="removeTag({{ $index }})">&times;</button>
            <span>{{ $tag }}</span>
        </div>
    @endforeach
</div>

<br>
<div class="col-md-6 col-sm-12 mb-3">
    <label for=""  class="form-label text-white">Add tasks</label>
    <input
    type="text"
    wire:model.defer="tagInput"
    wire:keydown.enter.prevent="addTag"
    class="form-control"
    style="background-color: #1C365E;color:white;"
    />
    <span>Type and press ENTER to save keyword</span>
</div>

                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['SA', 'MA', 'JO', 'LU', 'VEN', 'MER', 'SO'] as $select_code)

                                                    <th class="text-center border border-white cursor-pointer {{ !empty($code) && in_array($select_code,$code) ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                    @foreach(['SA', 'MA', 'JO', 'LU', 'VEN', 'MER', 'SO'] as $select_code)
                                                        <th class="text-center border border-white cursor-pointer">
                                                            <div class="d-flex">
                                                                <select
                                                                        class="form-control bg-transparent text-white text-center"
                                                                        wire:model="min_point_array.{{ $select_code }}"
                                                                        style="border-color: white;padding: 0px !important;">
                                                                        <option value="0" style="color: black">0</option>
                                                                        <option value="1" style="color: black">1</option>
                                                                        <option value="2" style="color: black">2</option>
                                                                        <option value="3" style="color: black">3</option>
                                                                        <option value="4" style="color: black">4</option>
                                                                        <option value="5" style="color: black">5</option>
                                                                        <option value="6" style="color: black">6</option>
                                                                        <option value="7" style="color: black">7</option>
                                                                        <option value="8" style="color: black">8</option>
                                                                        <option value="9" style="color: black">9</option>
                                                                        <option value="10" style="color: black">10</option>
                                                                    </select>
                                                                <select
                                                                        class="form-control bg-transparent text-white text-center"
                                                                        wire:model="max_point_array.{{ $select_code }}"
                                                                        style="border-color: white;padding: 0px !important;">
                                                                        <option value="0" style="color: black">0</option>
                                                                        <option value="1" style="color: black">1</option>
                                                                        <option value="2" style="color: black">2</option>
                                                                        <option value="3" style="color: black">3</option>
                                                                        <option value="4" style="color: black">4</option>
                                                                        <option value="5" style="color: black">5</option>
                                                                        <option value="6" style="color: black">6</option>
                                                                        <option value="7" style="color: black">7</option>
                                                                        <option value="8" style="color: black">8</option>
                                                                        <option value="9" style="color: black">9</option>
                                                                        <option value="10" style="color: black">10</option>
                                                                    </select>
                                                            </div>
                                                        </th>
                                                    @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive ">
                                        <table class="table table-flush" style="border-collapse: separate;table-layout: fixed; width: 100%;">
                                            <thead class="thead-light"  >
                                            <tr style="">
                                                @foreach(['DE', 'DOM', 'FE', 'GRE', 'LUN', 'NAI', 'NE', 'POW', 'SP', 'TRA', 'VAN', 'WIL'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer  {{ !empty($code) && in_array($select_code,$code) ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')" >
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            <tr class="">
                                                @foreach(['DE', 'DOM', 'FE', 'GRE', 'LUN', 'NAI', 'NE', 'POW', 'SP', 'TRA', 'VAN', 'WIL'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer ">
                                                        <div class="d-flex">
                                                        <select class="form-control bg-transparent text-white text-center" wire:model="min_point_array.{{ $select_code }}" style="padding: 0px !important; border-color:white;width:100%;"  >
                                                            <option value="0" style="color: black">0</option>
                                                            <option value="1" style="color: black">1</option>
                                                            <option value="2" style="color: black">2</option>
                                                            <option value="3" style="color: black">3</option>
                                                            <option value="4" style="color: black">4</option>
                                                        </select>
                                                        <select class="form-control bg-transparent text-white text-center" wire:model="max_point_array.{{ $select_code }}" style="padding: 0px !important; border-color: white"  >
                                                            <option value="0" style="color: black">0</option>
                                                            <option value="1" style="color: black">1</option>
                                                            <option value="2" style="color: black">2</option>
                                                            <option value="3" style="color: black">3</option>
                                                            <option value="4" style="color: black">4</option>
                                                        </select>
                                                        </div>
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['G', 'S', 'C','GS','SG','SC','CS'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ !empty($code) && in_array($select_code,$code) ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}

                                                    </th>
                                                @endforeach
                                            </tr>

                                            <tr>
                                                @foreach(['G', 'S', 'C','GS','SG','SC','CS'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer "
                                                        >
                                                        @if($select_code == 'G')
                                                            <select class="form-control text-center bg-transparent text-white" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important;"  >
                                                                <option value="502" style="color: black">502</option>
                                                                <option value="511" style="color: black">511</option>
                                                                <option value="520" style="color: black">520</option>
                                                                <option value="601" style="color: black">601</option>
                                                                <option value="610" style="color: black">610</option>
                                                                <option value="700" style="color: black">700</option>
                                                            </select>
                                                        @endif
                                                        @if($select_code == 'GS')
                                                            <select class="form-control text-center bg-transparent text-white" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important;"  >
                                                                <option value="403" style="color: black">403</option>
                                                                <option value="412" style="color: black">412</option>
                                                                <option value="421" style="color: black">421</option>
                                                                <option value="430" style="color: black">430</option>
                                                            </select>
                                                        @endif


                                                        @if($select_code == 'SG')
                                                            <select class="form-control text-center bg-transparent text-white" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important;"  >
                                                                <option value="322" style="color: black">322</option>
                                                                <option value="331" style="color: black">331</option>
                                                                <option value="340" style="color: black">340</option>
                                                            </select>
                                                        @endif

                                                        @if($select_code == 'S')
                                                            <select class="form-control text-center bg-transparent text-white" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important;"  >
                                                                <option value="52" style="color: black">52</option>
                                                                <option value="61" style="color: black">61</option>
                                                                <option value="70" style="color: black">70</option>
                                                                <option value="142" style="color: black">142</option>
                                                                <option value="151" style="color: black">151</option>
                                                                <option value="160" style="color: black">160</option>
                                                                <option value="241" style="color: black">241</option>
                                                                <option value="232" style="color: black">232</option>
                                                                <option value="250" style="color: black">250</option>
                                                                <option value="304" style="color: black">304</option>
                                                                <option value="313" style="color: black">313</option>
                                                            </select>
                                                        @endif

                                                        @if($select_code == 'SC')
                                                            <select class="form-control text-center bg-transparent text-white" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important;"  >
                                                                <option value="43" style="color: black">43</option>
                                                                <option value="133" style="color: black">133</option>
                                                                <option value="214" style="color: black">214</option>
                                                                <option value="223" style="color: black">223</option>
                                                            </select>
                                                        @endif
                                                        @if($select_code == 'CS')
                                                            <select class="form-control text-center bg-transparent text-white" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important;"  >
                                                                <option value="34" style="color: black">34</option>
                                                                <option value="115" style="color: black">115</option>
                                                                <option value="124" style="color: black">124</option>
                                                            </select>
                                                        @endif
                                                        @if($select_code == 'C')
                                                            <select class="form-control text-center bg-transparent text-white" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important;"  >
                                                                <option value="7" style="color: black">7</option>
                                                                <option value="16" style="color: black">16</option>
                                                                <option value="25" style="color: black">25</option>
                                                                <option value="106" style="color: black">106</option>
                                                                <option value="205" style="color: black">205</option>
                                                            </select>
                                                        @endif
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['EM', 'INS', 'INT', 'MOV'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ !empty($code) && in_array($select_code,$code) ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>

                                            <tr>
                                                @foreach(['EM', 'INS', 'INT', 'MOV'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer "
                                                        >

                                                        <select class="form-control bg-transparent text-white text-center" wire:model="min_point_array.{{ $select_code }}" style="border-color: white !important;padding: 0px !important"  >
                                                            <option value="3" style="color: black">3</option>
                                                            <option value="4" style="color: black">4</option>
                                                            <option value="5" style="color: black">5</option>
                                                            <option value="6" style="color: black">6</option>
                                                            <option value="7" style="color: black">7</option>
                                                            <option value="8" style="color: black">8</option>
                                                            <option value="9" style="color: black">9</option>
                                                            <option value="10" style="color: black">10</option>
                                                            <option value="11" style="color: black">11</option>
                                                            <option value="12" style="color: black">12</option>
                                                            <option value="13" style="color: black">13</option>
                                                            <option value="14" style="color: black">14</option>
                                                            <option value="15" style="color: black">15</option>
                                                            <option value="16" style="color: black">16</option>
                                                        </select>
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5 class="text-white">PV</h5>
                        </div>
                        <div id="pv_slider" wire:ignore ></div>
                        <div class="d-flex justify-content-center mt-5">
                            <h5 class="text-white">EP</h5>
                        </div>
                        <div id="ep_slider" wire:ignore ></div>
{{--                        <div class="row mt-4">--}}
{{--                            <div class="col-12">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="table-responsive">--}}
{{--                                        <table class="table table-flush" style="border-collapse: separate">--}}
{{--                                            <thead class="thead-light">--}}
{{--                                            <tr>--}}
{{--                                                @foreach(['+', '-', 'PV', 'EP'] as $select_code)--}}
{{--                                                    <th class="text-center border border-white cursor-pointer {{ $code == $select_code ? 'bg-success' : '' }}"--}}
{{--                                                        wire:click="selectCode('{{ $select_code }}')">--}}
{{--                                                        {{ strtoupper($select_code) }}--}}
{{--                                                    </th>--}}
{{--                                                @endforeach--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}
{{--                                        </table>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{-- <div class="row mt-5">
                            <div class="col-12">
                                <label class="form-label text-white">Title</label>
                                <div class="input-group">
                                    <input id="firstName" wire:model="title" name="title"
                                           class="form-control table-header-text text-white" style="background-color: #0f1534 !important;" type="text">
                                    <input id="code" wire:model="code" name="code"
                                           class="form-control table-header-text" type="hidden">
                                </div>
                            </div>
                        </div> --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                {{-- <label class="form-label text-white">Description</label> --}}
                                {{-- <div class="input-group w-100" wire:ignore >
                             <textarea class="form-control table-header-text" id="editor" rows="5" cols="5"
                                    name="description"
                                    wire:model="description"></textarea>
                                </div> --}}
                                @if($template_id)
                                <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                        style="background-color: #f2661c">Update Role
                                </button>
                                @else
                                    <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                            style="background-color: #f2661c">Save Role
                                    </button>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript" src="{{asset('js/rangerover/src/jquery.rangerover.js')}}"></script>
    <script type="importmap">
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

        // Function to initialize CKEditor for a specific textarea by ID
        let editorInstance;
            const editorElement = document.getElementById('editor');
            if (editorElement && !editorElement.classList.contains('ck-editor')) { // Check if not already initialized
                ClassicEditor
                    .create(editorElement, {
                        plugins: [ Essentials, Paragraph, Bold, Italic, Font ,List, Link, AutoLink ],
                        toolbar: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                            'bulletedList', 'numberedList', 'link'  // Add list options to toolbar
                        ]
                    })
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                        @this.set('description', editor.getData());
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
        $('.createForm').on('click', function() {
            if (editorInstance) {
                editorInstance.setData('');
            }
        });

        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                $('#roleModel').modal('hide');
            });

        });

    </script>

    <script type="text/javascript">
        $(document).ready(function (){
            let pv_data = {
                start: 0,
            };

            let ep_data = {
                start: 1,
            };
            var ep_slider = $("#ep_slider").rangeRover({
                range: false,
                mode: 'categorized',
                autocalculate:true,
                data: [
                    {
                        start: 1,
                        end: 25,
                        color: '#e0e0ff',
                    },
                    {
                        start: 25,
                        end: 30,
                        color: '#b4f2de',
                    },
                    {
                        start: 30,
                        end: 35,
                        color: '#c0e9ff',
                    },
                    {
                        start: 35,
                        end: 101,
                        color:'#fa99e7',
                    },
                ],
                onChange : function(val) {
                  @this.set('ep', val.start.value);
                }
            });

           var pv_slider = $("#pv_slider").rangeRover({
                range: false,
                mode: 'categorized',
                autocalculate:true,
                data: [
                    {
                        start: -30,
                        end: -8,
                        color: '#e0e0ff',
                    },
                    {
                        start: -8,
                        end: -4,
                        color: '#b4f2de',
                    },
                    {
                        start: -4,
                        end: 0,
                        color: '#c0e9ff',
                    },
                    {
                        start: 0,
                        end: 1,
                        color: '#d6d6d6',
                    },
                    {
                        start: 1,
                        end: 3,
                        color: '#c0e9ff',
                    },
                    {
                        start: 3,
                        end: 7,
                        color: '#fdd3bc',
                    },
                    {
                        start: 7,
                        end: 12,
                        color: '#ffcfd5',
                    },
                    {
                        start: 12,
                        end: 41,
                        color: '#fa99e7',
                    }],
                onChange : function(val) {
                @this.set('pv', val.start.value);

                }
            });

            function resetPv(){
                pv_slider.select(pv_data,'pv');
            }

            function resetEp(){
                ep_slider.select(ep_data,'ep');
            }

            $('.createForm').on('click',function(){
                setTimeout(function(){
                    resetPv();
                    resetEp();
                },1000);
            });
            window.livewire.on('emptyPv', () => {
                resetPv();
            });
            window.livewire.on('emptyEp', () => {
                resetEp();
            });

            Livewire.on('sliderUpdated', (code,point) => {
                    let data = {
                        start: point,
                    };
                    if (code == 'ep') {
                        ep_slider.select(data, 'ep');
                    }else if (code == 'pv') {
                        pv_slider.select(data, 'pv');
                    }else {
                        resetEp();
                        resetPv();
                    }

            });

        });
   

        $(document).ready(function(){
            $('#roleModel').on('hidden.bs.modal',function(){
                Livewire.emit('resetForm');
            });
        });

   

 

    </script>


@endpush
