# REST-in-SlimFramework
This a very api to understand REST operations as well as how to implement a multiplatform application via api. A simple android applicatoin is used to demonstate the simplicity of REST.

## App Screenshot
![ScreenShot](https://raw.githubusercontent.com/ikrum/REST-in-SlimFramework/master/android-client/screenshot.png)

## Installing API
Just upload the api folder on any web server or localhost. If you want to configure API with app then you must place the api on web server.
You must have MongoDB installed on your server or pc.

## Compiling app
The app need to be compiled on Android Studio. Before compiling please change the RootUrl from ApiConnection.java


##API USAGE
Here is list of Resouces and action used at the api.

<table>
	<thead>
		<tr>
			<th>Resource</th>
			<th>HTTP Method</th>
			<th>Operation</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>/api/v1/contacts</th>
			<td>GET</td>
			<td>Returns an array of contacts</td>
		</tr>
		<tr>
			<td>/api/v1/contacts</th>
			<td>POST</td>
			<td>Adds a new contact</td>
		</tr>
		<tr>
			<td>/api/v1/contacts?type=star</th>
			<td>GET</td>
			<td>Returns an array of favourite contacts</td>
		</tr>
		<tr>
			<td>/api/v1/contacts/:id</th>
			<td>GET</td>
			<td>Returns the contact with id of :id</td>
		</tr>
		<tr>
			<td>/api/v1/contacts/:id</th>
			<td>PUT</td>
			<td>Update the contact with id of :id</td>
		</tr>
		<tr>
			<td>/api/v1/contacts/:id</th>
			<td>DELETE</td>
			<td>Deletes the contact with id of :id</td>
		</tr>
		<tr>
			<td>/api/v1/contacts/:id/star</th>
			<td>PUT</td>
			<td>Adds to favourites  the contact with id of :id</td>
		</tr>
		<tr>
			<td>/api/v1/contacts/:id/star</th>
			<td>DELETE</td>
			<td>Removes from favourites  the contact with id of :id</td>
		</tr>
	</tbody>
</table>

## API Usage
you can also check the operation from Terminal or using POSTMAN

## GET all contacts

#### Request
`GET /api/v1/contacts`
<br/>
`curl -i -H 'Accept: application/json' http://localhost/api/v1/contacts`

#### Response
```
HTTP/1.1 200 OK
Date: Thu, 24 Feb 2011 12:36:30 GMT
Status: 200 OK
Connection: close
Content-Type: application/json
Content-Length: 99
{"status":200,"contacts":[{"contact_id":10,"name":"myname","number":"012345","is_favourite":true}]}
```
