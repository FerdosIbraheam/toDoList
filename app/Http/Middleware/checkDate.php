<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $url = $request->path();

        $id =  filter_var($url,FILTER_SANITIZE_NUMBER_INT);


         $task_data = DB :: table('task')->find($id);
        if (date()<= $task_data->end_date) {

            return $next($request);
        } else {
            session()->flash('Message', "Can't Remove Your this task ");
            return redirect(url('/task'));
        }
    }
}
