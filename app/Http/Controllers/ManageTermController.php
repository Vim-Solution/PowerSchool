<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Sequence;
use App\Setting;
use App\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ManageTermController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_term');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * show the page for where the exam parameters are set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddTermPage()
    {
        $sectionCode = Auth::user()->sections_section_code;
        $terms = Term::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($terms->isEmpty()) {
            $term_list = '';
        } else {
            $sn = 1;
            $term_list = View::make('academic_setting.term_list', compact('terms', 'sn'));
        }
        return view('academic_setting.add_term', compact('term_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addTerm(Request $request)
    {
        $this->validate($request, ['term-code' => 'required',
            'term-name' => 'required',
        ]);

        $data = $request->all();

        //get user section code
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the term code exist
            if (Term::termCodeExist($data['term-code'], $sectionCode)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.code_exist', ['code' => $data['term-code']]))]);
            }

            //check if the term name exist
            if (Term::termNameExist($data['term-name'], $sectionCode)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.name_exist', ['name' => $data['term-name']]))]);
            }


            Term::create([trans('database/table.term_name') => $data['term-name'],
                trans('database/table.term_code') => $data['term-code'],
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);

            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('academic_setting/manage_term.as_success'))]);

        } catch (Illuminate\Filesystem\FileNotFoundException $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.as_failure'))]);
        }

    }


    /**
     * @param $termId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTerm($termId)
    {
        $sid = Encrypter::decrypt($termId);
        try {
            $term = Term::find($sid);

            $sequenceId = Sequence::where(trans('database/table.terms_term_id'), $term->term_id)->get()->pluck(trans('database/table.sequence_id'))->toarray();
            $setting = Setting::whereIn(trans('database/table.sequence_id'), $sequenceId)->get();
            $scores = DB::table(trans('database/table.tests_has_scores'))
                ->whereIn(trans('database/table.sequences_sequence_id'), $sequenceId)
                ->get();

            if ($setting->isNotEmpty() || $scores->isNotEmpty()) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.ds_failure', ['name' => $term->term_name]))]);
            }
            $term->delete();
            Term::recordTermActions(1, $term->term_name);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('academic_setting/manage_term.ds_success', ['name' => $term->term_name]))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.ds_failure', ['name' => $term->term_name]))]);

        }
    }


    /**
     * @param Request $request
     * @param $termId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editTerm(Request $request, $termId)
    {
        $this->validate($request, ['term-code' => 'required',
            'term-name' => 'required',
        ]);
        $sid = Encrypter::decrypt($termId);
        $data = $request->all();
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the term code exist
            if (Term::termCodeExistById($data['term-code'], $sectionCode, $sid)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.code_exist', ['code' => $data['term-code']]))]);
            }

            //check if the term name exist
            if (Term::termNameExistById($data['term-name'], $sectionCode, $sid)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.name_exist', ['name' => $data['term-name']]))]);
            }
            $term = Term::find($sid);

            $term->term_name = strip_tags($data['term-name']);
            $term->term_code = $data['term-code'];
            $term->users_user_id = $userId;
            $term->save();
            Term::recordTermActions(2, $term->term_name);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('academic_setting/manage_term.es_success', ['name' => $term->term_name]))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_term.es_failure', ['name' => $data['term-name']]))]);

        }
    }
}
