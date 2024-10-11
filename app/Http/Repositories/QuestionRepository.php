<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Interfaces\ExamInterface;
use App\Http\Interfaces\QuestionInterface;
use App\Http\Resources\Resources\ExamCollection;
use App\Http\Resources\Resources\QuestionResource;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Question;
use App\Models\QuestionImage;
use App\Models\Role;
use App\Models\StudentGroup;
use App\Models\SystemAnswer;
use App\Models\User;

use App\Http\Interfaces\StaffInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Collection;

class QuestionRepository implements QuestionInterface{

    use ApiDesignTrait;
    use UploadImageTrait;
    use FileUploadTrait;


    private $question;
    private $exam;
    private $systemAnswer;
    private $examType;
    private $questionImage;


    public function __construct(Question $question, Exam $exam, SystemAnswer $systemAnswer, ExamType $examType, QuestionImage $questionImage) {

        $this->question = $question;
        $this->exam = $exam;
        $this->systemAnswer = $systemAnswer;
        $this->examType = $examType;
        $this->questionImage = $questionImage;

    }


    public function allQuestions($request)
    {
        // TODO: Implement allQuestions() method.

        $validator = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
        ]);

//        dd(auth()->user()->id);
//        dd(auth()->user()->ip());
//        dd($request->ip());

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }

        $data = $this->question->where('exam_id', $request->exam_id)->get();
//        return $this->ApiResponse('200', 'All Questions', null, $data);
        return $this->ApiResponse('200', 'All Questions', null, QuestionResource::collection($data));

    }

    public function addQuestion($request)
    {
        // TODO: Implement addQuestion() method.

//        dd('cc');

        $validator = Validator::make($request->all(),[

            'title' => 'required',
            'exam_id' => 'required|exists:exams,id',
            'image' => 'required|array',
            'image.*' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Errors',$validator->errors());
        }

        $question = $this->question->create([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
        ]);

        if($request->image){
//            $this->questionImage->create([
//                'image' => $this->uploadImage($request, 'question_image'),
//                'question_id' => $question->id
//            ]);
//            $file = $request->image;
//            $fileName = $file->getClientOriginalName();
//            $file->storePubliclyAs('question_image' . $question->id, $fileName, 's3');
//            $question->update([
//                'image' => $fileName,
//            ]);
//            dd($fileName);
            $this->uploadFile($request->image, 'question_image', $question, $this->questionImage,'question_id');
//            dd($fileName);
//            dd($question->id);
        }


//        return Storage::disk('s3')->response('questions' . $question->id . '/' . $question->image);

        $exam = $this->exam->where('id', $request->exam_id)->first();
//        dd($exam);

        if($exam){

            $validator = Validator::make($request->all(),[
                'answer' => 'required',
            ]);

            if($validator->fails()){
                return $this->apiResponse(422,'Errors',$validator->errors());
            }

            $this->addQuestionAnswer($request->answer, $question->id);
            }

        return $this->ApiResponse(200, 'Added Successfully', null, new QuestionResource($question));
    }


    public function addQuestionAnswer($answer, $questionId){

        $this->systemAnswer->create([
            'question_id' => $questionId,
            'answer' => $answer,
        ]);
    }


    public function updateQuestion($request)
    {
        // TODO: Implement updateQuestion() method.



        $validator = Validator::make($request->all(),[

            'title' => 'required',
            'question_id' => 'required|exists:questions,id',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);


        if($validator->fails()){
            return $this->apiResponse(422,'Errors',$validator->errors());
        }


        $question = $this->question->find($request->question_id);
        $questionImage = $this->questionImage->where('question_id', $request->question_id)->first();
//        dd($questionImage->image);

        if($request->image){
            $this->deleteImage('question_image', $questionImage->image);
            $questionImage->delete();
            $this->questionImage->create([
                'image' => $this->uploadImage($request, 'question_image'),
                'question_id' => $question->id
            ]);
        }

        $question->update([
            'title' => $request->title,
        ]);

        if($question->has('answer')){

            $this->systemAnswer->where('question_id', $question->id)->update([
               'answer' => $request->answer,
            ]);

        }

        return $this->apiResponse(200, 'Updated Successfully');
    }


    public function deleteQuestion($request)
    {
        // TODO: Implement deleteQuestion() method.

        $validator = Validator::make($request->all(),[
            'question_id' => 'required|exists:questions,id',
        ]);

        $questionImage = $this->questionImage->where('question_id', $request->question_id)->first();
//        $this->deleteImage('question_image', $questionImage->image);
        $this->deleteFile();
//        $questionImage->delete();

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }

//        $this->question::find($request->question_id)->delete();

        return $this->apiResponse(200, 'Deleted Successfully');
    }

    public function questionImages($request)
    {
        // TODO: Implement questionImages() method.
        $validator = Validator::make($request->all(),[
            'question_id' => 'required|exists:questions,id',
        ]);

        $questionImages = $this->questionImage->where('question_id', $request->question_id)->get();

        if($questionImages){
            return $this->apiResponse(200, 'Question images', null, $questionImages);
        }
        return $this->apiResponse(200, 'No Images');
    }
}
