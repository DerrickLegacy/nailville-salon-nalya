# API Documentation

This document describes the internal API endpoints used by the Nailville Salon Management System.

## Base URL

```
http://localhost:8000/api
```

## Authentication

All API requests require authentication using Laravel Sanctum tokens.

### Get Token

```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  }
}
```

### Use Token

Include token in headers:
```http
Authorization: Bearer 1|abc123...
```

## Endpoints

### Transactions

#### Get Transactions (DataTables)

```http
POST /transactions/get-records
Content-Type: application/json
X-Requested-With: XMLHttpRequest

{
  "draw": 1,
  "start": 0,
  "length": 10,
  "transaction_type": "Income",
  "searchTerm": "",
  "cashTypeFilter": "",
  "fromDate": "2024-01-01",
  "toDate": "2024-12-31",
  "order": [
    {
      "column": 0,
      "dir": "desc"
    }
  ]
}
```

**Response:**
```json
{
  "draw": 1,
  "recordsTotal": 100,
  "recordsFiltered": 50,
  "data": [
    {
      "id": 1,
      "transaction_id": "TXN123456",
      "employee_id": 1,
      "customer_name": "John Doe",
      "amount": "50000.00",
      "transaction_type": "Income",
      "payment_method": "Cash",
      "service_description": "HairCut",
      "date": "2024-01-15T10:30:00",
      "employee": {
        "employee_id": 1,
        "first_name": "Jane",
        "last_name": "Smith"
      }
    }
  ],
  "totalAmountAllPages": 2500000,
  "totalIncome": 5000000,
  "totalExpense": 2500000
}
```

#### Create Transaction

```http
POST /transactions/store
Content-Type: application/x-www-form-urlencoded

employee_id=1
customer_name=John Doe
amount=50000
transaction_type=Income
payment_method=Cash
service_offered=HairCut
date=2024-01-15
notes=Regular customer
```

**Response:**
```
Redirect to /transactions/income with success message
```

#### Get Transaction Details

```http
GET /transactions/{id}
```

**Response:**
```html
Transaction details page
```

#### Update Transaction

```http
PUT /transactions/{id}
Content-Type: application/x-www-form-urlencoded

employee_id=1
customer_name=John Doe Updated
amount=75000
transaction_type=Income
payment_method=MobileMoney
service_offered=HairStyling
date=2024-01-15
```

**Response:**
```
Redirect to /transactions/income with success message
```

#### Delete Transaction

```http
DELETE /transactions/{id}
```

**Response:**
```
Redirect back with success message
```

### Employees

#### List Employees

```http
GET /employees
```

**Response:**
```json
[
  {
    "id": 1,
    "employee_id": 1001,
    "first_name": "Jane",
    "last_name": "Smith",
    "email": "jane@example.com",
    "phone_number": "+256700000000",
    "job_title": "Senior Stylist",
    "hire_date": "2023-01-15",
    "work_status": "Active"
  }
]
```

### Reports

#### Income vs Expense Report

```http
GET /reports/income-expense?from=2024-01-01&to=2024-12-31
```

**Response:**
```html
Report page with charts
```

#### Net Income Report

```http
GET /reports/net-income?from=2024-01-01&to=2024-12-31
```

**Response:**
```html
Net income report page
```

## Data Models

### Transaction

```json
{
  "id": 1,
  "transaction_id": "TXN123456",
  "employee_id": 1,
  "recorded_by": 1,
  "receipt_id": "RCP-1234",
  "customer_name": "John Doe",
  "amount": "50000.00",
  "transaction_type": "Income|Expense",
  "payment_method": "Cash|MobileMoney|Card|Bank|Other",
  "service_description": "HairCut",
  "notes": "Regular customer",
  "date": "2024-01-15",
  "created_at": "2024-01-15T10:30:00",
  "updated_at": "2024-01-15T10:30:00"
}
```

### Employee

```json
{
  "employee_id": 1001,
  "first_name": "Jane",
  "last_name": "Smith",
  "email": "jane@example.com",
  "phone_number": "+256700000000",
  "job_title": "Senior Stylist",
  "department": "Hair Services",
  "hire_date": "2023-01-15",
  "work_status": "Active",
  "salary": "500000.00"
}
```

## Error Responses

### Validation Error (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "amount": [
      "The amount field is required."
    ],
    "employee_id": [
      "The selected employee id is invalid."
    ]
  }
}
```

### Unauthorized (401)

```json
{
  "message": "Unauthenticated."
}
```

### Not Found (404)

```json
{
  "message": "Resource not found."
}
```

### Server Error (500)

```json
{
  "message": "Server Error",
  "exception": "Exception message (only in debug mode)"
}
```

## Rate Limiting

API endpoints are rate-limited to:
- **60 requests per minute** for authenticated users
- **10 requests per minute** for guests

Rate limit headers:
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640000000
```

## Pagination

List endpoints support pagination:

```http
GET /api/transactions?page=2&per_page=20
```

**Response:**
```json
{
  "data": [...],
  "current_page": 2,
  "per_page": 20,
  "total": 100,
  "last_page": 5,
  "from": 21,
  "to": 40
}
```

## Filtering

### Date Range

```http
GET /api/transactions?from=2024-01-01&to=2024-12-31
```

### Transaction Type

```http
GET /api/transactions?type=Income
```

### Payment Method

```http
GET /api/transactions?payment_method=Cash
```

### Search

```http
GET /api/transactions?search=John
```

### Combined Filters

```http
GET /api/transactions?type=Income&payment_method=Cash&from=2024-01-01&search=John
```

## Sorting

```http
GET /api/transactions?sort_by=date&sort_order=desc
```

Available sort fields:
- `date`
- `amount`
- `customer_name`
- `created_at`

## Export

### PDF Export

```http
GET /transactions/export/pdf?type=Income&from=2024-01-01&to=2024-12-31
```

**Response:**
```
Content-Type: application/pdf
Content-Disposition: attachment; filename="transactions-2024-01-01-to-2024-12-31.pdf"
```

## Webhooks (Future)

Webhook endpoints for external integrations (planned feature):

```http
POST /webhooks/transaction-created
POST /webhooks/transaction-updated
POST /webhooks/transaction-deleted
```

## SDK Examples

### JavaScript/Axios

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

// Get transactions
const getTransactions = async () => {
  const response = await api.post('/transactions/get-records', {
    draw: 1,
    start: 0,
    length: 10,
    transaction_type: 'Income'
  });
  return response.data;
};

// Create transaction
const createTransaction = async (data) => {
  const response = await api.post('/transactions/store', data);
  return response.data;
};
```

### PHP/Guzzle

```php
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://localhost:8000',
    'headers' => [
        'Authorization' => 'Bearer YOUR_TOKEN',
        'Accept' => 'application/json',
    ]
]);

// Get transactions
$response = $client->post('/transactions/get-records', [
    'json' => [
        'draw' => 1,
        'start' => 0,
        'length' => 10,
        'transaction_type' => 'Income'
    ]
]);

$data = json_decode($response->getBody(), true);
```

## Testing

### Using cURL

```bash
# Get transactions
curl -X POST http://localhost:8000/transactions/get-records \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d '{
    "draw": 1,
    "start": 0,
    "length": 10,
    "transaction_type": "Income"
  }'

# Create transaction
curl -X POST http://localhost:8000/transactions/store \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "employee_id=1&amount=50000&transaction_type=Income&payment_method=Cash&date=2024-01-15"
```

## Changelog

### Version 1.0.0
- Initial API release
- Transaction CRUD endpoints
- DataTables integration
- Basic filtering and sorting

---

**Need Help?** Contact api-support@nailvillesalon.com
