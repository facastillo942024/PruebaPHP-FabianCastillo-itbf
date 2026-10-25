<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Excepción para violaciones de reglas de negocio del dominio.
 *
 * Se renderiza como HTTP 422 (Unprocessable Entity) con un cuerpo JSON
 * consistente, de forma que la capa HTTP no necesita conocer los detalles
 * de validación del dominio (Separación de responsabilidades / SRP).
 */
class BusinessRuleException extends Exception
{
    public function __construct(
        string $message,
        private readonly string $field = 'general'
    ) {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => [
                $this->field => [$this->getMessage()],
            ],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
