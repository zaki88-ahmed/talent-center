<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiDesignTrait;
use Closure;
use Illuminate\Http\Request;

class Roles
{

    use ApiDesignTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {

        $userRole = auth()->user()->roleName->name;
//        dd($userRole);

        $allowRoles = explode('.', $roles);
//        dd($allowRoles);


        if(!in_array($userRole, $allowRoles)){
            return $this->ApiResponse(422, "user don't have promotions");
        }
        return $next($request);
    }
}
