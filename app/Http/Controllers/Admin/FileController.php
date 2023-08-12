<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    public function create()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'filenames' => 'required',
            'filenames.*' => 'image'
        ]);

        $files = [];
        if ($request->hasfile('filenames')) {
            foreach ($request->file('filenames') as $file) {
                $name = time() . rand(1, 50) . '.' . $file->extension();
                $file->move(public_path('assets/uploads/product'), $name);
                $files[] = $name;
            }
        }

        $file = new File();
        $file->filenames = $files;
        $file->save();

        return back()->with('success', 'Images are successfully uploaded');
    }

    public function productImage($id)
    {
        $product = $id;
        $images = Image::where('prod_id', $product)->get();

        return view('admin.product.image', compact('images', 'product'));
    }

    public function productImageDelete(Request $request)
    {
        $id = $request->post('id');
        $check = Image::find($id);

        if ($check) {
            $image = $check->toArray()['image'];
            $path = 'assets/uploads/product/' . $image;

            if (File::exists($path)) {
                File::delete($path);
            }

            if ($check->delete()) {
                return response()->json(['text' => 'Deleted successfully', 'result' => 'success']);
            }
        } else {
            return response()->json(['text' => "Nothing has been deleted", 'result' => 'fail']);
        }
    }

    public function productImageAdd(Request $request, $id)
    {
        $product = $id;
        $image = new Image();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('assets/uploads/product/', $filename);
            $image->image = $filename;
            $image->prod_id = $product;
            $image->save();
        }

        return back()->with('success', 'The image was successfully uploaded');
    }
}
