<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VictimaController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\VictimaCollection
     */
    public function index(Request $request)
    {
        $Victimas = Victima::orderBy('apellido', 'ASC')
            ->get();

        return new VictimaCollection($Victimas);
    }

    /**
     * @param \App\Http\Requests\VictimaStoreRequest $request
     * @return \App\Http\Resources\VictimaResource
     */
    public function store(VictimaStoreRequest $request)
    {
        $Victima = Victima::create($request->validated());

        return new VictimaResource($Victima);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Victima $Victima
     * @return \App\Http\Resources\VictimaResource
     */
    public function show(Request $request, Victima $Victima)
    {
        return new VictimaResource($Victima);
    }

    /**
     * @param \App\Http\Requests\VictimaUpdateRequest $request
     * @param \App\Models\Victima $Victima
     * @return \App\Http\Resources\VictimaResource
     */
    public function update(VictimaUpdateRequest $request, Victima $Victima)
    {
        $Victima->update($request->validated());

        return new VictimaResource($Victima);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Victima $Victima
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Victima $Victima)
    {
        $Victima->delete();

        return response()->noContent();
    }
}
