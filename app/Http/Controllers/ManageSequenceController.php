<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Sequence;
use App\Setting;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ManageSequenceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_sequence');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * show the page for where the exam parameters are set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddSequencePage()
    {
        $sectionCode = Auth::user()->sections_section_code;
        $sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($sequences->isEmpty()) {
            $sequence_list = '';
        } else {
            $sn = 1;
            $sequence_list = View::make('academic_setting.sequence_list', compact('sequences', 'sn'));
        }
        return view('academic_setting.add_sequence', compact('sequence_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addSequence(Request $request)
    {
        $this->validate($request, ['sequence-code' => 'required',
            'sequence-name' => 'required',
            'term-id' => 'required'
        ]);

        $data = $request->all();

        //get user section code
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the sequence code exist
            if (Sequence::sequenceCodeExist($data['sequence-code'], $sectionCode)) {
                $code_exist = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.code_exist', ['code' => $data['sequence-code']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $code_exist]);
            }

            //check if the sequence name exist
            if (Sequence::sequenceNameExist($data['sequence-name'], $sectionCode)) {
                $name_exist = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.name_exist', ['name' => $data['sequence-name']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $name_exist]);
            }


            Sequence::create([trans('database/table.sequence_name') => $data['sequence-name'],
                trans('database/table.sequence_code') => $data['sequence-code'],
                trans('database/table.terms_term_id') => $data['term-id'],
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);

            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('academic_setting/manage_sequence.as_success') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $success_alert]);

        } catch (Illuminate\Filesystem\FileNotFoundException $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.as_failure') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);
        }

    }


    /**
     * @param $sequenceId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSequence($sequenceId)
    {
        $sid = Encrypter::decrypt($sequenceId);
        try {
            $sequence = Sequence::find($sid);
            $setting = Setting::where(trans('database/table.sequences_sequence_id'),$sid)->first();

            if(!empty($setting) || Subject::sequenceScoresExistance($sequenceId)){
                $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.ds_failure', ['name' => $sequence->sequence_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

                return redirect()->back()->with(['status' => $failure_alert]);
            }
            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('academic_setting/manage_sequence.ds_success', ['name' => $sequence->sequence_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
            $sequence->delete();

            Sequence::recordSequenceActions(1,$sequence->sequence_name);
            return redirect()->back()->with(['status' => $success_alert]);


        } catch (\Exception $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.ds_failure', ['name' => $sequence->sequence_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);

        }
    }


    /**
     * @param Request $request
     * @param $sequenceId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editSequence(Request $request, $sequenceId)
    {
        $this->validate($request, ['sequence-code' => 'required',
            'sequence-name' => 'required',
            'term-id' => 'required'
        ]);
        $sid = Encrypter::decrypt($sequenceId);
        $data = $request->all();
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the sequence code exist
            if (Sequence::sequenceCodeExistById($data['sequence-code'], $sectionCode, $sid)) {
                $code_exist = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.code_exist', ['code' => $data['sequence-code']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $code_exist]);
            }

            //check if the sequence name exist
            if (Sequence::sequenceNameExistById($data['sequence-name'], $sectionCode, $sid)) {
                $name_exist = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.name_exist', ['name' => $data['sequence-name']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $name_exist]);
            }
            $sequence = Sequence::find($sid);
            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('academic_setting/manage_sequence.es_success', ['name' => $sequence->sequence_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
            $sequence->sequence_name = strip_tags($data['sequence-name']);
            $sequence->sequence_code = $data['sequence-code'];
            $sequence->terms_term_id = $data['term-id'];
            $sequence->users_user_id = $userId;
            $sequence->save();
            Sequence::recordSequenceActions(2,$sequence->sequence_name);
            return redirect()->back()->with(['status' => $success_alert]);
        } catch (\Exception $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('academic_setting/manage_sequence.es_failure', ['name' =>$data['sequence-name']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);

        }
    }

}


