/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
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
	    Context _context;
	     
	    // Shared pref mode
	    int PRIVATE_MODE = 0;
	     
	    // Sharedpref file name
	    private static final String PREF_NAME = "ponypark";
	     
	    // All Shared Preferences Keys
	    private static final String IS_LOGIN = "isLoggedIn";

	     
	    // Constructor
	    public SessionControl(Context context){
	        this._context = context;
	        pref = _context.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
	        editor = pref.edit();
	    }
	     
	    //Creating login session
	    public void createLoginSession(String fName, String lName, String email,
				String password, String phone){
	        // Storing login value as true
	        editor.putBoolean(IS_LOGIN, true);
	        editor.putString(Register.KEY_fName, fName);
	        editor.putString(Register.KEY_lName, lName);
	        editor.putString(Register.KEY_email, email);
	        editor.putString(Register.KEY_password, password);
	        editor.putString(Register.KEY_phone,phone);	
	        //Submit changes
	        editor.commit();
	    }       
	   
	    public void checkLogin(){
	        // Check login status
	        if(!this.isLoggedIn()){
	            // user is not logged in redirect him to Login Activity
	            Intent i = new Intent(_context, Login.class);
	            // Closing all the Activities
	            i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
	             
	            // Add new Flag to start new Activity
	            i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
	             
	            // Staring Login Activity
	            _context.startActivity(i);
	        }
	         
	    }
	   //Get stored details of pony park user
	    public HashMap<String, String> getUserDetails(){
	        HashMap<String, String> user = new HashMap<String, String>();
	        // user name
	        user.put(Register.KEY_fName, pref.getString(Register.KEY_fName, null));
	        // user name
	        user.put(Register.KEY_lName, pref.getString(Register.KEY_lName, null));
	        // user name
	        user.put(Register.KEY_email, pref.getString(Register.KEY_email, null));
	        // user name
	        user.put(Register.KEY_password, pref.getString(Register.KEY_password, null));
	        // user name
	        user.put(Register.KEY_phone, pref.getString(Register.KEY_phone, null));
	        // user name
	        user.put(Register.KEY_fName, pref.getString(Register.KEY_fName, null));
	        // return user
	        return user;
	    }
	    //Reset everything and display normal
	    public void logoutUser(){
	        // Clearing all data from Shared Preferences
	        editor.clear();
	        editor.commit();
	         
	        // After logout redirect user to Loing Activity
	        Intent i = new Intent(_context, MainActivity.class);
	        // Closing all the Activities
	        i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
	         
	        // Add new Flag to start new Activity
	        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
	         
	        // Staring Login Activity
	        _context.startActivity(i);
	    }
	    // Get Login State
	    public boolean isLoggedIn(){
	        return pref.getBoolean(IS_LOGIN, false);
	    }
	
}
