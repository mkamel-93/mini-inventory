<?php

declare(strict_types=1);

namespace App\Http\Requests\API\Warehouse;

use App\Http\Requests\API\BaseFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class WarehouseListRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'page' => [
                'integer',
                'min:1',
            ],
            'per_page' => [
                'integer',
                'min:1',
            ],
            'name' => [
                'string',
                'max:255',
            ],
        ];
    }
}
