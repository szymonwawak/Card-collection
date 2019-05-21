<?php

namespace App\Http\Controllers;

use App\Card;
use App\CardProposition;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $cards = Card::all();
      return view('cards.index')->with('cards',$cards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("cards.create");
    }

    public function createFromProposition($id)
    {
        $proposition = CardProposition::find($id);
        return view("cards.createFromProposition", compact('proposition'));

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cardName' => 'required|max:40',
            'cardDescription' => 'required|max:200',
            'cardCost' => 'required|integer',
            'cardAttack' => 'required|integer',
            'cardHealth' => 'required|integer',
            'cardRarity' => 'required|',
            'cardFraction' => 'required|',
            'image' => 'required|image|mimes:png|max:2048'
        ]);

        $card = new Card();
        $card->name = request('cardName');
        $card->description = request('cardDescription');
        $card->cost = request('cardCost');
        $card->attack = request('cardAttack');
        $card->health = request('cardHealth');
        $card->rarity = request('cardRarity');
        $card->fraction = request('cardFraction');
        if (request('cardRarity') === 'Zwyczajna') {
            $card->scraps_cost = 20;
            $card->scraps_earned = 5;
        } else if (request('cardRarity') === 'Rzadka') {
            $card->scraps_cost = 80;
            $card->scraps_earned = 20;
        } else if (request('cardRarity') === 'Epicka') {
            $card->scraps_cost = 400;
            $card->scraps_earned = 100;
        } else if (request('cardRarity') === 'Legendarna') {
            $card->scraps_cost = 1600;
            $card->scraps_earned = 400;
        }
        $image = $request->file('image');
        $imageName = $image->getClientOriginalName().'.'.$image->getClientOriginalExtension();

        $image->move(public_path('images'),$imageName);
        $card->filename = $imageName;
        $card->save();

        return redirect('propositions')->with('success', 'Dziękujemy za przesłanie nam własnej propozycji! :)');
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
        $proposition = CardProposition::find($id);


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
}
