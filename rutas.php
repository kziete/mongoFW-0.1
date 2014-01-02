<?php 
Toro::serve(array(
    "/" => "Index",
    "/prueba" => "Probando",
    //De sistema, favor no tocar
    "/admin" => "Admin",
    "/admin/:alpha" => "AdminLista",
    "/admin/:alpha/:alpha" => "AdminForm",
    "/(.*)" => 'Error404'
));
