// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function insert(OrderRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $response = $this->orderService->processOrder($validatedData);
        return response()->json($response);
    }
}