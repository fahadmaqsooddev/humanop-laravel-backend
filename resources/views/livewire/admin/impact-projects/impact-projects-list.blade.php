<div>
    @livewire('admin.impact-projects.add-impact-project')
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush text-center">
            <thead class="thead-light">
             <tr class="text-color-blue">
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>HP Required</th>
                    <th>Verification Text</th>
                    <th>Status</th>    
                </tr>
            </thead>
            <tbody style="color:black">
                @foreach($impact_projects as $index => $project)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->description }}</td>
                        <td>{{ $project->hp_required }}</td>
                        <td>{{ $project->verification_text ?? '-' }}</td>
                        <td>{{ $project->status ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @endforeach

                @if($impact_projects->isEmpty())
                    <tr>
                        <td colspan="10" class="text-center">No Impact Projects found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>