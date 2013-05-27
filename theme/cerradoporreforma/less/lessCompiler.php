<?php

$bootstrapFiles = array('bootstrap.less', 'cerrado.less', 'responsive.less','responsive-1200px-min.less','responsive-767px-max.less','responsive-768px-979px.less');

do {
    $compilarTodo = false;
    $configuracion = null;
    if (file_exists('less.log')) {
        $configuracion = json_decode(file_get_contents('less.log'), ARRAY_A);
    } else {
        $compilarTodo = true;
    }


    foreach ($bootstrapFiles as $file) {
        $ultimaModificacion = filemtime($file);
        if (!isset($configuracion[$file]) || $ultimaModificacion > $configuracion[$file]) {
            $compilarTodo = true;
        }
    }
    if ($compilarTodo) {
        passthru('./lessCompiler.sh');
        $configuracion = array();
        foreach ($bootstrapFiles as $file) {
            $configuracion[$file] = filemtime($file);
        }
        file_put_contents("less.log", json_encode($configuracion));
    }
    sleep(1);
} while (true);


