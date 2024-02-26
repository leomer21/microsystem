
@if(isset($poll))
    <table class="table">
    <thead>
        <tr>
            <th>Options</th>
            <th>Votes</th>
        </tr>
    </thead>
    <tbody>
    @foreach($poll as $vale)
    <tr class="border-dashed">
        <td><a href="#" data-type="text" data-inputclass="form-control" data-pk="1" data-title="{{ $vale->options }}">{{ $vale->options }}</a></td>
        <td>{{  App\Models\Survey::where(['options' => $vale->id, 'campaign_id' => $vale->campaign_id])->count() }}</td>
    </tr>
    @endforeach
@elseif(isset($rating))
    <table class="table">
    <thead>
        <tr>
            <th>Stars</th>
            <th>Votes</th>
        </tr>
    </thead>
    <tbody>
    <?php $ratingArray=[0.5,1,1.5,2,2.5,3,3.5,4,4.5,5]; ?>
    @foreach ($ratingArray as $rate)

        <tr class="border-dashed">
            <td><a href="#" data-type="text" data-inputclass="form-control" data-pk="1" data-title="{{ $rate }}">{{ $rate }}</a></td>
            <td>{{ App\Models\CampaignsStatisticsDays::where(['campaign_id' => $campaignID, 'survey_id' => $rate])->count() }}</td>
        </tr>
        
    @endforeach    
@endif
    </tbody>
</table>

