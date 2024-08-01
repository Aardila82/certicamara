<?php
    namespace App\Services;
    
    class Coordenadas{
        
        public function __construct(){}
        public  function  getCoordenadas(){
            $capitalesColombia = [
                ['latitud' => 4.7110, 'longitud' => -74.0721],  // Bogotá
                ['latitud' => 6.2442, 'longitud' => -75.5812],  // Medellín
                ['latitud' => 3.4516, 'longitud' => -76.5319],  // Cali
                ['latitud' => 10.9685, 'longitud' => -74.7813], // Barranquilla
                ['latitud' => 10.3910, 'longitud' => -75.4794], // Cartagena
                ['latitud' => 7.8939, 'longitud' => -72.5078],  // Cúcuta
                ['latitud' => 7.1193, 'longitud' => -73.1227],  // Bucaramanga
                ['latitud' => 4.8133, 'longitud' => -75.6961],  // Pereira
                ['latitud' => 11.2408, 'longitud' => -74.1990], // Santa Marta
                ['latitud' => 4.4389, 'longitud' => -75.2322],  // Ibagué
                ['latitud' => 4.1420, 'longitud' => -73.6266],  // Villavicencio
                ['latitud' => 1.2136, 'longitud' => -77.2811],  // Pasto
                ['latitud' => 5.0703, 'longitud' => -75.5138],  // Manizales
                ['latitud' => 8.7472, 'longitud' => -75.8814],  // Montería
                ['latitud' => 2.9386, 'longitud' => -75.2678],  // Neiva
                ['latitud' => 4.5339, 'longitud' => -75.6811],  // Armenia
                ['latitud' => 9.3047, 'longitud' => -75.3978],  // Sincelejo
                ['latitud' => 11.5444, 'longitud' => -72.9078], // Riohacha
                ['latitud' => 5.5353, 'longitud' => -73.3678],  // Tunja
                ['latitud' => 1.6144, 'longitud' => -75.6062],  // Florencia
                ['latitud' => 5.6947, 'longitud' => -76.6611],  // Quibdó
                ['latitud' => 2.4448, 'longitud' => -76.6147],  // Popayán
                ['latitud' => 10.4631, 'longitud' => -73.2532], // Valledupar
                ['latitud' => 1.1476, 'longitud' => -76.6475],  // Mocoa
                ['latitud' => 12.5833, 'longitud' => -81.7006], // San Andrés
                ['latitud' => -4.2153, 'longitud' => -69.9406], // Leticia
                ['latitud' => 5.3378, 'longitud' => -72.3959],  // Yopal
                ['latitud' => 3.8653, 'longitud' => -67.9239],  // Inírida
                ['latitud' => 6.1847, 'longitud' => -67.4851],  // Puerto Carreño
                ['latitud' => 1.1983, 'longitud' => -70.1739]   // Mitú
            ];
            $index = array_rand($capitalesColombia);
            return $capitalesColombia[$index];
            
        }
  
    }