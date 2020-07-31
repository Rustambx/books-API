**1. Авторизация пользователя и получение токена**<br>
**Url:** api.loc/api/signin
<br>**Request-type:** POST
<br>**Body:**
```json  
{
    "email" : "email пользователя",
    "password" : "password пользователя"
}
```
**Response-type:** json
<br>**Response:**
```json 
{
    "acces_token" : "auth_token",
    "token_type" : "bearer",
    "expires_in" : 3600
}
```
**2. Регистрация пользователя**<br>
**Url:** api.loc/api/signup
<br>**Request-type:** POST
<br>**Body:**
```json  
{
    "name" : "Имя пользователя",
    "email" : "email пользователя",
    "password" : "password пользователя",
    "photo" : "Фото пользователя"
}
```
<br>**Response-type:** json
<br>**Response:** 
```json 
{
    "success" : "Пользователь успешно зарегистрирован"
}
```
**3. Профиль пользователя**<br>
**Url:** api.loc/api/user/profile
<br>**Request-type:** GET
<br>**Header:** authorization = bearer auth_token
<br>**Response-type:** json
<br>**Response:** 
```json 
{
    "Информация о пользователе"
}
```
**4. Избранные книги пользователя**<br>
**id :** id пользователя
<br>**Url:** api.loc/api/user/library/{id}
<br>**Request-type:** GET
<br>**Header:** authorization = bearer auth_token
<br>**Response-type:** json
<br>**Response:**
```json  
{
    "books" : "Избранные книги пользователя"
}
```
**5. Редактирования данные пользователя**<br>
**id** : id пользователя
<br>**Url:** api.loc/api/user/edit/{id}
<br>**Request-type:** POST
<br>**Body:** 
```json
{
    "name" : "Имя пользователя",
    "email" : "email пользователя",
    "password" : "password пользователя",
    "photo" : "Фото пользователя"
}
```
<br>**Header:** authorization = bearer auth_token
<br>**Response-type:** json
<br>**Response:** 
```json 
{	
    "success" : "Пользователь успешно обновлен"
}
```

**6. Отравление токена на email пользователя**<br>
**Url:** api.loc/api/password/email
<br>**Description:** Отправляеть на email пользователя код токена
<br>**Request-type:** POST
<br>**Body:** 
```json
{
    "email" : "email пользователя"
}
```
<br>**Header:** authorization = bearer auth_token
<br>**Response-type:** json
<br>**Response:** 
```json
{
    "message": "passwords.sent"
}
```
**7. Восстановление пароля**<br>
**Url:** api.loc/api/password/reset
<br>**Description:** Принимает код токена
<br>**Request-type:** POST
<br>**Body:** 
```json
{
    "token" : "Код токена",
    "email" : "email пользователя",
    "password" : "Новый пароль",
    "pasword_confirmation" : "Подтверждение нового пароля"
}
```
<br>**Header**: authorization = bearer auth_token
<br>**Response-type:** json
<br>**Response:** 
```json
{
    "message": "passwords.reset"
}
```
**8. Возвращает самые последние 5 книг**<br>
**Url:** api.loc/api/books/latest
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** GET
<br>**Response-type:** json
<br>**Response:** 
```json
{
    "books" : "Возвращает последние 5 книг"
}
```
**9. Возвращает все жанры**<br>
<br>**Url:** api.loc/api/books/categories
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** GET
<br>**Response-type:** json
<br>**Response:** 
```json
{
    "genres" : "Возвращает родительские жанры",
    "subGenres" : "Возвращает поджанры жанры",
    "all" : "Возвращает все жанры"
}
```
**10. Возвращает рекомендованные книги**<br>
**Url:** api.loc/api/books/featured
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** GET
<br>**Response-type:** json
<br>**Response:**
```json
{
    "books" : "Возвращает рекомендуемые книги по жанрам"
}
``` 
**11. Возвращает комментарии книги**<br>
**id:** id Книги
<br>**Url:** api.loc/api/book/comments/{id}
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** GET
<br>**Response-type:** json
<br>**Response:**
```json
{
    "comments" : "Возвращает комментарии книги"
}
``` 
**12. Возвращает глави книги**<br>
**id:** id Книги
<br>**Url:** api.loc/api/book/chapters/{id}
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** GET
<br>**Response-type:** json
<br>**Response:** 
```json
{
    "comments" : "Возвращает глави книги"
}
```
1**3. Добавление комментарии**<br>
**Url:** api.loc/api/book/comment
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** POST
<br>**Body:**
```json
{
    "description" : "Текст комментарии",
    "user_id" : "id пользователя",
    "book_id" : "id книги"
}
``` 
<br>**Response-type:** json
<br>**Response:**
```json
{
    "success" : "Комментария успешно добавлена"
}
``` 
**14. Добавление like в книги**<br>
**Url:** api.loc/api/book/comment/like
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** POST
<br>**Body:** 
```json
{
    "comment_id" : "id комментарии",
    "user_id" : "id пользователя"
}
```
<br>**Response-type:** json
<br>**Response:** 
```json
{
    "success" : "Comment liked"
}
```
**15. Поиск книг по названием**<br>
**Url:** api.loc/api/search
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** POST
<br>**Body:** 
```json
{
    "name" : "Название книги"
}
```
**Response-type:** json
<br>**Response:** 
```json
{
    "books" : "Результаты"
}
```
**16. Возвращает условии и положении**<br>
**Url:** api.loc/api/conditions
<br>**Header:** authorization = bearer auth_token
<br>**Request-type:** GET
<br>**Response-type:** json
<br>**Response:** 
```json
{
    "conditions" : "Возвращает условии и положении"
}
```

