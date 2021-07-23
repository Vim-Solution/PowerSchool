<h4 class="text-center">
    <b>{{ trans('series_management/manage_series.manage_series_title') }}</b></h4>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('series_management/manage_series.sn')</th>
            <th>@lang('series_management/manage_series.series_name')</th>
            <th>@lang('series_management/manage_series.series_code')</th>
            <th>@lang('series_management/manage_series.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($seriess as $series)
            <tr>
                <td>{{ $sn++ }}</td>
                <td>{{ $series->series_name }}</td>
                <td>{{$series->series_code}}</td>
                <td>
                    <button class="btn bg-amber text-white btn--icon zmdi zmdi-edit" data-toggle="modal"
                            data-target="{{ '#' . $series->series_code}}"></button>
                    <a class="btn btn--icon bg-red  text-white"
                       href="{{ trans('settings/routes.manage_series') . trans('settings/routes.delete')  . '/' . \App\Encrypter::encrypt($series->series_id) }}"><i
                                class="zmdi zmdi-delete"></i> </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

@foreach($seriess as $series)
    <div class="modal fade note-view" id="{{$series->series_code}}" data-backdrop="static" data-keyboard="false">
        <form method="get"
              action="{{ trans('settings/routes.manage_series') . trans('settings/routes.edit')  . '/' . \App\Encrypter::encrypt($series->series_id) }}"
              enctype="multipart/form-data">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="series-code"
                                   value="{{ $series->series_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label></label>
                                <textarea class="form-control wysiwyg-editor "
                                          name="series-name"> {{ $series->series_name }}</textarea>
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

