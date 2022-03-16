<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Interfaces\ExamInterface;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamType;
use App\Models\Role;
use App\Models\StudentExam;
use App\Models\StudentExamAnswer;
use App\Models\StudentGroup;
use App\Models\User;

use App\Http\Interfaces\StaffInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ExamRepository implements ExamInterface
{

    use ApiDesignTrait;

    private $examType;
    private $exam;
    private $studentGroup;
    private $studentExam;
    private $studentExamAnswer;
    private $examMark;


    public function __construct(ExamType $examType, Exam $exam, StudentGroup $studentGroup, StudentExam  $studentExam, StudentExamAnswer $studentExamAnswer, ExamMark $examMark)
    {

        $this->examType = $examType;
        $this->exam = $exam;
        $this->studentGroup = $studentGroup;
        $this->studentExam = $studentExam;
        $this->studentExamAnswer = $studentExamAnswer;
        $this->examMark = $examMark;

    }


    public function examTypes()
    {
        // TODO: Implement examTypes() method.

        $data = $this->examType->get();

        return $this->ApiResponse(200, 'done', null, $data);
    }


    public function allExams()
    {
        // TODO: Implement allExams() method.

        $userId = auth()->user()->id;
//        dd($userId);
        $userRole = auth()->user()->roleName->name;

//        dd($userRole);

        if ($userRole == 'Teacher') {

            $data = $this->exam->where('teacher_id', $userId)->get();

        } elseif ($userRole == 'Student') {

            $data = $this->exam->whereHas('studentGroups', function ($q) use ($userId) {
                $q->where([['student_id', $userId], ['count', '>=', 0]]);
            })->get();
        }

        return $this->apiResponse(200, 'Exams', null, $data);

    }


    public function addExam($request)
    {
        // TODO: Implement createExam() method.

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'time' => 'required',
            'degree' => 'required',
            //'count' => 'required',
            'type_id' => 'required|exists:exam_types,id',
            'group_id' => 'required|exists:groups,id'
        ]);


        if ($validator->fails()) {
            return $this->apiResponse(422, 'Error', $validator->errors());
        }


        $exam = $this->exam->create([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
            //'count' => $request->count,
            'type_id' => $request->type_id,
            'group_id' => $request->group_id,
            'teacher_id' => auth()->id(),
        ]);

        return $this->ApiResponse(200, 'Added Successfully', null, $exam);
    }


    public function updateExam($request)
    {
        // TODO: Implement updateExam() method.

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'time' => 'required',
            'degree' => 'required',
//            'count' => 'required',
            'exam_id' => 'required|exists:exams,id',
            'group_id' => 'required|exists:groups,id',

        ]);

        if ($validator->fails()) {
            return $this->apiResponse(422, 'Error', $validator->errors());
        }

        $exam = $this->exam->find($request->exam_id);


        $exam->update([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
//            'count' => $request->count,
            'group_id' => $request->group_id,
            'teacher_id' => auth()->id(),
        ]);

//        dd($exam);
        return $this->ApiResponse(200, 'Updated Successfully', null, $exam);
    }


    public function deleteExam($request)
    {
        // TODO: Implement deleteExam() method.

        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(422, 'Error', $validator->errors());
        }

        $this->exam->find($request->exam_id)->delete();

        return $this->ApiResponse(200, 'Deleted');

    }


    public function updateExamStatus($request)
    {
        // TODO: Implement updateExamStatus() method.


        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(422, 'Error', $validator->errors());
        }


        $data = $this->exam->find($request->exam_id);
        $data->update([
            'is_closed' => $request->status,
        ]);

        return $this->ApiResponse(200, 'Exam Status Updated', null, $data);

    }


    /*Start Exam Operation*/

    public function examStudents($request)
    {
//        dd('aa');
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Errors', $validation->errors());
        }


//        $exam = $this->exam->find($request->exam_id);
//        dd($exam);

        $data = $this->studentExam::where('exam_id', $request->exam_id)->with('StudentData')->get();

        return $this->ApiResponse(200, 'Done',null ,$data);
    }



    public function examStudentDetails($request){

        $validation = Validator::make($request->all(), [

            'student_exam_id' => 'required|exists:student_exams,id',

        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Errors', $validation->errors());
        }


        $markedExam = $this->studentExam::where('id', $request->student_exam_id)
            ->whereHas('examData', function ($q) {
                $q->whereHas('examTypes', function ($q) {
                    $q->where('is_mark', 1);
                });
            })->first();


        if($markedExam){
//            $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)->with(['questionData', 'questionAnswer'])->get();

            $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)->with('questionData')->get();

        }else{

            $markedExam = $this->examMark::where('student_exam_id', $request->student_exam_id)->first();

            if($markedExam){
                $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)->get();

            }else{

                $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)
                    ->with('questionData')->get(['id', 'question_id', 'answer']);

            }
        }

        return $this->ApiResponse(200, 'Done',null ,$data);



    }


}
