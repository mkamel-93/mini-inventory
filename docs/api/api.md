# API Documentation

## Overview

This API provides endpoints for managing inventory, warehouses, and stock transfers in the inventory management system. All endpoints require authentication using Laravel Sanctum.

### Postman Collection

You can download the Postman collection for this API from the following file:
- [`Mini-inventory.postman-collection.json`](./Mini-inventory.postman-collection.json)

This collection contains all the API endpoints with proper authentication setup and example requests for easy testing and integration.

## Authentication

All API endpoints are protected by the `auth:sanctum` middleware and require a valid bearer token in the `Authorization` header:

```
Authorization: Bearer {token}
```

## Base URL

```
http://172.19.0.12/api
```

## Endpoints

### Authentication & Profile

#### GET /profile
Get the authenticated user's profile information.

**Response:**
```json
{
    "success": true,
    "message": "Profile retrieved successfully",
    "data": {
        "id": 1,
        "name": "Mostafa Kamel",
        "email": "mostafakamel000@gmail.com"
    }
}
```

#### POST /logout
Log the user out by revoking their current access token.

**Response:**
```json
{
    "success": true,
    "message": "Logged out successfully",
    "data": null
}
```

### Inventory Management

#### GET /warehouses-inventory-item-list
Get a paginated list of inventory items with optional filtering.

**Query Parameters:**
- `page` (integer, optional): Page number (min: 1)
- `per_page` (integer, optional): Items per page (min: 1)
- `warehouse_id` (integer, optional): Filter by warehouse ID
- `name` (string, optional): Filter by item name (partial match)
- `min_price` (numeric, optional): Filter by minimum price
- `max_price` (numeric, optional): Filter by maximum price

**Example Request:**
```
GET /api/warehouses-inventory-item-list?page=1&per_page=10&warehouse_id=1&name=laptop
```

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Laptop Pro",
            "description": "High-performance laptop",
            "sku": "LP-001",
            "price": 1299.99,
            "quantity": 15
        }
    ],
    "links": {
        "first": "http://172.19.0.12/api/warehouses-inventory-item-list?page=1",
        "last": "http://172.19.0.12/api/warehouses-inventory-item-list?page=5",
        "prev": null,
        "next": "http://172.19.0.12/api/warehouses-inventory-item-list?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 10,
        "to": 10,
        "total": 45
    }
}
```

### Warehouse Management

#### GET /warehouses
Get a paginated list of warehouses with optional filtering.

**Query Parameters:**
- `page` (integer, optional): Page number (min: 1)
- `per_page` (integer, optional): Items per page (min: 1)
- `name` (string, optional): Filter by warehouse name (partial match)

**Example Request:**
```
GET /api/warehouses?page=1&per_page=10&name=main
```

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Main Warehouse",
            "location": "123 Storage St, City, State"
        }
    ],
    "links": {
        "first": "http://172.19.0.12/api/warehouses?page=1",
        "last": "http://172.19.0.12/api/warehouses?page=3",
        "prev": null,
        "next": "http://172.19.0.12/api/warehouses?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 3,
        "per_page": 10,
        "to": 10,
        "total": 25
    }
}
```

#### GET /warehouses/{warehouse}/inventory
Get detailed information about a specific warehouse including its inventory items and transfer history.

**Path Parameters:**
- `warehouse` (integer): Warehouse ID

**Response:**
```json
{
    "id": 1,
    "name": "Main Warehouse",
    "location": "123 Storage St, City, State",
    "inventoryItems": [
        {
            "id": 1,
            "name": "Laptop Pro",
            "description": "High-performance laptop",
            "sku": "LP-001",
            "price": 1299.99,
            "quantity": 15
        }
    ],
    "transfers": {
        "outgoing": [
            {
                "id": 1,
                "from_warehouse_id": 1,
                "to_warehouse_id": 2,
                "inventory_item_id": 1,
                "quantity": 5,
                "created_at": "2024-01-15T10:30:00.000000Z"
            }
        ],
        "incoming": [
            {
                "id": 2,
                "from_warehouse_id": 3,
                "to_warehouse_id": 1,
                "inventory_item_id": 2,
                "quantity": 10,
                "created_at": "2024-01-14T15:20:00.000000Z"
            }
        ]
    }
}
```

### Stock Transfers

#### POST /warehouses-stock-transfers
Transfer stock between warehouses.

**Request Body:**
```json
{
    "from_warehouse_id": 1,
    "to_warehouse_id": 2,
    "inventory_item_id": 1,
    "quantity": 5
}
```

**Validation Rules:**
- `from_warehouse_id`: Required, must exist in warehouses table, must be different from `to_warehouse_id`
- `to_warehouse_id`: Required, must exist in warehouses table
- `inventory_item_id`: Required, must exist in inventory_items table
- `quantity`: Required, integer, minimum 1

**Success Response (201 Created):**
```json
{
    "success": true,
    "message": "From Source Quantity after Decrement [10] To Source Quantity after Increment [5]",
    "data": {
        "from_quantity": 10,
        "to_quantity": 5
    }
}
```

**Error Response (422 Unprocessable Entity):**
```json
{
    "success": false,
    "message": "Insufficient stock at source warehouse.",
    "errors": {
        "quantity": ["Insufficient stock at source warehouse."]
    }
}
```

## Response Format

All API responses follow a consistent format:

**Success Response:**
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { /* response data */ }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error description",
    "errors": { /* validation errors if applicable */ }
}
```

## HTTP Status Codes

- `200 OK`: Request successful
- `201 Created`: Resource created successfully
- `401 Unauthorized`: Authentication required
- `422 Unprocessable Entity`: Validation errors
- `500 Internal Server Error`: Server error

## Caching

The API uses caching for improved performance:
- Inventory lists are cached based on filter parameters
- Warehouse data is cached by warehouse ID
- Caches are automatically invalidated when stock transfers occur

## Error Handling

The API provides comprehensive error handling:
- Validation errors return detailed field-specific messages
- Insufficient stock scenarios are properly handled
- Database transactions ensure data integrity during transfers
