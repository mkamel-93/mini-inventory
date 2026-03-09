<?php

declare(strict_types=1);

return [
    'warehouses' => [
        ['id' => 1, 'name' => 'North Central Hub', 'location' => 'Chicago, IL'],
        ['id' => 2, 'name' => 'West Coast Fulfillment', 'location' => 'Seattle, WA'],
        ['id' => 3, 'name' => 'East Coast Logistics', 'location' => 'Newark, NJ'],
    ],

    'inventory_items' => [
        // Computing
        ['id' => 101, 'name' => 'Ultra-Slim Laptop 14', 'sku' => 'LAP-US14-SLV', 'price' => 899.99, 'description' => '16GB RAM, 512GB SSD'],
        ['id' => 102, 'name' => '27-inch 4K Monitor', 'sku' => 'DIS-4K27-IPS', 'price' => 349.99, 'description' => 'IPS panel, 60Hz'],
        ['id' => 103, 'name' => 'External SSD 1TB', 'sku' => 'STR-SSD1-USB', 'price' => 89.00, 'description' => 'USB-C 3.2 Gen 2'],
        // Audio
        ['id' => 104, 'name' => 'Noise Cancelling Headphones', 'sku' => 'AUD-NC700-BLK', 'price' => 299.00, 'description' => 'Wireless over-ear'],
        ['id' => 105, 'name' => 'Studio Microphone', 'sku' => 'AUD-MIC-XLR', 'price' => 199.50, 'description' => 'Cardioid Condenser'],
        // Peripherals
        ['id' => 106, 'name' => 'Mechanical Keyboard', 'sku' => 'INP-MECH-RGB', 'price' => 129.50, 'description' => 'Brown switches, RGB'],
        ['id' => 107, 'name' => 'Wireless Ergonomic Mouse', 'sku' => 'INP-ERGO-WRLS', 'price' => 79.99, 'description' => 'Multi-device pairing'],
        ['id' => 108, 'name' => 'HD Webcam 1080p', 'sku' => 'VID-CAM-HD', 'price' => 65.00, 'description' => 'Built-in privacy shutter'],
        // Office Furniture
        ['id' => 109, 'name' => 'Ergonomic Desk Chair', 'sku' => 'OFF-ERG-WHT', 'price' => 450.00, 'description' => 'Breathable mesh'],
        ['id' => 110, 'name' => 'Standing Desk Frame', 'sku' => 'OFF-STD-DSK', 'price' => 320.00, 'description' => 'Dual motor, adjustable'],
        // Networking
        ['id' => 111, 'name' => 'Wi-Fi 6 Router', 'sku' => 'NET-WF6-AX', 'price' => 159.99, 'description' => 'Tri-band, 5400 Mbps'],
        ['id' => 112, 'name' => '8-Port Gigabit Switch', 'sku' => 'NET-SW8-GB', 'price' => 45.00, 'description' => 'Unmanaged, metal housing'],
        // Accessories
        ['id' => 113, 'name' => 'Laptop Stand', 'sku' => 'ACC-LAP-STND', 'price' => 35.00, 'description' => 'Foldable aluminum'],
        ['id' => 114, 'name' => 'USB-C Docking Station', 'sku' => 'ACC-DOCK-10IN1', 'price' => 115.00, 'description' => 'Dual HDMI, Ethernet'],
        ['id' => 115, 'name' => 'Cable Management Kit', 'sku' => 'ACC-CBL-MGMT', 'price' => 19.99, 'description' => '50-piece organizer set'],
    ],

    'stock' => [
        ['id' => 1, 'warehouse_id' => 1, 'inventory_item_id' => 101, 'quantity' => 120],
        //        ['id' => 2, 'warehouse_id' => 2, 'inventory_item_id' => 101, 'quantity' => 45],
        //        ['id' => 3, 'warehouse_id' => 1, 'inventory_item_id' => 104, 'quantity' => 200],
        //        ['id' => 4, 'warehouse_id' => 3, 'inventory_item_id' => 104, 'quantity' => 85],
        //        ['id' => 5, 'warehouse_id' => 2, 'inventory_item_id' => 109, 'quantity' => 12],
        //        ['id' => 6, 'warehouse_id' => 1, 'inventory_item_id' => 106, 'quantity' => 55],
        //        ['id' => 7, 'warehouse_id' => 3, 'inventory_item_id' => 111, 'quantity' => 30],
        //        ['id' => 8, 'warehouse_id' => 2, 'inventory_item_id' => 114, 'quantity' => 22],
        //        ['id' => 9, 'warehouse_id' => 1, 'inventory_item_id' => 102, 'quantity' => 15],
        //        ['id' => 10, 'warehouse_id' => 3, 'inventory_item_id' => 103, 'quantity' => 100],
        //        ['id' => 11, 'warehouse_id' => 2, 'inventory_item_id' => 115, 'quantity' => 500],
        //        ['id' => 12, 'warehouse_id' => 1, 'inventory_item_id' => 112, 'quantity' => 40],
    ],

    'stock_transfers' => [
        ['id' => 801, 'from_warehouse_id' => 1, 'to_warehouse_id' => 2, 'inventory_item_id' => 101, 'quantity' => 20],
        ['id' => 802, 'from_warehouse_id' => 1, 'to_warehouse_id' => 3, 'inventory_item_id' => 104, 'quantity' => 50],
        ['id' => 803, 'from_warehouse_id' => 3, 'to_warehouse_id' => 2, 'inventory_item_id' => 111, 'quantity' => 5],
        ['id' => 804, 'from_warehouse_id' => 2, 'to_warehouse_id' => 1, 'inventory_item_id' => 109, 'quantity' => 2],
    ],
];
