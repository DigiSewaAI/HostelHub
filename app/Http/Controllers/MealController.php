<?php

namespace App\Http\Controllers;

class MealController extends Controller
{
    public function publicIndex()
    {
        $meals = [
            ['id' => 1, 'name' => 'Breakfast Package'],
            ['id' => 2, 'name' => 'Lunch Special']
        ];

        return view('meals.index', compact('meals'));
    }

    public function menu()
    {
        $menu = [
            ['name' => 'Item 1', 'price' => 10.99],
            ['name' => 'Item 2', 'price' => 12.99]
        ];

        return view('meals.menu', compact('menu'));
    }
}
