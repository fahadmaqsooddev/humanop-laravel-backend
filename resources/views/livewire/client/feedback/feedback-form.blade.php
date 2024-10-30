<div class="row d-flex">
    @include('layouts.message')

    <form wire:submit.prevent="submitForm" class="mb-4">

        <div class="card-body pt-0">
            <label class="form-label text-white" style="font-size: 18px">Thanks for being a Beta Tester! In
                the last 3 logins, do you have any constructive feedback of what’s working well or what
                could be improved?</label>
            <div class="form-group">
                <textarea class="form-control text-color-dark setting-box-background text-white" cols="10"
                          rows="10" wire:model="comment"
                          name="comment"></textarea>
            </div>
            <button type="submit" class=" btn-sm  float-end mt-4 mb-3 rainbow-border-user-nav-btn" >send feedback</button>
        </div>
    </form>
</div>
