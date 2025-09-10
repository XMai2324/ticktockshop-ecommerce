<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchBox;
use App\Models\WatchStrap;
use App\Models\WatchGlass;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AccessoriesController extends Controller
{
    /* ===== Helpers ===== */

    private function getModelFromType(string $type)
    {
        return match ($type) {
            'straps'  => WatchStrap::class,
            'boxes'   => WatchBox::class,
            'glasses' => WatchGlass::class,
            default   => abort(404),
        };
    }

    private function getFolderFromType(string $type): string
    {
        // Thư mục theo cấu trúc bạn đang dùng
        return match ($type) {
            'straps'  => 'accessories/straps',
            'boxes'   => 'accessories/boxes',
            'glasses' => 'accessories/glass', // giữ nguyên "glass" theo project của bạn
            default   => abort(404),
        };
    }

    private function loadAccessoryView(string $type, bool $isAdmin = false)
    {
        $items = match ($type) {
            'straps'  => WatchStrap::all(),
            'boxes'   => WatchBox::all(),
            'glasses' => WatchGlass::all(),
            default   => abort(404),
        };

        $view = $isAdmin ? 'admin.accessories_index' : 'client.accessories';

        return view($view, [
            'items' => $items,
            'type'  => $type,
        ]);
    }

    /* ===== Client pages ===== */

    public function showStraps()  { return $this->loadAccessoryView('straps'); }
    public function showBoxes()   { return $this->loadAccessoryView('boxes'); }
    public function showGlasses() { return $this->loadAccessoryView('glasses'); }

    /* ===== Admin pages ===== */

    public function adminStraps()  { return $this->loadAccessoryView('straps', true); }
    public function adminBoxes()   { return $this->loadAccessoryView('boxes', true); }
    public function adminGlasses() { return $this->loadAccessoryView('glasses', true); }

    /* ===== CRUD ===== */

    // Create
    public function store(Request $request, string $type)
    {
        $model  = $this->getModelFromType($type);
        $folder = $this->getFolderFromType($type);

        // Validate theo loại
        switch ($type) {
            case 'straps':
                $request->validate([
                    'name'     => 'required|string|max:255',
                    'material' => 'required|string|max:255',
                    'color'    => 'required|string|max:100',
                    'price'    => 'required|numeric|min:0',
                    'quantity' => 'nullable|integer|min:0',
                    'image'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'description' => 'nullable|string',
                ]);
                break;

            case 'glasses':
                $request->validate([
                    'name'        => 'required|string|max:255',
                    'material'    => 'required|string',
                    'color'       => 'required|string',
                    'price'       => 'required|numeric|min:0',
                    'quantity'    => 'nullable|integer|min:0',
                    'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'description' => 'nullable|string',
                ]);
                break;

            case 'boxes':
                $request->validate([
                    'name'        => 'required|string|max:255',
                    'price'       => 'required|numeric|min:0',
                    'quantity'    => 'nullable|integer|min:0',
                    'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'description' => 'nullable|string',
                ]);
                break;
        }

        // Xử lý ảnh
        $image     = $request->file('image');
        $imageName = null;

        if ($image) {
            $original  = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $ext       = $image->getClientOriginalExtension();
            $imageName = Str::slug($original) . '-' . time() . '.' . $ext;
            $image->storeAs('public/' . $folder, $imageName);
        }

        // Data chung
        $data = [
            'name'        => $request->name,
            'price'       => $request->price,
            'quantity'    => $request->input('quantity', 100),
            'image'       => $imageName,
            'description' => $request->input('description'),
        ];

        // Data riêng
        if ($type === 'straps' || $type === 'glasses') {
            $data['material'] = $request->material;
            $data['color']    = $request->color;
        }

        $model::create($data);

        return redirect()->route('admin.accessories.' . $type)
            ->with('success', 'Thêm phụ kiện thành công!');
    }

    // Read one for quick view
    public function quickView(string $type, int $id)
    {
        $model = $this->getModelFromType($type);
        $item  = $model::findOrFail($id);

        return view('client.products.quick_view', [
            'item' => $item,
            'type' => $type,
        ]);
    }

    // Update
    public function update(Request $request, string $type, int $id)
    {
        $model = $this->getModelFromType($type);
        $item  = $model::findOrFail($id);

        // Validate chung
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($type === 'straps' || $type === 'glasses') {
            $request->validate([
                'material' => 'required|string|max:255',
                'color'    => 'required|string|max:100',
            ]);
        }

        // Cập nhật field
        $item->name        = $request->name;
        $item->price       = $request->price;
        $item->quantity    = $request->quantity;
        $item->description = $request->input('description');

        if ($type === 'straps' || $type === 'glasses') {
            $item->material = $request->material;
            $item->color    = $request->color;
        }

        // Ảnh mới nếu có
        if ($request->hasFile('image')) {
            $folder    = $this->getFolderFromType($type);
            $image     = $request->file('image');
            $original  = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $ext       = $image->getClientOriginalExtension();
            $imageName = Str::slug($original) . '-' . time() . '.' . $ext;

            // Xóa ảnh cũ nếu tồn tại
            if ($item->image) {
                $oldPath = 'public/' . $folder . '/' . $item->image;
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }

            $image->storeAs('public/' . $folder, $imageName);
            $item->image = $imageName;
        }

        $item->save();

        return redirect()->route('admin.accessories.' . $type)
            ->with('success', 'Cập nhật phụ kiện thành công!');
    }

    // Delete
    public function destroy(string $type, int $id)
    {
        $model  = $this->getModelFromType($type);
        $item   = $model::findOrFail($id);
        $folder = $this->getFolderFromType($type);

        if ($item->image) {
            $imagePath = 'public/' . $folder . '/' . $item->image;
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $item->delete();

        return redirect()->route('admin.accessories.' . $type)
            ->with('success', 'Xóa phụ kiện thành công!');
    }
}