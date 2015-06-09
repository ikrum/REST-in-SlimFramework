package com.apikothon.contact;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpDelete;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.methods.HttpPut;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.StrictMode;

public class ApiConnection {

	public static final String RootUrl = "http://ikrum.net/contact_api";

	DefaultHttpClient httpClient;

	public ApiConnection() {
		StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder()
				.permitAll().build();
		StrictMode.setThreadPolicy(policy);
		HttpParams httpParameters = new BasicHttpParams();
		HttpConnectionParams.setConnectionTimeout(httpParameters, 3000);
		HttpConnectionParams.setSoTimeout(httpParameters, 5000);
		httpClient = new DefaultHttpClient(httpParameters);
	}

	public ArrayList<ContactData> getContacts(String params) {
		if (params==null)
			params="";
		String url = RootUrl + "/v1/contacts"+params;
		String response = makeGetRequest(url);
		return parseContact(response);
	}

	public String addContact(ContactData contact) {
		ArrayList<NameValuePair> postParameters = getParams(contact);
		String url = RootUrl + "/v1/contacts";
		String response = makePostRequest(url, postParameters);
		return response;
	}

	public String updateContact(int id, ContactData contact) {
		ArrayList<NameValuePair> putParameters = getParams(contact);
		String url = RootUrl + "/v1/contacts/" + id;
		String response = makePutRequest(url, putParameters);
		return response;
	}

	public String deleteContact(int id) {
		String url = RootUrl + "/v1/contacts/" + id;
		String response = makeDeleteRequest(url);
		return response;
	}

	public String addToFavourite(int id) {
		String url = RootUrl + "/v1/contacts/" + id+"/star";
		String response = makePutRequest(url, null);
		return response;
	}

	public String removeFromFavourite(int id) {
		String url = RootUrl + "/v1/contacts/" + id+"/star";
		String response = makeDeleteRequest(url);
		return response;
	}

	private String makeGetRequest(String url) {
		HttpGet getRequest = new HttpGet(url);
		getRequest.addHeader("accept", "application/json");
		return executeRequest(getRequest);
	}

	private String makePostRequest(String url,
			ArrayList<NameValuePair> postParameters) {

		HttpPost postRequest = new HttpPost(url);
		postRequest.addHeader("accept", "application/json");

		try {
			postRequest.setEntity(new UrlEncodedFormEntity(postParameters));
		} catch (Exception e) {
			e.printStackTrace();
		}

		return executeRequest(postRequest);
	}

	private String makePutRequest(String url,
			ArrayList<NameValuePair> putParameters) {

		HttpPut putRequest = new HttpPut(url);
		putRequest.addHeader("accept", "application/json");

		try {
			putRequest.setEntity(new UrlEncodedFormEntity(putParameters));
		} catch (Exception e) {
			e.printStackTrace();
		}

		return executeRequest(putRequest);
	}

	private String makeDeleteRequest(String url) {

		HttpDelete deleteRequest = new HttpDelete(url);
		deleteRequest.addHeader("accept", "application/json");
		return executeRequest(deleteRequest);
	}

	private String executeRequest(HttpUriRequest request) {

		HttpResponse response = null;
		try {
			response = httpClient.execute(request);
		} catch (IOException e) {
			e.printStackTrace();
		}
		return responseParser(response);
	}

	private String responseParser(HttpResponse response) {

		// if (response == null)
		// return "{\"status\":0,\"message\":\"Connection failed\"}";

		String stringResponse = null;
		try {
			stringResponse = getStringFromInputStream(response.getEntity()
					.getContent());
		} catch (IOException e) {
			e.printStackTrace();
			stringResponse = "{\"status\":0,\"message\":\"Connection failed\"}";
		}
		return stringResponse;
	}

	private String getStringFromInputStream(InputStream is) {

		BufferedReader br = null;
		StringBuilder sb = new StringBuilder();

		String line;
		try {

			br = new BufferedReader(new InputStreamReader(is));
			while ((line = br.readLine()) != null) {
				sb.append(line);
			}

		} catch (IOException e) {
			e.printStackTrace();
		} finally {
			if (br != null) {
				try {
					br.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		}

		return sb.toString();

	}
	private ArrayList<ContactData> parseContact(String response){
		ArrayList<ContactData> list = new ArrayList<ContactData>();

		try {
			JSONObject jsonResponse = new JSONObject(response);
			JSONArray jsonLocationArray = jsonResponse.getJSONArray("contacts");

			for (int i = 0; i < jsonLocationArray.length(); i++) {
				ContactData contact = new ContactData();
				contact.setId(jsonLocationArray.getJSONObject(i).getInt("contact_id"));
				contact.setName(jsonLocationArray.getJSONObject(i).getString(
						"name"));
				contact.setEmail(jsonLocationArray.getJSONObject(i).getString(
						"email"));
				contact.setNumber(jsonLocationArray.getJSONObject(i).getString(
						"number"));
				contact.setFavourite(jsonLocationArray.getJSONObject(i)
						.getBoolean("is_favourite"));
				list.add(contact);
			}
		} catch (JSONException e) {
			e.printStackTrace();
		}
		return list;
	}
	private ArrayList<NameValuePair> getParams(ContactData contact) {
		ArrayList<NameValuePair> param = new ArrayList<NameValuePair>();
		param.add(new BasicNameValuePair("name", contact.getName()));
		param.add(new BasicNameValuePair("email", contact.getEmail()));
		param.add(new BasicNameValuePair("number", contact.getNumber()));
		param.add(new BasicNameValuePair("is_favourite", contact.isFavourite()+""));
		
		return param;
	}
	public static String getMessage(String response){
		String message="";
		try {
			JSONObject jsonResponse = new JSONObject(response);
			message = jsonResponse.getString("message");
		} catch (JSONException e) {
			e.printStackTrace();
		}
		return message;
	}
}