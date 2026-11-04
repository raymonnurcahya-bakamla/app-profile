<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Setting::query();

        if ($request->filled('group')) {
            $query->where('group', $request->string('group'));
        }

        $settings = $query->get();

        $formatted = [];
        foreach ($settings as $setting) {
            $value = $setting->value;
            if ($setting->type === 'image' && $value) {
                $value = str_starts_with($value, 'http') ? $value : asset('storage/'.$value);
            } elseif ($setting->type === 'boolean') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($setting->type === 'json') {
                $value = json_decode($value ?? '[]', true);
            }
            $formatted[$setting->key] = $value;
        }

        return response()->json([
            'data' => $formatted,
            'list' => SettingResource::collection($settings),
        ]);
    }

    public function show(string $key): JsonResponse
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        return response()->json([
            'data' => new SettingResource($setting),
        ]);
    }
}
