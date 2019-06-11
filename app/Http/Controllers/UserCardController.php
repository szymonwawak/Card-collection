<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class UserCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|View
     * @throws Throwable
     */
    public function index(Request $request)
    {

        $collection = Auth::user()->cards()->paginate(4);
        if ($request->ajax()){
            $sections = view('collection.index')->with('collection', $collection)->renderSections();
            return $sections['content'];
        }
        return view('collection.index')->with('collection',$collection);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function show($id, Request $request)
    {
        $user = User::find($id);
        $collection = $user->cards()->paginate(4);
        if ($request->ajax()){
            $sections = view('collection.guest')->with(compact('id','collection'))->renderSections();
            return $sections['content'];
        }
        return view('collection.guest')->with(compact('id','collection'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
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
        //
    }
    public function getUserCollection($userId){

        $collection = User::find($userId)->cards();
        return $collection;
    }

    public function getWholeCollection ($userId){
        $collection = DB::table('cards');
        return $collection;
    }

    public function getMissingCards ($userId){
        $collection = DB::table('cards')->whereNotIn('id', (DB::table('collections')
            ->where('user_id','=', $userId)->pluck('card_id')));
     return $collection;
    }

    public function search(Request $request){
        $collection = $this->searchInCollection($request);
        if ($request->ajax()){
            $sections = view('collection.index')->with('collection', $collection)->renderSections();
            return $sections['content'];
        }
        return view('collection.index')->with('collection',$collection);
    }

    public function searchAsGuest(Request $request){
        $id = request('id');
        $collection = $this->searchInCollection($request);
        if ($request->ajax()){
            $sections = view('collection.guest',compact('id','collection'))->renderSections();
            return $sections['content'];
        }
        return view('collection.guest',compact('id','collection'));
    }

    public function searchInCollection(Request $request){
        $userId = request('id');
        if($userId==null) $userId=$request->user()->id;

        $validatedData = $request->validate([
            'text' => 'max:200',
            'attack' => 'integer|nullable',
            'cost' => 'integer|nullable',
            'health' => 'integer|nullable',
            'rarity' => 'max:200',
            'fraction' => 'max:200'
        ]);

        $searchedCards = $request->get('collection');
        if($searchedCards=='users'){
            $searchedCards = UserCardController::getUserCollection($userId);
        }else if($searchedCards=='all'){
            $searchedCards = UserCardController::getWholeCollection();
        }else if($searchedCards=='missing'){
            $searchedCards = UserCardController::getMissingCards($userId);
        }


        $collection = $searchedCards -> where(function ($query) use ($validatedData) {
            $query->when($validatedData['text'] != '',
                function ($query) use ($validatedData) {
                    return $query->where('name', 'like', '%' . $validatedData['text'] . '%')
                        ->orWhere('description', 'like', '%'.$validatedData['text'].'%')
                        ->orWhere('cost', '=', $validatedData['cost'])
                        ->orWhere('health', '=', $validatedData['health'])
                        ->orWhere('attack', '=', $validatedData['attack'])
                        ->orWhere('fraction', '=', $validatedData['fraction'])
                        ->orWhere('rarity', '=', $validatedData['rarity']);
                }, function ($query) use ($validatedData) {
                    return $query->where('cost', '=', $validatedData['cost'])
                        ->orWhere('health', '=', $validatedData['health'])
                        ->orWhere('attack', '=', $validatedData['attack'])
                        ->orWhere('fraction', '=', $validatedData['fraction'])
                        ->orWhere('rarity', '=', $validatedData['rarity']);
                });
        })->paginate(8);

        return $collection;
    }
}
