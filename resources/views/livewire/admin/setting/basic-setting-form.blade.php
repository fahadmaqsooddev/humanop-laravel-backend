
<div class="card setting-box-background mt-4" id="basic-info">
    <style>
        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }
    </style>
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">Basic Info</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" >
        <input type="hidden" wire:model.defer="currentUser.id">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-6">
                    <label class="form-label text-color-dark">First Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="firstName"
                               wire:model.defer="currentUser.first_name"
                               class="form-control text-color-dark setting-box-background" type="text">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label text-color-dark">Last Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="lastName" wire:model.defer="currentUser.last_name"
                               class="form-control text-color-dark setting-box-background" type="text" placeholder="{{$currentUser['last_name']}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-4 text-color-dark">Email</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="email" wire:model.defer="currentUser.email"
                               class="form-control text-color-dark setting-box-background" type="email" placeholder="{{$currentUser['email']}}">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label mt-4 text-color-dark">Phone Number</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="phone" wire:model.defer="currentUser.phone"
                               class="form-control text-color-dark setting-box-background" type="text" placeholder="Phone #">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4 col-6 w-50">
                    <label class="form-label mt-4 text-color-dark">I'm</label>
                    <select style="background-color: #0f1535" class="form-control text-color-dark setting-box-background" wire:model.defer="currentUser.gender" >
                        <option value="0">Male [XY]</option>
                        <option value="1">Female [XX]</option>
                    </select>
                </div>

                <div class="col-sm-4 col-6 w-50">
                    <label for="name" class="text-color-dark mt-4">Date of Birth</label>

                    <div class="d-flex w-100">

                        <?php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                            'August', 'September', 'October', 'November', 'December'];

                        $current_year = (int)(\Carbon\Carbon::now()->year - 7);
                        ?>

                        <select class="justify-content-center form-control m-1 setting-box-background text-color-dark" wire:model="month"
                                style="border-radius: 12px;">
                            <option value="">Month</option>
                            @foreach($months as $key => $month)
                                <option value="{{$key + 1}}">{{$month}}</option>
                            @endforeach
                        </select>

                        <select class="justify-content-center form-control m-1 setting-box-background text-color-dark" wire:model="day"
                                style="border-radius: 12px;">
                            <option value="">Day</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>

                        <select class="justify-content-center form-control m-1 setting-box-background text-color-dark" wire:model="year"
                                style=" border-radius: 12px;">
                            <option value="">Year</option>
                            @for($i = $current_year; $i >= 1900; $i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>

                    </div>

                    @error('date_of_birth')
                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-md-12 col-sm-12">
                    <label class="form-label mt-4 text-color-dark">Profile Image</label>
                    <input type="file" class="form-control text-color-dark setting-box-background profileImage">
                </div>


            </div>
            <button type="submit" class=" btn-sm float-end mt-4 mb-4" style="background:#f2661c !important;color:white;font-weight:bolder;border:none;">
                Update Info
            </button>
        </div>

    </form>

    {{--  Profile image cropper model   --}}
    <div class="modal fade" id="profileImageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <label class="form-label fs-4 text-white">Update User Profile</label>
                    <button type="button" class="close modal-close-btn closeCropperModel" data-bs-dismiss="modal"
                            aria-label="Close" id="close-info-modal-button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <!--  default image where we will set the src via jquery-->
                                <img id="image">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 float-end">
                        <button type="button" class="btn btn-secondary closeCropperModel" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-info-modal-button" >Cancel</button>
                        <button type="button" class="btn" style="background-color: #f2661c; color: white" id="crop">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@push('javascript')
    <script>

        $('.closeCropperModel').on('click', function () {

            $('.profileImage').val('');

        });

        var image = document.getElementById('profileImageModal');

        var bs_modal = $('#profileImageModal');
        var image = document.getElementById('image'); // Correct reference to the image element
        var cropper, reader, file;

        $(".profileImage").on("change", function (e) {
            console.log('image model open');
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                bs_modal.modal('show');
            };

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        bs_modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        function base64ToFile(base64, filename, mimeType) {
            const byteString = atob(base64.split(',')[1]); // Decode base64 string
            const arrayBuffer = new ArrayBuffer(byteString.length);
            const uint8Array = new Uint8Array(arrayBuffer);

            for (let i = 0; i < byteString.length; i++) {
                uint8Array[i] = byteString.charCodeAt(i);
            }

            return new File([uint8Array], filename, {type: mimeType});
        }

        $("#crop").click(function () {
            if (cropper) {
                var canvas = cropper.getCroppedCanvas({
                    width: 160,
                    height: 160,
                });

                canvas.toBlob(function (blob) {
                    const reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        const base64data = reader.result;

                        // Convert base64data to a File object
                        const file = base64ToFile(base64data, 'cropped-image.png', 'image/png');

                        // Create a FormData object
                        const formData = new FormData();
                        formData.append('image', file);

                        $.ajax({
                            type: "POST",
                            url: '{{ route("admin_user_profile_image") }}',
                            headers: {
                                'X-CSRF-TOKEN': "{{csrf_token()}}"
                            },
                            data: formData, // Pass the FormData object directly
                            processData: false, // Prevent jQuery from processing the data
                            contentType: false, // Prevent jQuery from setting the content type
                            success: function (data) {
                                $('.user_profile_image').attr('src', data.url.url);
                                bs_modal.modal('hide');
                            },
                            error: function () {
                                console.error("Image upload failed");
                            }
                        });
                    };
                });
            }
        });

    </script>
@endpush
