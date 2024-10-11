<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StudentExamInterface;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Question;
use App\Models\StudentExam;
use App\Models\StudentExamAnswer;
use App\Models\SystemAnswer;

use App\Models\User;
use Illuminate\Support\Facades\Validator;


class StudentExamRepository implements StudentExamInterface{

    use ApiDesignTrait;

    private $examModel;
    private $questionModel;
    private $studentExamModel;
    private $systemAnswerModel;
    private $studentExamAnswer;
    private $userModel;




    public function __construct(User $user, Exam $exam, Question $question, StudentExam $studentExam, SystemAnswer $systemAnswer, StudentExamAnswer $studentExamAnswer) {


        $this->userModel = $user;
        $this->examModel = $exam;
        $this->questionModel = $question;
        $this->studentExamModel = $studentExam;
        $this->systemAnswerModel = $systemAnswer;
        $this->studentExamAnswer = $studentExamAnswer;

    }


//    public function newExams()
//    {
//        // TODO: Implement newExams() method.
//
//        $userId = auth()->user()->id;
//        $userRole = auth()->user()->roleName->name;
//
////        $data = $this->examModel::where('is_closed', 0)->whereHas('students', function ($query) use($userId){
////            return $query->where([['student_id', $userId], ['count', '>=', 1]]);
////        })->get();
//        $data = $this->examModel::where('is_closed', 0)->whereHas('students', function ($query) use($userId){
//             $query->where('student_id', $userId)->whereHas('groups', function ($query) {
//                 $query->where('count', '>=', 1);
//             });
//        })->get();
//
//
//        return $this->apiResponse(200, 'New Exams', null, $data);
//    }
//
//
//    public function oldExams()
//    {
//        // TODO: Implement oldExams() method.
//        // TODO: Implement newExams() method.
//
//        $userId = auth()->user()->id;
//        $userRole = auth()->user()->roleName->name;
//
////        $data = $this->examModel::where('is_closed', 1)->whereHas('students', function ($query) use($userId){
////            return $query->where('student_id', $userId);
////        })->get();
////        $data = $this->examModel::where('is_closed', 1)->whereHas('students', function ($query) use($userId){
////            $query->where('student_id', $userId)->whereHas('groups', function ($query) {
////                $query->where('count', '>=', 1);
////            });
////        })->get();
//        $data = $this->examModel::where('is_closed', 1)->whereHas('students', function ($query) use($userId){
//            return $query->where('student_id', $userId);
//        })->get();
//
//
//
//        return $this->apiResponse(200, 'Old Exams', null, $data);
//
//    }
//
//    public function newStudentExam($request)
//    {
//        // TODO: Implement newStudentExam() method.
//
//
//        $validator = Validator::make($request->all(),[
//            'exam_id'  => 'required|exists:exams,id',
//        ]);
//
//        if($validator->fails()){
//            return $this->apiResponse(422,'Error',$validator->errors());
//        }
//        $userId = auth()->user()->id;
////        dd($userId);
//        $questionsCount = $this->examModel::find($request->exam_id);
////        dd($questionsCount);
//
////        $questions = $this->questionModel->where('exam_id', $request->exam_id)->inRandomOrder()
////            ->Limit($questionsCount->count)-> with('questionImage')->get();
//
//        $exam = $this->examModel::where('id', $request->exam_id)->first();
////        dd($exam);
////        dd($exam->id);
////        $questions = $this->questionModel::whereHas('exams', function ($query) use($exam){
////            return $query->where('exam_id', $exam->id);
////        })->whereHas('studentGroups', function ($query) use($userId){
////        return $query->where('student_id', $userId);
////        })
////            ->Limit($questionsCount->count)-> with('questionImage')
////            ->get();
////        $questions = $this->questionModel::whereHas('exams', function ($query) use($exam){
////            return $query->where('exam_id', $exam->id);
////        })->get();
////        dd($questions);
//
//        $questions = $this->questionModel::whereHas('exams', function ($query) use($exam, $userId){
//            $query->where('exam_id', $exam->id)->whereHas('students', function ($query)use($userId) {
//                $query->where('student_id', $userId);
//            });
//        })->get();
////        dd($questions);
//
//        return $this->apiResponse(200, 'Done', null, $questions);
//
//    }
//
//
//    public function storeStudentExam($request)
//    {
//        // TODO: Implement storeStudentExam() method.
//
//        //dd($request->all());
//        //return $request->question;
//
//
//        $examData = $this->examModel::whereHas('examTypes', function ($q) {
//            $q->where('is_mark', 1);
//        })->select('type_id', 'degree', 'count')->find($request->exam_id);
//
//        $setStudentExam = $this->studentExamModel::create([
//            'exam_id' => $request->exam_id,
//            'student_id' => auth()->user()->id,
//        ]);
//
//
//        if($examData){
//
//            $questionDegree = $examData->degree / $examData->count;
//            $totalDegree = 0;
//
//            //dd($questionDegree);
//
//            foreach ($request->questions as $question){
//
//                $getSystemAnswer = $this->systemAnswerModel::where('question_id', $question['question'])->first();
//                //dd($getSystemAnswer);
//
//
//
//
//                if($question['answer'] == $getSystemAnswer['answer']){
//
//                    //dd('done');
//
//                    $degree = $questionDegree;
//                    $totalDegree += $questionDegree;
//
//                }else {
//
//                    //dd('not');
//                    $degree = 0;
//
//                }
//
//                $this->studentExamAnswer::create([
//                    'student_exam_id' => $setStudentExam->id,
//                    'question_id' => $question['question'],
//                    'answer' => $question['answer'],
//                    'degree' => $degree,
//                ]);
//            }
//
//            $setStudentExam->update([
//               'total_degree' => $totalDegree,
//            ]);
//
//            return $this->ApiResponse(200, 'Done', null, $totalDegree);
//
//
//        }else{
//
//            foreach ($request->questions as $question){
//
//                $this->studentExamAnswer::create([
//                    'student_exam_id' => $setStudentExam->id,
//                    'question_id' => $question['question'],
//                    'answer' => $question['answer'],
//                ]);
//
//            }
//
//
//        }

//    }



}
