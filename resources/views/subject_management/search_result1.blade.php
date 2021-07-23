@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">

@endsection

@section('title')
    @lang('subject_management/view_subject.view_subject_header')
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
    <br>

    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>

        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="post"
                  action="{{ trans('settings/routes.search_subject') }}">
                @csrf()
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">Enter keyword</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input type="text" name="q" class="form-control"
                                   placeholder="enter any keyword of the subject infos">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;position: relative;top: ;">@lang('subject_management/edit_subject.search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <div class="box-body">
        <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <!-- <th></th> -->
                    <th>@lang('subject_management/batch_subject_upload.subject_code')</th>
                    <th>@lang('subject_management/batch_subject_upload.subject_title')</th>
                    <th>@lang('subject_management/batch_subject_upload.coefficient')</th>
                    <th>@lang('subject_management/batch_subject_upload.state')</th>
                    <th>@lang('subject_management/batch_subject_upload.series')</th>
                    <th>@lang('subject_management/batch_subject_upload.class')</th>
                    <th>@lang('subject_management/batch_subject_upload.subject_weight')</th>
                    <th>@lang('subject_management/batch_subject_upload.cycle_name')</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        @foreach ($subjects as $subject)
            <tr>
                <td>{{$subject->subject_code }}</td>
                <td>{{$subject->subject_title }}</td>
                <td>{{$subject->coefficient }}</td>
                <td>{{$subject->state }}</td>
                <td>{{\App\Subject::getSubjectSeriesListById($subject->subject_id) }}</td>
                <td>{{$subject->classes_class_code}}</td>
                <td>{{$subject->subject_weight }}</td>
                <td>{{\App\Program::getCycleNameByCode($subject->programs_program_code)}}</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit-modal" vaule="{{$subject->subject_id}}">
                            <i class="zmdi zmdi-edit zmdi-hc-fw"></i>
                        </button>
                        <a href="{{ trans('settings/routes.delete_subject'). '/' . \App\Encrypter::encrypt($subject->subject_id) }}">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#delete-modal">
                        <i class="zmdi zmdi-delete zmdi-hc-fw"></i>
                        </button></a>
                    </div>
                </td>
            </tr>
        @endforeach
            </tbody>
        </table>
    </div>




    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card bg-blue card--inverse">
                        <div class="card-body">
                            <h3 class="card-text">
                                @lang('subject_management/edit_subject.edit_subject_header')
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="card new-contact">
                        <div class="card-body">
                            <form method="get" action="{{ trans('settings/routes.edit_subject') . trans('settings/routes.save_subject') }}"
                            enctype="multipart/form-data">
                            @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/edit_subject.subject_code')</label>
                                            <input type="text" class="form-control" name="subject-code"
                                                placeholder="@lang('subject_management/edit_subject.subject_code_placeholder')"
                                                value="{{ $subject->subject_code }}" readonly>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/edit_subject.subject_title')</label>
                                            <input type="text" name="subject-title" class="form-control"
                                                placeholder="@lang('subject_management/add_subject.subject_title_placeholder')"  value="{{ $subject->subject_title }}" required>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/edit_subject.coefficient')</label>
                                            <input type="text" name= "coefficient" class="form-control input-mask" data-mask="00" placeholder="eg: 05" value="{{ $subject->coefficient }}" required>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-4 col-md-3">
                                                <label>@lang('subject_management/edit_subject.active_state')</label>
                                                <br>
                                                <div class="form-group">
                                                    <div class="toggle-switch">
                                                        <input type="checkbox" id = "state" name="state" class="toggle-switch__checkbox" checked>
                                                        <i class="toggle-switch__helper"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/edit_subject.select_class')</label>
                                            <select class="select2" name="class-code" id="class-code" required>
                                                {!! \App\AcademicLevel::getClassList() !!}
                                            </select>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/add_subject.series_code')</label>
                                            <select class="select2" multiple data-placeholder="Select one or more choices" name ="series-code[]" id="series-code">
                                                {!! \App\Series::getSeriesList() !!}
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/edit_subject.subject_weight')</label>
                                            <input type="text" name= "subject-weight" class="form-control input-mask" data-mask="00" placeholder="eg: 20"  value= "{{$subject->subject_weight}}" required>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/edit_subject.select_program')</label>
                                            <select class="select2" name="program" id="program" required>
                                                {!! \App\Program::getProgramsList() !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subject_management/edit_subject.select_section')</label>
                                            <select class="select2" name="section" id="section" required>
                                                {!! \App\SchoolSection::getSectionsList() !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="academic-year" style="color: black;">@lang('subject_management/edit_subject.academic_year')</label>
                                            <select class="select2" name="academic-year" id="academic-year" required>
                                                {!! \App\AcademicLevel::getAcademicYearList() !!}
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>







                </div>
            </div>
        </div>
    </div>




@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.edit_subject')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('#class-code').val("<?php echo $subject->classes_class_code ?>");
        $('#section').val("<?php echo $subject->sections_section_code ?>");
        $('#program').val("<?php echo $subject->programs_program_code ?>");
        $('#academic-year').val("<?php echo $subject->academic_year ?>");

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

        jQuery(document).ready(function(){
            jQuery('#ajaxSubmit').click(function(e){
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
               jQuery.ajax({
                  url: "{{ url('/chempionleague') }}",
                  method: 'post',
                  data: {
                     name: jQuery('#name').val(),
                     club: jQuery('#club').val(),
                     country: jQuery('#country').val(),
                     score: jQuery('#score').val(),
                  },
                  success: function(result){
                  	if(result.errors)
                  	{
                  		jQuery('.alert-danger').html('');

                  		jQuery.each(result.errors, function(key, value){
                  			jQuery('.alert-danger').show();
                  			jQuery('.alert-danger').append('<li>'+value+'</li>');
                  		});
                  	}
                  	else
                  	{
                  		jQuery('.alert-danger').hide();
                  		$('#open').hide();
                  		$('#myModal').modal('hide');
                  	}
                  }});
               });
            });


    </script>
@endsection
