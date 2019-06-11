<?php

use App\Card;
use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $card = new Card();
        $card->name = 'Janusz: Szalony Tytan';
        $card->description='Rozmieszczenie: zniszcz całą rękę przeciwnika.';
        $card->cost = 20;
        $card->attack=5;
        $card->health=5;
        $card->rarity='Legendarna';
        $card->fraction='Nierząd';
        $card->scraps_cost=1600;
        $card->scraps_earned=400;
        $card->filename = 'janusz_szalony_tytan.png';
        $card->save();


        $card = new Card();
        $card->name = 'Donald Zdobywca';
        $card->description='Rozmieszczenie: Wygnaj dwie karty przeciwnika na emigrację';
        $card->cost = 15;
        $card->attack=8;
        $card->health=8;
        $card->rarity='Legendarna';
        $card->fraction='Opozycja';
        $card->scraps_cost=1600;
        $card->scraps_earned=400;
        $card->filename = 'donald_zdobywca.png';
        $card->save();

        $card = new Card();
        $card->name = 'Jarosław Van Damme';
        $card->description='Szarża. Gdy przeciwnik zagra stronnika, zaatakuj go';
        $card->cost = 15;
        $card->attack=8;
        $card->health=8;
        $card->rarity='Legendarna';
        $card->fraction='Rząd';
        $card->scraps_cost=1600;
        $card->scraps_earned=400;
        $card->filename = 'jaroslaw_van_damme.png';
        $card->save();
    }
}
