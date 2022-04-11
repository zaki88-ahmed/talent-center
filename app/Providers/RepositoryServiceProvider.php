<?php

namespace App\Providers;

use App\Http\Interfaces\SystemAnswerInterface;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Interfaces\AuthInterface',
            'App\Http\Repositories\AuthRepository'
        );


        $this->app->bind(
            'App\Http\Interfaces\StaffInterface',
            'App\Http\Repositories\StaffRepository'
        );



        $this->app->bind(
            'App\Http\Interfaces\TeachersInterface',
            'App\Http\Repositories\TeachersRepository'
        );



        $this->app->bind(
            'App\Http\Interfaces\GroupInterface',
            'App\Http\Repositories\GroupRepository'
        );



        $this->app->bind(
            'App\Http\Interfaces\StudentInterface',
            'App\Http\Repositories\StudentRepository'
        );




        $this->app->bind(
            'App\Http\Interfaces\EndUserInterface',
            'App\Http\Repositories\EndUserRepository'
        );



        $this->app->bind(
            'App\Http\Interfaces\ExamInterface',
            'App\Http\Repositories\ExamRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\QuestionInterface',
            'App\Http\Repositories\QuestionRepository'
        );


        $this->app->bind(
            'App\Http\Interfaces\StudentExamInterface',
            'App\Http\Repositories\StudentExamRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\SystemAnswerInterface',
            'App\Http\Repositories\SystemAnswerRepository'
        );

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
