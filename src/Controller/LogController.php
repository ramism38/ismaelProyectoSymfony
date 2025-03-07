<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LogController extends AbstractController
{

    #[Route('/log/{action}', name: 'log_action')]
    public function logAction(string $action): JsonResponse
    {
        $logFilePath = __DIR__ . '/../../var/log/actions.log';

        // Verificar si el archivo existe, si no, crearlo
        if (!file_exists($logFilePath)) {
            file_put_contents($logFilePath, "Log de acciones\n\n");
        }

        // Obtener usuario autenticado (si está logueado)
        $user = $this->getUser();
        $username = $user ? $user->getUserIdentifier() : 'Anonimo';

        // Formatear la línea de log
        $logEntry = sprintf("[%s] Usuario: %s - Acción: %s\n", date('Y-m-d H:i:s'), $username, $action);

        // Escribir en el archivo de log
        file_put_contents($logFilePath, $logEntry, FILE_APPEND);

        return new JsonResponse(['status' => 'ok', 'message' => 'Acción registrada']);
    }
}
