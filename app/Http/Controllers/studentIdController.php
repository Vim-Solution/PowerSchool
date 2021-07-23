<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Student;
use App\Encrypter;
use Barryvdh\Snappy\Facades\SnappyPdf as PDFPRINT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class studentIdController extends Controller
{
    /**
     * studentIdController constructor.
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.id_card');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * Show th batch student upload page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStudentIdPage()
    {

        $student_id_card = '';
        return view('student_management.student_id_search', compact('student_id_card'));
    }

    public function generateStudentId()
    {
        $q = Input::get ('q');
        $students = DB::table(trans('database/table.students'))
            ->where(trans('database/table.matricule'), 'LIKE', '%' .$q. '%')
            ->orwhere(trans('database/table.full_name'), 'LIKE', '%' .$q. '%')
            ->orwhere(trans('database/table.tutor_name'), 'LIKE', '%' .$q. '%')
            ->orwhere(trans('database/table.date_of_birth'), 'LIKE', '%' .$q. '%')
            ->get();

        if (count($students) <= 0){
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/generate_id.generate_id_failure'))]);
        }
        else{
            return view('student_management.get_student_id', compact('students'));
        }
    }

    /**
     * @param $studentId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function getStudentId($studentId)
    {
        $student_id = Encrypter::decrypt($studentId);

        $student = DB::table(trans('database/table.students'))
            ->where(trans('database/table.student_id'), $student_id)
            ->first();
        $student_id_card = View::make('student_management/student_id_card', compact('student'));
       return \view('student_management.student_id_search',compact('student_id_card'));
    }

    /**
     * @param $studentId
     * @return mixed
     */
    public function downloadIDCard($studentId){
        $student_id = Encrypter::decrypt($studentId);

        $student = DB::table(trans('database/table.students'))
            ->where(trans('database/table.student_id'), $student_id)
            ->first();
        $pdf = PDFPRINT::loadView('student_management/student_id_card_download', compact('student'));
        $filename = str_replace(" ", "_", $student->full_name) . '.pdf';
        return $pdf->download($filename);

    }

}
