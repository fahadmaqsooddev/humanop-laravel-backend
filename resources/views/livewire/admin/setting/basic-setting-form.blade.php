<div class="card mt-4" id="basic-info">
    <div class="card-header">
        <h5>Basic Info</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" >
        <input type="hidden" wire:model.defer="currentUser.id">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-6">
                    <label class="form-label text-white">First Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="firstName"
                               wire:model.defer="currentUser.first_name"
                               class="form-control" type="text">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label text-white">Last Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="lastName" wire:model.defer="currentUser.last_name"
                               class="form-control" type="text" placeholder="{{$currentUser['last_name']}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-4">Email</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="email" wire:model.defer="currentUser.email"
                               class="form-control" type="email" placeholder="{{$currentUser['email']}}">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label mt-4">Phone Number</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="phone" wire:model.defer="currentUser.phone"
                               class="form-control" type="number" placeholder="{{$currentUser['phone']}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4 col-6 w-50">
                    <label class="form-label mt-4">I'm</label>
                    <select class="form-control" wire:model.defer="currentUser.gender" >
                        <option value="2">Male</option>
                        <option value="1">Female</option>
                    </select>
                </div>

                <div class="col-sm-4 col-6 w-50">
                    <label class="form-label mt-4">Age Group</label>
                    <select class="form-control" wire:model.defer="currentUser.age_range" >
                        <option value="5-6">5-6</option>
                        <option value="7-11">7-11</option>
                        <option value="12-15">12-15</option>
                        <option value="16-20">16-20</option>
                        <option value="21-29">21-29</option>
                        <option value="30-33">30-33</option>
                        <option value="34-42">34-42</option>
                        <option value="43-51">43-51</option>
                        <option value="52-65">52-65</option>
                        <option value="66-69">66-69</option>
                        <option value="70-74">70-74</option>
                        <option value="75-83">75-83</option>
                        <option value="84-93">84-93</option>
                        <option value="94-101">94&up</option>
                    </select>
                </div>


            </div>
            <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white" style="background-color: #f2661c">Update Info</button>
        </div>

    </form>
</div>
