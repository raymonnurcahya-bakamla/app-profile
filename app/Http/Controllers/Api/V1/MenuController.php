<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $menus = Menu::query()
            ->where('is_active', true)
            ->with(['items' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->get();

        return MenuResource::collection($menus);
    }

    public function show(string $location): MenuResource
    {
        $menu = Menu::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->with(['items' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->firstOrFail();

        return new MenuResource($menu);
    }
}
