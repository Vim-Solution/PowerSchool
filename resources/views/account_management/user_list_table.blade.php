
<h4 class="text-center">
    <b>{{ trans('account_management/user_list.user_list_title') }}</b>
</h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped" style="width: 100%;">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('account_management/user_list.full_name')</th>
            <th>@lang('account_management/user_list.email')</th>
            <th>@lang('account_management/user_list.position')</th>
            <th>@lang('account_management/user_list.address')</th>
            <th>@lang('account_management/user_list.phone_number')</th>
            <th>@lang('account_management/user_list.office_address')</th>
            <th>@lang('account_management/user_list.suspension_state')</th>
            <th>@lang('account_management/user_list.date_created')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->full_name }}</td>
                <td>{{$user->email }}</td>
                <td>{{ $user->position  }}</td>
                <td>{{ $user->address }}</td>
                <td>{{$user->phone_number }}</td>
                <td>{{$user->office_address }}</td>
                <td style="color: red;">
                    @if($user->academic_state == 0)
                        {{ trans('account_management/user_list.suspended') }}
                        @else
                        {{ trans('account_management/user_list.not_suspended') }}
                    @endif
                </td>
                <td> {{ date('F d,Y',strtotime($user->created_at)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div><br>

