package com.apikothon.contact;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;

public class Contact extends Activity {
	Context context;
	ContactData contact;
	EditText inpName;
	EditText inpNumber;
	EditText inpEmail;
	CheckBox inpFavourite;
	boolean isUpdate;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_contact);
		
		context = getApplicationContext();
		Intent intent = getIntent();
		contact = new ContactData();
		try {
			contact = (ContactData) intent.getExtras().getSerializable("contact");
			isUpdate = true;
		} catch (Exception e) {
			e.printStackTrace();
		}
		
		inpName = (EditText) findViewById(R.id.inpName);
		inpNumber = (EditText) findViewById(R.id.inpNumber);
		inpEmail = (EditText) findViewById(R.id.inpEmail);
		inpFavourite = (CheckBox) findViewById(R.id.inpFavourite);
		
		if(contact!=null){
			inpName.setText(contact.getName());
			inpNumber.setText(contact.getNumber());
			inpEmail.setText(contact.getEmail());
			inpFavourite.setChecked(contact.isFavourite());
		}
	}

	public void save(View v){
		contact.setName(inpName.getText().toString());
		contact.setNumber(inpNumber.getText().toString());
		contact.setEmail(inpEmail.getText().toString());
		contact.setFavourite(inpFavourite.isChecked());
		
		ApiConnection api = new ApiConnection();
		String response,message = null;
		int status = 0;
		
		if(isUpdate){
			response = api.updateContact(contact.getId(), contact);
		}else{
			response = api.addContact(contact);
		}
		
		try {
			JSONObject jsonResponse = new JSONObject(response);
			message = jsonResponse.getString("message");
			status = jsonResponse.getInt("status");
		} catch (JSONException e) {
			e.printStackTrace();
		}
		
		Toast.makeText(context, message, Toast.LENGTH_SHORT).show();
		if(status==200){
			finish();
		}
	}


}
