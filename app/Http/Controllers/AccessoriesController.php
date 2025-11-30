<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\WatchStrap;
use App\Models\WatchBox;
use App\Models\WatchGlass;

class AccessoriesController extends Controller
{
    /* ================== HÀM DÙNG CHUNG ================== */

    // Chuẩn hoá type + map sang model
    protected function getModelByType(string $type)
    {
        return match ($type) {
            'straps'  => WatchStrap::class,
            'boxes'   => WatchBox::class,
            'glasses' => WatchGlass::class,
            default   => abort(404),
        };
    }

    // Thư mục lưu ảnh
    protected function getFolderByType(string $type): string
    {
        $this->getModelByType($type); // để abort nếu sai
        return "accessories/{$type}";
    }

    /* ================== CLIENT ================== */

    public function showStraps()
    {
        $type  = 'straps';
        $items = WatchStrap::where('is_hidden', false)->get();

        return view('client.accessories', compact('items', 'type'));
    }

    public function showBoxes()
    {
        $type  = 'boxes';
        $items = WatchBox::where('is_hidden', false)->get();

        return view('client.accessories', compact('items', 'type'));
    }

    public function showGlasses()
    {
        $type  = 'glasses';
        $items = WatchGlass::where('is_hidden', false)->get();

        return view('client.accessories', compact('items', 'type'));
    }

    // Quick view: /accessories/quick-view/{type}/{id}
    public function quickView(string $type, int $id)
    {
        $model = $this->getModelByType($type);

        $item = $model::findOrFail($id);

        return view('client.accessories.quickview', [
            'item' => $item,
            'type' => $type,
        ]);
    }

    /* ================== ADMIN LIST ================== */

    public function index()
    {
        // Cho index mặc định là dây đeo
        return $this->adminStraps();
    }

    public function adminStraps()
    {
        $type  = 'straps';
        $items = WatchStrap::all();

        return view('admin.accessories_index', compact('items', 'type'));
    }

    public function adminBoxes()
    {
        $type  = 'boxes';
        $items = WatchBox::all();

        return view('admin.accessories_index', compact('items', 'type'));
    }

    public function adminGlasses()
    {
        $type  = 'glasses';
        $items = WatchGlass::all();

        return view('admin.accessories_index', compact('items', 'type'));
    }

    /* ================== STORE ================== */
    // POST /admin/accessories/{type}/store
    public function store(Request $request, string $type)
    {
        $modelClass = $this->getModelByType($type);
        $folder     = $this->getFolderByType($type);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp,avif|max:4096',
        ]);

        $originalName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $request->file('image')->getClientOriginalExtension();
        $imageName    = Str::slug($originalName) . '.' . $extension;

        $request->file('image')->storeAs('public/' . $folder, $imageName);

        $modelClass::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'image'       => $imageName,
            'is_hidden'   => false,
        ]);

        return redirect()->route("admin.accessories.$type")
                         ->with('success', 'Thêm phụ kiện thành công!');
    }

    /* ================== UPDATE ================== */
    // PUT /admin/accessories/{type}/{id}
    public function update(Request $request, string $type, int $id)
    {
        $modelClass = $this->getModelByType($type);
        $folder     = $this->getFolderByType($type);

        $accessory = $modelClass::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:4096',
        ]);

        $accessory->name        = $request->name;
        $accessory->price       = $request->price;
        $accessory->description = $request->description;

        if ($request->hasFile('image')) {
            if ($accessory->image) {
                $oldPath = 'public/' . $folder . '/' . $accessory->image;
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }

            $originalName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension    = $request->file('image')->getClientOriginalExtension();
            $imageName    = Str::slug($originalName) . '.' . $extension;

            $request->file('image')->storeAs('public/' . $folder, $imageName);
            $accessory->image = $imageName;
        }

        $accessory->save();

        return redirect()->route("admin.accessories.$type")
                         ->with('success', 'Cập nhật phụ kiện thành công!');
    }

    /* ================== DELETE ================== */
    // DELETE /admin/accessories/{type}/{id}
    public function delete(string $type, int $id)
    {
        $modelClass = $this->getModelByType($type);
        $folder     = $this->getFolderByType($type);

        $accessory = $modelClass::findOrFail($id);

        if ($accessory->image) {
            $path = 'public/' . $folder . '/' . $accessory->image;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        $accessory->delete();

        return redirect()->route("admin.accessories.$type")
                         ->with('success', 'Đã xoá phụ kiện thành công!');
    }

    /* ================== TOGGLE ẨN / HIỆN ================== */
    // POST /admin/accessories/toggle/{type}/{id}
    public function toggleHidden(string $type, int $id)
    {
        $modelClass = $this->getModelByType($type);

        $accessory = $modelClass::findOrFail($id);
        $accessory->is_hidden = ! $accessory->is_hidden;
        $accessory->save();

        return response()->json([
            'hidden' => $accessory->is_hidden,
        ]);
    }
}