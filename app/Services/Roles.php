<?php
    namespace App\Services;

    use App\Models\Usuario;
    use Illuminate\Support\Facades\Auth;

    class Roles{

        public function __construct(){}

        public function getRoles()
        {        
            $usuarioEmail = Auth::user()->email;
            $usuario = Usuario::where('email', $usuarioEmail)
            ->first();

            $roles = [
                [
                    'id' => 1,
                    'nombre' => 'SuperAdmin',
                    'permisos' => [
                        'importaralfa' => true,
                        'masiva' => true,
                        'usuario' => true,
                        'cotejounoauno' => true,
                        'log' => true,
                    ]
                ],
                [
                    'id' => 2,
                    'nombre' => 'Admin',
                    'permisos' => [
                        'importaralfa' => true,
                        'masiva' => true,
                        'usuario' => false,
                        'cotejounoauno' => true,
                        'log' => true,
                    ]
                ],
                [
                    'id' => 3,
                    'nombre' => 'Operario',
                    'permisos' => [
                        'importaralfa' => false,
                        'masiva' => false,
                        'usuario' => false,
                        'cotejounoauno' => true,
                        'log' => false,
                    ]
                ],
            ];

            $role = collect($roles)->firstWhere('id', $usuario->rol);

            return $role['permisos'];
            
        }
    }
?>