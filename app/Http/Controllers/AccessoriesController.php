<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchBox;
use App\Models\WatchStrap;
use App\Models\WatchGlass; // nếu có

class AccessoriesController extends Controller
{
    public function showStraps()
    {
        $items = WatchStrap::all();
        return view('client.accessories', [
            'items' => $items,
            'type' => 'straps'
        ]);
    }

    public function showBoxes()
    {
        $items = WatchBox::all();
        return view('client.accessories', [
            'items' => $items,
            'type' => 'boxes'
        ]);
    }

    public function showGlasses()
    {
        $items = WatchGlass::all();
        return view('client.accessories', [
            'items' => $items,
            'type' => 'glasses'
        ]);
    }

    public function index()
    {
        // Gộp cả 3 loại phụ kiện vào cùng view nếu bạn muốn
        $straps = WatchStrap::all();
        $boxes = WatchBox::all();
        $glasses = WatchGlass::all();

        return view('client.products.accessories_index', [
            'straps' => $straps,
            'boxes' => $boxes,
            'glasses' => $glasses,
        ]);
    }

}