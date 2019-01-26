<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $msj = 'imagenes de sliders';
        $notificar = false;
        $tipo = 'success';
        $respuesta = false;


        $respuesta = Slider::all();

        return response([
            'respuesta' => $respuesta,
            'notificar' => $notificar,
            'tipo' => $tipo,
            'mensaje' => $msj
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msj = 'Store';
        $notificar = false;
        $tipo = 'Info';
        $respuesta = false;

        $credenciales = $request->only([
                'nombre',
            ]);

        $validacion = Validator::make($credenciales,[
            'nombre' => 'required|max:8|min:6',

        ]);

        if (!$validacion->fails()) {

            $file = $request->file('file');

            $extension = $file->getClientOriginalExtension();
            $almacena = Storage::disk('local')->put('slider/'.$credenciales['nombre'].'.'.$extension,  File::get($file));

            if ($almacena ) {

                $archivo = Slider::where('url', 'LIKE', '%'.$credenciales['nombre'].'%')->first();

                if (!empty($archivo)) {
                    $okInsert = Slider::where('id', '=', $archivo->id)->update([
                        'url' => 'storage/app/slider/'.$credenciales['nombre'].'.'.$extension
                    ]);
                    
                }else{
                    $okInsert = Slider::insert([
                        'url' => 'storage/app/slider/'.$credenciales['nombre'].'.'.$extension
                    ]);
                }

                $respuesta = $okInsert;

                
            }else{
                $msj = 'No fue posible subir el archivo';
                $notificar = true;
                $tipo = 'error';
                $respuesta = false;

            }

        }else{
            $msj = $validacion->messages();
            $notificar = true;
            $tipo = 'error';
            $respuesta = false;

        }




        return response([
              'respuesta' => $respuesta,
              'notificar' => $notificar,
              'tipo' => $tipo,
              'msj' => $msj
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        $msj = '';
        $notificar = false;
        $tipo = '';
        $respuesta = false;


            $okDelete = $slider->delete();

            if ($okDelete) {
                $msj = ['msj' => ['Eliminado']];
                $notificar = true;
                $tipo = 'success';
                $respuesta = true;   
            }else{
                $msj = ['msj' => ['No fue posible realizar la acción']];
                $notificar = true;
                $tipo = 'error';
                $respuesta = false;
            }
        

        return response([
              'respuesta' => $respuesta,
              'notificar' => $notificar,
              'tipo' => $tipo,
              'mensaje' => $msj
        ],200);
    }
}
