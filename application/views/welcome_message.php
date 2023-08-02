<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management API Documentation</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Product Management API Documentation</h1>

        <!-- Add Product -->
        <h2 class="mt-5">Add Product</h2>
        <p>Add a new product to the system.</p>
        <h5>Endpoint:</h5>
        <p><code>POST /api/add-product</code></p>
        <h5>Request Payload:</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>name</td>
                    <td>string</td>
                    <td>The name of the product.</td>
                </tr>
                <tr>
                    <td>description</td>
                    <td>string</td>
                    <td>The description of the product.</td>
                </tr>
                <tr>
                    <td>image</td>
                    <td>string</td>
                    <td>URL of the product image.</td>
                </tr>
                <tr>
                    <td>category</td>
                    <td>string</td>
                    <td>The category of the product.</td>
                </tr>
                <!-- Add more fields as needed -->
            </tbody>
        </table>
        <h5>Response:</h5>
        <pre>
{
  "status": "success",
  "message": "Product added successfully",
  "data": {
    "product_id": 123,
    "name": "Product Name",
    "description": "Product Description",
    "image": "http://example.com/images/product.jpg",
    "category": "Category Name"
  }
}
        </pre>

        <!-- List All Products -->
        <h2 class="mt-5">List All Products</h2>
        <p>Retrieve a list of all products in the system.</p>
        <h5>Endpoint:</h5>
        <p><code>GET /api/list-all-product</code></p>
        <h5>Response:</h5>
        <pre>
{
  "status": "success",
  "data": [
    {
      "product_id": 123,
      "name": "Product Name",
      "description": "Product Description",
      "image": "http://example.com/images/product.jpg",
      "category": "Category Name"
    },
    {
      "product_id": 456,
      "name": "Another Product",
      "description": "Another Product Description",
      "image": "http://example.com/images/another-product.jpg",
      "category": "Other Category"
    },
    ...
  ]
}
        </pre>

        <!-- List Products with Pagination -->
        <h2 class="mt-5">List Products with Pagination</h2>
        <p>Retrieve a paginated list of products.</p>
        <h5>Endpoint:</h5>
        <p><code>GET /api/products?page={page_no}</code></p>
        <h5>Parameters:</h5>
        <p><code>page_no</code> (optional): The page number for pagination. Defaults to 1.</p>
        <h5>Response:</h5>
        <pre>
{
  "status": "success",
  "data": [
    {
      "product_id": 123,
      "name": "Product Name",
      "description": "Product Description",
      "image": "http://example.com/images/product.jpg",
      "category": "Category Name"
    },
    {
      "product_id": 456,
      "name": "Another Product",
      "description": "Another Product Description",
      "image": "http://example.com/images/another-product.jpg",
      "category": "Other Category"
    },
    ...
  ],
  "pagination": {
    "total_pages": 3,
    "current_page": 2,
    "per_page": 10
  }
}
        </pre>

        <!-- Search Products -->
        <h2 class="mt-5">Search Products</h2>
        <p>Search products by name or description.</p>
        <h5>Endpoint:</h5>
        <p><code>GET /api/products/search?q={search_query}</code></p>
        <h5>Parameters:</h5>
        <p><code>q</code> (required): The search query for products.</p>
        <h5>Response:</h5>
        <pre>
{
  "status": "success",
  "data": [
    {
      "product_id": 123,
      "name": "Product Name",
      "description": "Product Description",
      "image": "http://example.com/images/product.jpg",
      "category": "Category Name"
    },
    {
      "product_id": 456,
      "name": "Another Product",
      "description": "Another Product Description",
      "image": "http://example.com/images/another-product.jpg",
      "category": "Other Category"
    },
    ...
  ]
}
        </pre>
    </div>

    <!-- Bootstrap 5 JS and Popper.js (for dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
