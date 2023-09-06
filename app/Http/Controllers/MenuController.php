<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $menus = Menu::paginate($perPage);

        return response()->json([
            'result' => $menus,
            'msg' => __('success'),
            'status' => 200,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuRequest $request)
    {
        try {
            $image = null;
            $slug = $request->slug;

            $request->whenHas('image_url', function ($imageInp) use (&$image, $slug) {
                $image_name = $slug . '.' . $imageInp->getClientOriginalExtension();
                $imageInp->storeAs('public/images_urls', $image_name);
                $image = 'storage/images_urls/' . $image_name;
            });
            $menu = Menu::create([
                ...$request->all(),
                'image_url' => $image,
            ]);

            if ($menu) {
                $result = $menu;
                $msg = __('success.add');
                $status = 200;
            } else {
                $result = null;
                $msg = __('failure.add');
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ], $status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json([
            'result' => $menu,
            'msg' => __('success'),
            'status' => 200,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuController $request, Menu $menu)
    {
        try {
            $request->whenHas('image_url', function ($imageInp) use (&$request) {
                $request->merge([
                    'image_url' => 'storage/' . $imageInp->store('images_urls'),
                ]);
            });
            if ($menu->update($request->all())) {
                $result = $menu;
                $msg = __('success.update');
                $status = 200;
            } else {
                $result = null;
                $msg = __('failure.update');
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ], $status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        try {
            if ($menu->delete()) {
                $result = $menu;
                $msg = __('success.delete');
                $status = 200;
            } else {
                $result = null;
                $msg = __('failure.delete');
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ], $status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
