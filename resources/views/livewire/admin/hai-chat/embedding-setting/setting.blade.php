<div class="flex-column">
    @include('layouts.message')

    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
        <i class="bi bi-graph-up"></i>
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="fw-bold main-heading">Chunks</div>
        </div>

        <form wire:submit.prevent="submitForm" style="width: 100%">
            <div>
                <select style="background-color: #f3deba"
                        class="form-control text-color-dark "
                        wire:model.defer="chunk">
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <div class="d-flex float-end">
                    <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                            class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                        save
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
