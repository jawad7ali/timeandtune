# Time and tune freight

## Laravel REST API's

### Login

```
[POST] http://54.218.51.77/api/auth/mobile/login
```

#### Request

```
{
	"phone_no" : "03123456789",
	"password" : "123456"
}
```

#### Response

```
{
    "success": "User Login success!",
    "data": {
        "id": 102,
        "name": "gulshan khan",
        "email": "islam123456333333@gmail.com",
        "phone_no": "123456333333",
        "created_at": "24 Feb 2020"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC81NC4yMTguNTEuNzdcL2FwaVwvYXV0aFwvbW9iaWxlXC9sb2dpbiIsImlhdCI6MTU4MjU0MDE1NywiZXhwIjoxNTgyNTQzNzU3LCJuYmYiOjE1ODI1NDAxNTcsImp0aSI6IlRiU0NaTElnbUxJdllpMFAiLCJzdWIiOjEwMiwicHJ2IjoiZWQyMDQ4ZWNkMWI2NzQyNmQxM2UwNTA5ODMwNmYyMzY3NmY2YTNjMyJ9.OVdA_3enLCC-CXwaJXev5EzkKSv4aGvl8gKySlOsMX8"
}
```



### Signup

```
[POST] http://54.218.51.77/api/signup
```

#### Request

```
{
	"name"					:  "gulshan khan",
	"role_id"				:	2,
	"password"				:	"123456",
	"confirmPassword"		:	"123456",
	"company_name"			:	"Tested",
	"role"					:	"shipper",
	"phone_no"				:	"123456333333"
}
```

#### Response

```
{
    "success": "User sign up successfully !",
    "data": {
        "phone": "123456333333",
        "id": 102,
        "loginstatus": 0
    }
}
```

### Available Loads

```
[POST] http://54.218.51.77/api/mobile/load
```

#### Response

```
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "name": "Time and Tune",
                "from": "Karachi",
                "to": "islam",
                "categories": 1,
                "price": "2345",
                "pickup_date": "2020-01-22",
                "pickup_time": "12:00",
                "distance": "2345Km",
                "model": "To Pay",
                "loadtype": "Full container",
                "weight": "342",
                "length": "342",
                "width": "342",
                "user_id": 63,
                "role_id": 3,
                "status": "Active",
                "created_at": "2020-01-29 00:00:00",
                "updated_at": "2020-01-22 00:00:00",
                "pickuplocation": "H-9",
                "droplocation": "Monday"
            }
        ]
    },
    "error": ""
}
```

### Pending Orders

```
[POST] http://54.218.51.77/api/order/pending
```

#### Request

```
{
	"user_id" : 77
}
```

#### Response

```
{
    "success": "User sign up successfully !",
    "data": [
        {
            "BID": 4,
            "UserID": 77,
            "id": 2,
            "load_id": 2,
            "user_id": 63,
            "shipper_offer": "2345",
            "mybidoffer": "322",
            "commission": "234.5",
            "total": "32323232",
            "created_at": "2020-01-29 00:00:00",
            "updated_at": "2020-01-22 00:00:00",
            "status": "Active",
            "name": "PakStock",
            "from": "lahore",
            "to": "pindi",
            "categories": 2,
            "price": "2345",
            "pickup_date": "2020-01-22",
            "pickup_time": "12:00",
            "distance": "2345Km",
            "model": "Advance",
            "loadtype": "Full container",
            "weight": "342",
            "length": "342",
            "width": "342",
            "role_id": 3,
            "pickuplocation": "H-9",
            "droplocation": "Monday"
        }
    ]
}
```



### Active Orders

```
[POST] http://54.218.51.77/api/order/pending
```

#### Request

```
{
	"shipper_offer" : "2345", 
	"status"        : "pending",
	"total"			:  "32323232",
	"load_id"		:   "1", 
	"user_id"		:	62,
	"commission"	:	234.5
}
```

#### Response

```
{
    "success": "User sign up successfully !",
    "data": [
        {
            "BID": 3,
            "UserID": 62,
            "id": 3,
            "load_id": 3,
            "user_id": 63,
            "shipper_offer": "2345",
            "mybidoffer": "2345",
            "commission": "234.5",
            "total": "32323232",
            "created_at": "2020-01-29 00:00:00",
            "updated_at": "2020-01-22 00:00:00",
            "status": "Active",
            "name": "Mimar",
            "from": "kohat",
            "to": "peshawar",
            "categories": 2,
            "price": "2345",
            "pickup_date": "2020-01-22",
            "pickup_time": "12:00",
            "distance": "2345Km",
            "model": "Advance",
            "loadtype": "Full container",
            "weight": "342",
            "length": "342",
            "width": "342",
            "role_id": 3,
            "pickuplocation": "H-9",
            "droplocation": "Monday"
        }
    ]
}
```



### Transit Orders

```
[POST] http://54.218.51.77/api/order/transit
```

#### Request

```
{
	"user_id" : "77" 
}
```

#### Response

```
{
    "success": "My order !",
    "data": [
        {
            "BID": 6,
            "id": 1,
            "load_id": 1,
            "user_id": 63,
            "shipper_offer": "2345",
            "mybidoffer": "2345",
            "commission": "234.5",
            "total": "32323232",
            "created_at": "2020-01-29 00:00:00",
            "updated_at": "2020-01-22 00:00:00",
            "status": "Active",
            "name": "Time and Tune",
            "from": "Karachi",
            "to": "islam",
            "categories": 1,
            "price": "2345",
            "pickup_date": "2020-01-22",
            "pickup_time": "12:00",
            "distance": "2345Km",
            "model": "To Pay",
            "loadtype": "Full container",
            "weight": "342",
            "length": "342",
            "width": "342",
            "role_id": 3,
            "pickuplocation": "H-9",
            "droplocation": "Monday"
        }
    ]
}
```


### Transit Orders

```
[POST] http://54.218.51.77/api/order/accept
```

#### Request

```
{
	"user_id" : "2345", 
	"id"        : ""
}
```

#### Response

```
{
    "success": "Accept Order!"
}
```


### Edit Bid

```
[POST] http://54.218.51.77/api/order/edit
```

#### Request

```
{
	"id" : 17 ,
	"mybidoffer" : 300,
	"user_id" : 3

}
```

#### Response

```
{
    "error": "Already confirm"
}
```

### Cancel Bid

```
[POST] http://54.218.51.77/api/order/cancel
```

#### Request

```
{
	"id" : 17 ,
	"user_id" : 3

}
```

#### Response

```
{
    "success": "Cancel successfully"
}
```

### Pofile settings

```
[POST] http://54.218.51.77/api/user/profile
```

#### Request

```
{ 
	"user_id" : 88

}
```

#### Response

```
{
    "success": "Not active!",
    "data": [
        {
            "id": 1,
            "name": "gulshan islam2",
            "email": "islam03354848643@gmail.com",
            "email_verified_at": null,
            "password": "$2y$10$NiLLMo/ISbqMeCfsUNwG1eFl5PxxRZ.wobdrWzh1pU5Dnsp3SI3gu",
            "remember_token": null,
            "created_at": "2020-02-13 12:27:12",
            "updated_at": "2020-02-13 12:42:04",
            "role_id": 1,
            "truct_number": null,
            "company_name": null,
            "phone_no": "03354848643",
            "status": 1,
            "latitude": "12345674",
            "longitude": "12345674",
            "online": 0,
            "address": "dsadsa",
            "truck_photo_no_plate": "1581597724Image 1.png",
            "truck_type": "dsadsa",
            "NIC_front": "1581597724Image 1.png",
            "NIC_back": "1581597724Image 1.png",
            "licence": "1581597724Image 1.png",
            "photo": "1581597724Image 1.png",
            "user_id": 80
        }
    ]
}
```
### Carriers

```
[GET] http://54.218.51.77/api/carriers
```

## Getting started
 

### Installing
```bash
# Clone the project and run composer
composer install

# Migration and DB seeder (after changing your DB settings in .env)
php artisan migrate --seed

# Generate JWT secret key
php artisan jwt:secret

# Install dependency - we recommend using Yarn instead of NPM since we get errors while using NPM
yarn install

# develop
yarn run dev # or yarn run watch

# Build on production
yarn run production
```