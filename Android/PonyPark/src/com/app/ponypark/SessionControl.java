/*
 * Justin Trantham
 * 11/23/13
 * PonyPark by BAM Software
 * Latest version for Iteration 3 12/7/13
 */
package com.app.ponypark;

import java.util.HashMap;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

public class SessionControl {
	// Shared Preferences
	SharedPreferences pref;
	// Editor for Shared preferences
	Editor editor;
	// Context
	Context mContext;
	// Shared pref mode
	int PRIVATE_MODE = 0;
	// Sharedpref file name
	private static final String PREF_NAME = "ponypark";
	// Flag that keeps track of user session as logged in or not
	private static final String IS_LOGIN = "isLoggedIn";

	// Constructor
	public SessionControl(Context context) {
		this.mContext = context;
		pref = mContext.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
		editor = pref.edit();
	}

	/*
	 * Stores locally in SharedPreferences the user information to keep track of
	 * session
	 * 
	 * @ Facebook object, First name, last name, email, user id
	 */
	public void createFacebookSession(String fName, String lName, String email,
			String userId, String extId) {

		editor.putBoolean(IS_LOGIN, true);
		editor.putString(UserActions.KEY_fName, fName);
		editor.putString(UserActions.KEY_lName, lName);
		editor.putString(UserActions.KEY_email, email);
		editor.putString(UserActions.KEY_userID, userId);
		editor.putString(UserActions.KEY_externalID, extId);
		editor.putString(UserActions.KEY_loginMethod, "Facebook");
		editor.commit();
		System.out.println("INside the facebook session");
	}

	/*
	 * Stores locally in SharedPreferences the user information to keep track of
	 * session. Stores as Google plus login.
	 * 
	 * @ First name,Last Name, Email, User ID
	 */
	public void createGoogleSession(String fName, String lName, String email,
			String userId, String extId) {
		editor.putBoolean(IS_LOGIN, true);
		editor.putString(UserActions.KEY_fName, fName);
		editor.putString(UserActions.KEY_lName, lName);
		editor.putString(UserActions.KEY_email, email);
		editor.putString(UserActions.KEY_userID, userId);
		editor.putString(UserActions.KEY_externalID, extId);
		editor.putString(UserActions.KEY_loginMethod, "Google");
		editor.commit();
	}

	/*
	 * Stores locally in SharedPreferences the user information to keep track of
	 * session
	 * 
	 * @ First name,Last Name, Email, User ID
	 */
	public void createLoginSession(String fName, String lName, String email,
			String userId) {
		// Storing login value as true
		editor.putBoolean(IS_LOGIN, true);
		editor.putString(UserActions.KEY_fName, fName);
		editor.putString(UserActions.KEY_lName, lName);
		editor.putString(UserActions.KEY_email, email);
		editor.putString(UserActions.KEY_loginMethod, "Regular");
		editor.putString(UserActions.KEY_userID, userId);
		// Submit changes
		editor.commit();
	}

	/*
	 * Using the keys returns mappings of user information
	 */
	public HashMap<String, String> getUserDetails() {
		HashMap<String, String> userInfo = new HashMap<String, String>();
		userInfo.put(UserActions.KEY_fName,
				pref.getString(UserActions.KEY_fName, null));
		userInfo.put(UserActions.KEY_lName,
				pref.getString(UserActions.KEY_lName, null));
		userInfo.put(UserActions.KEY_email,
				pref.getString(UserActions.KEY_email, null));
		userInfo.put(UserActions.KEY_loginMethod,
				pref.getString(UserActions.KEY_loginMethod, null));
		userInfo.put(UserActions.KEY_userID,
				pref.getString(UserActions.KEY_userID, null));
		return userInfo;
	}

	/*
	 * Resets the shared preferences and delivers the user back to the main Home
	 * Fragment
	 */
	public void logoutUser() {
		editor.clear();
		editor.commit();
		// Login.signOut();
		Intent i = new Intent(mContext, MainActivity.class);
		// Closes all the windows displayed
		i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
		i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
		// Start MainActivity
		mContext.startActivity(i);
	}

	/*
	 * Returns true if the user is logged in and false if not based upon
	 * IS_LOGIN flag key.
	 */
	public boolean isLoggedIn() {
		return pref.getBoolean(IS_LOGIN, false);
	}
}
