<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Viatura;
use App\Models\OrdemServico;
use App\Models\Servico;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $viewName = 'dashboards.' . strtolower($user->role);
        
        // Prepare data based on user role
        $data = [];
        
        switch($user->role) {
            case 'admin':
                $data = [
                    'usuarios' => Usuario::count(),
                    'viaturas' => Viatura::count(),
                    'ordensServico' => OrdemServico::count(),
                    'servicos' => Servico::count(),
                    'ultimasOrdens' => OrdemServico::with(['viatura', 'viatura.cliente', 'mecanico'])
                        ->latest()
                        ->take(5)
                        ->get()
                ];
                break;
            
            case 'cliente':
                $data = [
                    'viaturas' => Viatura::where('cliente_id', $user->id)->get()
                ];
                break;
                
            // Add other roles as needed
        }
        
        return view($viewName, $data);
    }
}