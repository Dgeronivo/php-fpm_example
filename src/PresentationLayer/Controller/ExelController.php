<?php

declare(strict_types=1);

namespace App\PresentationLayer\Controller;

use App\DomainLayer\Service\CellProvider;
use App\DomainLayer\Service\CellUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExelController extends AbstractController
{
    public function __construct(
        private readonly CellUpdater $cellUpdater,
        private readonly CellProvider $cellProvider,
    ) {}

    #[Route('api/v1/{sheetId}/{cellId}', methods: 'POST')]
    public function updateCellAction(string $sheetId, string $cellId, Request $request): JsonResponse
    {
        $formula = $request->request->get('value');
        if (empty($formula)) {
            $this->cellUpdater->removeCell($sheetId, $cellId);

            return new JsonResponse(['value' => '', 'result' => '',], 201);
        }

        if (intval($cellId)) {
            return new JsonResponse(['value' => $formula, 'result' => 'ERROR: cell id can`t be int'], 422);
        }

        $response = $this->cellUpdater->update(strtolower($sheetId), strtolower($cellId), $formula);
        $statusCode = $response['withError'] ? 422 : 201;
        unset($response['withError']);

        return new JsonResponse($response, $statusCode);
    }

    #[Route('api/v1/{sheetId}/{cellId}', methods: 'GET')]
    public function getCellAction(string $sheetId, string $cellId): JsonResponse
    {
        $response = $this->cellProvider->getCell(strtolower($sheetId), strtolower($cellId));
        if (empty($response)) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse($response);
    }

    #[Route('api/v1/{sheetId}', methods: 'GET')]
    public function getSheetAction(string $sheetId): JsonResponse
    {
        $response = $this->cellProvider->getSheet(strtolower($sheetId));
        if (empty($response)) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse($response);
    }
}
