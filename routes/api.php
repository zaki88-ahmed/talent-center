<?php

use App\Http\Controllers\EndUserController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\SystemAnswerController;
use App\Http\Controllers\TeachersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

/* Route::get('test/{name}', [ApiController::class, 'testApi']); */

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);


    Route::post('add/user/test', [AuthController::class, 'addTestUser']);

});


Route::get('test',[AuthController::class, 'test']);


//Route::group(['prefix' => 'admin'], function(){
//
//});

//Route::group(['prefix' => 'admin'], function(){
//
//});
//
//
//
//Route::group(['prefix' => 'admin'], function(){
//
//});




Route::group(['prefix' => 'admin', 'middleware' => ['jwt.token', 'roles:Admin']], function() {

    /** Start Staff Routes */
    Route::post('staff/add', [StaffController::class, 'addStaff']);
    Route::get('staff/all', [StaffController::class, 'allStaff']);
    Route::post('staff/delete', [StaffController::class, 'deleteStaff']);
    Route::get('staff/specific', [StaffController::class, 'specificStaff']);
    Route::post('staff/update', [StaffController::class, 'updateStaff']);

});



Route::group(['prefix' => 'dashboard', 'middleware' => 'roles:Admin.Support.Secretary'], function(){


    /** Start student Routes */
    Route::post('student/add', [StudentController::class, 'addStudent']);
    Route::get('student/all', [StudentController::class, 'allStudents']);
    Route::post('student/delete', [StudentController::class, 'deleteStudent']);
    Route::get('student/specific', [StudentController::class, 'specificStudent']);
    Route::post('student/update', [StudentController::class, 'updateStudent']);





    /** Start group Routes */
    Route::post('group/add', [GroupController::class, 'addGroup']);
    Route::get('group/all', [GroupController::class, 'allGroups']);
    Route::post('group/delete', [GroupController::class, 'deleteGroup']);
    Route::get('group/specific', [GroupController::class, 'specificGroup']);
    Route::post('group/update', [GroupController::class, 'updateGroup']);


    /** Start teacher Routes */
    Route::post('teacher/add', [TeachersController::class, 'addTeacher']);
    Route::get('teacher/all', [TeachersController::class, 'allTeachers']);
    Route::post('teacher/delete', [TeachersController::class, 'deleteTeacher']);
    Route::get('teacher/specific', [TeachersController::class, 'specificTeacher']);
    Route::post('teacher/update', [TeachersController::class, 'updateTeacher']);


});

/** Start EndUser Routes */
Route::get('enduser/groups', [EndUserController::class, 'userGroups']);

    Route::group(['prefix' => 'teacher', 'middleware' => ['jwt.token', 'roles:Teacher']], function() {

    /** Start Exam Routes */
    Route::get('exams/types', [ExamController::class, 'examTypes']);
    Route::get('exams/all', [ExamController::class, 'allExams']);
    Route::post('exam/add', [ExamController::class, 'addExam']);
    Route::post('exam/delete', [ExamController::class, 'deleteExam']);
    Route::post('exam/update', [ExamController::class, 'updateExam']);
    Route::post('exam/status/update', [ExamController::class, 'updateExamStatus']);
    Route::post('students/exams/add', [ExamController::class, 'addStudentExam']);


    Route::get('exams/students', [ExamController::class, 'examStudents']);
    Route::get('exams/students/details', [ExamController::class, 'examStudentDetails']);


    Route::get('answers/all', [SystemAnswerController::class, 'getAnswer']);
    Route::post('answers/add', [SystemAnswerController::class, 'addAnswer']);
    Route::post('answers/update', [SystemAnswerController::class, 'upadteAnswer']);
    Route::post('answers/delete', [SystemAnswerController::class, 'deleteAnswer']);

    Route::post('questions/all', [QuestionController::class, 'allQuestions']);
    Route::post('question/add', [QuestionController::class, 'addQuestion']);
    Route::post('question/update', [QuestionController::class, 'updateQuestion']);
    Route::post('question/delete', [QuestionController::class, 'deleteQuestion']);
    Route::post('question/images', [QuestionController::class, 'questionImages']);

    /** Start File Routes */
    Route::post('file/add', [TeachersController::class, 'addFile']);
    Route::post('files', [TeachersController::class, 'allFiles']);
    Route::post('specificFile', [TeachersController::class, 'specificFile']);
    Route::post('deleteFile', [TeachersController::class, 'deleteFile']);

});





Route::group(['prefix' => 'student', 'middleware' => ['jwt.token', 'roles:Student']], function() {

    Route::get('exams/all', [ExamController::class, 'allExams']);
    Route::post('exams/student/new', [ExamController::class, 'newStudentExam']);
    Route::post('exams/students/store', [ExamController::class, 'storeStudentExam']);

    Route::get('exams/new', [ExamController::class, 'newExams']);
    Route::get('exams/old', [ExamController::class, 'oldExams']);

    Route::post('questions/all', [QuestionController::class, 'allQuestions']);


});
