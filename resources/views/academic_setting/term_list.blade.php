<h4 class="text-center">
    <b>{{ trans('academic_setting/manage_term.manage_term_title') }}</b></h4>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('academic_setting/manage_term.sn')</th>
            <th>@lang('academic_setting/manage_term.term_name')</th>
            <th>@lang('academic_setting/manage_term.term_code')</th>
            <th>@lang('academic_setting/manage_term.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($terms as $term)
            <tr>
                <td>{{ $sn++ }}</td>
                <td>{{ $term->term_name }}</td>
                <td>{{$term->term_code}}</td>
                <td>
                    <button class="btn btn-info text-white btn--icon zmdi zmdi-edit" data-toggle="modal"
                            data-target="{{ '#' . $term->term_code}}"></button>
                    <a class="btn btn--icon bg-red  text-white"
                       href="{{ trans('settings/routes.manage_term') . trans('settings/routes.delete')  . '/' . \App\Encrypter::encrypt($term->term_id) }}"><i
                                class="zmdi zmdi-delete"></i> </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

@foreach($terms as $term)
    <div class="modal fade note-view" id="{{$term->term_code}}" data-backdrop="static" data-keyboard="false">
        <form method="get"
              action="{{ trans('settings/routes.manage_term') . trans('settings/routes.edit')  . '/' . \App\Encrypter::encrypt($term->term_id) }}"
              enctype="multipart/form-data">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="term-code"
                                   value="{{ $term->term_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label></label>
                                <textarea class="form-control wysiwyg-editor "
                                          name="term-name"> {{ $term->term_name }}</textarea>
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

