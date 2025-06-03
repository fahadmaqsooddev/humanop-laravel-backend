<form wire:submit.prevent="updateTip">
    @include('layouts.message')
    <div class="row mt-4">
        <div class="col-6">
            <div class="card">
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" style="border-collapse: separate">
                        <thead class="thead-light">
                        <tr>
                            @foreach(['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'] as $select_code)
                                <th class="text-center border cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}" style="color: #1b3a62"
                                    wire:click="selectCode('{{ $select_code }}')">
                                    {{ strtoupper($select_code) }}
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
        <div class="col-11">
            <div class="card">
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" style="border-collapse: separate">
                        <thead class="thead-light">
                        <tr>
                            @foreach(['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'] as $select_code)
                                <th class="text-center border cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}" style="color: #1b3a62"
                                    wire:click="selectCode('{{ $select_code }}')">
                                    {{ strtoupper($select_code) }}
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
        <div class="col-4">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-flush" style="border-collapse: separate">
                        <thead class="thead-light">
                        <tr>
                            @foreach(['g', 's', 'c'] as $select_code)
                                <th class="text-center border cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}" style="color: #1b3a62"
                                    wire:click="selectCode('{{ $select_code }}')">
                                    {{ strtoupper($select_code) }}
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
        <div class="col-4">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-flush" style="border-collapse: separate">
                        <thead class="thead-light">
                        <tr>
                            @foreach(['em', 'ins', 'int', 'mov'] as $select_code)
                                <th class="text-center border cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}" style="color: #1b3a62"
                                    wire:click="selectCode('{{ $select_code }}')">
                                    {{ strtoupper($select_code) }}
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
        <div class="col-4">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-flush" style="border-collapse: separate">
                        <thead class="thead-light">
                        <tr>
                            @foreach(['+', '-', 'pv', 'ep'] as $select_code)
                                <th class="text-center border cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}" style="color: #1b3a62"
                                    wire:click="selectCode('{{ $select_code }}')">
                                    {{ strtoupper($select_code) }}
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
        <div class="col-6">
            <label class="form-label text-white">Title</label>
            <div class="input-group">
                <input id="firstName" wire:model="title" name="title"
                       class="form-control text-white table-header-text" type="text">
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-8">
            <label class="form-label text-white">Description</label>
            <div class="input-group">
                    <textarea class="form-control text-white table-header-text" rows="5" cols="5"
                              name="description"
                              wire:model="description"></textarea>
            </div>
            <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                    style="background-color: #1b3a62">Update Info
            </button>
        </div>

    </div>
</form>
