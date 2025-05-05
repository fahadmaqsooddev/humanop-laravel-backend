@push('css')
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

        .card{
            background-color: #1C365E !important;
        }

        .ck.ck-balloon-panel {
            z-index: 1050 !important;
        }

        .ck > p > a{
            color: blue !important;
        }
    </style>
@endpush

<div class="row mt-4 container-fluid">
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    @if (!empty($versionId))
                    <h5 class="mb-0 text-white">Edit Version Control</h5>
                    @else
                    <h5 class="mb-0 text-white">Create Version Control</h5>
                    @endif
                </div>
                <div class="card-body pt-0">
                  
                    <form wire:submit.prevent="storeVersionAndDescription" style="">
                        <!-- Version Field -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-white">Version</label>
                                <input style="background-color: #0f1534;color:white;" wire:model="version"
                                       class="form-control table-header-text" type="text">
                                       @error('version') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-white">Note</label>
                                <div wire:ignore>
                                    <textarea id="noteEditor" class="form-control table-header-text"
                                        style="background-color: #0f1534;color:white;"
                                        rows="2"></textarea>
                                </div>
                                @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="text-end mt-3">
                            <button type="button" class="btn text-white fw-bolder" style="background-color: #f2661c" wire:click="addVersionField">
                                <span style="font-weight: bolder;font-size:1rem;">+</span>
                            </button>
                        </div>
                       
                        @foreach ($versionDetails as $index => $detail)
                        <div class="row mb-3">
                            <!-- Version Type Checkboxes -->
                            <div class="col-md-12 mb-3">
                                <label class="text-white mb-1">Select Type</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                       type="checkbox"
                                       wire:model="versionDetails.{{ $index }}.type"
                                       value="Web"
                                       id="web_{{ $index }}">
                                    <label class="form-check-label text-white" for="web_{{ $index }}">Web</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                       type="checkbox"
                                       wire:model="versionDetails.{{ $index }}.type"
                                       value="App"
                                       id="app_{{ $index }}">
                                    <label class="form-check-label text-white" for="app_{{ $index }}">App</label>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div class="col-md-12">
                                <label class="text-white mb-1">Description</label>
                                <div wire:ignore>
                                    <textarea id="descriptionEditor{{ $index }}" class="form-control" 
                                        style="background-color: #0f1534; color: white;" 
                                        rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mt-3">
                                <label for="version_heading" class="form-label text-white">Select Version Heading</label>
                                <select wire:model='versionDetails.{{ $index }}.version_heading' 
                                    style="background-color: #0f1534;" class="form-control text-white">
                                    <option value="">Select Option</option>
                                    <option value="0">Issue Fixed</option>
                                    <option value="1">New Feature</option>
                                </select>
                            </div>
                            
                            @if (count($versionDetails) > 1)
                            <div class="col-md-2 d-flex align-items-end mt-2">
                                <button type="button" class="btn btn-danger btn-sm"
                                    wire:click="removeVersionField({{ $index }})">
                                    <span>-</span>
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                        
                        <!-- Submit Button -->
                        @if(!empty($versionId))
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm text-white" style="background-color: #f2661c;">
                                Update Version
                            </button>
                        </div>
                        @else
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm text-white" style="background-color: #f2661c;">
                                Save Version
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Use a more reliable CDN for CKEditor -->
<script src="https://cdn.jsdelivr.net/npm/ckeditor5@43.2.0/build/classic/ckeditor.min.js" defer></script>

<script>
    // Global store for editor instances
    let editors = {};
    
    document.addEventListener('DOMContentLoaded', function() {
        // Load CKEditor dynamically to ensure it's available
        loadCKEditor();
    });
    
    function loadCKEditor() {
        // Check if CKEditor is already loaded
        if (typeof ClassicEditor !== 'undefined') {
            console.log('CKEditor already loaded, initializing editors');
            initializeEditors();
            return;
        }
        
        console.log('Loading CKEditor dynamically');
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/ckeditor5@38.1.0/build/classic/ckeditor.min.js'; // Try a different version
        script.onload = function() {
            console.log('CKEditor loaded successfully');
            initializeEditors();
        };
        script.onerror = function() {
            console.error('Failed to load CKEditor, trying fallback');
            const fallbackScript = document.createElement('script');
            fallbackScript.src = 'https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js'; // Fallback to an older version
            fallbackScript.onload = function() {
                console.log('Fallback CKEditor loaded');
                initializeEditors();
            };
            document.head.appendChild(fallbackScript);
        };
        document.head.appendChild(script);
    }
    
    function initializeEditors() {
        if (typeof ClassicEditor === 'undefined') {
            console.error('CKEditor is still not available');
            return;
        }
        
        // Initialize note editor
        initializeNoteEditor();
        
        // Initialize description editors
        initializeDescriptionEditors();
        
        // Listen for new version fields
        Livewire.hook('message.processed', (message, component) => {
            console.log('Livewire message processed, checking for new editors');
            initializeDescriptionEditors();
        });
    }
    
    function initializeNoteEditor() {
        const noteEl = document.getElementById('noteEditor');
        if (!noteEl) return;
        
        // Skip if already initialized
        if (editors['note']) return;
        
        ClassicEditor
            .create(noteEl)
            .then(editor => {
                console.log('Note editor initialized');
                editors['note'] = editor;
                
                // Handle content changes
                editor.model.document.on('change:data', () => {
                    const data = editor.getData();
                    console.log('Note content changed:', data);
                    // Use debounce to prevent too many updates
                    clearTimeout(window.noteUpdateTimeout);
                    window.noteUpdateTimeout = setTimeout(() => {
                        @this.set('note', data);
                    }, 300);
                });
                
                // Set initial data if available
                if (@this.get('note')) {
                    editor.setData(@this.get('note'));
                }
            })
            .catch(error => console.error('Note editor initialization error:', error));
    }
    
    function initializeDescriptionEditors() {
        document.querySelectorAll('[id^="descriptionEditor"]').forEach(el => {
            const index = el.id.replace('descriptionEditor', '');
            const editorKey = `desc-${index}`;
            
            // Skip if already initialized
            if (editors[editorKey]) return;
            
            ClassicEditor
                .create(el)
                .then(editor => {
                    console.log(`Description editor ${index} initialized`);
                    editors[editorKey] = editor;
                    
                    // Handle content changes
                    editor.model.document.on('change:data', () => {
                        const data = editor.getData();
                       
                        window[`descUpdateTimeout${index}`] = setTimeout(() => {
                            @this.set(`versionDetails.${index}.description`, data);
                        }, 300);
                    });
                    
                    // Set initial data if available
                    const initialData = @this.get(`versionDetails.${index}.description`);
                    if (initialData) {
                        editor.setData(initialData);
                    }
                })
                .catch(error => console.error(`Description editor ${index} initialization error:`, error));
        });
    }
    
    // Handle Livewire events
    document.addEventListener('livewire:load', function() {
        console.log('Livewire loaded');
        
        // Re-check for CKEditor after Livewire loads
        if (typeof ClassicEditor === 'undefined') {
            loadCKEditor();
        } else {
            initializeEditors();
        }
        
        // Handle modal closing
        Livewire.on('closeModal', () => {
            const modalElement = document.getElementById('versionModel');
            if (modalElement) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });
    });
    
    // Reset form when modal is hidden
    $(document).ready(function() {
        $('#versionModel').on('hidden.bs.modal', function() {
            Livewire.emit('emptyVersionControlValues');
        });
    });
</script>
@endpush