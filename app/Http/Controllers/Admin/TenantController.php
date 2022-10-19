<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUpdateTenant;

class TenantController extends Controller
{
    private $repository;

    public function __construct(Tenant $tenant)
    {
        $this->repository = $tenant;

        $this->middleware('can:tenants');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tenants = $this->repository->latest()->paginate();

        return view(
            'admin.pages.tenants.index', compact('tenants')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(
            'admin.pages.tenants.create'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\StoreUpdateTenant $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateTenant $request)
    {
        $this->repository->create($request->all());

        return redirect()->route('tenants.index');
    }
    
    // public function store(StoreUpdateTenant $request)
    // {
    //     $data = $request->all();

    //     // // tenant do usuário autenticado
    //     // $tenant = auth()->user()->tenant;

    //     // salva a imagem
    //     if ($request->hasFile('logo') && $request->logo->isValid()) {
    //         // organiza as pastas por tenant
    //         $data['logo'] = $request->logo->store("tenants/{$tenant->uuid}");
    //     }

    //     $this->repository->create($data);

    //     return redirect()->route('tenants.index');
    // }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$tenant = $this->repository->with('plan')->find($id)) {
            return redirect()->back();
        }

        return view(
            'admin.pages.tenants.show', compact('tenant')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!$tenant = $this->repository->find($id)) {
            return redirect()->back();
        }

        return view(
            'admin.pages.tenants.edit', compact('tenant')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\StoreUpdateTenant $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateTenant $request, $id)
    {
        if (!$tenant = $this->repository->find($id)) {
            return redirect()->back();
        }

        $data = $request->all();

        // salva a imagem
        if ($request->hasFile('logo') && $request->logo->isValid()) {

            // remove a imagem anterior
            if (!is_null(Storage::exists($tenant->logo))) {
                Storage::delete($tenant->logo);
            }

            // organiza as pastas por tenant
            $data['logo'] = $request->logo->store("tenants/{$tenant->uuid}");
        }

        $tenant->update($data);

        return redirect()->route('tenants.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$tenant = $this->repository->find($id)) {
            return redirect()->back();
        }

        // remove a imagem anterior
        if (Storage::exists($tenant->logo)) {
            Storage::delete($tenant->logo);
        }

        $tenant->delete();

        return redirect()->route('tenants.index');
    }

    /**
     * Search results
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $tenants = $this->repository
            ->where(
                function ($query) use ($request) {
                    if ($request->filter) {
                        $query->where('name', 'LIKE', "%{$request->filter}%");
                    }
                }
            )
            ->latest()
            ->paginate();

        return view('admin.pages.tenants.index', compact('tenants', 'filters'));
    }
}
