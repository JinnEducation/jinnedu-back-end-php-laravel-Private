<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use App\Models\SliderLang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $slider = Slider::with('langs.language')
            ->get();

        return SliderResource::collection($slider);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'images' => 'image',
            'langs' => 'required',
            'langs.*.language_id' => 'required', 'exists:language,id',
            'langs.*.title' => 'required',
            'langs.*.sub_title' => 'required',
            'langs.*.btn_name' => 'required',
            'btn_url' => 'required',
        ]);

     
        $images = [];
        if ($request->hasFile('images_files')) {
            foreach ($request->file('images_files') as $file) {
                $images[] = $file->store('sliders', 'public');
            }
        }

        $slider = Slider::create([
            'images' => $images,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        foreach ($data['langs'] as $langData) {
            $langData['slider_id'] = $slider->id;
            SliderLang::create($langData);
        }

        $slider->load('langs.language');

        return new SliderResource($slider);
    }

    public function show(Slider $slider)
    {
        $slider->load('langs.language');
        return new SliderResource($slider);
    }

    public function destroy(Slider $slider)
    {
        if (is_array($slider->images)) {
            foreach ($slider->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $slider->delete();
        return response()->json(['message' => 'Slider deleted successfully']);
    }
}
