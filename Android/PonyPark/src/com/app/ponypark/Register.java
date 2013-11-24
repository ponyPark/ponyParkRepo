/*
 * Justin Trantham
 * 11/23/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

public class Register extends Activity {
	Button btnSignUp;
	Button btnLoginLink;
	EditText txtFName, txtLName, txtEmail, txtPhone, txtPassword;
	TextView signUpError;
	private String fName, lName, email, phone, password, userId;
	SessionControl session;
	private Context context;
	private ProgressDialog pd;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.register);

		txtFName = (EditText) findViewById(R.id.firstName);
		txtLName = (EditText) findViewById(R.id.lastName);
		txtEmail = (EditText) findViewById(R.id.email);
		txtPhone = (EditText) findViewById(R.id.phone);
		txtPassword = (EditText) findViewById(R.id.password);
		btnSignUp = (Button) findViewById(R.id.btnRegister);
		btnLoginLink = (Button) findViewById(R.id.btnLogin);
		signUpError = (TextView) findViewById(R.id.register_error);

		context = this;
		// Session Control
		session = new SessionControl(getApplicationContext());
		btnSignUp.setOnClickListener(new View.OnClickListener() {

			public void onClick(View view) {

				fName = txtFName.getText().toString();
				lName = txtLName.getText().toString();
				email = txtEmail.getText().toString();
				phone = txtPhone.getText().toString();
				password = txtPassword.getText().toString();
				// Form validation
				if (fName.length() != 0 && lName.length() != 0
						&& email.length() != 0 && phone.length() != 0
						&& password.length() != 0) {
					if (emailValid(email) && passValid(password)
							&& isPhoneValid(phone) && validFName(fName)
							&& validLName(lName)) {
						registerUser task = new registerUser();

						task.execute((Object[]) null);

					} else {

						signUpError.setText("Error occured in registration");
					}
				} else {

					signUpError.setText("Error occured in registration");
				}
			}
		});

		// Link to Login Screen
		btnLoginLink.setOnClickListener(new View.OnClickListener() {

			public void onClick(View view) {
				Intent i = new Intent(getApplicationContext(), Login.class);
				startActivity(i);
				// Close current screen
				finish();
			}
		});
	}

	private class registerUser extends AsyncTask<Object, Object, Object> {
		private boolean success = false, duplicateEmail = false;

		@Override
		protected void onPreExecute() {
			pd = new ProgressDialog(context);
			pd.setTitle("Signing Up....");
			pd.setMessage("Please wait.");
			pd.setCancelable(false);
			// pd.setIndeterminate(true);
			pd.show();
		}

		@Override
		protected Object doInBackground(Object... params) {

			UserActions user = new UserActions();
			System.out.println(fName + " " + lName + " " + email + " "
					+ password);
			JSONObject json = user.signUp(fName, lName, email, password, phone);

			duplicateEmail = false;
			try {
				if (!json.isNull("emailAlready")) {
					if (json.getBoolean("emailAlready")) {
						duplicateEmail = true;
						success = false;
					}
				} else {
					if (json.getJSONObject("UserInfo").get("Email").toString()
							.length() != 0) {
						fName = json.getJSONObject("UserInfo").get("FirstName")
								.toString();
						lName = json.getJSONObject("UserInfo").get("LastName")
								.toString();
						email = json.getJSONObject("UserInfo").get("Email")
								.toString();
						userId = json.getJSONObject("UserInfo").get("UserID")
								.toString();
						duplicateEmail = false;
						success = true;
					} else {
						success = false;
						// Error in login
					}
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
				signUpError.setText("Thank You!");
				successMessageUI("Thank you for joining!");
				// Create login session
				MainActivity.session.createLoginSession(fName, lName, email,
						userId);
				finish();
			} else {

				if (duplicateEmail) {
					signUpError
							.setText("Already an account with that email address.");
				} else
					signUpError.setText("Error occured in registration");

			}
		}
	}

	public final boolean emailValid(CharSequence target) {
		if (target == null) {
			return false;
		} else {

			if (android.util.Patterns.EMAIL_ADDRESS.matcher(target).matches()) {
				return true;

			} else {
				txtEmail.setError("Incorrect email address");
				return false;
			}
		}
	}

	public final boolean passValid(CharSequence target) {
		if (TextUtils.isEmpty(target) || target.length() < 8) {
			txtPassword.setError("You must have 8 characters in your password");
			return false;
		} else
			return true;
	}

	public final boolean isPhoneValid(String no) {
		String expression = "^[0-9-+]{9,10}$";
		CharSequence inputStr = no;
		Pattern pattern = Pattern.compile(expression, Pattern.CASE_INSENSITIVE);
		Matcher matcher = pattern.matcher(inputStr);
		if (matcher.matches())
			return true;
		else {

			txtPhone.setError("Incorrect phone number");
			return false;
		}
	}

	public final boolean validFName(String input) throws NumberFormatException {
		if (input.matches("[a-zA-Z ]+")) {
			return true;
		} else {
			txtFName.setError("Accept alphabetical letters only.");
			return false;
		}
	}

	public final boolean validLName(String input) throws NumberFormatException {
		if (input.matches("[a-zA-Z ]+")) {
			return true;
		} else {
			txtLName.setError("Accept alphabetical letters only.");
			return false;
		}
	}

	public void successMessageUI(final String message) {
		runOnUiThread(new Runnable() {

			@Override
			public void run() {
				Toast.makeText(context, message, Toast.LENGTH_LONG).show();
			}
		});
	}
}