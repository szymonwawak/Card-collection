<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $users = DB::table('users')->orderBy('name', 'ASC')->paginate(10);
        if ($request->ajax()){
            $sections = view('users.index')->with('users', $users)->renderSections();
            return $sections['content'];
        }
        return view('users.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        User::find($id)->delete();
    }
    public function search(Request $request){
        $search = $request->get('search');
        $users = DB::table('users')->where('name', 'like', '%'.$search.'%' )
            ->paginate(10);
        if ($request->ajax()){
            $sections = view('users.index')->with('users', $users)->renderSections();
            return $sections['content'];
        }
        return view('users.index')->with('users', $users);
    }

}
