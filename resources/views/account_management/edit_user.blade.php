@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('account_management/edit_user.edit_user_header')
@endsection

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('status'))
        {!! session('status') !!}
    @endif
    <div class="card new-contact">
        <div class="new-contact__header">
            <a href="" class="zmdi zmdi-camera new-contact__upload"></a>

            <img src="{{ asset($user->profile) }}" class="new-contact__img" alt="">
        </div>

        <div class="card-body">
            <form method="get" action="{{ trans('settings/routes.edit_user') . trans('settings/routes.save') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.first_name')</label>
                            <input type="text" class="form-control" name="first-name"
                                   placeholder="@lang('account_management/edit_user.first_name_placeholder')"
                                   value="{{ strtok($user->full_name,' ') }}" required>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/add_user.last_name')</label>
                            <input type="text" name="last-name" class="form-control"
                                   placeholder="@lang('account_management/edit_user.last_name_placeholder')"
                                   value="{{ strtok('') }}" required>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/add_user.job_title')</label>
                            <select class="select2" name="job-title" id="job-title" required>
                                <option selected disabled>@lang('general.nothing_selected')</option>
                                <option value="{{ trans('account_management/edit_user.principal') }}">{{ ucfirst(trans('account_management/edit_user.principal'))}}</option>
                                <option value="{{ trans('account_management/edit_user.vice_principal') }}">{{ ucfirst(trans('account_management/edit_user.vice_principal'))}}</option>
                                <option value="{{ trans('account_management/edit_user.discipline_master') }}">{{ ucfirst(trans('account_management/edit_user.discipline_master'))}}</option>
                                <option value="{{ trans('account_management/edit_user.teacher')}}">{{ ucfirst(trans('account_management/edit_user.teacher'))}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.job_type')</label>
                            <select class="select2" name="job-type" id="job-type" required>
                                <option selected disabled>@lang('general.nothing_selected')</option>
                                <option value="{{ trans('account_management/edit_user.full_time') }}">{{ ucfirst(trans('account_management/edit_user.full_time'))}}</option>
                                <option value="{{ trans('account_management/edit_user.part_time') }}">{{ ucfirst(trans('account_management/edit_user.part_time'))}}</option>
                            </select>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.email_address')</label>
                            <input type="email" class="form-control" name="email-address"
                                   placeholder="@lang('account_management/edit_user.email_address_placeholder')"
                                   value="{{ $user->email }}" readonly>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.mobile_phone')</label>
                            <input type="number" class="form-control" name="mobile-phone"
                                   placeholder="@lang('account_management/edit_user.mobile_phone_placeholder')"
                                   value="{{ $user->phone_number }}" required>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.office_address')</label>
                            <input type="text" class="form-control" name="office-address"
                                   placeholder="@lang('account_management/edit_user.office_address_placeholder')"
                                   value="{{ $user->office_address }}" required>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.address')</label>
                            <input type="text" class="form-control" name="address"
                                   placeholder="@lang('account_management/edit_user.address_placeholder')"
                                   value="{{ $user->address }}" required>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.select_program')</label>
                            <select class="select2" name="program" id="program" required>
                                {!! \App\Program::getProgramsList() !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.select_section')</label>
                            <select class="select2" name="section" id="section" required>
                                {!! \App\SchoolSection::getSectionsList() !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.select_department')</label>
                            <select class="select2" name="department" id="department" required>
                                {!! \App\Department::getDepartmentsList() !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('account_management/edit_user.select_role')</label>
                            <select class="select2" name="role" id="role" required>
                                {!! \App\Role::getRolesList() !!}
                            </select>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="mt-5 text-center">
                    <button type="submit" class="btn c-ewangclarks" style="width: 30%;"><i
                                class="zmdi zmdi-account-add"></i> @lang('account_management/edit_user.edit_user_header')
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.account_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.edit_user')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('#department').val("<?php echo $user->departments_department_id ?>");
        $('#section').val("<?php echo $user->sections_section_code ?>");
        $('#program').val("<?php echo $user->programs_program_code ?>");
        $('#job-type').val("<?php echo $user->type ?>");
        $('#job-title').val("<?php echo $user->position ?>");
        $('#role').val("<?php echo $user->roles_role_id ?>");


        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();
    </script>
@endsection