<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublishStatus extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $table = 'publish_status';


    /**
     * @param $sequenceId
     * @param $academicYear
     */
    public static function updateOrCreate($sequenceId, $academicYear)
    {
        DB::table(trans('database/table.publish_status'))
            ->updateOrInsert([trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                trans('database/table.academic_year') => $academicYear, trans('database/table.sequences_sequence_id') => $sequenceId],
                [trans('database/table.publish_state') => 1]);
    }

    /**
     * @param $sequenceId
     * @param $academicYear
     * @param $sectionCode
     * @return bool
     */
    public static function sequenceResultExistance($sequenceId, $academicYear, $sectionCode)
    {
        $publishStatus = DB::table(trans('database/table.publish_status'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'),$sequenceId)
            ->first();

        if (empty($publishStatus)) {
            return false;
        }
        if ($publishStatus->publish_state == 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $termId
     * @param $academicYear
     * @param $sectionCode
     * @return mixed
     */
    public static function termResultExistance($termId, $academicYear, $sectionCode)
    {
        $sequences = Sequence::where(trans('database/table.terms_term_id'), $termId)->get();
        $sequenceIds = $sequences->pluck(trans('database/table.sequence_id'))->toArray();
        $publishStatus = DB::table(trans('database/table.publish_status'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->whereIn(trans('database/table.sequences_sequence_id'), $sequenceIds)
            ->get();
        $publishStatusIds = $publishStatus->unique(trans('database/table.sequences_sequence_id'))->pluck(trans('database/table.sequences_sequence_id'))->toArray();
        if($publishStatus->count() == $sequences->count()){
            $data['status'] = 1;
            $data['sequence_name'] = null;
        }else{
            $data['status'] = 0;
            $np_sequences = $sequences->whereNotIn(trans('database/table.sequence_id'),$publishStatusIds);
            $data['sequence_name'] = '<ul>';
            foreach ($np_sequences as $sequence){
                $data['sequence_name'] .= '<li>' . $sequence->sequence_name . ' ' . trans('general.result') . '</li>';
            }
            $data['sequence_name'] .= '</ul>';
        }

        return $data;
    }
}
