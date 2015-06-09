package com.apikothon.contact;

import java.util.ArrayList;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;


public class ContactAdapter extends ArrayAdapter<ContactData> {
	private Context context;
	private ArrayList<ContactData>contacts;
	private ApiConnection api = new ApiConnection();

	public ContactAdapter(Context context, int resource,ArrayList<ContactData> contacts) {
		super(context, resource, contacts);
		this.context = context;
		this.contacts = contacts;
		api = new ApiConnection();
	}

	@Override
	public View getView(final int position, View convertView, ViewGroup parent) {
		LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		View rowView  = inflater.inflate(R.layout.contact_item, parent, false);
		TextView name = (TextView)rowView.findViewById(R.id.name);
		TextView email = (TextView)rowView.findViewById(R.id.email);
		TextView number = (TextView)rowView.findViewById(R.id.number);
		Button btnFavourite = (Button) rowView.findViewById(R.id.btnFavorite);
		Button btnDelete = (Button) rowView.findViewById(R.id.btnDelete);
		
		name.setText(contacts.get(position).getName());
		number.setText(contacts.get(position).getNumber());
		email.setText(contacts.get(position).getEmail());
		
		if(contacts.get(position).isFavourite()){
			btnFavourite.setBackgroundResource(R.drawable.favourite);
		}else{
			btnFavourite.setBackgroundResource(R.drawable.not_favourite);
		}
		
		btnFavourite.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				String response;
				if(contacts.get(position).isFavourite()){
					response = api.removeFromFavourite(contacts.get(position).getId());
					contacts.get(position).setFavourite(false);
				}
				else{
					response = api.addToFavourite(contacts.get(position).getId());
					contacts.get(position).setFavourite(true);
				}
				String message = ApiConnection.getMessage(response);
				Toast.makeText(context, message, Toast.LENGTH_SHORT).show();
				notifyDataSetChanged();
			}
		});
		
		btnDelete.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				String response = api.deleteContact(contacts.get(position).getId());
				String message = ApiConnection.getMessage(response);
				Toast.makeText(context, message, Toast.LENGTH_SHORT).show();
				contacts.remove(position);
			    notifyDataSetChanged();
			}
		});
		
		return rowView;
	}



}
