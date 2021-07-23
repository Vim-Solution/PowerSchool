
<h4 class="text-center">
    <b>{{ trans('academic_setting/manage_sequence.manage_sequence_title') }}</b></h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('academic_setting/manage_sequence.sn')</th>
            <th>@lang('academic_setting/manage_sequence.sequence_name')</th>
            <th>@lang('academic_setting/manage_sequence.sequence_code')</th>
            <th>@lang('academic_setting/manage_sequence.term_name')</th>
            <th>@lang('academic_setting/manage_sequence.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sequences as $sequence)
            <tr>
                <td>{{ $sn++ }}</td>
                <td>{{ $sequence->sequence_name }}</td>
                <td>{{$sequence->sequence_code}}</td>
                <td>{{ \App\Term::getTermNameById($sequence->terms_term_id) }}</td>

                <td>
                    <button class="btn btn-success text-white btn--icon zmdi zmdi-edit" data-toggle="modal"
                            data-target="{{ '#' . $sequence->sequence_code}}"></button>
                    <a class="btn btn--icon bg-red  text-white"
                       href="{{ trans('settings/routes.manage_sequence') . trans('settings/routes.delete')  . '/' . \App\Encrypter::encrypt($sequence->sequence_id) }}"><i
                                class="zmdi zmdi-delete"></i> </a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

@foreach($sequences as $sequence)
    <div class="modal fade note-view" id="{{$sequence->sequence_code}}" data-backdrop="static" data-keyboard="false">
        <form method="get"
              action="{{ trans('settings/routes.manage_sequence') . trans('settings/routes.edit')  . '/' . \App\Encrypter::encrypt($sequence->sequence_id) }}"
              enctype="multipart/form-data">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                   <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="sequence-code"
                                   value="{{ $sequence->sequence_code }}"  readonly>
                        </div>
                           <div class="form-group">
                               <label for="term"
                                      style="color: black;">@lang('academic_setting/manage_sequence.select_term')</label>
                               <select class="" name="term-id" id="term">
                                   {!! \App\Term::getTermList()!!}
                               </select>
                           </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label></label>
                                <textarea class="form-control wysiwyg-editor "
                                          name="sequence-name"> {{ $sequence->sequence_name }}</textarea>
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

