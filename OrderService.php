// app/Services/OrderService.php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderService
{
    const USD_TO_TWD_RATE = 31;

    public function processOrder(array $data)
    {
        $this->validateOrderData($data);
        $this->transformCurrency($data);
        return $data;
    }

    protected function validateOrderData(array $data)
    {
        if (!preg_match('/^[A-Za-z ]+$/', $data['name'])) {
            throw ValidationException::withMessages(['name' => 'Name contains non-English characters']);
        }

        if (!preg_match('/^[A-Z][a-z]+( [A-Z][a-z]+)*$/', $data['name'])) {
            throw ValidationException::withMessages(['name' => 'Name Is not capitalized']);
        }

        if ($data['price'] > 2000) {
            throw ValidationException::withMessages(['price' => 'Price Is over 2000']);
        }
    }

    protected function transformCurrency(array &$data)
    {
        if ($data['currency'] === 'USD') {
            $data['price'] *= self::USD_TO_TWD_RATE;
            $data['currency'] = 'TWD';
        }
    }
}