<?php

namespace Modules\Area\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\Province;

class ProvinceController extends Controller
{
    public function index(): Renderable
    {
        $provinces = Province::query()
            ->sortable()
            ->orderBy('name', 'asc')
            ->paginate();

        return view('area::admin.province.index', compact('provinces'));
    }
}
