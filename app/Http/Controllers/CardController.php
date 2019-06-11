<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Response;
use Validator;
use App\CardProposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $cards = DB::table('cards')->paginate(5);
        if ($request->ajax()) {
            $sections = view('cards.index')->with('cards', $cards)->renderSections();
            return $sections['content'];
        }
        return view('cards.index')->with('cards', $cards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $sections = view('cards.create')->renderSections();
            return $sections['content'];
        }
        return view('cards.create');
    }

    public function createFromProposition($id, Request $request)
    {
        $proposition = CardProposition::find($id);
        if ($request->ajax()) {
            $sections = view('cards.createFromProposition', compact('proposition'))->renderSections();
            return $sections['content'];
        }
        return view('cards.createFromProposition', compact('proposition'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'cardName' => 'required|max:40|unique:cards,name',
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

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()->all()]);
        }

        $image = $request->file('image');
        $imageName = $image->getClientOriginalName();

        $image->move(public_path('img'), $imageName);
        $card->filename = $imageName;
        $card->save();

        return response()->json(['success' => 'Dodano rekord']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     * @throws \Throwable
     */
    public function edit($id, Request $request)
    {
        $card = Card::find($id);

        if ($request->ajax()) {
            $sections = view('cards.edit', compact('card'), compact('id'))->renderSections();
            return $sections['content'];
        }
        return view('cards.edit', compact('card'), compact('id'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'cardName' => 'required|max:40',
            'cardDescription' => 'required|max:200',
            'cardCost' => 'required|integer',
            'cardAttack' => 'required|integer',
            'cardHealth' => 'required|integer',
            'cardRarity' => 'required|',
            'cardFraction' => 'required|',
        ]);

        $card = Card::find($id);      $card->name = request('cardName');
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


        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()->all()]);
        }
        if ($request->file() != null) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->move(public_path('img'), $imageName);
            $card->filename = $imageName;
        } else {
            $card->filename = Card::find($id)->filename;
        }

        $card->save();

        return response()->json(['success' => 'Zmieniono rekord']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Card::find($id)->delete();
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $cards = DB::table('cards')->where('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')->paginate(10);
        if ($request->ajax()) {
            if ($request->ajax()) {
                $sections = view('cards.index')->with('cards', $cards)->renderSections();
                return $sections['content'];
            }
            return view('cards.index')->with('cards', $cards);
        }
    }
}
