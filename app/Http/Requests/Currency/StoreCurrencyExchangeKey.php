<?php

namespace App\Http\Requests\Currency;

use App\Http\Requests\CoreRequest;

class StoreCurrencyExchangeKey extends CoreRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency_converter_key' => 'required',
            'dedicated_subdomain' => 'required_if:currency_key_version,dedicated',
        ];
    }

}
