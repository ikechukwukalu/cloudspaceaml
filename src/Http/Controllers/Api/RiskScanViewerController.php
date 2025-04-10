<?php

namespace Cloudspace\AML\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Cloudspace\AML\Models\RiskScanResult;
use Cloudspace\AML\Services\RiskScan\RiskScanViewerService;
use Cloudspace\AML\Traits\ResponseData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RiskScanViewerController extends Controller
{
    use ResponseData;

    public function index(Request $request, RiskScanViewerService $service): JsonResponse
    {
        $filters = $request->only(['risk_level', 'name', 'from', 'to']);
        // $data = $service->list($filters);
        $data = $service->list($filters)->through(function ($scan) {
            $scan->matches->transform(fn ($match) => [
                'source' => $match->source,
                'type' => $match->match_type,
                'confidence' => $match->confidence,
                'description' => $match->description,
                'link' => $match->source_url,
            ]);

            return $scan;
        });
        $response = $this->responseData(true,'Risk scan results retrieved successfully', $data);

        return response()->json($response, Response::HTTP_OK);
    }

    public function show($id, RiskScanViewerService $service): JsonResponse
    {
        $result = $service->getById($id);

        if (!$result) {
            $response = $this->responseData(false, null, 'Risk scan result not found');
            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $response = $this->responseData(true, $result, 'Risk scan result retrieved successfully');

        return response()->json($response, Response::HTTP_OK);
    }

    public function downloadPdf($id)
    {
        $result = RiskScanResult::with('matches')->findOrFail($id);

        $pdf = Pdf::loadView('aml::reports.risk-report', compact('result'));

        return $pdf->download("risk-report-{$result->id}.pdf");
    }
}
