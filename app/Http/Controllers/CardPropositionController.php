<?php

namespace App\Http\Controllers;

use Validator;
use App\CardProposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CardPropositionController extends Controller
{
    public function index(Request $request)
    {
        $cardPropositions = DB::table('card_propositions')->paginate(5);
        if ($request->ajax()){
            $sections = view('propositions.index')->with('cardPropositions', $cardPropositions)->renderSections();
            return $sections['content'];
        }
        return view('propositions.index')->with('cardPropositions',$cardPropositions);
    }

    public function create(Request $request)
    {
        if ($request->ajax()){
            $sections = view('propositions.create')->renderSections();
            return $sections['content'];
        }
        return view('propositions.create');
    }

    public function store(Request $request)
    {

        $validatedData = Validator::make($request->all(),[
            'cardName' => 'required|max:40',
            'cardDescription' => 'required|max:200',
            'cardCost' => 'required|integer',
            'cardAttack' => 'required|integer',
            'cardHealth' => 'required|integer',
            'cardRarity' => 'required|',
            'cardFraction' => 'required|',
        ]);

        if($validatedData->fails()){
            return response()->json(['errors'=>$validatedData->errors()->all()]);
        }
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

        return response()->json(['success'=>'Dodano rekord']);
    }

    public function edit($id, Request $request)
    {
        $proposition = CardProposition::find($id);

        if ($request->ajax()){
            $sections = view('propositions.edit', compact('proposition'),compact('id'))->renderSections();
            return $sections['content'];
        }
        return view('propositions.edit', compact('proposition'),compact('id'));


    }
    public function update(Request $request, $id){

        $validatedData = Validator::make($request->all(),[
            'cardName' => 'required|max:40',
            'cardDescription' => 'required|max:200',
            'cardCost' => 'required|integer',
            'cardAttack' => 'required|integer',
            'cardHealth' => 'required|integer',
            'cardRarity' => 'required|',
            'cardFraction' => 'required|',
        ]);

        if($validatedData->fails()){
            return response()->json(['errors'=>$validatedData->errors()->all()]);
        }

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
        return response()->json(['success'=>'Pomyślnie zmodyfikowano rekord']);

    }
    public function destroy($id){
        CardProposition::find($id)->delete();
    }
}