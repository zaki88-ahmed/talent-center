<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Interfaces\ExamInterface;
use App\Http\Resources\Resources\ExamCollection;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Http\Traits\FileUploaderTrait;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamType;
use App\Models\Question;
use App\Models\Role;
use App\Models\StudentExam;
use App\Models\StudentExamAnswer;
use App\Models\StudentGroup;
use App\Models\SystemAnswer;
use App\Models\User;

use App\Http\Interfaces\StaffInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ExamRepository implements ExamInterface
{

    use ApiDesignTrait;
    use FileUploaderTrait;

    private $examType;
    private $exam;
    private $studentGroup;
    private $studentExam;
    private $studentExamAnswer;
    private $examMark;
    private $userModel;
    private $question;
    private $systemAnswerModel;


    public function __construct(Question $question, User $user, ExamType $examType, Exam $exam, StudentGroup $studentGroup, StudentExam $studentExam, StudentExamAnswer $studentExamAnswer, ExamMark $examMark, SystemAnswer $systemAnswer)
    {
        $this->userModel = $user;
        $this->examType = $examType;
        $this->exam = $exam;
        $this->studentGroup = $studentGroup;
        $this->studentExam = $studentExam;
        $this->studentExamAnswer = $studentExamAnswer;
        $this->examMark = $examMark;
        $this->question = $question;
        $this->systemAnswerModel = $systemAnswer;
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

            $data = $this->exam->where('teacher_id', $userId)->with('students')->get();

        } elseif ($userRole == 'Student') {

//            dd($userId);
//            $data = $this->exam->with('students')->get();
            $data = $this->exam->whereHas('students', function ($q) use ($userId) {
                $q->where('student_id', $userId);
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
            'group_id' => 'required|exists:groups,id',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
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
//            'image' => $this->uploadFile($request->image, 'https://s3.console.aws.amazon.com/s3/buckets/talent-center?region=us-east-1&tab=objects'),
        ]);

        return $this->ApiResponse(200, 'Added Successfully', null, $exam);
//        return $this->ApiResponse(200, 'Added Successfully', null, new ExamCollection($exam));
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


        $data = $this->exam::where('id', $request->exam_id)->with('students')->get();

        return $this->ApiResponse(200, 'Done',null ,$data);
    }



    public function examStudentDetails($request){

        $validation = Validator::make($request->all(), [

            'student_exam_id' => 'required|exists:exams,id',

        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Errors', $validation->errors());
        }

        $markedExam = $this->exam::where('id', $request->student_exam_id)
            ->whereHas('examTypes', function ($q) {
                $q->where('is_mark', 1);
            })->with('questions')->first();

        if($markedExam){
//            $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)->with(['questionData', 'questionAnswer'])->get();

            return $this->ApiResponse(200, 'Done',null ,$markedExam);

        }
            return $this->ApiResponse(200, 'Done',null ,'Exam Not Marked');

    }


    public function newExams()
    {
        // TODO: Implement newExams() method.

        $userId = auth()->user()->id;
        $userRole = auth()->user()->roleName->name;

        $data = $this->exam::where('is_closed', 0)->whereHas('students', function ($query) use($userId){
            $query->where('student_id', $userId)->whereHas('groups', function ($query) {
                $query->where('count', '>=', 1);
            });
        })->get();


        return $this->apiResponse(200, 'New Exams', null, $data);
    }


    public function oldExams()
    {
        // TODO: Implement oldExams() method.
        // TODO: Implement newExams() method.

        $userId = auth()->user()->id;
        $userRole = auth()->user()->roleName->name;

        $data = $this->exam::where('is_closed', 1)->whereHas('students', function ($query) use($userId){
            return $query->where('student_id', $userId);
        })->get();



        return $this->apiResponse(200, 'Old Exams', null, $data);

    }




    public function newStudentExam($request)
    {
        // TODO: Implement newStudentExam() method.


        $validator = Validator::make($request->all(),[
            'exam_id'  => 'required|exists:exams,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $userId = auth()->user()->id;

        $exam = $this->exam->find($request->exam_id);


        $questions = $this->question::whereHas('exams', function ($query) use($exam){
            $query->where('exam_id', $exam->id)->where('is_closed', 0);
        })->get();

        return $this->apiResponse(200, 'Done', null, $questions);

    }




    public function storeStudentExam($request)
    {
        // TODO: Implement storeStudentExam() method.

        $validator = Validator::make($request->all(),[
            'exam_id'  => 'required|exists:exams,id',
//            'question_id'  => 'required|exists:questions,id',
            'answers'  => 'required|array',
            'answers.*' => 'required|in:1,2,3,4',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }

        $exam = $this->exam->find($request->exam_id);

        $totalQuestionNum = $exam->questions->count();


        $point = 0;
        foreach ($exam->questions as $question) {
            if(isset($request->answers[$question->id])){
                $systemAnswer = $this->systemAnswerModel->where('question_id', $question->id)->first();
                $userAns = $request->answers[$question->id];
                $rightAns = $systemAnswer->answer;
            }
            if($userAns == $rightAns){
                $point += 1;
            }
        }

//        $questionIds = $exam->questions->pluck('id')->all();
        $score = ($point/$totalQuestionNum) * 100;

        $student = auth()->user();
         $studentExam = $this->addStudentExam($exam->id, $student, $score);

        $questionDegree = $exam->degree / $totalQuestionNum;

        foreach ($exam->questions as $question) {
            $this->addStudentExamAnswer($studentExam, $question, $request);
        }

        return $this->ApiResponse(200, 'Student Score ', null, "$score %");

    }


    public function addStudentExam($examId, $student, $score)
    {
        // TODO: Implement addStudentExam() method.

        $data = [
            'exam_id'  => $examId,
            'student_id'  => $student->id,
            'total_degree'  => $score,
        ];
          $student->exams()->attach($student->id, $data);
        $pivotRow = $student->exams()->where('exam_id', $examId)->first();
            return $pivotRow;
    }


    public function addStudentExamAnswer($studentExam, $question, $request)
    {
        // TODO: Implement addStudentExam() method.

        $pivotRow = $this->studentExamAnswer::create([
                'student_exam_id' => $studentExam->id,
                'question_id' => $question->id,
                'answer' => $request->answers[$question->id]
            ]);
        return $pivotRow;
    }

}
