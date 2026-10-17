<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClimaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'cidade' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        try {
            $localizacaoResponse = Http::acceptJson()
                ->timeout(8)
                ->get('https://geocoding-api.open-meteo.com/v1/search', [
                    'name' => $dados['cidade'],
                    'count' => 1,
                    'language' => 'pt',
                    'format' => 'json',
                ]);

            if ($localizacaoResponse->failed()) {
                return response()->json(['message' => 'O serviço de localização está indisponível.'], 502);
            }

            $localizacao = $localizacaoResponse->json('results.0');

            if (! is_array($localizacao)) {
                return response()->json(['message' => 'Cidade não encontrada.'], 404);
            }

            $climaResponse = Http::acceptJson()
                ->timeout(8)
                ->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $localizacao['latitude'],
                    'longitude' => $localizacao['longitude'],
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m',
                    'timezone' => 'auto',
                ]);

            if ($climaResponse->failed()) {
                return response()->json(['message' => 'O serviço de clima está indisponível.'], 502);
            }

            return response()->json([
                'cidade' => $localizacao['name'],
                'regiao' => $localizacao['admin1'] ?? null,
                'pais' => $localizacao['country'] ?? null,
                'fuso_horario' => $climaResponse->json('timezone'),
                'clima_atual' => $climaResponse->json('current'),
            ]);
        } catch (ConnectionException) {
            return response()->json(['message' => 'Não foi possível conectar ao serviço Open-Meteo.'], 502);
        }
    }
}
