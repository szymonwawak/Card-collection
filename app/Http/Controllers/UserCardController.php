<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Card;
use Illuminate\Support\Facades\DB;

class UserCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = Auth::user()->cards()->paginate(8);
        return view ('collection.index',compact('collection'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getUserCollection(){
        $collection = Auth::user()->cards();
        return $collection;
    }

    public function getWholeCollection (){
        $collection = DB::table('cards');
        return $collection;
    }

    public function getMissingCards (){
        $userId=Auth::user()->id;
        $collection = DB::table('cards')->whereNotIn('id', (DB::table('collections')
            ->where('user_id','=', $userId)->pluck('card_id')));
     return $collection;
    }

    public function search(Request $request){

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
            $searchedCards = UserCardController::getUserCollection();
        }else if($searchedCards=='all'){
            $searchedCards = UserCardController::getWholeCollection();
        }else if($searchedCards=='missing'){
            $searchedCards = UserCardController::getMissingCards();
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

     return view ('collection.index',compact('collection'));
    }
}
