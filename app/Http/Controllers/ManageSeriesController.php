<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Series;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ManageSeriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_series');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * show the page for where the exam parameters are set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddSeriesPage()
    {
        $sectionCode = Auth::user()->sections_section_code;
        $seriess = Series::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($seriess->isEmpty()) {
            $series_list = '';
        } else {
            $sn = 1;
            $series_list = View::make('series_management.series_list', compact('seriess', 'sn'));
        }
        return view('series_management.add_series', compact('series_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addSeries(Request $request)
    {
        $this->validate($request, ['series-code' => 'required',
            'series-name' => 'required',
        ]);

        $data = $request->all();

        //get user section code
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the series code exist
            if (Series::seriesCodeExist($data['series-code'], $sectionCode)) {
                $code_exist = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_series.code_exist', ['code' => $data['series-code']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $code_exist]);
            }

            //check if the series name exist
            if (Series::seriesNameExist($data['series-name'], $sectionCode)) {
                $name_exist = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_series.name_exist', ['name' => $data['series-name']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $name_exist]);
            }


            Series::create([trans('database/table.series_name') => $data['series-name'],
                trans('database/table.series_code') => $data['series-code'],
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);

            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('series_management/manage_series.as_success') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $success_alert]);

        } catch (Illuminate\Filesystem\FileNotFoundException $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_series.as_failure') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);
        }

    }


    /**
     * @param $seriesId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSeries($seriesId)
    {
        $sid = Encrypter::decrypt($seriesId);
        try {
            $series = Series::find($sid);

            if(Series::checkSeriesDistributedExistance($series->series_code)){
                return redirect()->back()->with(['status' => Setting::getAlertFailure( trans('series_management/manage_series.ds_exception', ['name' => $series->series_name]))]);
            }
            $series->delete();
            return redirect()->back()->with(['status' => Setting::getAlertFailure( trans('series_management/manage_series.ds_success', ['name' => $series->series_name]))]);


        } catch (\Exception $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_series.ds_failure', ['name' => $series->series_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);

        }
    }


    /**
     * @param Request $request
     * @param $seriesId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editSeries(Request $request, $seriesId)
    {
        $this->validate($request, ['series-code' => 'required',
            'series-name' => 'required',
        ]);
        $sid = Encrypter::decrypt($seriesId);
        $data = $request->all();
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the series code exist
            if (Series::seriesCodeExistById($data['series-code'], $sectionCode, $sid)) {
                $code_exist = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_series.code_exist', ['code' => $data['series-code']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $code_exist]);
            }

            //check if the series name exist
            if (Series::seriesNameExistById($data['series-name'], $sectionCode, $sid)) {
                $name_exist = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_series.name_exist', ['name' => $data['series-name']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $name_exist]);
            }
            $series = Series::find($sid);
            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('series_management/manage_series.es_success', ['name' => $series->series_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
            $series->series_name = strip_tags($data['series-name']);
            $series->series_code = $data['series-code'];
            $series->users_user_id = $userId;
            $series->save();
            return redirect()->back()->with(['status' => $success_alert]);
        } catch (\Exception $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_series.es_failure', ['name' => $data['series-name']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);

        }
    }
}
