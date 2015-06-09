package com.apikothon.contact;

import java.io.Serializable;

public class ContactData implements Serializable{

	private static final long serialVersionUID = -6521292969963945720L;
	private int id;
	private String name;
	private String number;
	private String email;
	private boolean isFavourite;
	
	
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getNumber() {
		return number;
	}
	public void setNumber(String number) {
		this.number = number;
	}
	public String getEmail() {
		return email;
	}
	public void setEmail(String email) {
		this.email = email;
	}
	public boolean isFavourite() {
		return isFavourite;
	}
	public void setFavourite(boolean isFavourite) {
		this.isFavourite = isFavourite;
	}
	
	
}
