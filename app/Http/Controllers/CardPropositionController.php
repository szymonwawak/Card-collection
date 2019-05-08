<?php

namespace App\Http\Controllers;

use App\CardProposition;
use Illuminate\Http\Request;

class CardPropositionController extends Controller
{
    public function index()
    {
        return view("propose.create");
    }
    public function store(Request $request){

        $validatedData = $request->validate([
            'cardName' => 'required|max:40',
            'cardDescription' => 'required|max:200',
            'cardCost' => 'required|integer',
            'cardAttack' => 'required|integer',
            'cardHealth' => 'required|integer',
            'cardRarity' => 'required|',
        ]);

        $cardProposition = new CardProposition();
        $cardProposition->name  = request('cardName');
        $cardProposition->description = request('cardDescription');
        $cardProposition->cost = request('cardCost');
        $cardProposition->attack = request('cardAttack');
        $cardProposition->health = request('cardHealth');
        if(request('cardRarity') === 'common'){
            $cardProposition->scraps_cost = 20;
            $cardProposition->scraps_earned = 5;
        } else if (request('cardRarity') === 'rare') {
            $cardProposition->scraps_cost = 80;
            $cardProposition->scraps_earned = 20;
        } else if (request('cardRarity') === 'epic') {
            $cardProposition->scraps_cost = 400;
            $cardProposition->scraps_earned = 100;
        } else if (request('cardRarity') === 'legendary') {
            $cardProposition->scraps_cost = 1600;
            $cardProposition->scraps_earned = 400;
        }
        $cardProposition->user_name = $request->user()->name;
        $cardProposition->save();

        return back()->with('success', 'Dziękujemy za przesłanie nam własnej propozycji! :)');
    }
}
