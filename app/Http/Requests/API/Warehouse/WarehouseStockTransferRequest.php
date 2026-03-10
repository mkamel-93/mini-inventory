<?php

declare(strict_types=1);

namespace App\Http\Requests\API\Warehouse;

use App\Http\Requests\API\BaseFormRequest;

class WarehouseStockTransferRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'from_warehouse_id' => [
                'required',
                'exists:warehouses,id',
                'different:to_warehouse_id',
            ],
            'to_warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],
            'inventory_item_id' => [
                'required',
                'exists:inventory_items,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
