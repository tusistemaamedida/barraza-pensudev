<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deposito;
use App\Models\Altura;
use App\Models\Calle;
use App\Models\Estado;
use App\Models\Profundidad;
use App\Models\Ubicacion;
use App\Models\User;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateDepositosSeeder extends Seeder
{

    private function generateEAN($number) {
        $number = rand(1,99).'0'.$number;
        $code = '20' . str_pad($number, 10, '0',STR_PAD_LEFT);
        $weightflag = true;
        $sum = 0;
        for ($i = strlen($code) - 1; $i >= 0; $i--) {
            $sum += (int)$code[$i] * ($weightflag ? 3 : 1);
            $weightflag = !$weightflag;
        }
        $code .= (10 - ($sum % 10)) % 10;

        return $code;
    }

    public function run(){
        User::create([
            'nombre'            => 'Ivan D. Fontana',
            'email'             => 'ivan@pensudev.com',
            'password'          => Hash::make('pensudev2023'),
            'remember_token'    => Str::random(40),
            'rol'               => 'ADMIN',
            'activo'            => 1,
        ]);

        // CAMARA 1
        $dep = Deposito::create([
            'nombre' => 'Cámara N°: 1',
            'codigo_barra' => $this->generateEAN('1'),
            'abrev' => 'CAM1'
        ]);
        // 33 calles
        for ($c=1; $c < 34; $c++) {
            $ce = Calle::create([
                'nombre' => 'Calle N°: '.$c,
                'codigo_barra' => $this->generateEAN('1'.$c),
                'abrev' => 'CALLE'.$c
            ]);

            //5 niveles / alturas
            for ($a=1; $a < 6; $a++) {
                $alt = Altura::create([
                    'nombre' => 'Altura N°: '.$a,
                    'codigo_barra' => $this->generateEAN('1'.$c.$a),
                    'abrev' => 'AL'.$a
                ]);
                // 5 posiciones / profundidades
                for ($p=1; $p < 6; $p++) {
                    $prof = Profundidad::create([
                        'nombre' => '1'.$c.$a.$p,
                        'codigo_barra' => $this->generateEAN('1'.$c.$a.$p),
                        'abrev' => 'POS'.$p
                    ]);

                    Ubicacion::create([
                        'deposito_id' => $dep->id,
                        'calle_id' => $ce->id,
                        'altura_id' => $alt->id,
                        'profundidad_id' => $prof->id,
                        'pallet' => null
                    ]);
                }
            }
        }

        // CAMARA 3
        $dep = Deposito::create([
            'nombre' => 'Cámara N°: 3',
            'codigo_barra' => $this->generateEAN('3'),
            'abrev' => 'CAM3'
        ]);
        // 20 calles
        for ($c=1; $c < 21; $c++) {
            $ce = Calle::create([
                'nombre' => 'Calle N°: '.$c,
                'codigo_barra' => $this->generateEAN('3'.$c),
                'abrev' => 'CALLE'.$c
            ]);

            //4 niveles / alturas
            for ($a=1; $a < 5; $a++) {
                $alt = Altura::create([
                    'nombre' => 'Altura N°: '.$a,
                    'codigo_barra' => $this->generateEAN('3'.$c.$a),
                    'abrev' => 'AL'.$a
                ]);
                // 5 posiciones / profundidades
                for ($p=1; $p < 6; $p++) {
                    $prof = Profundidad::create([
                        'nombre' => '3'.$c.$a.$p,
                        'codigo_barra' => $this->generateEAN('3'.$c.$a.$p),
                        'abrev' => 'POS'.$p
                    ]);

                    Ubicacion::create([
                        'deposito_id' => $dep->id,
                        'calle_id' => $ce->id,
                        'altura_id' => $alt->id,
                        'profundidad_id' => $prof->id,
                        'pallet' => null
                    ]);
                }
            }
        }

        // CAMARA 4
        $dep = Deposito::create([
            'nombre' => 'Cámara N°: 4',
            'codigo_barra' => $this->generateEAN('4'),
            'abrev' => 'CAM4'
        ]);
        // 18 calles
        for ($c=1; $c < 19; $c++) {
            $ce = Calle::create([
                'nombre' => 'Calle N°: '.$c,
                'codigo_barra' => $this->generateEAN('4'.$c),
                'abrev' => 'CALLE'.$c
            ]);

            //4 niveles / alturas
            for ($a=1; $a < 5; $a++) {
                $alt = Altura::create([
                    'nombre' => 'Altura N°: '.$a,
                    'codigo_barra' => $this->generateEAN('4'.$c.$a),
                    'abrev' => 'AL'.$a
                ]);
                // 4 posiciones / profundidades
                for ($p=1; $p < 5; $p++) {
                    $prof = Profundidad::create([
                        'nombre' => '4'.$c.$a.$p,
                        'codigo_barra' => $this->generateEAN('4'.$c.$a.$p),
                        'abrev' => 'POS'.$p
                    ]);

                    Ubicacion::create([
                        'deposito_id' => $dep->id,
                        'calle_id' => $ce->id,
                        'altura_id' => $alt->id,
                        'profundidad_id' => $prof->id,
                        'pallet' => null
                    ]);
                }
            }
        }

        Estado::create(['nombre' => 'Liberado','color' => '#a1dba1','default' => 1]);
        Estado::create(['nombre' => 'Retenido','color' => '#e1e1ae','default' => 0]);
        Estado::create(['nombre' => 'Decomisado','color' => '#f44336','default' => 0]);
    }
}
