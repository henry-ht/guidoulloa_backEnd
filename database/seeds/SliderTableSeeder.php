<?php

use Illuminate\Database\Seeder;

use App\Models\Slider;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            array(
                'url' => 'storage/app/slider/slider_1.jpg'
            ),
            array(
                'url' => 'storage/app/slider/slider_2.jpg'
            ),
            array(
                'url' => 'storage/app/slider/slider_3.jpg'
            ),
        ];
        

        foreach ($datos as $key => $value) {
            Slider::updateOrCreate($value);
        }
    }
}
