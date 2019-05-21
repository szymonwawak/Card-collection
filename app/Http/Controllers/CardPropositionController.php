<?php

namespace App\Http\Controllers;

use App\CardProposition;
use Illuminate\Http\Request;

class CardPropositionController extends Controller
{
    public function index()
    {
        $cardPropositions = CardProposition::all();
        return view("propositions.index")->with('cardPropositions', $cardPropositions);
    }

    public function create()
    {
        return view("propositions.create");
    }

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
        ]);

        $cardProposition = new CardProposition();
        $cardProposition->name = request('cardName');
        $cardProposition->description = request('cardDescription');
        $cardProposition->cost = request('cardCost');
        $cardProposition->attack = request('cardAttack');
        $cardProposition->health = request('cardHealth');
        $cardProposition->rarity = request('cardRarity');
        $cardProposition->fraction = request('cardFraction');
        if (request('cardRarity') === 'Zwyczajna') {
            $cardProposition->scraps_cost = 20;
            $cardProposition->scraps_earned = 5;
        } else if (request('cardRarity') === 'Rzadka') {
            $cardProposition->scraps_cost = 80;
            $cardProposition->scraps_earned = 20;
        } else if (request('cardRarity') === 'Epicka') {
            $cardProposition->scraps_cost = 400;
            $cardProposition->scraps_earned = 100;
        } else if (request('cardRarity') === 'Legendarna') {
            $cardProposition->scraps_cost = 1600;
            $cardProposition->scraps_earned = 400;
        }
        $cardProposition->user_name = $request->user()->name;
        $cardProposition->save();

        return redirect('propositions')->with('success', 'Dziękujemy za przesłanie nam własnej propozycji! :)');
    }

    public function edit($id)
    {
        $proposition = CardProposition::find($id);
        return view('propositions.edit', compact('proposition'));
    }
    public function update(Request $request, $id){

        $validatedData = $request->validate([
            'cardName' => 'required|max:40',
            'cardDescription' => 'required|max:200',
            'cardCost' => 'required|integer',
            'cardAttack' => 'required|integer',
            'cardHealth' => 'required|integer',
            'cardRarity' => 'required|',
            'cardFraction' => 'required|',
        ]);

        $cardProposition = CardProposition::find($id);
        $cardProposition->name = request('cardName');
        $cardProposition->description = request('cardDescription');
        $cardProposition->cost = request('cardCost');
        $cardProposition->attack = request('cardAttack');
        $cardProposition->health = request('cardHealth');
        $cardProposition->rarity = request('cardRarity');
        $cardProposition->fraction = request('cardFraction');
        if (request('cardRarity') === 'Zwyczajna') {
            $cardProposition->scraps_cost = 20;
            $cardProposition->scraps_earned = 5;
        } else if (request('cardRarity') === 'Rzadka') {
            $cardProposition->scraps_cost = 80;
            $cardProposition->scraps_earned = 20;
        } else if (request('cardRarity') === 'Epicka') {
            $cardProposition->scraps_cost = 400;
            $cardProposition->scraps_earned = 100;
        } else if (request('cardRarity') === 'Legendarna') {
            $cardProposition->scraps_cost = 1600;
            $cardProposition->scraps_earned = 400;
        }

        $cardProposition->save();
        return redirect ('/propositions')->with('success', 'Edycja powiodła się');
    }
    public function destroy($id){
        CardProposition::find($id)->delete();
        return redirect('/propositions')->with('success', 'Usuwanie pomyślne');
    }
}