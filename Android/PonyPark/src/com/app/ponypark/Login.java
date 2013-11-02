/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import org.json.JSONException;
import org.json.JSONObject;
import com.example.ponypark.R;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

public class Login extends Activity {
	Button btnLogin;
	Button btnLinkToRegister;
	EditText inputEmail;
	EditText inputPassword;
	TextView loginErrorMsg;
	private Context context;
	private ProgressDialog pd;
	private String email, password;
	SessionControl session;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);

		// Importing all assets like buttons, text fields
		inputEmail = (EditText) findViewById(R.id.loginEmail);
		inputPassword = (EditText) findViewById(R.id.loginPassword);
		btnLogin = (Button) findViewById(R.id.btnLogin);
		btnLinkToRegister = (Button) findViewById(R.id.btnRegScreen);
		loginErrorMsg = (TextView) findViewById(R.id.login_error);
		context = this;
		// Session Control
		session = new SessionControl(getApplicationContext());
		// Login button Click Event
		btnLogin.setOnClickListener(new View.OnClickListener() {

			public void onClick(View view) {
				email = inputEmail.getText().toString();
				password = inputPassword.getText().toString();
				background task = new background();
				task.execute((Object[]) null);
			}
		});

		// Link to Register Screen
		btnLinkToRegister.setOnClickListener(new View.OnClickListener() {
			public void onClick(View view) {
				Intent i = new Intent(getApplicationContext(), Register.class);
				startActivity(i);
				finish();
			}
		});
	}

	@Override
	protected void onDestroy() {
		if (pd != null) {
			pd.dismiss();
		}
		super.onDestroy();
	}

	private class background extends AsyncTask<Object, Object, Object> {
		private boolean success;

		@Override
		protected void onPreExecute() {
			pd = new ProgressDialog(context);
			pd.setTitle("Logging in....");
			pd.setMessage("Please wait.");
			pd.setCancelable(false);
			pd.setIndeterminate(true);
			pd.show();
		}

		@Override
		protected Object doInBackground(Object... params) {
			UserActions userFunction = new UserActions();
			JSONObject json = userFunction.loginUser(email, password);
			String fName, lName, email, password, phone;
			// check for login response
			try {

				if (json.getJSONObject("UserInfo").get("Email").toString()
						.length() != 0) {
					fName = json.getJSONObject("UserInfo").get("FirstName")
							.toString();
					lName = json.getJSONObject("UserInfo").get("LastName")
							.toString();
					email = json.getJSONObject("UserInfo").get("Email")
							.toString();
					password = json.getJSONObject("UserInfo").get("Password")
							.toString();
					phone = json.getJSONObject("UserInfo").get("PhoneNumber")
							.toString();
					// Create login session
					session.createLoginSession(fName, lName, email, password,
							phone);
					success = true;
				} else {
					success = false;
					// Error in login
				}
			} catch (JSONException e) {
				e.printStackTrace();
			}
			return null;
		}

		@Override
		protected void onPostExecute(Object result) {
			if (pd != null) {
				pd.dismiss();
			}
			if (success) {
				Toast.makeText(getApplicationContext(), "Login Successful",
						Toast.LENGTH_LONG).show();
				finish();
			} else {
				loginErrorMsg.setText("Incorrect username/password");
			}
		}
	}
}