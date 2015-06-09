package com.apikothon.contact;


import java.util.ArrayList;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;
import android.widget.TabHost;
import android.widget.TabHost.TabSpec;

public class MainActivity extends Activity implements OnItemClickListener {
	ListView contactList,favouriteList;
	ArrayList<ContactData> contacts;
	ArrayList<ContactData> favContacts;
	ApiConnection api;
	Context context;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		api = new ApiConnection();
		context = getApplicationContext();
		
		TabHost th = (TabHost) findViewById(R.id.myTab);
		th.setup();
		
		
		TabSpec spec= th.newTabSpec("Contacts");
		spec.setContent(R.id.tab1);
		spec.setIndicator("Contacts");
		th.addTab(spec);
		spec= th.newTabSpec("tag1");
		
		spec= th.newTabSpec("Favourite");
		spec.setContent(R.id.tab2);
		spec.setIndicator("Favourite");
		th.addTab(spec);
		
		
		
		contactList = (ListView) findViewById(R.id.contactList);
		favouriteList = (ListView) findViewById(R.id.favoriteList);
		
		
		contactList.setOnItemClickListener(this);
		favouriteList.setOnItemClickListener(this);
	}
	private void setContactView(){
	
		contacts = api.getContacts(null);
		contactList.setAdapter(new ContactAdapter(context, R.layout.contact_item, contacts));
		
		/*
		 * Redundant api call to show the ...............
		 */
		favContacts = api.getContacts("?type=favourite");
		favouriteList.setAdapter(new ContactAdapter(context, R.layout.contact_item, favContacts));
		
	}
	@Override
	public void onItemClick(AdapterView<?> adapterView, View view, int position, long arg3) {
		Intent intent = new Intent(context,Contact.class);
		ContactData contactData;
		if (adapterView.getId() == R.id.contactList)
			contactData = contacts.get(position);
		else
			contactData = favContacts.get(position);
		
		intent.putExtra("contact", contactData);
		startActivity(intent);
	}
	@Override
	protected void onResume() {
		super.onResume();
		setContactView();
	}
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
	    MenuInflater inflater = getMenuInflater();
	    inflater.inflate(R.menu.main, menu);
	    return super.onCreateOptionsMenu(menu);
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
	    switch (item.getItemId()) {
	        case R.id.add_contact:
	            startActivity(new Intent(context,Contact.class));
	            return true;
	        default:
	            return super.onOptionsItemSelected(item);
	    }
	}



}
