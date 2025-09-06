<?php

use App\Models\Configuration;

function configInfo()
{
    $config = new Configuration();
    if($data = $config->find(1)){
        return $data;
    }
    return [];
}

function hexToRgb($hex) {
    // Quitar el símbolo '#' si está presente
    $hex = ltrim($hex, '#');

    // Si el formato es abreviado (e.g., "fff"), expandirlo
    if (strlen($hex) === 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }

    // Convertir hexadecimal a decimal
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    return "$r, $g, $b";
}

function darkenColor($hex, $percent) {
    // Quitar el carácter '#' si está presente
    $hex = str_replace('#', '', $hex);

    // Convertir el valor HEX a RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Interpolar cada componente RGB hacia negro (0, 0, 0)
    $r = $r * (1 - $percent / 100);
    $g = $g * (1 - $percent / 100);
    $b = $b * (1 - $percent / 100);

    // Convertir de vuelta a HEX y retornar el nuevo color
    return sprintf("#%02x%02x%02x", (int)$r, (int)$g, (int)$b);
}

function lightenColor($hex, $percent) {
    // Quitar el carácter '#' si está presente
    $hex = str_replace('#', '', $hex);

    // Convertir el valor HEX a RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Interpolar cada componente RGB hacia blanco (255, 255, 255)
    $r = $r + (255 - $r) * ($percent / 100);
    $g = $g + (255 - $g) * ($percent / 100);
    $b = $b + (255 - $b) * ($percent / 100);

    // Convertir de vuelta a HEX y retornar el nuevo color
    return sprintf("#%02x%02x%02x", (int)$r, (int)$g, (int)$b);
}

function getCommit(){
    return env('GIT_COMMIT_HASH', strtotime(date('Y-m-d H:i:s')));
}

function Color() {
    $path = FCPATH . 'assets/json/colors.json';
    if (file_exists($path)) {
        $json = file_get_contents($path);
        $colores = json_decode($json, true);
        $css = "";

        foreach ($colores as $color => $variaciones) {
            if (isset($variaciones['base'])) {
                $base = $variaciones['base'];

                // 🔹 Fondo y texto base
                $css .= ".$color { background-color: $base !important; }\n";
                $css .= ".text-$color { color: $base !important; }\n";
                $css .= ".select2-results__option.$color{ background-color: $base !important; color: #fff !important; }\n";

                // 🔹 Sombra en cards
                $css .= ".card.card-border-shadow-$color::after { border-bottom-color: $base; }\n";
                $css .= ".card.card-border-shadow-$color:hover::after { border-bottom-color: $base; }\n";

                // 🔹 bg-label-{color} (usa lighten-5 si existe, si no el base)
                $bgLabel = $variaciones['lighten-5'] ?? $base;
                $css .= ".bg-label-$color { background-color: $bgLabel !important; color: $base !important; }\n";

                // Variaciones
                foreach ((array) $variaciones as $key => $color_value) {
                    if ($key !== "base") {
                        $css .= ".$color.$key { background-color: $color_value !important; }\n";
                        $css .= ".text-$color.text-$key { color: $color_value !important; }\n";
                        $css .= ".select2-results__option.$color.$key{ background-color: $color_value !important; color: #fff !important; }\n";

                        // Card shadow con variaciones
                        $css .= ".card.card-border-shadow-$color-$key::after { border-bottom-color: $color_value; }\n";
                        $css .= ".card.card-border-shadow-$color-$key:hover::after { border-bottom-color: $color_value; }\n";

                        // bg-label con variaciones
                        $css .= ".bg-label-$color-$key { background-color: $color_value !important; color: $base !important; }\n";
                    }
                }
            } else if ($color == "black" || $color == "white") {
                $base = $variaciones;
                $css .= ".$color { background-color: $base !important; }\n";
                $css .= ".text-$color { color: $base !important; }\n";
                $css .= ".select2-results__option.$color.select2-results__option--highlighted { background-color: $base !important; color: #000 !important; }\n";

                // Card shadow para black y white
                $css .= ".card.card-border-shadow-$color::after { border-bottom-color: $base; }\n";
                $css .= ".card.card-border-shadow-$color:hover::after { border-bottom-color: $base; }\n";

                // bg-label para black y white
                $css .= ".bg-label-$color { background-color: $base !important; color: #000 !important; }\n";
            }
        }

        file_put_contents(FCPATH . 'assets/css/colors.css', $css);
        echo "Archivo 'colores.css' generado exitosamente con sombras y labels.";
    } else {
        echo "El archivo no existe";
    }
}

