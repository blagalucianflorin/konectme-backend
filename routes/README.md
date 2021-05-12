# API Documentation

## USER ROUTES

### GET /api/user/{id}
- Expected input: Nothing

- Possible outputs:  
**SUCCESS**:
```json
{
    "success": true,
    "user": {
        "id": "<user_id>",
        "token": "<bearer_token>"
    }
}
```

**FAILED**:
```json
{
    "success": false,
    "message": "<error_message>"
}
```
error_message values:
* User is not logged in
* Token doesn't exist
* Unauthorized access
* User doesn't exist

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


### PUT /api/user/{id} (Update user)
 - Expected input:
```json
{
    "email": "<new/old_email>",
    "password": "<new/old_password>"
}
```
Note: Even if you only wish to change the password, sending the current email is necessary.

 - Possible outputs:   
**SUCCESS**:
```json
{
    "success": true,
    "message": "User data updated"
}
```

**FAILED**:
```json
{
    "success": false,
    "message": "<error_message>"
}
```
error_message values:
* User doesn't exist
* Unauthorized access
* Token doesn't exist
* User is not logged in

In case email or password is wrong/mising, another possible output is (may vary):
```json
{
    "success": false,
    "validator": {
        "email": {
            "Required": [
                "required"
            ]
        },
        "password": {
            "Required": [
                "required"
            ]
        }
    }
}
```

### DELETE /api/user/{id} (Delete user)
- Expected input: Nothing

- Possible outputs:
**SUCCESS**:   
```json
{
    "success": true,
    "message": "User deleted"
}
```

**FAILED**:
```json
{
    "success": false,
    "message": "<error_message>"
}
```
error_message values:
* User doesn't exist
* Unauthorized access
* Token doesn't exist
* User is not logged in



## MESSAGE ROUTES

### POST /api/message (Create message)

 - Expected input:
```json
{
    "sender_id": <sender_id>,
    "chat_d":  <chat_id>,
    "content": <content>,
    "type" : <type_of_content>
}
```
content :
1. "the content of the message" (e.g : "good morning everyone")
2. <photo_id>  (e.g : 2)

type_of_content : 
1. "text"
2. "photo"

   
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
  "content": "<content>",
  "type" : "<type_of_content"
}
```
content :
1. "the content of the message" (e.g : "good morning everyone")
2. <photo_id>  (e.g : 2)

type_of_content : 
1. "text"
2. "photo"

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
   "type" : "<type_of_content>",
   "content" : "<new content>"
 }
 ```
type_of_content : 
1. "text"
2. "photo"

new content :
1. "the content of the message" (e.g : "good morning everyone")
2. <photo_id>  (e.g : 2)



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
     "content": "<new content>"
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


## FRIENDS ROUTES

### GET /api/friend (See if users are friends)
 - Expected input:
```json
{
    "friend_one": "<friend_one_id>",
    "friend_two": "<friend_two_id>"
}
```
Note: friend_one_id has to be the id of the user sending the request

 - Possible outputs:   
**SUCCESS**:
```json
{
    "success": true,
    "message": "Users are/are not friends"
}
```
Notice that the message indicates whether the users are friends or not, not the success field.

**FAILED**:
```json
{
    "success": false,
    "message": "<error_message>"
}
```
error_message possible values:
* Unauthorized access
* Invalid token
* User does not exist


### POST /api/friend (Make users friends)
- Expected input:
```json
{
    "friend_one": "<friend_one_id>",
    "friend_two": "<friend_two_id>"
}
```
Note: friend_one_id has to be the id of the user sending the request

- Possible outputs:   
**SUCCESS**:   
```json
{
    "success": true,
    "message": "Users are now friends"
}
```
**FAILED**:
```json
{
    "success": false,
    "message": "<error_message>"
}
```
error_message possible values:
* Unauthorized access
* User does not exist
* Invalid token
* Unauthorized access
* Users are already friends


### DELETE /api/friend (Delete friendship relation)
- Expected input:
```json
{
    "friend_one": "<friend_one_id>",
    "friend_two": "<friend_two_id>"
}
```
Note: friend_one_id has to be the id of the user sending the request

- Possible outputs:   
**SUCCESS**:
```json
{
    "success": true,
    "message": "Users aren't friends anymore"
}
```
**FAILED**:
```json
{
    "success": false,
    "message": "<error_message>"
}
```
error_message possible values:
* Users aren't friends
* Invalid token
* User does not exist
* Unauthorized access
