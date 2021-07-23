@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
@endsection

@section('title')
    @lang('access_manager/manage_role.manage_role_header')
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
        {!! session('status')!!}
    @endif
   {!! $info !!}
    <div class="row todo">
        <div class="col-md-8">
            <div class="card">
                <div class="toolbar toolbar--inner">
                    <div class="toolbar__label" style="color: black">@lang('access_manager/manage_role.manage_role_title')</div>
                </div>
                {!! \App\Role::getRoleTodoList() !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('access_manager/manage_role.category_list')</h4>
                    <h6 class="card-subtitle">@lang('access_manager/manage_role.category_list_subtitle')</h6>
                    {!! \App\Category::getCategoryList() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade note-view" id="modal-new-role" data-backdrop="static" data-keyboard="false">
        <form method="post" action="{{ trans('settings/routes.manage_role') }}" enctype="multipart/form-data">
            @csrf()
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body" >
                        <div class="form-group">
                            <input type="text" class="form-control" name="role-name"
                                   placeholder="@lang('access_manager/manage_role.role_name_placeholder')">
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label></label>
                                    <textarea class="form-control wysiwyg-editor " name="role-description" placeholder="@lang('access_manager/manage_role.description_placeholder')"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-white"><i class="zmdi zmdi-nature-people"></i>Save
                        </button>
                        <button type="button" class="btn btn-danger text-white" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <button class="btn btn-danger btn--action zmdi zmdi-plus" data-toggle="modal"
            data-target="#modal-new-role"></button>
@endsection
@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/trumbowyg/trumbowyg.min.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.administration') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_role')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection
