<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class CheckRolesAndPermissions
{
    // protected $permissionMap = [
    //     'index' => [
    //         'policyMethod' => 'viewAny',
    //         'arguments' => ['user' => 'Illuminate\Support\Facades\Auth::user()']
    //     ],
    //     'store' => [
    //         'policyMethod' => 'create',
    //         'arguments' => []
    //     ],
    //     'show' => [
    //         'policyMethod' => 'view',
    //         'arguments' => ['user' => 'Illuminate\Support\Facades\Auth::user()', 'resource' => 'model']
    //     ],
    //     'update' => [
    //         'policyMethod' => 'update',
    //         'arguments' => ['user' => 'Illuminate\Support\Facades\Auth::user()', 'resource' => 'model']
    //     ],
    //     'destroy' => [
    //         'policyMethod' => 'delete',
    //         'arguments' => ['user' => 'Illuminate\Support\Facades\Auth::user()', 'resource' => 'model']
    //     ],
    // ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        
        $permissionMap = [
            'index' => 'viewAny',
            'store' => 'create',
            'show' => 'view',
            'update' => 'update',
            'destroy' => 'delete'
        ];
        $actionMethod = Route::getCurrentRoute()->getActionMethod();
        if (!array_key_exists($actionMethod, $permissionMap)) {
            return $next($request);
        }

        $permission = $permissionMap[$actionMethod];
        $response = Gate::inspect($permission, [Auth::user()]);
        if (!$response->allowed()) {
            return response()->json([
                'message' => $response->message()
            ], $response->code());
        }
        return $next($request);
    }

    // protected function resolveArguments(array $arguments, Request $request)
    // {
    //     $resolvedArguments = [];

    //     foreach ($arguments as $key => $value) {
    //         if ($value === 'Illuminate\Support\Facades\Auth::user()') {
    //             // Resolve o usuário autenticado
    //             $resolvedArguments[] = Auth::user();
    //         } elseif ($value === 'model') {
    //             // Exemplo de resolução de um modelo, se for o caso
    //             // (Aqui você pode obter o modelo, dependendo da sua lógica)
    //             $resolvedArguments[] = $this->getModelForRequest($request);
    //         }
    //         // Você pode adicionar outros casos de resolução de argumentos conforme necessário
    //     }

    //     return $resolvedArguments;
    // }

    // protected function getModelForRequest(Request $request)
    // {
    //     // Aqui você pode determinar o modelo dependendo da URL ou parâmetros
    //     // Por exemplo, se for um ID de recurso passado na URL
    //     $resourceId = $request->route('id');
    //     return \App\Models\User::find($resourceId);  // Exemplo com User, modifique conforme necessário
    // }
}
