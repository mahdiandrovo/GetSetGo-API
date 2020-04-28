# GetSetGo API

This **REST API** is only created for [GetSetGo Application](https://github.com/mahdiandrovo/GetSetGo). **MySQL Database** used for GetSetGo app. To maintain **HTTP** calls I have used this API. I have used [Slim Framework](http://www.slimframework.com/) to make this **REST API**.
[GetSetGo](https://github.com/mahdiandrovo/GetSetGo) is a demo app. That’s why I needed only 3 calls(2 POST, 1 GET). POST call are “**createuser**” and “**userlogin**”. GET call is “**places**”.

#### Database Format:
I have used **MySQL Database**. For now, there are two tables. First one is “**users**”, where user information stored. Now it is having 4 columns (id, name, email, password). Second one is “**places**”, where place information stored. Now that table is having 6 columns (id, name, location, description, latitude, longitude). All data are fetching from this database.

#### Calls:
**createuser:** This is a **POST** call. It is created to create a user in database. Three conditions have been checked.
- **USER_CREATED:** It is a confirmation call. That means if sent data is not in the database and no other errors occurred, this condition will be applied.
- **USER_FAILURE:** If any error occurred, this condition will be applied.
- **USER_EXISTS:** If sent email address is already in the database this condition will be applied.

**userlogin:** This is a **POST** call. It is created for Login activity. Three conditions have been checked.
- **USER_AUTHENTICATED:** If send information is matched with one row this condition will be applied.
- **USER_NOT_FOUND:** If the email address is not stored in the database this condition will be applied.
- **USER_PASSWORD_DO_NOT_MATCH:** If inputted password will not match this condition will be applied.

**places:** This is a **GET** call. It is used to send place informations.
