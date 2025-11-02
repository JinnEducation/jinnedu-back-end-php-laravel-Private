<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use App\Models\Language;
use App\Models\SliderLang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{

    public function __construct()
    {
         $this->middleware('auth:sanctum');
    }
    public function index()
    {
        $slider = Slider::with('langs.language')
            ->get();

        return SliderResource::collection($slider);
    }

    public function store(Request $request)
    {

    $langShort = $request->header('lang');
    $language = $langShort
        ? Language::where('shortname', $langShort)->first()
        : null;

    $languageId = $language?->id ?? $request->input('language_id');

    
         $dataSlider = $request->validate([
            'image' => 'required|file|image', 
            'btn_url' => 'required',
        ]);

        $dataLang = $request->validate([
        'title'       => 'required',
        'sub_title'        => 'required',
        'btn_name' => 'required',
    ]);

      if ($request->hasFile('image')) {
        $file = $request->file('image');

        if (! $file->isValid()) {
            return response()->json(['message' => 'Uploaded image is not valid.'], 422);
        }

       
        $imagePath = $file->store('uploads/blogs', 'public'); 
         $dataSlider['image'] = $imagePath;
    }

        $slider = Slider::create( $dataSlider);

        SliderLang::create([
        'slider_id'      => $slider->id,
        'language_id'  => $languageId,
        'title'        => $dataLang['title'],
        'sub_title'         => $dataLang['sub_title'],
        'btn_name'  => $dataLang['btn_name'],
    ]);

        $slider->load('langs');

        return new SliderResource($slider);
    }

    public function show($id)
    {
         $slider = Slider::with('langs')->findOrFail($id);
         return new SliderResource($slider);
    }


     public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $langShort = $request->header('lang');
        $language = $langShort ? Language::where('shortname', $langShort)->first() : null;
        $languageId = $language?->id ?? $request->input('language_id');

        $dataSlider = $request->validate([
            'image'         => ['sometimes', 'nullable'],
            'btn_url'          => ['sometimes', 'required'],
        ]);

        $dataLang = $request->validate([
        'title'       => 'sometimes|required',
        'sub_title'        => 'sometimes|required',
        'btn_name' => 'sometimes|required',
        ]);

       

        if (!empty($dataSlider)) {
            $slider->update( $dataSlider);
        }


        if (!empty($dataLang)) {
            $langRow = SliderLang::firstOrNew([
                'slider_id'     => $slider->id,
                'language_id' => $languageId,
            ]);

            if (array_key_exists('title', $dataLang))       $langRow->title = $dataLang['title'];
            if (array_key_exists('sub_title', $dataLang))        $langRow->sub_title = $dataLang['sub_title'];
            if (array_key_exists('btn_name', $dataLang)) $langRow->btn_name = $dataLang['btn_name'];

            $langRow->save();
        }

        $slider->load(['langs']);

        return new SliderResource($slider);
    }



    public function destroy($id)
    {
        Slider::destroy($id);
        return response()->json([
            'message' => 'Slider deleted successfully',

        ], 204);
    }
}
