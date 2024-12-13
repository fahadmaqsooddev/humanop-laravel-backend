<div class="row d-flex">
    @include('layouts.message')

    <form wire:submit.prevent="submitForm" class="mb-4">

        <div class="card-body pt-0">
            <label class="form-label" style="font-size: 18px; color: #1c3e6d">Thanks for being a Beta Tester! In
                the last 3 logins, do you have any constructive feedback of what’s working well or what
                could be improved?</label>
            <div class="form-group">
                <textarea class="form-control text-color-dark setting-box-background " style="color: #1c3e6d" cols="10"
                          rows="10" wire:model="comment"
                          name="comment" required></textarea>
                <div class="float-end text-sm" style="color: #1c3e6d">(Max:1000)</div>
            </div>

            <button type="submit" class=" btn-sm  float-end mt-4 mb-3 connection-btn" style="font-size: 16px !important;" >send feedback</button>
        </div>
    </form>
</div>
