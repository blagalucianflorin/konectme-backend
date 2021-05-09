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


## MESSAGE ROUTES

### POST /api/message (Create message)

 - Expected input:
```json
{
    "sender_id": <sender_id>,
    "chat_d":  <chat_id>,
    "content":   "<content>"
}
```

 - Possible outputs:

**SUCCESS**:
```json
{
  "success": true,
  "message": "None"
}
```

**FAILED** :
```json
{
    "success": false,
    "message": "This user is not in chat"
}
```

### GET /api/message/{id} (Show message)

 - Expected input: Nothing


 - Possible outputs:  

**SUCCESS**:
```json
{
  "sender_id": <sender_id>,
  "chat_id": <chat_id>,
  "sent_at": "<date>",
  "content": "<content>"
  
}
```

**FAILED**:
```json
{
    "success": false,
    "message" : "This message does not exist."
}
```

### PUT /api/message/{id} (Edit message)

 - Expected input: 
 ```json
 {
     "content" : "<new content>"
 }
 ```

- Possible outputs:  

**SUCCESS**:
```json
{
  "success": true,
  "chat_id": "Message was edited"
}
```

**FAILED**:
```json
{
    "success": false,
    "message" : "<Error_message>"
}
```
Error_message:
1. This message does not exist.
2. User is not authenticated

### DELETE /api/message/{id} (Delete message)

 - Expected input: Nothing
 

- Possible outputs:  

**SUCCESS**:
```json
{
  "success": true,
  "chat_id": "Message deleted"
}
```

**FAILED**:
```json
{
    "success": false,
    "message" : "<Error_message"
}
```
Error_message:

1. This message does not exist

2. User is not authenticated




## CHAT ROUTES

### POST /api/chat (Create chat)

 - Expected input:
```json
{
    "users": "[user_id list]",  
    "name" :  "<name>"
}
Example user_id list : [1,2,3]
```

 - Possible outputs:

**SUCCESS**:
```json
{
  "success": true,
  "message": "Chat was created"
}
```

**FAILED** :
```json
{
    "success": false,
    "message": "User does not exist"
}
```


### GET /api/chat/{id} (Show chat)

 - Expected input: Nothing


 - Possible outputs:  

**SUCCESS**:
```json
{
  "users": "[user_id list]",
  "name": "<name>",
}
```
Example user_id list : [1,2,3]

**FAILED**:
```json
{
    "success": false,
    "message" : "This chat does not exist."
}
```

### PUT /api/chat/{id} (Edit chat)

 - Expected input: 
 ```json
 {
     "content" : "<new content>"
 }
 ```

- Possible outputs:  

**SUCCESS**:
```json
{
  "success": true,
  "chat_id": "Chat was edited"
}
```

**FAILED**:

```json
{
    "success": false,
    "message" : "<Error_message>"
}

```
Error_message:
1. Token does not exist
2. User does not exist
