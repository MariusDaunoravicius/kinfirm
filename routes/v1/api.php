<?php

declare(strict_types=1);

Route::prefix('products')->as('products:')->group(
    base_path(path: 'routes/v1/products.php'),
);
