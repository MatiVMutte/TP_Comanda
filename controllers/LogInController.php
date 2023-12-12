<?php

include_once "utils/AutentificadorJWT.php";

class LoginControlador 
{
    //tener en cuenta que el usuario este activo para poder loguearse
    public static function VerificarExistenciaUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if(isset($parametros['nombre']) && isset($parametros['contrasenia']))
        {
           $retorno = EmpleadoDAO::traerPorUsuarioYContra($parametros['nombre'], $parametros['contrasenia']);
     
           if($retorno!=null)
           {
                $datos=array('nombre'=>$retorno['nombre'],'rol'=>$retorno['rol']);
                $token = AutentificadorJWT::CrearToken($datos);
                $payload = json_encode(array('jwt' => $token));
                session_start();
                $_SESSION['idEmpleado'] = $retorno['id'];
           }
           else
           {
                $payload = json_encode(array('ERROR, revise datos ingresados'));
           }    
        }
        else
        {
            $payload = json_encode(array('ERROR' => 'Faltan ingresar datos'));
        }
        $response->getBody()->write($payload);
        return $response;
    }
}












?>