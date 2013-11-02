/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import org.json.JSONObject;
import com.example.ponypark.R;
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
	public static String KEY_fName = "fname";
	public static String KEY_lName = "lname";
	public static String KEY_email = "email";
	public static String KEY_password = "pw";
	public static String KEY_phone = "phone";
	private String fName, lName, email, phone, password;
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
				//Form validation
				if (fName.length() != 0 && lName.length() != 0
						&& email.length() != 0 && phone.length() != 0
						&& password.length() != 0) {
					if (emailValid(email) && passValid(password)) {
						background task = new background();

						task.execute((Object[]) null);
					} else {

						signUpError.setText("Error occured in registration");
					}
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

	private class background extends AsyncTask<Object, Object, Object> {
		private boolean success;

		@Override
		protected void onPreExecute() {
			pd = new ProgressDialog(context);
			pd.setTitle("Signing Up....");
			pd.setMessage("Please wait.");
			pd.setCancelable(false);
			pd.setIndeterminate(true);
			pd.show();
		}

		@Override
		protected Object doInBackground(Object... params) {

			UserActions user = new UserActions();
			user.signUp(fName, lName, email, password, phone);
			
			//TODO we need to add ability to get success,failure,or already a user
			session.createLoginSession(email, password, fName, lName, password);
			// Close Registration Screen
			success = true;
			return null;
		}

		@Override
		protected void onPostExecute(Object result) {
			if (pd != null) {
				pd.dismiss();
			}
			if (success) {
				signUpError.setText("Thank You!");
				Toast.makeText(getApplicationContext(), "Success!",
						Toast.LENGTH_SHORT).show();
				finish();
			} else {
				signUpError.setText("Error occured in registration");
			}
		}
	}
	public final static boolean emailValid(CharSequence target) {
		if (target == null) {
			return false;
		} else {
			return android.util.Patterns.EMAIL_ADDRESS.matcher(target)
					.matches();
		}
	}
	public final boolean passValid(CharSequence target) {
		if (TextUtils.isEmpty(target) || target.length() < 8) {
			txtPassword.setError("You must have 8 characters in your password");
			return false;
		} else
			return true;
	}
}