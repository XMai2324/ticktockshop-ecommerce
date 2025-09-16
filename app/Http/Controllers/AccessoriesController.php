<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchBox;
use App\Models\WatchStrap;

use App\Models\WatchGlass;

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

            'type' => 'glass'
        ]);
    }

    public function quickView($type, $id)
    {
        switch ($type) {
            case 'straps':
                $item = WatchStrap::findOrFail($id);
                break;
            case 'boxes':
                $item = WatchBox::findOrFail($id);
                break;
            case 'glass':
                $item = WatchGlass::findOrFail($id);
                break;
            default:
                abort(404);
        }

        return view('client.products.quick_view', [
            'item' => $item,
            'type' => $type
    ]);
}

}