<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Admin\Usuario\CreateUserRequest;

use App\Models\User;

class UsuarioController extends Controller
{
    public function index(){
        $roles = ['ADMIN', 'OPERARIO','GERENTE','GERENTE PLANTA','SUPERVISOR','OPERARIO ENTRADA','OPERARIO SALIDA'];
        return view('usuarios.index', compact('roles'));
    }

    public function getUsuarios(Request $request){
        if ($request->ajax()) {
            $usuarios = User::orderBy('created_at','DESC')->get();
            return Datatables::of($usuarios)
                ->addIndexColumn()
                ->addColumn('creado', function ($usuario) {
                    return  Carbon::parse($usuario->created_at)->format('d/m/Y');
                })
                ->addColumn('activo', function ($usuario) {
                    if($usuario->activo) return '<span class="badge bg-info">Activo</span>';
                    return  '<span class="badge bg-danger">Inactivo</span>';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="btn btn-primary mr-1 mb-2"><i class="fa fa-edit"></i></a>';
                    $actionBtn .= ' <a href="javascript:void(0)" class="btn btn-danger mr-1 mb-2"><i class="fa fa-trash"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action','activo'])
                ->make(true);
        }
    }

    public function store(CreateUserRequest $request){
        try {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $data['created_at'] = Carbon::parse(now())->format('Y-m-dTH:i:s');
            $data['updated_at'] = Carbon::parse(now())->format('Y-m-dTH:i:s');
            $data['activo'] = ((int)$data['activo'] == 1)?'True':'False';
            $user =  User::create($data);
            return new JsonResponse([
                'msj'  => 'Usuario creado correctamente!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['msj' => $e->getMessage(), 'type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
