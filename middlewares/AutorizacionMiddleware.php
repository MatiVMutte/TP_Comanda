<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Message;
use Slim\Psr7\Response;

    class AutorizacionMiddleware
    {
        public $autorizado;
        public function __construct($autorizado)
        {
            $this->autorizado = $autorizado;
        }
        public function __invoke(Request $request, RequestHandler $handler): Response
        {
            $header = $request->getHeaderLine('Authorization');
            $token = trim(explode("Bearer", $header)[1]);
            try
            {
                
                AutentificadorJWT::VerificarToken($token); //VALIDEZ DEL TOKEN
                
                $data=AutentificadorJWT::ObtenerData($token); //OBTENGO LA DATA
                
                // var_dump( $this->autorizado);

                if ($data->rol === "Socio" || $data->rol === $this->autorizado) 
                {
                    //PERMITO QUE SE CONTINUE CON EL FLUJO NORMAL
                    $response = $handler->handle($request);
                }
                else
                {
                    //Utilizo Response para construir la respuesta para el cliente que hizo la solicitud http
                    $response = new Response();
                    $payload = json_encode(array('mensaje' => 'ERROR, no tiene los permisos'));
                    $response->getBody()->write($payload);
                }
            }
            catch(Exception $e)
            {
                $response = new Response();
                // $payload = json_encode(array("ERROR, TOKEN NO VALIDO"));
                $payload = json_encode(array($e));
                $response->getBody()->write($payload);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>
