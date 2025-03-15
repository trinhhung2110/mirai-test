# Backend API Project
## Installation

1. Install dependencies
```bash
composer install
```

2. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database
- Edit .env file with your database credentials:

4. Run migrations
```bash
php artisan migrate
```

5. Start the development server
```bash
php artisan serve
```

## API Endpoints

### TEST 1: Account Management
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/accounts` | Get all accounts (paginated) |
| GET | `/api/accounts/{registerId}` | Get specific account details |
| POST | `/api/accounts` | Create new account |
| PUT | `/api/accounts/{registerId}` | Update account information |
| DELETE | `/api/accounts/{registerId}` | Delete an account |

### TEST 2: Serial Paso
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/showSerialpaso` | Show the serialpaso |

### TEST 3: Student Count
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/countStudents` | Count the number of students in each class |

### TEST 4: Top 20 Percent
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/top20Percent` | Get top 20% of students |

#### Example Request
```json
{
    "data": [
        {
            "name": "name1",
            "age": "18"
        }
    ]
}
```

### TEST 5: Find Furthest People
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/findFurthestPeople` | Find furthest people based on positions |

#### Example Request
```json
{
    "data": [
        {
            "name": "name1",
            "position": [-5, 5]
        }
    ]
}
```