{!! $header !!}
<div class=" card profile">
    <div class="profile__img">
    </div>
    <div class="profile__info">
        <ul class="icon-list">
            <li style="color: black">
                <i class="zmdi zmdi-calendar-check"></i>{{ App\Term::getTermNameById($termId) . ' ' . trans('general.result') . ', ' . $year }}
            </li>
        </ul>
    </div>
</div>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped ">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('student_portal/result_portal.subject_title')</th>
            <th>@lang('student_portal/result_portal.subject_weight')</th>
            <th>@lang('student_portal/result_portal.coefficient')</th>
            @foreach($sequences as $sequence)
                <th>{{$sequence->sequence_name}}</th>
            @endforeach
            <th>@lang('student_portal/result_portal.final_mark')</th>
            <th>@lang('student_portal/result_portal.total')</th>

        </tr>
        </thead>
        <tbody>
        {!! $term_result !!}
        </tbody>
    </table>
</div>
