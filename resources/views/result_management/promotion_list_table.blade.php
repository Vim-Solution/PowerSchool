@if(!empty($promotion_records))
    <h5 class="text-center">
        <b>{{ trans('result_management/auto_promotion.student_list_title',['year' => $academicYear])  }}</b>
    </h5>
    <br>

    <div class="table-responsive">
        <table id="@lang('general.data_table')" class="table table-striped">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('result_management/auto_promotion.matricule')</th>
                <th>@lang('result_management/auto_promotion.full_name')</th>
                <th>@lang('result_management/auto_promotion.present_class')</th>
                <th>@lang('result_management/auto_promotion.next_class')</th>
                <th>@lang('result_management/auto_promotion.academic_stand')</th>
                <th>@lang('result_management/auto_promotion.annual_average')</th>
                <th>@lang('result_management/auto_promotion.action')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($promotion_records as $promotion_record)
                <tr>
                    <td>{{$promotion_record['matricule'] }}</td>
                    <td>{{$promotion_record['full_name'] }}</td>
                    <td>{{$promotion_record['present_class'] }}</td>
                    <td>{{$promotion_record['promotion_class'] }}</td>
                    <td>{{$promotion_record['promotion_state'] }}</td>
                    @if($promotion_record['annual_average'] < 20)
                        <td style="color: red">{{$promotion_record['annual_average']  . '/' . 20}}</td>
                    @else
                        <td>{{$promotion_record['annual_average']  . '/' . 20}}</td>

                    @endif
                    <td>
                        <div class="row">
                            <div class="">
                            <a href="{{trans('settings/routes.repeat_class') . '/' . \App\Encrypter::encrypt($promotion_record['matricule']) . '/' . \App\Encrypter::encrypt($academicYear) }}"
                               class="btn bg-red"><h6 class="text-white" style="font-size: 12px;"><i
                                        class="zmdi zmdi-refresh-sync"></i>{{trans('result_management/auto_promotion.repeat_class')}}
                                </h6></a>
                            </div>
                            <div style="position: relative;left: 2%;">
                            <a href="{{trans('settings/routes.promote_student') . '/' . \App\Encrypter::encrypt($promotion_record['matricule']) . '/' . \App\Encrypter::encrypt($academicYear) }}"
                               class="btn bg-green "><h6 class="text-white" style="font-size: 12px;"><i
                                        class="zmdi zmdi-walk"></i>{{trans('result_management/auto_promotion.promote_student')}}
                                </h6></a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div><br>
    <a href="{{trans('settings/routes.batch_promotion') . '/' . \App\Encrypter::encrypt($academicYear) }}"
       class="btn c-ewangclarks"
       style="width: 40%;position: relative;left: 30%;"><h6 class="text-white"><i
                class="zmdi zmdi-refresh-sync"></i>{{trans('result_management/auto_promotion.auto_promote')}}
        </h6></a><br><br>
@else
    {!! \App\Setting::getAlertFailure(trans('result_management/auto_promotion.no_student_alert')) !!}
@endif
