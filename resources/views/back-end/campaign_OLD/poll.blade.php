<table class="table">
    <thead>
        <tr>
            <th>Options</th>
            <th>Voted</th>
        </tr>
    </thead>
    <tbody>
        @foreach($poll as $vale)
        <tr class="border-dashed">
            <td><a href="#" data-type="text" data-inputclass="form-control" data-pk="1" data-title="{{ $vale->options }}">{{ $vale->options }}</a></td>
            <td>{{  App\Models\Survey::where(['options' => $vale->id, 'campaign_id' => $vale->campaign_id])->count() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

