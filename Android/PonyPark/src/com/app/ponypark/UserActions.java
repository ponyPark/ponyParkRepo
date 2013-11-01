/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

public class UserActions {

	private JSONParser jsonParser;
	private static String loginURL = "http://ponypark.floccul.us/verifyAndroid.php";
	private static String registerURL = "http://ponypark.floccul.us/createAndroid.php";

	// constructor
	public UserActions() {
		jsonParser = new JSONParser();
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

	// Sign up user
	public JSONObject signUp(String fName, String lName, String email,
			String password, String phone) {
		// Parameters to send to server
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("fname", fName));
		params.add(new BasicNameValuePair("lname", lName));
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("pw", password));
		params.add(new BasicNameValuePair("phone", phone));

		// getting JSON Object
		JSONObject json = jsonParser.getJSONFromUrl(registerURL, params);
		// return json
		return json;
	}
}
