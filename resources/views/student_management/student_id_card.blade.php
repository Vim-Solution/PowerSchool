<style>
    #card {
        float: left;
        width: 360px;
        height: 230px;
        margin: 5px;
        border: 1px solid black;
        background-image: url("/images/avatars/id_design.jpg");
        background-repeat: no-repeat;
        background-size: 360px 230px;
        -webkit-print-color-adjust: exact;
        position: relative;
        left: 25%;
    }

    #c_left {
        margin-top: 65px;
        margin-left: 10px;
        float: left;
        width: 80px;
        height: 120px;
    }

    #c_box {
        width: 80px;
        height: 20px;
        padding: 5px;
    }

    #c_right {

        margin-left: 120px;
        width: 220px;
        height: 200px;
    }

    td {
        font-size: 12px;
    }

</style>
<div class="card">
    <div class="card-body">
        <div id="card">
            <div id="c_left">
                <img src="{{ asset($student->profile) }}" width="80px" height="100px" style="border:1px solid black;"><br>
                <div id="c_box">
                    Class : {{  \App\Student::getStudentClassNameByMatricule($student->matricule)}}
                </div>
            </div>
            <div id="c_right">
                <div style="margin-top:2px;margin-left:117px;color:#fff;font-size:10px;">Contact No. 675669236<br></div>
                <div style="margin-left:168px;color:#fff;font-size:10px;"> 675669236 <br></div>
                <div style="margin-top:4px;margin-left:90px;color:#fff;">Mat: {{ $student->matricule }} <br></div>
                <table style="margin-top:23px;">
                    <tr>
                        <td><b>Name</b></td>
                        <td><b>: {{ $student->full_name }} </b></td>
                    </tr>
                    <tr>
                        <td><b>Date Of Birth</b></td>
                        <td>: {{  date('F d ,Y',strtotime($student->date_of_birth)) }}</td>
                    </tr>
                    <td><b>Place Of Birth</b></td>
                    <td>:{{$student->place_of_birth }} </td>
                    </tr>
                    <tr>
                        <td><b>Contact No.</b></td>
                        <td>: {{$student->tutor_address}}</td>
                    </tr>
                    <tr>
                        <td><b>Admission date.</b></td>
                        <td>: {{ date('F d ,Y',strtotime($student->admission_date)) }}</td>
                    </tr>
                    <tr>
                        <td><i>Principal</i></td>
                        <td><img src="{{asset(trans('img/img.sign'))}}" width="100px" height="30px"></td>
                    </tr>
                </table>

            </div>
        </div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <a href="{{trans('settings/routes.download_id_card') . '/' . \App\Encrypter::encrypt($student->student_id) }}"
           class="btn c-ewangclarks " style="width: 40%;position: relative;left: 25%;"><h6 class="text-white" style="font-size: 12px;"><i
                    class="zmdi zmdi-lock-outline"></i>{{trans('student_management/generate_id.download_id')}}
            </h6></a>
    </div>
</div>

