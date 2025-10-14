<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchBox;
use App\Models\WatchStrap;
use Illuminate\Support\Str;

use App\Models\WatchGlass;

class AccessoriesController extends Controller
{

    private function loadAccessoryView($type, $isAdmin = false)
    {
        switch ($type) {
            case 'straps':
                $items = WatchStrap::all();
                break;
            case 'boxes':
                $items = WatchBox::all();
                break;
            case 'glasses':
                $items = WatchGlass::all();
                break;
            default:
                abort(404);
        }

        $view = $isAdmin ? 'admin.accessories_index' : 'client.accessories';

        return view($view, [
            'items' => $items,
            'type' => $type
        ]);
    }


    public function showStraps()  { return $this->loadAccessoryView('straps'); }
    public function showBoxes()   { return $this->loadAccessoryView('boxes'); }
    public function showGlasses() { return $this->loadAccessoryView('glasses'); }

    public function adminStraps()  { return $this->loadAccessoryView('straps', true); }
    public function adminBoxes()   { return $this->loadAccessoryView('boxes', true); }
    public function adminGlasses() { return $this->loadAccessoryView('glasses', true); }

    private function getModelFromType($type)
    {
        return match ($type) {
            'straps' => \App\Models\WatchStrap::class,
            'boxes' => \App\Models\WatchBox::class,
            'glasses' => \App\Models\WatchGlass::class,
            default => abort(404),
        };
    }

    public function store(Request $request, $type)
    {
        $folder = match ($type) {
            'straps' => 'accessories/straps',
            'boxes' => 'accessories/boxes',
            'glasses' => 'accessories/glasses',
            default => abort(404),
        };

        // Xác định model
        $model = $this->getModelFromType($type);

        // Validate theo loại
        switch ($type) {
            case 'straps':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'material' => 'required|string|max:255',
                    'color' => 'required|string|max:100',
                    'price' => 'required|numeric|min:0',
                    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                ]);
                break;

            case 'glasses':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'material' => 'required|string',
                    'color' => 'required|string',
                    'price' => 'required|numeric|min:0',
                    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'description' => 'nullable|string',
                ]);
                break;

            case 'boxes':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'price' => 'required|numeric|min:0',
                    'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'description' => 'nullable|string',
                ]);
                break;
        }

        // Xử lý ảnh
        $image = $request->file('image');

        if ($image) {
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::slug($originalName) . '-' . time() . '.' . $extension;
            $image->storeAs('public/' . $folder, $imageName);
            $data['image'] = $imageName;
        } else {
            $data['image'] = null; // hoặc có thể báo lỗi tùy logic bạn muốn
        }


        // Dữ liệu riêng theo bảng
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => 100,
            'image' => $imageName,
            'description' => $request->description,
        ];

        if ($type === 'straps' || $type === 'glasses') {
            $data['material'] = $request->material;
            $data['color'] = $request->color;
        }
        if ($request->has('description')) {
            $data['description'] = $request->description;
        }


        $model::create($data);

        return redirect()->route('admin.accessories.' . $type)
            ->with('success', 'Thêm phụ kiện thành công!');

    }




    // ✅ Giao diện sửa phụ kiện
    public function update(Request $request, $type, $id)
{
    $model = $this->getModelFromType($type);
    $item  = $model::findOrFail($id);

    // chung
    $request->validate([
        'name'     => 'required|string|max:255',
        'price'    => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // vật liệu, màu cho straps/glasses
    if ($type === 'straps' || $type === 'glasses') {
        $request->validate([
            'material' => 'required|string|max:255',
            'color'    => 'required|string|max:100',
        ]);
    }

    // description cho boxes/glasses (straps KHÔNG có cột này)
    if ($type === 'boxes' || $type === 'glasses') {
        $request->validate(['description' => 'nullable|string']);
    }

    $item->name     = $request->name;
    $item->price    = $request->price;
    $item->quantity = $request->quantity;

    // chỉ gán khi bảng có cột
    if ($type === 'boxes' || $type === 'glasses') {
        $item->description = $request->description;
    }

    if ($type === 'straps' || $type === 'glasses') {
        $item->material = $request->material;
        $item->color    = $request->color;
    }

    if ($request->hasFile('image')) {
        $folder = match ($type) {
            'straps'  => 'accessories/straps',
            'boxes'   => 'accessories/boxes',
            'glasses' => 'accessories/glasses',
            default   => abort(404),
        };
        $image     = $request->file('image');
        $imageName = $image->getClientOriginalName();
        $image->storeAs('public/' . $folder, $imageName);
        $item->image = $imageName;
    }

    $item->save();

    return redirect()->route('admin.accessories.' . $type)
                     ->with('success', 'Cập nhật phụ kiện thành công!');
}



    // ✅ Xóa phụ kiện
    public function delete($type, $id)
    {
        $model = $this->getModelFromType($type);
        $item = $model::findOrFail($id);

        $folder = match ($type) {
            'straps' => 'accessories/straps',
            'boxes' => 'accessories/boxes',
            'glasses' => 'accessories/glasses',
            default => abort(404),
        };

        $imagePath = 'public/' . $folder . '/' . $item->image;
        if (\Storage::exists($imagePath)) {
            \Storage::delete($imagePath);
        }

        $item->delete();

        return redirect()->route('admin.accessories.' . $type)
                                ->with('success', 'Xóa phụ kiện thành công!');

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
            case 'glasses':
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
