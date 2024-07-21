// app/Http/Requests/OrderRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|string',
            'name' => 'required|string|regex:/^[A-Za-z ]+$/|regex:/^[A-Z][a-z]+( [A-Z][a-z]+)*$/',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric|max:2000',
            'currency' => 'required|string|in:TWD,USD',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Name contains non-English characters or is not capitalized',
            'price.max' => 'Price Is over 2000',
            'currency.in' => 'Currency format Is wrong',
        ];
    }
}