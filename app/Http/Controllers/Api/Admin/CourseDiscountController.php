<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseDiscount};
use Illuminate\Http\Request;

class CourseDiscountController extends Controller
{
    private function mustBeAdmin(Request $request)
    {
        if (($request->user()->type ?? 0) != 0) {
            abort(403, 'Admin only.');
        }
    }

    public function index(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        return response()->json(
            $course->discounts()->latest()->get()
        );
    }

    public function store(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ]);

        // Optional rule: only one active discount at a time
        if (($data['is_active'] ?? true) === true) {
            $course->discounts()->where('is_active', true)->update(['is_active' => false]);
        }

        $discount = $course->discounts()->create($data);

        return response()->json([
            'message' => 'Discount created.',
            'discount' => $discount,
        ], 201);
    }

    public function update(Request $request, CourseDiscount $discount)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'type' => 'sometimes|in:percent,fixed',
            'value' => 'sometimes|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ]);

        if (array_key_exists('is_active', $data) && $data['is_active'] === true) {
            $discount->course->discounts()
                ->where('id', '!=', $discount->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $discount->update($data);

        return response()->json([
            'message' => 'Discount updated.',
            'discount' => $discount->fresh(),
        ]);
    }

    public function destroy(Request $request, CourseDiscount $discount)
    {
        $this->mustBeAdmin($request);

        $discount->delete();
        return response()->json(['message' => 'Discount deleted.']);
    }
}

