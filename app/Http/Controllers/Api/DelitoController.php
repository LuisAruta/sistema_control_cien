<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DelitoStoreRequest;
use App\Http\Requests\DelitoUpdateRequest;
use App\Http\Resources\DelitoCollection;
use App\Http\Resources\DelitoResource;
use App\Models\Delito;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DelitoController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\DelitoCollection
     */
    public function index(Request $request)
    {
        $delitos = Delito::orderBy('nombre', 'ASC')
            ->get();

        return new DelitoCollection($delitos);
    }

    /**
     * @param \App\Http\Requests\DelitoStoreRequest $request
     * @return \App\Http\Resources\DelitoResource
     */
    public function store(DelitoStoreRequest $request)
    {
        $delito = Delito::create($request->validated());

        return new DelitoResource($delito);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Delito $delito
     * @return \App\Http\Resources\DelitoResource
     */
    public function show(Request $request, Delito $delito)
    {
        return new DelitoResource($delito);
    }

    /**
     * @param \App\Http\Requests\DelitoUpdateRequest $request
     * @param \App\Models\Delito $delito
     * @return \App\Http\Resources\DelitoResource
     */
    public function update(DelitoUpdateRequest $request, Delito $delito)
    {
        $delito->update($request->validated());

        return new DelitoResource($delito);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Delito $delito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Delito $delito)
    {
        $delito->delete();

        return response()->noContent();
    }
}
