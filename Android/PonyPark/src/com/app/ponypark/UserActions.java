/*
 * Justin Trantham
 * 11/23/13
 * PonyPark by BAM Software
 * Latest version for Iteration 3 12/7/13
 */
package com.app.ponypark;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import AsyncTasks.MapParserJSON;

public class UserActions {

	private JSONParser jsonParser;
	MapParserJSON mapJSON;
	private static String loginURL = "http://ponypark.floccul.us/verifyAndroid.php";
	private static String registerURL = "http://ponypark.floccul.us/createAndroid.php";
	private static String verifyGoogleURL = "http://ponypark.floccul.us/verifyGoogleUserAndroid.php";
	private static String verifyFacebookURL = "http://ponypark.floccul.us/verifyFacebookUserAndroid.php";
	private static String getGaragesURL = "http://ponypark.floccul.us/getParkingLocations.php";
	private static String reportURL = "http://ponypark.floccul.us/addRatingAndroid.php";
	private static String addFavURL = "http://ponypark.floccul.us/addFavoritesAndroid.php";
	private static String delFavURL = "http://ponypark.floccul.us/deleteFavoriteAndroid.php";
	private static String getFavURL = "http://ponypark.floccul.us/getFavoritesAndroid.php";
	private static String hasFavURL = "http://ponypark.floccul.us/hasFavoriteAndroid.php";
	private static String logoutURL = "http://ponypark.floccul.us/signOutAndroid.php";
	private static String getParkingInfoURL = "http://ponypark.floccul.us/getParkingInfo.php";

	public static String KEY_Name = "Name";
	public static String KEY_LRated = "Last_Rated";
	public static String KEY_LRating = "Latest_Rating";
	public static String KEY_Adress = "Address";
	public static String KEY_fName = "fname";
	public static String KEY_lName = "lname";
	public static String KEY_email = "email";
	public static String KEY_password = "pw";
	public static String KEY_phone = "phone";
	public static String KEY_userID = "userID";
	public static String KEY_favID = "FavoriteID";
	public static String KEY_parkingID = "ParkingID";
	public static String KEY_loginMethod = "method";
	public static String KEY_externalID = "extId";

	// constructor
	public UserActions() {
		jsonParser = new JSONParser();
		mapJSON = new MapParserJSON();
	}

	// Send rating
	public JSONObject addRating(String userId, String parkingId, String level,
			String available) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("level", parkingId));
		params.add(new BasicNameValuePair("availability", available));
		JSONObject json = jsonParser.getJSONFromUrl(reportURL + "?userID="
				+ userId + "&parkingID=" + parkingId, params);
		return json;
	}

	// Login user request
	public JSONObject loginUser(String email, String password) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("pw", password));
		JSONObject json = jsonParser.getJSONFromUrl(loginURL, params);
		return json;
	}

	/*
	 * Verify Google user
	 * 
	 * @fname, lname, email
	 */
	public JSONObject verifyGoogleUser(String fName, String lName,
			String email, String extID) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("fname", fName));
		params.add(new BasicNameValuePair("lname", lName));
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("externalID", extID));
		JSONObject json = jsonParser.getJSONFromUrl(verifyGoogleURL, params);
		return json;
	}

	/*
	 * Verify Facebook user
	 * 
	 * @fname, lname, email
	 */
	public JSONObject verifyFacebookUser(String fName, String lName,
			String email, String extID) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("fname", fName));
		params.add(new BasicNameValuePair("lname", lName));
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("externalID", extID));
		JSONObject json = jsonParser.getJSONFromUrl(verifyFacebookURL, params);
		return json;
	}

	/*
	 * Gets the list of garages for the map and list view using the referenced
	 * URL making a GET request. 
	 */
	public JSONObject getGarages() {
		// Parameters to send to server
		JSONObject json = mapJSON.getMapJSONFromUrl(getGaragesURL, null);
		return json;
	}

	/*
	 * Get the favorites based on the userId using GET request.
	 */
	public JSONObject getFavorites(String userId) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("userID", userId));
		JSONObject json = jsonParser.getJSONFromUrl(getFavURL + "?userID="
				+ userId, params);
		return json;
	}

	/*
	 * With the user id and parking id, this will send the post message
	 * to add the parking garage to the user's favorites.
	 */
	public JSONObject addFavorite(String userId, String parkingId) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("userID", userId));
		params.add(new BasicNameValuePair("ParkingID", parkingId));
		JSONObject json = jsonParser.getJSONFromUrl(addFavURL + "?userID="
				+ userId + "&parkingID=" + parkingId, params);
		return json;
	}

	/*
	 * Checks to see if the logged in user has the favorite. 
	 * Accepts the user id and parking id of the garage. 
	 */
	public JSONObject hasFavorite(String userId, String parkingId) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("userID", userId));
		params.add(new BasicNameValuePair("ParkingID", parkingId));
		JSONObject json = jsonParser.getJSONFromUrl(hasFavURL + "?userID="
				+ userId + "&parkingID=" + parkingId, params);
		return json;
	}

	/*
	 * Get the current parking info based on the garage parking id
	 */
	public JSONObject getParkingInfo(String parkingId) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("parkingID", parkingId));
		JSONObject json = jsonParser.getJSONFromUrl(getParkingInfoURL
				+ "?parkingID=" + parkingId, params);
		return json;
	}

	/*
	 * Post the "favoriteID" that needs to be deleted, only should take place
	 * when user is logged in
	 */
	public JSONObject deleteFavorite(String favID) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("favoriteID", favID));
		JSONObject json = jsonParser.getJSONFromUrl(delFavURL + "?favoriteID="
				+ favID, params);
		return json;
	}

	// Sign up user
	public JSONObject signUp(String fName, String lName, String email,
			String password, String phone) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("fname", fName));
		params.add(new BasicNameValuePair("lname", lName));
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("phone", phone));
		params.add(new BasicNameValuePair("pw", password));

		// getting JSON Object
		JSONObject json = jsonParser.getJSONFromUrl(registerURL, params);
		// return json
		return json;
	}

	// Sign up user
	public JSONObject signOut() {
		// getting JSON Object
		JSONObject json = jsonParser.getJSONFromUrl(logoutURL, null);
		MainActivity.session.logoutUser();
		// return json
		return json;
	}
}
