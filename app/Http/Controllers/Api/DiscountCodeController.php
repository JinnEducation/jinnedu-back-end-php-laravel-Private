<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DiscountCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
     
        $query = DiscountCode::query();

    
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->query('code') . '%');
        }

        if ($request->has('is_active') && $request->query('is_active') !== null) {
            $query->where('is_active', (bool) $request->query('is_active'));
        }

        if ($request->filled('starts_from')) {
            $query->where('starts_at', '>=', $request->query('starts_from'));
        }

        if ($request->filled('ends_to')) {
            $query->where('ends_at', '<=', $request->query('ends_to'));
        }

        $discountCodes = $query->orderByDesc('id')->paginate(10);

        return response()->json($discountCodes);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate([
                'code' => ['required', 'string', 'max:255', 'unique:discount_codes,code'],
                'percentage' => ['required', 'integer', 'min:1', 'max:100'],
                'starts_at' => ['nullable', 'date'],
                'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            $discountCode = DiscountCode::create($data);

            DB::commit();
            return response()->json($discountCode, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $discountCode = DiscountCode::findOrFail($id);

        return response()->json($discountCode);
    }

    public function update(Request $request, $id)
    {
        $discountCode = DiscountCode::findOrFail($id);

        DB::beginTransaction();
        try {
            $data = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('discount_codes', 'code')->ignore($discountCode->id),
                ],
                'percentage' => ['sometimes', 'required', 'integer', 'min:1', 'max:100'],
                'starts_at' => ['sometimes', 'nullable', 'date'],
                'ends_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:starts_at'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            if (!empty($data)) {
                $discountCode->update($data);
            }

            DB::commit();
            return response()->json($discountCode);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DiscountCode::destroy($id);

        return response()->json([
            'message' => 'Discount code deleted successfully',
        ], 204);
    }
}
