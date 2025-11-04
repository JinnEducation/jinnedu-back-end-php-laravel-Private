<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use App\Models\Language;
use App\Models\SliderLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->paginate(10);

        return SliderResource::collection($slider);
    }

    
    public function store(Request $request)
    {
        $languages = $request->langs;
        DB::beginTransaction();
        try {
            
         $dataSlider = $request->validate([
            'image' => 'required', 
            'btn_url' => 'required',
        ]);

            $dataLang = $request->validate([
                'title.*' => 'required|string',
                'sub_title.*' => 'required|string',
                'btn_name.*' => 'required|string',
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if (! $file->isValid()) {
                    return response()->json(['message' => 'Uploaded image is not valid.'], 422);
                }

                $imagePath = $file->store('uploads/sliders', 'public');
                $dataSlider['image'] = $imagePath;
            } else {
                $dataSlider['image'] = $request->image ?? null;
            }

          
             $slider = Slider::create( $dataSlider);

            foreach ($languages as $language) {
                SliderLang::create([
                    'slider_id'      => $slider->id,
                    'language_id' => $language['id'],
                    'title' => $request->title[$language['id']],
                    'sub_title' => $request->sub_title[$language['id']],
                    'btn_name' => $request->btn_name[$language['id']],

    
                ]);
            }

             $slider->load('langs');
            DB::commit();

            return response()->json($slider);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    
    public function show($id)
    {
         $slider = Slider::with('langs')->findOrFail($id);
         return new SliderResource($slider);
    }


    public function update(Request $request, $id)
    {
         $slider = Slider::findOrFail($id);
        $languages = $request->langs;
        DB::beginTransaction();
        try {
                $dataSlider = $request->validate([
            'image'         => ['sometimes', 'nullable'],
            'btn_url'          => ['sometimes', 'required'],
        ]);
            $dataLang = $request->validate([
                'title.*' => ['sometimes', 'required'],
                'sub_title.*' => ['sometimes', 'required'],
                'btn_name.*' => ['sometimes', 'required'],
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if (! $file->isValid()) {
                    return response()->json(['message' => 'Uploaded image is not valid.'], 422);
                }

                $imagePath = $file->store('uploads/sliders', 'public');
                $dataSlider['image'] = $imagePath;
            } else {
                $dataSlider['image'] = $request->image ?? $slider->image;
            }


           

            if (! empty($dataSlider)) {
                 $slider->update($dataSlider);
            }

            if (! empty($dataLang)) {
                foreach($languages as $language){
                    SliderLang::updateOrCreate([
                       'slider_id'     => $slider->id,
                        'language_id' => $language['id'],    
                    ], [
                        'title' => $request->title[$language['id']],
                        'sub_title' => $request->sub_title[$language['id']],
                        'btn_name' => $request->btn_name[$language['id']],
                    ]);
                }
            }

           $slider->load(['langs']);
            DB::commit();

           return new SliderResource($slider);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    public function destroy($id)
    {
        Slider::destroy($id);
        return response()->json([
            'message' => 'Slider deleted successfully',

        ], 204);
    }
}
