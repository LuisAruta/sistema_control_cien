<?php

namespace App\Http\Controllers\Auth;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Detenido;
use App\Models\Documento;
use App\Models\Necro;
use App\Models\Traslado;
use App\Models\User;
use App\Models\Victima;
use App\Traits\ValidationTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    use ValidationTrait;
    public function index(Request $request)
    {
        $query = User::query();
        if (!$this->validateDependenciaUsuario()) {
            $user = Auth::user();
            $query->where('dependencia_id', '=', $user->dependencia_id);
        }

        if ($request->filtros) {
            if ($request->filtros["nombre_usuario"]) {
                $query->whereRaw("unaccent(lower(name)) ilike unaccent('%".strtolower($request->filtros["nombre_usuario"])."%')");
            }
            if ($request->filtros["email"]) {
                $query->whereRaw("unaccent(lower(email)) ilike unaccent('%".strtolower($request->filtros["email"])."%')");
            }
            if ($request->filtros["dependencia"]) {
                $query->where('dependencia_id', $request->filtros["dependencia"]);
            }
            if ($request->filtros["nombre_completo_usuario"]) {
                $query->whereRaw("unaccent(lower(nombre_completo)) ilike unaccent('%".strtolower($request->filtros["nombre_completo_usuario"])."%')");
            }
            if ($request->filtros["dni_usuario"]) {
                $query->whereHas('documento', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(numero)) ilike unaccent('%".strtolower($request->filtros["dni_usuario"])."%')");
                });
            }
            if ($request->filtros["perito"]) {
                $query->where('perito', $request->filtros["perito"] === 'SI');
            }
        } else {
            $query->whereRaw("unaccent(lower(name)) ilike unaccent('%".strtolower($request->filter)."%')")
                ->orWhereRaw("unaccent(lower(email)) ilike unaccent('%".strtolower($request->filter)."%')")
                ->orWhereHas('dependencia', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                })
                ->orWhereHas('documento', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(numero)) ilike unaccent('%".strtolower($request->filter)."%')");
                })
                ->orWhereRaw("unaccent(lower(nombre_completo)) ilike unaccent('%".strtolower($request->filter)."%')");
        }


        $usuarios =  $query->orderBy('id')->paginate(10);
        return new UserCollection($usuarios);
    }

    /**
     * Get authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request)
    {
        $user = $request->user();
        $permissions = $user->getAllPermissions()->pluck('name');
        return response()->json(['user' => $user, 'permissions' => $permissions]);
    }

    public function allRoles(){
        $roles = Role::all();
        return new RoleCollection($roles);
    }

    public function store(UserStoreRequest $request)
    {
        $this->validatePermission(PermissionConstant::CREAR_USUARIO);
        $request->validated();
        $usuario = new User();
        $usuario->name = $request['usuario']['name'];
        $usuario->email = $request['usuario']['email'];
        $usuario->dependencia_id = $request['usuario']['dependencia_id'];
        $usuario->nombre_completo = $request['usuario']['nombre_completo'];
        $usuario->perito = $request['usuario']['perito'];
        $documento = Documento::where('tipo_documento', $request['documento']['tipo_documento'])
            ->where('numero', $request['documento']['numero'])
            ->first();
        if ($documento){
            $usuario->documento_id = $documento->id;
        } else {
            $doc = new Documento();
            $doc->tipo_documento = $request['documento']['tipo_documento'];
            $doc->numero = $request['documento']['numero'];
            $doc->save();
            $usuario->documento_id = $doc->id;
        }
        $usuario->assignRole($request['usuario']['rolesUsuario']);
        $usuario->password = bcrypt($request['documento']['numero']);
        $usuario->save();

        return new UserResource($usuario);
    }

    public function blanquear($id)
    {
        $this->validatePermission(PermissionConstant::EDITAR_USUARIO);
        $usuario = User::find($id);
        $usuario->password = bcrypt($usuario->documento->numero);
        $usuario->save();
    }

    /**
     * Traer usuarios Perito
     *
     * @param Request $request
     * @return UserCollection
     */
    public function allPerito(request $request)
    {
        $user = User::where('perito', true)->get();

        return new UserCollection($user);
    }

    public function update(UserUpdateRequest $request)
    {
        $this->validatePermission(PermissionConstant::EDITAR_USUARIO);
        $request->validated();
        $usuario = User::find($request['usuario']['id']);
        $usuario->name = $request['usuario']['name'];
        $usuario->email = $request['usuario']['email'];
        $usuario->dependencia_id = $request['usuario']['dependencia_id'];
        $usuario->nombre_completo = $request['usuario']['nombre_completo'];
        $usuario->perito = $request['usuario']['perito'];
        $documento = Documento::where('tipo_documento', $request['documento']['tipo_documento'])
            ->where('numero', $request['documento']['numero'])
            ->first();
        if ($documento){
            $usuario->documento_id = $documento->id;
        } else {
            $doc = new Documento();
            $doc->tipo_documento = $request['documento']['tipo_documento'];
            $doc->numero = $request['documento']['numero'];
            $doc->save();
            $usuario->documento_id = $doc->id;
        }
        $usuario->syncRoles($request['usuario']['rolesUsuario']);
        $usuario->save();
        //$this->borrarDocumento();
        return new UserResource($usuario);
    }

    public function destroy(Request $request)
    {
        $this->validatePermission(PermissionConstant::ELIMINAR_USUARIO);
        $usuario = User::find($request->id);
        $usuario->delete();
        //$this->borrarDocumento();
        return response()->noContent();
    }

    public function borrarDocumento(){
        $registros = DB::select(DB::raw("
              SELECT documento_id as id FROM users where deleted_at is null
                UNION
              SELECT documento_id as id  FROM victimas where deleted_at is null
                UNION
              SELECT documento_id as id  FROM detenidos where deleted_at is null
                UNION
              SELECT cadaver_documento_id as id FROM necros where deleted_at is null
                UNION
              SELECT cadaver_documento_id as id FROM traslados where deleted_at is null"));

        $documentosActivos = array();
        foreach ($registros as $reg) {
            $documentosActivos[] = $reg->id;
        }

        DB::table('documentos')->whereNotIn('id', $documentosActivos)
            ->delete();
    }
}
