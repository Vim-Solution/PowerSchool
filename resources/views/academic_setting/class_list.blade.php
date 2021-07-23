<h4 class="text-center">
    <b>{{ trans('academic_setting/manage_class.manage_class_title') }}</b></h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('academic_setting/manage_class.sn')</th>
            <th>@lang('academic_setting/manage_class.class_name')</th>
            <th>@lang('academic_setting/manage_class.promotion_average')</th>
            <th>@lang('academic_setting/manage_class.promotion_class')</th>
            <th>@lang('academic_setting/manage_class.class_code')</th>
            <th>@lang('academic_setting/manage_class.program_name')</th>
            <th>@lang('academic_setting/manage_class.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($classs as $class)
            <tr>
                <td>{{ $sn++ }}</td>
                <td>{{ $class->class_name }}</td>
                <td>{{$class->annual_promotion_average}}</td>
                <td>{{App\AcademicLevel::getClassNameByCode($class->next_promotion_class)}}</td>
                <td>{{$class->class_code}}</td>
                <td>{{ \App\Program::getCycleNameByCode($class->programs_program_code) }}</td>

                <td>
                    <button class="btn bg-cyan text-white btn--icon zmdi zmdi-edit" data-toggle="modal"
                            data-target="{{ '#' . $class->class_code}}"></button>
                    <a class="btn btn--icon bg-red  text-white"
                       href="{{ trans('settings/routes.manage_class') . trans('settings/routes.delete')  . '/' . \App\Encrypter::encrypt($class->class_id) }}"><i
                            class="zmdi zmdi-delete"></i> </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

@foreach($classs as $class)
    <div class="modal fade note-view" id="{{$class->class_code}}" data-backdrop="static" data-keyboard="false">
        <form method="get"
              action="{{ trans('settings/routes.manage_class') . trans('settings/routes.edit')  . '/' . \App\Encrypter::encrypt($class->class_id) }}"
              enctype="multipart/form-data">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="class-code"
                                   value="{{ $class->class_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" step="0.001" name="promotion-average"
                                   value="{{ $class->annual_promotion_average }}">
                        </div>
                        <div class="form-group">
                            <label for="program-code"
                                   style="color: black;">@lang('academic_setting/manage_class.select_program')</label>
                            <select class="" name="program-code" id="program-code">
                                {!! \App\Program::getProgramsList() !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="p-class-code"
                                   style="color: black;">@lang('academic_setting/manage_class.select_next_class')</label>
                            <select  name="p-class-code" id="p-class-code">
                                {!! App\AcademicLevel::getPromotionClassList() !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label></label>
                                <textarea class="form-control wysiwyg-editor "
                                          name="class-name"> {{ $class->class_name }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-white"><i
                                class="zmdi zmdi-edit"></i>Edit
                        </button>
                        <button type="button" class="btn btn-danger text-white" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endforeach

