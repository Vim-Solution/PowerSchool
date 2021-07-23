<div class="card profile">
    <div class="profile__img">
        <img src="{{asset($student->profile)}}" alt="">

        <a href="#" class="zmdi zmdi-camera profile__img__edit"></a>
    </div>

    <div class="profile__info">
        <h3 style="color: #0D0A0A;"><b>{{ trans('student_portal/student_info.by_phone_title') }}</b></h3>

        <form method="get" action="{{ trans('settings/routes.student_info_phone') }}">
            <ul class="icon-list">
                <li>
                    <div class="form-group" style="position: relative;top: 14px;">
                        <label for="full-name" style="color: black;">@lang('student_portal/student_info.full_name')</label>

                        <input type="text" name="name" class="form-control" id="full-name"
                               value="{{ $student->full_name }}" readonly>
                        <i class="form-group__bar"></i>
                    </div>
                </li>
                <li>
                    <div class="form-group" style="position: relative;top: 14px;">
                        <label for="father-phone" style="color: black;">@lang('student_portal/student_info.father_address')</label>
                        <input type="number" name="father-phone" class="form-control" id="father-phone"
                               placeholder="67367......">
                        <i class="form-group__bar"></i>
                    </div>
                </li>
                <li>
                    <div class="form-group" style="position: relative;top: 14px;">
                        <label for="program" style="color: black;">@lang('student_portal/student_info.select_cycle')</label>
                        <select class="select2" name="program" id="program" required>
                            {!! \App\Program::getOverallProgramsList() !!}
                        </select>
                    </div>
                </li>
                <li>
                    <button type="submit" class="btn c-ewangclarks"
                            style="width: 100%;"><i
                                class="zmdi zmdi-arrow-forward"></i> @lang('student_portal/student_info.btn_p_text')
                    </button>
                </li>
            </ul>
        </form>
    </div>
</div>
