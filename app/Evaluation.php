<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

define('NUMBER_OF_TERMS', 3);
define('MINIMUM_AVERAGE', 10);
define('ROUND_UP_PRECISION', 2);

class Evaluation extends Model
{

    /**
     * @param $student_scores
     * @param $sequenceId
     * @param $classCode
     * @param $academicYear
     * @param $studentId
     * @return string
     */
    public static function calculateSequenceResults($student_scores, $sequenceId, $classCode, $academicYear, $studentId)
    {
        $subjectIds = $student_scores->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $subjects = Subject::whereIn(trans('database/table.subject_id'), $subjectIds)->get();

        $classAverages = Sequence::getClassSequenceAverages($sequenceId, $classCode, $academicYear);

        //sort the averages in descending order
        $classAverages = $classAverages->sortByDesc(trans('database/table.average'));
        $sortedAverages = $classAverages->values()->all();

        $cumulative_coefficient = 0;
        $cumulative_total = 0;
        $total_coef = 0;


        $res = '';
        foreach ($student_scores as $subject_score) {
            $subject = $subjects->where(trans('database/table.subject_id'), $subject_score->subjects_subject_id)->first();
            if (!empty($subject)) {
                $total = 0;
                $total = $subject_score->subject_score * $subject->coefficient;
                $cumulative_coefficient += $subject->coefficient * $subject->subject_weight;
                $total_coef += $subject->coefficient;
                $cumulative_total += $total;
                $res .= '<tr>' .
                    '<td>' . $subject->subject_title . '</td>' .
                    '<td>' . $subject->subject_weight . '</td>' .
                    '<td>' . $subject->coefficient . '</td>';
                if ($subject_score->subject_score < ($subject->subject_weight / ROUND_UP_PRECISION)) {
                    $res .= '<td style="color: red">' . round($subject_score->subject_score, ROUND_UP_PRECISION) . '</td>';
                    $res .= '<td style="color: red">' . round($total, ROUND_UP_PRECISION) . '</td></tr>';
                } else {
                    $res .= '<td>' . round($subject_score->subject_score, ROUND_UP_PRECISION) . '</td>';
                    $res .= '<td>' . round($total, ROUND_UP_PRECISION) . '</td></tr>';
                }

            }
        }
        if ($total_coef == 0) {
            $total_coef = 1;
        }
        $res .= '<tr><td>' . trans('student_portal/result_portal.total') . '</td><td></td><td>' . $total_coef . '</td><td></td><td>' . round($cumulative_total, ROUND_UP_PRECISION) . '</td></tr>';

        $res .= '<tr class="bg-green text-white"><td>' . trans('student_portal/result_portal.average') . '</td><td></td><td></td><td></td><td>' . round(($cumulative_total / $total_coef), ROUND_UP_PRECISION) . '/20</td></tr>';

        $class_average = $classAverages->average(trans('database/table.average'));
        $highest_average = $classAverages->max(trans('database/table.average'));
        $lowest_average = $classAverages->min(trans('database/table.average'));
        $rank = self::getRank($sortedAverages, $studentId);

        $res .= '<div class="card"><div class="card-body"><div class="row" style="position: relative;left: 3%;"> <div class="row" style="width: 23%;"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.class_average') . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.high_average') . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.low_average') . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.Rank') . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.signature') . '</div></div></div>';
        $res .= '<div class="row" style="position: relative;left: 3%;"> <div class="row" style="width: 23%;"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . $class_average . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10" style="background-color:#32c787; color: white;padding:15px 15px 15px 15px;">' . $highest_average . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . $lowest_average . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . $rank . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . ' ' . '</div></div></div></div></div>';

        return $res;
    }

    /**
     * @param $sequences
     * @param $student
     * @param $academicYear
     * @return string
     */
    public static function calculateTermResults($sequences, $student, $academicYear)
    {
        $sequence_one = $sequences->first();
        $sequence_two = $sequences->last();

        $classCode = Student::getStudentClassCodeByMatricule($student->matricule);
        $classAverages = Term::getClassTermAverages($sequence_two->terms_term_id, $classCode, $academicYear);

        //sort the averages in descending order
        $classAverages = $classAverages->sortByDesc(trans('database/table.average'));
        $sortedAverages = $classAverages->values()->all();

        $sequence_one_scores = Subject::getFinalMarks($sequence_one->sequence_id, $student->student_id, $academicYear);
        $sequence_two_scores = Subject::getFinalMarks($sequence_two->sequence_id, $student->student_id, $academicYear);

        $sequence_one_subjectIds = $sequence_one_scores->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $sequence_two_subjectIds = $sequence_two_scores->pluck(trans('database/table.subjects_subject_id'))->toArray();
        if (count($sequence_one_subjectIds) > count($sequence_two_subjectIds)) {
            $subjects = Subject::whereIn(trans('database/table.subject_id'), $sequence_one_subjectIds)->get();
        } else {
            $subjects = Subject::whereIn(trans('database/table.subject_id'), $sequence_two_subjectIds)->get();
        }

        $res = '';

        $totalCoefficient = 0;
        $finalTotal = 0;
        $divisor = $sequences->count();
        foreach ($subjects as $subject) {
            $finalMark = 0;
            $total = 0;
            $subjectScoreOne = 0;
            $subjectScoreTwo = 0;
            $scoreOne = $sequence_one_scores->where(trans('database/table.subjects_subject_id'), $subject->subject_id)->first();
            $scoreTwo = $sequence_two_scores->where(trans('database/table.subjects_subject_id'), $subject->subject_id)->first();
            if (!empty($scoreOne)) {
                $subjectScoreOne = round($scoreOne->subject_score, ROUND_UP_PRECISION);
            }
            if (!empty($scoreTwo)) {
                $subjectScoreTwo = round($scoreTwo->subject_score, ROUND_UP_PRECISION);
            }
            $finalMark = ($subjectScoreOne + $subjectScoreTwo) / $divisor;
            $total = ($finalMark * $subject->coefficient);
            $finalTotal += $total;
            $totalCoefficient += $subject->coefficient;
            $res .= '<tr><td>' . $subject->subject_title . '</td><td>' . $subject->subject_weight . '</td><td>' . $subject->coefficient . '</td>';
            if ($subjectScoreOne < ($subject->subject_weight / 2)) {
                $res .= '<td style="color: red">' . (!empty($scoreOne) ? $subjectScoreOne : '-') . '</td>';
            } else {
                $res .= '<td>' . (!empty($scoreOne) ? $subjectScoreOne : '-') . '</td>';
            }
            if ($subjectScoreTwo < ($subject->subject_weight / 2)) {
                $res .= '<td style="color: red">' . (!empty($scoreTwo) ? $subjectScoreTwo : '-') . '</td>';
            } else {
                $res .= '<td>' . (!empty($scoreTwo) ? $subjectScoreTwo : '-') . '</td>';
            }
            if ($finalMark < ($subject->subject_weight / 2)) {
                $res .= '<td style="color: red">' . round($finalMark, ROUND_UP_PRECISION) . '</td>';
            } else {
                $res .= '<td>' . round($finalMark, ROUND_UP_PRECISION) . '</td>';
            }
            $res .= '<td>' . round($total, ROUND_UP_PRECISION) . '</td></tr>';
        }
        $res .= '<tr><td>' . trans('student_portal/result_portal.total') . '</td><td></td><td>' . $totalCoefficient . '</td><td></td><td></td><td></td><td>' . round($finalTotal, ROUND_UP_PRECISION) . '</td></tr>';

        if ($totalCoefficient == 0) {
            $totalCoefficient = 1;
            $res .= '<tr class="bg-green text-white"><td>' . trans('student_portal/result_portal.average') . '</td><td></td><td></td><td></td><td></td><td></td><td>' . trans('result_management/report_card.no_marks') . '</td></tr>';

        } else {
            $res .= '<tr class="bg-green text-white"><td>' . trans('student_portal/result_portal.average') . '</td><td></td><td></td><td></td><td></td><td></td><td>' . round(($finalTotal / $totalCoefficient), ROUND_UP_PRECISION) . '/20</td></tr>';
        }
        $class_average = $classAverages->average(trans('database/table.average'));
        $highest_average = $classAverages->max(trans('database/table.average'));
        $lowest_average = $classAverages->min(trans('database/table.average'));
        $rank = self::getRank($sortedAverages, $student->student_id);


        $res .= '<div class="card"><div class="card-body"><div class="row" style="position: relative;left: 3%;"> <div class="row" style="width: 23%;"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.class_average') . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.high_average') . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.low_average') . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.Rank') . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10 c-ewangclarks" style="color: white;padding:15px 15px 15px 15px;">' . trans('student_portal/result_portal.signature') . '</div></div></div>';
        $res .= '<div class="row" style="position: relative;left: 3%;"> <div class="row" style="width: 23%;"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . $class_average . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10" style="background-color:#32c787; color: white;padding:15px 15px 15px 15px;">' . $highest_average . '</div></div><div class="row" style="width: 23%;"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . $lowest_average . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . $rank . '</div></div><div class="row" style="width: 22%"><div class="col-sm-10" style="background-color: #32c787;color: white;padding:15px 15px 15px 15px;">' . ' ' . '</div></div></div></div></div>';

        $term_session_key = 'term_' . $sequence_one->terms_term_id;
        if (Session::has($term_session_key)) {
            Session::forget($term_session_key);
        }
        Session::put($term_session_key, round(($finalTotal / $totalCoefficient), ROUND_UP_PRECISION));
        return $res;

    }

    /**
     * @param $averages
     * @param $studentId
     * @return int|null
     */
    public static function getRank($averages, $studentId)
    {
        $rank = 0;
        foreach ($averages as $average) {
            $rank++;
            if ($average->students_student_id == $studentId) {
                break;
            }
        }

        if ($rank == 0) {
            return null;
        }
        return $rank;
    }

    /**
     * @param $sequence
     * @param $academicYear
     * @return int
     */
    public static function calculateAverages($sequence, $academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $all_sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $classes = AcademicLevel::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $all_students = Student::all();
        $class_has_students_pivot = AcademicLevel::getClassStudentsPivot($sectionCode);
        $final_marks = Subject::getAllFinalMarks($academicYear);

        $subjects = Subject::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $sequence_averages = collect([]);
        $term_averages = collect([]);

        foreach ($classes as $class) {
            $class_has_students = $class_has_students_pivot->where(trans('database/table.academic_year'), $academicYear)->where(trans('database/table.classes_class_code'), $class->class_code);
            $matricules = $class_has_students->unique(trans('database/table.matricule'))->pluck(trans('database/table.matricule'))->toArray();
            $students = $all_students->whereIn(trans('database/table.matricule'), $matricules);
            foreach ($students as $student) {
                $average = 0;
                if (($sequence->sequence_id % 2) == 0) {
                    $sequences = $all_sequences->where(trans('database/table.terms_term_id'), $sequence->terms_term_id);
                    $average = self::calculateTermAverage($sequences, $student, $academicYear, $subjects);
                    $term_averages = $term_averages->push([
                        trans('database/table.terms_term_id') => $sequence->terms_term_id,
                        trans('database/table.students_student_id') => $student->student_id,
                        trans('database/table.average') => $average,
                        trans('database/table.academic_year') => $academicYear,
                        trans('database/table.classes_class_code') => $class->class_code,
                        trans('database/table.state') => 1
                    ]);
                } else {
                    $student_scores = $final_marks->where(trans('database/table.sequences_sequence_id'), $sequence->sequence_id)->where(trans('database/table.students_student_id'), $student->student_id);
                    $average = self::calculateSequenceAverage($student_scores, $subjects);
                    $sequence_averages = $sequence_averages->push([
                        trans('database/table.sequences_sequence_id') => $sequence->sequence_id,
                        trans('database/table.students_student_id') => $student->student_id,
                        trans('database/table.average') => $average,
                        trans('database/table.academic_year') => $academicYear,
                        trans('database/table.classes_class_code') => $class->class_code,
                        trans('database/table.state') => 1
                    ]);
                }
            }
        }
        //insert students' sequence averages in the database
        if ($sequence_averages->isNotEmpty()) {
            DB::table(trans('database/table.sequence_averages'))
                ->where(trans('database/table.academic_year'), $academicYear)
                ->where(trans('database/table.sequences_sequence_id'), $sequence->sequence_id)
                ->update([trans('database/table.state') => 0]);
            DB::table(trans('database/table.sequence_averages'))
                ->insert($sequence_averages->toArray());
        }
        //insert students' term averages in the database
        if ($term_averages->isNotEmpty()) {
            DB::table(trans('database/table.term_averages'))
                ->where(trans('database/table.academic_year'), $academicYear)
                ->where(trans('database/table.terms_term_id'), $sequence->terms_term_id)
                ->update([trans('database/table.state') => 0]);
            DB::table(trans('database/table.term_averages'))
                ->insert($term_averages->toArray());
        }

        return 0;
    }


    /**
     * @param $student_scores
     * @param $subs
     * @return float
     */
    public static function calculateSequenceAverage($student_scores, $subs)
    {
        $subjectIds = $student_scores->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $subjects = $subs->whereIn(trans('database/table.subject_id'), $subjectIds);


        $cumulative_coefficient = 0;
        $cumulative_total = 0;
        $total_coef = 0;

        $res = '';
        foreach ($student_scores as $subject_score) {
            $subject = $subjects->where(trans('database/table.subject_id'), $subject_score->subjects_subject_id)->first();
            if (!empty($subject)) {
                $total = 0;
                $total = $subject_score->subject_score * $subject->coefficient;
                $cumulative_coefficient += $subject->coefficient * $subject->subject_weight;
                $total_coef += $subject->coefficient;
                $cumulative_total += $total;
            }
        }
        if ($total_coef == 0) {
            $total_coef = 1;
        }
        $average = round(($cumulative_total / $total_coef), ROUND_UP_PRECISION);
        return $average;
    }

    /**
     * @param $sequences
     * @param $student
     * @param $academicYear
     * @param $subs
     * @return float
     */
    public static function calculateTermAverage($sequences, $student, $academicYear, $subs)
    {
        $sequence_one = $sequences->first();
        $sequence_two = $sequences->last();

        $sequence_one_scores = Subject::getFinalMarks($sequence_one->sequence_id, $student->student_id, $academicYear);
        $sequence_two_scores = Subject::getFinalMarks($sequence_two->sequence_id, $student->student_id, $academicYear);

        $sequence_one_subjectIds = $sequence_one_scores->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $sequence_two_subjectIds = $sequence_two_scores->pluck(trans('database/table.subjects_subject_id'))->toArray();
        if (count($sequence_one_subjectIds) > count($sequence_two_subjectIds)) {
            $subjects = $subs->whereIn(trans('database/table.subject_id'), $sequence_one_subjectIds);
        } else {
            $subjects = $subs->whereIn(trans('database/table.subject_id'), $sequence_two_subjectIds);
        }

        $res = '';

        $totalCoefficient = 0;
        $finalTotal = 0;
        $divisor = $sequences->count();
        foreach ($subjects as $subject) {
            $finalMark = 0;
            $total = 0;
            $subjectScoreOne = 0;
            $subjectScoreTwo = 0;
            $scoreOne = $sequence_one_scores->where(trans('database/table.subjects_subject_id'), $subject->subject_id)->first();
            $scoreTwo = $sequence_two_scores->where(trans('database/table.subjects_subject_id'), $subject->subject_id)->first();
            if (!empty($scoreOne)) {
                $subjectScoreOne = round($scoreOne->subject_score, ROUND_UP_PRECISION);
            }
            if (!empty($scoreTwo)) {
                $subjectScoreTwo = round($scoreTwo->subject_score, ROUND_UP_PRECISION);
            }
            $finalMark = ($subjectScoreOne + $subjectScoreTwo) / $divisor;
            $total = ($finalMark * $subject->coefficient);
            $finalTotal += $total;
            $totalCoefficient += $subject->coefficient;
        }
        if ($totalCoefficient == 0) {
            $totalCoefficient = 1;
        }
        $average = round(($finalTotal / $totalCoefficient), ROUND_UP_PRECISION);
        return $average;

    }

    /**
     * @param $sectionCode
     * @param $academicYear
     * @return \Illuminate\Contracts\View\View
     */
    public static function autoPromotionList($sectionCode, $academicYear)
    {
        $terms = Term::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $all_sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $classes = AcademicLevel::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $class_has_students = AcademicLevel::getSchoolClassStudentRecords($sectionCode, $academicYear);
        $matricules = $class_has_students->unique(trans('database/table.matricule'))->pluck(trans('database/table.matricule'))->toArray();
        $students = Student::whereIn(trans('database/table.matricule'), $matricules)->get();

        $promotion_records = collect([]);
        $promotion_next_class = [];
        $repeat_class = [];


        foreach ($students as $student) {
            $annual_average = 0;
            $classCode = $class_has_students->where(trans('database/table.matricule'), $student->matricule)->first();
            if (empty($classCode)) {
                $annual_promotion_average = 10;
            } else {
                $class = $classes->where(trans('database/table.class_code'), $classCode->classes_class_code)->first();
                $annual_promotion_average = $class->annual_promotion_average;
            }
            if (!empty($class)) {
                if(strcmp($class->next_promotion_class, trans('general.university_code')) == 0 || strcmp($class->next_promotion_class, trans('general.none')) == 0){
                    $promotionClass =trans('general.' . $class->next_promotion_class);
                }else {
                    $promotionClass = AcademicLevel::getClassNameByCode($class->next_promotion_class);

                }
            } else {
                $promotionClass = trans('result_management/auto_promotion.university');
            }

            $nextAcademicYear = Setting::getNextAcademicYear($academicYear);


            $cummulative_average = 0;
            foreach ($terms as $term) {
                $termId = $term->term_id;
                $checker = PublishStatus::termResultExistance($termId, $academicYear, $sectionCode);
                if ($checker['status'] == 1) {
                    $sequences = $all_sequences->where(trans('database/table.terms_term_id'), $termId);
                    $term_result = Evaluation::calculateTermResults($sequences, $student, $academicYear);
                    $term_session_key = 'term_' . $termId;
                    if (Session::has($term_session_key)) {
                        $cummulative_average += Session::get($term_session_key);
                    }
                }
            }

            Evaluation::cleanClassReportCardSession($terms);
            $annual_average = round(($cummulative_average / NUMBER_OF_TERMS), ROUND_UP_PRECISION);
            $nextIndex = $term->term_id + 1;


            $nextAcademicClass = AcademicLevel::promotionClass($student->matricule, $nextAcademicYear);
            if (!empty($nextAcademicClass)) {
                $nextAcademicClassName = AcademicLevel::getClassNameByCode($nextAcademicClass->classes_class_code);
            }
            $report_card[$nextIndex] = '';

            if ($annual_average < $annual_promotion_average) {
                if (empty($nextAcademicClass)) {
                    $promotion_records = $promotion_records->push(
                        ['matricule' => $student->matricule, 'full_name' => $student->full_name,
                            'present_class' => empty($class) ? trans('result_management/auto_promotion.invalid_class') : $class->class_name,
                            'promotion_class' => ' ', 'annual_average' => $annual_average,
                            'promotion_state' => trans('result_management/auto_promotion.repeat_class')
                        ]);

                } elseif (strcmp($nextAcademicClassName, $class->class_name) == 0) {
                    $promotion_records = $promotion_records->push(
                        ['matricule' => $student->matricule, 'full_name' => $student->full_name,
                            'present_class' => empty($class) ? trans('result_management/auto_promotion.invalid_class') : $class->class_name,
                            'promotion_class' => $nextAcademicClassName, 'annual_average' => $annual_average,
                            'promotion_state' => trans('result_management/auto_promotion.repeat_class_state')
                        ]);
                } else {
                    $promotion_records = $promotion_records->push(
                        ['matricule' => $student->matricule, 'full_name' => $student->full_name,
                            'present_class' => empty($class) ? trans('result_management/auto_promotion.invalid_class') : $class->class_name,
                            'promotion_class' => $promotionClass, 'annual_average' => $annual_average,
                            'promotion_state' => trans('result_management/auto_promotion.promote_state')
                        ]);
                }
                $repeat_class[] = $student->matricule;
            } else {
                if (empty($nextAcademicClass)) {
                    $promotion_records = $promotion_records->push(
                        ['matricule' => $student->matricule, 'full_name' => $student->full_name,
                            'present_class' => empty($class) ? trans('result_management/auto_promotion.invalid_class') : $class->class_name,
                            'promotion_class' => ' ', 'annual_average' => $annual_average,
                            'promotion_state' => trans('result_management/auto_promotion.promote_student')
                        ]);

                } elseif ($nextAcademicClassName == $class->class_name) {
                    $promotion_records = $promotion_records->push(
                        ['matricule' => $student->matricule, 'full_name' => $student->full_name,
                            'present_class' => empty($class) ? trans('result_management/auto_promotion.invalid_class') : $class->class_name,
                            'promotion_class' => $nextAcademicClassName, 'annual_average' => $annual_average,
                            'promotion_state' => trans('result_management/auto_promotion.repeat_class')
                        ]);
                } else {
                    $promotion_records = $promotion_records->push(
                        ['matricule' => $student->matricule, 'full_name' => $student->full_name,
                            'present_class' => empty($class) ? trans('result_management/auto_promotion.invalid_class') : $class->class_name,
                            'promotion_class' => $promotionClass, 'annual_average' => $annual_average,
                            'promotion_state' => trans('result_management/auto_promotion.promote_state')
                        ]);
                }
                $promotion_next_class[] = $student->matricule;
            }
        }
        if (Session::has('general_promotion')) {
            Session::forget('general_promotion');
        }
        if (Session::has('general_repeat_class')) {
            Session::forget('general_repeat_class');
        }
        Session::put('general_promotion', $promotion_next_class);
        Session::put('general_repeat_class', $repeat_class);
        $promotion_records = $promotion_records->toArray();
        $promotion_list = View::make('result_management/promotion_list_table', compact('promotion_records', 'academicYear'));
        return $promotion_list;
    }

    /**
     * @param $terms
     * @return int
     */
    public static function cleanClassReportCardSession($terms)
    {
        foreach ($terms as $term) {
            $term_session_key = 'term_' . $term->term_id;
            if (Session::has($term_session_key)) {
                Session::forget($term_session_key);
            }
        }
        return 0;
    }

    /**
     * @param $matricules
     * @param $classCode
     * @param $academicYear
     * @return int
     */
    public static function classRepeats($matricules, $classCode, $academicYear)
    {
        foreach ($matricules as $matricule) {
            if (empty($matricule) || empty($academicYear)) {
            } else {
                $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
                AcademicLevel::updateOrCreate($matricule, $classCode, $nextAcademicYear,1);
            }
        }
        return 0;
    }

    /**
     * @param $matricules
     * @return int
     */
    public static function nextClassPromotion($matricules, $classCode, $academicYear)
    {
        foreach ($matricules as $matricule) {
            if (empty($matricule) || empty($academicYear)) {
            } else {
                $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
                $class = AcademicLevel::getClassByCode($classCode);
                if(strcmp($class->next_promotion_class, trans('general.university_code')) == 0 || strcmp($class->next_promotion_class, trans('general.none')) == 0){
                    $promotionClass =null;
                }else {
                    $promotionClass = AcademicLevel::getClassByCode($class->next_promotion_class);
                }
                if (empty($promotionClass)) {
                } else {
                    AcademicLevel::updateOrCreate($matricule, $promotionClass->class_code, $nextAcademicYear,1);
                }
            }
        }
        return 0;
    }

}
