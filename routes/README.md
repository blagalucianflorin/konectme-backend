# API Documentation

## User routes

### POST /api/user (Create user)

 - Expected input:
```json
{
    "first_name": "<first_name>",
    "last_name":  "<last_name>",
    "username":   "<username>",
    "email":      "<email>",
    "password":   "<password_in_plain_text>"
}
```

 - Possible outputs:  
**SUCCESS**:
```json
{
  "success": true,
  "user": {
    "id": 1,
    "token": "<bearer_token>"
  }
}
```

**FAILED** (example*):
```json
{
    "success": false,
    "validator": {
        "username": {
            "Unique": [
                "users"
            ]
        },
        "email": {
            "Unique": [
                "users"
            ]
        }
    }
}
```
*Constraints may vary.


### PUT /api/user (Update user)
