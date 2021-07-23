<h4 class="text-center">
    <b>{{ trans('settings/setting.mat_setting_title') }}</b></h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('settings/setting.matricule_initial')</th>
            <th>@lang('settings/setting.program_name')</th>

        </tr>
        </thead>
        <tbody>
        @foreach($matSettings as $matSetting)
        <tr>
            <td>{{ $matSetting->matricule_initial }}</td>
            <td>{{\App\Program::getCycleNameByCode($matSetting->programs_program_code)  }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
