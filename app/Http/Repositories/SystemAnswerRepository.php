<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StudentExamInterface;
use App\Http\Interfaces\SystemAnswerInterface;
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


class SystemAnswerRepository implements SystemAnswerInterface{

    use ApiDesignTrait;

    private $examModel;
    private $questionModel;
    private $studentExamModel;
    private $systemAnswerModel;
    private $userModel;




    public function __construct(User $user, Exam $exam, Question $question, SystemAnswer $systemAnswer) {


        $this->userModel = $user;
        $this->examModel = $exam;
        $this->questionModel = $question;
        $this->systemAnswerModel = $systemAnswer;

    }


    public function getAnswer()
    {
        // TODO: Implement getAnswer() method.

        $data = $this->systemAnswerModel->with('question')->get();
        return $this->ApiResponse(200, 'System Answers', null, $data);
    }

    public function addAnswer($request)
    {
        // TODO: Implement addAnswer() method.

        $validator = Validator::make($request->all(), [

            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|numeric'
        ]);


        if ($validator->fails()) {
            return $this->apiResponse(422, 'Error', $validator->errors());
        }

        $question = $this->questionModel->where('id', $request->question_id)->first();
        $answer = $this->systemAnswerModel->create([
            'question_id' => $request->question_id,
            'answer' => $request->answer,
        ]);

        return $this->ApiResponse(200, 'System Answer Added Successfully', null, $answer);
    }

    public function upadteAnswer($request)
    {
        // TODO: Implement upadteAnswer() method.
    }

    public function deleteAnswer($request)
    {
        // TODO: Implement deleteAnswer() method.
    }
}
