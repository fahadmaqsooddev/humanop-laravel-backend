<div wire:ignore.self class="modal fade" id="dailyTipModel" tabindex="-1"
     role="dialog"
     aria-labelledby="dailyTipModel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                <div class="card-body pt-0">
                    <label class="form-label fs-4 text-white">Daily Tip</label>

                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form wire:submit.prevent="updateTip">
                        @include('layouts.message')
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
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
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive ">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
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
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['g', 's', 'c'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
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
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['em', 'ins', 'int', 'mov'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
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
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['+', '-', 'pv', 'ep'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
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
                            <div class="col-12">
                                <label class="form-label text-white">Title</label>
                                <div class="input-group">
                                    <input id="firstName" wire:model="title" name="title"
                                           class="form-control table-header-text" type="text">
                                    <input id="code" wire:model="code" name="code"
                                           class="form-control table-header-text" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-white">Description</label>
                                <div class="input-group">
                             <textarea class="form-control table-header-text" rows="5" cols="5"
                              name="description"
                              wire:model="description"></textarea>
                                </div>
                                @if($tip_id)
                                <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                        style="background-color: #f2661c">Update Tip
                                </button>

                                @else
                                    <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                            style="background-color: #f2661c">Add Tip
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
