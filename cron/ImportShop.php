<?php

class ImportShop {

    const PATH_SHOP = 'C:/xampp/htdocs/sistemas/bongo_transactions/pay/';
    const PATH_NEW_SHOP = 'C:/xampp/htdocs/sistemas/checkout/public/static/images/logos/';

    public function run() {
        $this->leerRutas();
    }

    function leerRutas($ruta = self::PATH_SHOP) {
        // abrir un directorio y listarlo recursivo        
        if (is_dir($ruta)) {
            if ($dh = opendir($ruta)) {
                while (($file = readdir($dh)) !== false) {
                    //esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
                    //mostraría tanto archivos como directorios
                    //echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
                    //var_dump($ruta . $file);Exit;
                    if (is_dir($ruta . $file) && $file != "." && $file != "..") {
                        //solo si el archivo es un directorio, distinto que "." y ".."                        
                        if ($file == 'images') {
                            //echo "<br>Directorio: $ruta$file";
                            $ruras = array_reverse(explode('/', $ruta . $file));
                            $aa = $ruta . $file;
                            if ($dhs = opendir($aa)) {
                                while (($filea = readdir($dhs)) !== false) {
                                    if ($filea != "." && $filea != ".." && strpos($aa . '/' . $filea, 'logo')) {
                                        copy($aa . '/' . $filea, self::PATH_NEW_SHOP . $ruras[2] . '_' . $filea);
                                        echo "<br>arch: $aa" . '/' . "$filea";
                                    }
                                }
                            }
                        }
                        $this->leerRutas($ruta . $file . "/");
                    }
                }
                closedir($dh);
            }
        } else
            echo "<br>No es ruta valida";
    }

}

$class = new ImportShop();
$class->run();
