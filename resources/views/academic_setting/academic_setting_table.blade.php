<h4 class="text-center">
    <b>{{ trans('academic_setting/academic_setting.academic_setting_title') }}</b></h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('academic_setting/academic_setting.sequence_name')</th>
            <th>@lang('academic_setting/academic_setting.term_name')</th>
            <th>@lang('academic_setting/academic_setting.select_publish_date')</th>
            <th>@lang('academic_setting/academic_setting.academic_year')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($academicSettings as $academicSetting)
            <tr>
                <td>{{ \App\Sequence::getSequenceNameById($academicSetting->sequences_sequence_id) }}</td>
                <td>{{ \App\Term::getTermNameBySequenceId($academicSetting->sequences_sequence_id)  }}</td>
                <td>{{date('F d,Y' ,strtotime($academicSetting->publish_date)) }}</td>
                <td>{{$academicSetting->academic_year }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
