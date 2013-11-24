package com.app.ponypark;

public class GarageEntry {

	private String name, address, lRated, lRating, id, UserID, FavID, dist;
	private double lat, lon;

	public GarageEntry(String gName, String gAddress, String gLRated,
			String gLRating, String gId, String userId, String favId) {
		this.name = gName;
		this.address = gAddress;
		this.lRated = gLRated;
		this.lRating = gLRating;
		this.id = gId;
		this.UserID = userId;
		this.FavID = favId;
	}

	public GarageEntry(String gName, String gAddress, String gLRated,
			String gLRating, String gId, String distanceTo) {
		this.name = gName;
		this.address = gAddress;
		this.lRated = gLRated;
		this.lRating = gLRating;
		this.id = gId;
		this.dist = distanceTo;
	}

	public void setName(String input) {
		this.name = input;
	}

	public void setAddress(String input) {
		this.address = input;
	}

	public void setLRated(String input) {
		this.lRated = input;
	}

	public void setLRating(String input) {
		this.lRating = input;
	}

	public String getName() {
		return name;
	}

	public String getAddress() {
		return address;
	}

	public String getDist() {
		return dist;
	}

	public String getLRated() {
		return lRated;
	}

	public String getLRating() {
		return lRating;
	}

	public String getFavId() {
		return FavID;
	}

	public String getUserId() {
		return UserID;
	}

	public void setLat(double in) {
		this.lat = in;
	}

	public void setLong(double in) {
		this.lon = in;

	}

	public double getLat() {
		return lat;
	}

	public double getLong() {
		return lon;
	}

	public String getParkingId() {
		return id;
	}

}