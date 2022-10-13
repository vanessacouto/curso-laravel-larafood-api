<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\EvaluationService;
use App\Http\Controllers\Controller;
use App\Http\Resources\EvaluationResource;
use App\Http\Requests\Api\StoreEvaluationOrder;

class EvaluationApiController extends Controller
{
    protected $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    public function store(StoreEvaluationOrder $request) 
    {
        // array com o stars e o comentario
        $data = $request->only('stars', 'comment');
        $evaluation = $this->evaluationService
            ->createNewEvaluation($request->identifyOrder, $data);

        return new EvaluationResource($evaluation);
    }
}
