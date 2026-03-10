<?php

declare(strict_types=1);

namespace App\Http\Requests\API\Inventory;

use App\Http\Requests\API\BaseFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class InventoryListRequest extends BaseFormRequest
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
            'warehouse_id' => [
                'nullable',
                'integer',
                'exists:warehouses,id',
            ],
            'name' => [
                'string',
                'max:255',
            ],
            'min_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'max_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}
