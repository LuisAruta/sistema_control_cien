<?php

namespace App\Http\Controllers\Api;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Traits\ValidationTrait;
use Httpful\Request;
use Httpful\Exception\ConnectionErrorException;
use function GuzzleHttp\json_encode;

class ExternalController extends Controller
{
    use ValidationTrait;

    public function ExpedienteByNumero($numeroExpediente)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_EXPEDIENTE);
        if ($numeroExpediente[0] === 'T' || $numeroExpediente[0] === 'P') {
            try{
                $request = Request::get("https://10.102.38.4/api_rest/getExpte/$numeroExpediente");
                $response =  $request
                    ->timeout(5)
                    ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
                    ->send();
                $respuesta = $response->body;
            } catch (ConnectionErrorException $e) {
                $respuesta['error'] = "Se supero el tiempo de espera";
            } catch (\Exception $e) {
                $respuesta['error'] = $e->getMessage();
            }
        } else {
            $respuesta['error'] = 'Numero de expediente no válido';
        }
        return response()->json($respuesta, 200);
    }

    public function personal($documento)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_PERSONAL);
        $data['WSData']['Servicio'] = "WsGetLegInfAct";
        $data['ParmIn']['Parm'] = "[{\"Codigo\":\"DOCUMENTO_DESDE\",\"Valor\":\"" . $documento . "\"},{\"Codigo\":\"DOCUMENTO_HASTA\",\"Valor\":\"" . $documento . "\"}]";
        try{
        $response = Request::post('http://10.100.1.165:8080/sip/rest/acpswsapj')
            ->timeout(5)
            ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
            ->body(json_encode($data))
            ->send();
        $respuesta = $response->body && $response->body->ParmOut && $response->body->ParmOut->Parm ? $response->body->ParmOut->Parm : [];
        } catch (ConnectionErrorException $e) {
            $respuesta['error'] = "Se supero el tiempo de espera";
        } catch (\Exception $e) {
            $respuesta['error'] = $e->getMessage();
        }
        return response()->json($respuesta, 200);
    }

    public function renaper($documento)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_RENAPER);
        try{
        $response = Request::get("http://10.100.1.138:8080/renaper/Renaper/UltimoEjemplarDocumentoPersona?nroDoc=$documento&clientID=sys")
            ->timeout(5)
            ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
            ->send();
        $respuesta = $response->body;
        } catch (ConnectionErrorException $e) {
            $respuesta['error'] = "Se supero el tiempo de espera";
        } catch (\Exception $e) {
            $respuesta['error'] = $e->getMessage();
        }
        return response()->json($respuesta, 200);
    }

    public function departamentos()
    {
        try {
            $response = Request::get("10.100.1.150/ws/dptosmza.php")
                ->timeout(5)
                ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
                ->send();
            $respuesta = $response->body;
        } catch (ConnectionErrorException $e) {
            $respuesta['error'] = "Se supero el tiempo de espera";
        } catch (\Exception $e) {
            $respuesta['error'] = $e->getMessage();
        }
        return response()->json($respuesta, 200);
    }

    public function calles($departamento, $calle_incompleta)
    {
        $departamento = str_replace(" ", "%20", $departamento);
        try {
            $response = Request::get("10.100.1.150/ws/callesmza.php?calle=$calle_incompleta&dpto=$departamento")
                ->timeoutIn(5)
                ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
                ->send();
            $respuesta = $response->body;
        } catch (ConnectionErrorException $e) {
            $respuesta['error'] = "Se supero el tiempo de espera";
        } catch (\Exception $e) {
            $respuesta['error'] = $e->getMessage();
        }
        return response()->json($respuesta, 200);
    }

    public function localidades($departamento)
    {
        $departamento = str_replace(" ", "%20", $departamento);
        try {
            $response = Request::get("10.100.1.150/ws/distritosmza.php?DEPARTAMENTO=$departamento")
                ->timeoutIn(5)
                ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
                ->send();
            $respuesta = $response->body;
        } catch (ConnectionErrorException $e) {
            $respuesta['error'] = "Se supero el tiempo de espera";
        } catch (\Exception $e) {
            $respuesta['error'] = $e->getMessage();
        }
        return response()->json($respuesta, 200);
    }

    public function barrios($departamento, $barrio_incompleto = "")
    {
        $departamento = str_replace(" ", "%20", $departamento);
        try {
            $response = Request::get("10.100.1.150/ws/barriosmza.php?barrio=$barrio_incompleto&dpto=$departamento")
                ->timeoutIn(5)
                ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
                ->send();
            $respuesta = $response->body;
        } catch (ConnectionErrorException $e) {
            $respuesta['error'] = "Se supero el tiempo de espera";
        } catch (\Exception $e) {
            $respuesta['error'] = $e->getMessage();
        }
        return response()->json($respuesta, 200);
    }


}
