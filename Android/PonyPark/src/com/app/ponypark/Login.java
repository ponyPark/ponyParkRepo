/*
 * Justin Trantham
 * 11/23/13
 * PonyPark by BAM Software
 * Latest version for Iteration 3 12/7/13
 */
package com.app.ponypark;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Arrays;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.IntentSender.SendIntentException;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.facebook.*;
import com.facebook.Request.GraphUserCallback;
import com.facebook.Session.StatusCallback;
import com.facebook.model.*;
import com.google.android.gms.auth.GoogleAuthException;
import com.google.android.gms.auth.GoogleAuthUtil;
import com.google.android.gms.auth.UserRecoverableAuthException;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesClient.ConnectionCallbacks;
import com.google.android.gms.common.GooglePlayServicesClient.OnConnectionFailedListener;
import com.google.android.gms.common.Scopes;
import com.google.android.gms.plus.PlusClient;
import com.google.android.gms.plus.model.people.Person;

public class Login extends Activity implements OnClickListener,
		ConnectionCallbacks, OnConnectionFailedListener {
	Button btnLogin;
	Button btnLinkToRegister;
	EditText inputEmail;
	EditText inputPassword;
	TextView loginErrorMsg;
	private Context context;
	private ProgressDialog pd;
	private String email, password;
	private String googleFName = "", googleLName = "", googleEmail = "",
			googleId = "";
	private static Login instance;
	private static final String TAG = "SignInTestActivity";
	private static final int REQUEST_CODE_RESOLVE_ERR = 9000;
	private static ProgressDialog mConnectionProgressDialog;
	private static PlusClient mPlusClient;
	private static ConnectionResult mConnectionResult;
	private String fbId, fName, lName, userId;

	public Login() {
		instance = this;
	}

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		// Set all views
		inputEmail = (EditText) findViewById(R.id.loginEmail);
		inputPassword = (EditText) findViewById(R.id.loginPassword);
		btnLogin = (Button) findViewById(R.id.btnLogin);
		btnLinkToRegister = (Button) findViewById(R.id.btnRegScreen);
		loginErrorMsg = (TextView) findViewById(R.id.login_error);
		findViewById(R.id.sign_in_button).setOnClickListener(this);
		findViewById(R.id.login_button).setOnClickListener(this);
		btnLogin.setOnClickListener(this);
		// Link to Register Screen
		btnLinkToRegister.setOnClickListener(this);
		mPlusClient = new PlusClient.Builder(this, this, this)
				.setActions("http://schemas.google.com/AddActivity",
						"http://schemas.google.com/BuyActivity")
				.setScopes(Scopes.PLUS_LOGIN) // Space separated list of scopes
				.build();

		// Google Plus login dialog
		mConnectionProgressDialog = new ProgressDialog(this);
		mConnectionProgressDialog.setMessage("Signing in...");
		instance = this;
		context = this;

		// Settings.addLoggingBehavior(LoggingBehavior.INCLUDE_ACCESS_TOKENS);

	}

	public static Login getInstance() {
		if (instance == null) {
			instance = new Login();
		}
		return instance;
	}

	@Override
	public void onStart() {
		super.onStart();
	}

	@Override
	public void onStop() {
		super.onStop();
		mPlusClient.disconnect();
	}

	@Override
	public void onConnected(Bundle connectionHint) {
		mConnectionProgressDialog.dismiss();
		final String account = mPlusClient.getAccountName();

		AsyncTask<Object, Object, Object> task = new AsyncTask<Object, Object, Object>() {

			String fName, lName, email, userId;

			@Override
			protected Object doInBackground(Object... params) {
				HttpURLConnection urlConnection = null;
				UserActions userFunction = new UserActions();
				try {
					URL url = new URL(
							"https://www.googleapis.com/oauth2/v1/userinfo");
					String sAccessToken = GoogleAuthUtil
							.getToken(
									Login.this,
									account,
									"oauth2:"
											+ Scopes.PLUS_LOGIN
											+ " https://www.googleapis.com/auth/userinfo.email");

					urlConnection = (HttpURLConnection) url.openConnection();
					urlConnection.setRequestProperty("Authorization", "Bearer "
							+ sAccessToken);
					BufferedReader in = new BufferedReader(
							new InputStreamReader(
									urlConnection.getInputStream(), "UTF-8"));

					StringBuilder sb = new StringBuilder();
					String line = null;
					while ((line = in.readLine()) != null) {
						sb.append(line);
					}
					in.close();
					// Verify the Google account and if so then get the user's
					// information
					String content = sb.toString();
					JSONObject googleInfo = new JSONObject(content);
					if (!content.isEmpty()) {
						googleEmail = googleInfo.getString("email");
						googleId = googleInfo.getString("id");
						// Getting the res
						if (mPlusClient.getCurrentPerson() != null) {
							Person currentPerson = mPlusClient
									.getCurrentPerson();

							JSONObject json = userFunction.verifyGoogleUser(
									currentPerson.getName().getGivenName(),
									currentPerson.getName().getFamilyName(),
									googleEmail, googleId);
							fName = json.getJSONObject("UserInfo")
									.get("FirstName").toString();
							lName = json.getJSONObject("UserInfo")
									.get("LastName").toString();
							email = json.getJSONObject("UserInfo").get("Email")
									.toString();
							password = json.getJSONObject("UserInfo")
									.get("Password").toString();
							userId = json.getJSONObject("UserInfo")
									.get("UserID").toString();

						}
					}
				} catch (UserRecoverableAuthException userAuthEx) {
					Login.this.startActivityForResult(userAuthEx.getIntent(),
							REQUEST_CODE_RESOLVE_ERR);
				} catch (JSONException e) {
					e.printStackTrace();
				} catch (IOException e) {
					e.printStackTrace();
				} catch (GoogleAuthException e) {
					e.printStackTrace();
				} finally {
					if (urlConnection != null) {
						urlConnection.disconnect();
					}
				}
				return null;
			}

			@Override
			protected void onPostExecute(Object result) {
				// If sucess then display greeting
				if (mPlusClient.getCurrentPerson() != null) {
					Person currentPerson = mPlusClient.getCurrentPerson();
					googleFName = currentPerson.getName().getGivenName();
					googleLName = currentPerson.getName().getFamilyName();

				}
				// If it was a success then create a login session
				if (!MainActivity.isLoggedIn()) {
					MainActivity.session.createGoogleSession(googleFName, lName,
							email, userId, googleId);
					mConnectionProgressDialog.dismiss();
					welcomeMessageUI("Welcome,  " + googleFName);
					finish();
				}
			}
		};
		task.execute();
	}

	/*
	 * Display welcom message with first name on the UI thread
	 */
	public void welcomeMessageUI(final String message) {
		runOnUiThread(new Runnable() {

			@Override
			public void run() {
				Toast.makeText(context, message, Toast.LENGTH_LONG).show();
			}
		});
	}

	@Override
	public void onConnectionFailed(ConnectionResult result) {
		if (mConnectionProgressDialog.isShowing()) {
			// The user clicked the sign-in button already. Start to resolve
			// connection errors. Wait until onConnected() to dismiss the
			// connection dialog.
			if (result.hasResolution()) {
				try {
					result.startResolutionForResult(this,
							REQUEST_CODE_RESOLVE_ERR);
				} catch (SendIntentException e) {
					mPlusClient.connect();
				}
			}
		}
		// Save the result and resolve the connection failure upon a user click.
		mConnectionResult = result;
	}

	@Override
	protected void onActivityResult(int requestCode, int responseCode,
			Intent intent) {
		super.onActivityResult(requestCode, responseCode, intent);
		// facebook.authorizeCallback(requestCode, responseCode, intent);
		if (requestCode == REQUEST_CODE_RESOLVE_ERR
				&& responseCode == RESULT_OK) {
			mConnectionResult = null;
			mPlusClient.connect();
		} else if (Session.getActiveSession() != null)
			Session.getActiveSession().onActivityResult(this, requestCode,
					responseCode, intent);
	}

	@Override
	public void onDisconnected() {
		Log.d(TAG, "disconnected");
	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		/* Google Plus login */
		case R.id.sign_in_button:
			mPlusClient.disconnect();
			mPlusClient.connect();
			if (mConnectionResult == null) {
				mConnectionProgressDialog.show();
				mPlusClient.disconnect();
				mPlusClient.connect();
			} else {
				try {
					mConnectionResult.startResolutionForResult(this,
							REQUEST_CODE_RESOLVE_ERR);
				} catch (SendIntentException e) {
					// Try connecting again.
					mConnectionResult = null;
					mPlusClient.connect();
				}
			}
			break;
		/* Register Click listener */
		case R.id.btnRegScreen:
			Intent i = new Intent(getApplicationContext(), Register.class);
			startActivity(i);
			finish();
			break;
		/* Regular login * */
		case R.id.btnLogin:
			email = inputEmail.getText().toString();
			password = inputPassword.getText().toString();
			RegularLoginAsync task = new RegularLoginAsync();
			task.execute((Object[]) null);
			break;
		/* Facebook login listener */
		case R.id.login_button:
			loginToFacebook();
			break;
		}
	}

	@Override
	protected void onDestroy() {
		// Dismiss the Progress Dialog
		if (pd != null) {
			pd.dismiss();
		}
		super.onDestroy();
	}

	/** Login Background task to send login information to PonyPark servers **/

	private class RegularLoginAsync extends AsyncTask<Object, Object, Object> {
		private boolean success = false;

		String fName, lName, userId;

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
					userId = json.getJSONObject("UserInfo").get("UserID")
							.toString();

					success = true;
				} else {
					// Error in login, signal post execute there was a problem
					success = false;
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
				// Create login session in shared preferences
				MainActivity.session.createLoginSession(fName, lName, email,
						userId);
				welcomeMessageUI("Welcome,  " + fName);
				finish();
			} else {
				loginErrorMsg.setText("Incorrect email/password");
			}
		}
	}

	public void loginToFacebook() {
		if (Session.getActiveSession() == null
				|| Session.getActiveSession().isClosed()) {
			Session session = new Session(this); // <-- where "this" is a
													// reference to your
													// Activity, or Context
			Session.OpenRequest request = new Session.OpenRequest(this)
					.setPermissions("basic_info", "email");
			request.setCallback(new Session.StatusCallback() {
				@SuppressWarnings("deprecation")
				@Override
				public void call(Session session, SessionState state,
						Exception exception) {
					System.out.println("State= " + state);

					if (session.isOpened()) {
						System.out.println("Token=" + session.getAccessToken());
						Request.executeMeRequestAsync(session,
								new GraphUserCallback() {
									@Override
									public void onCompleted(GraphUser user,
											Response response) {
										if (user != null) {
											fbId = user.getId();
											fName = user.getFirstName();
											lName = user.getLastName();
											email = user.asMap().get("email")
													.toString();
											getInfo task = new getInfo();
											task.execute((Object[]) null);
										}
										if (response != null) {
										}
									}
								});
					}
					if (exception != null) {
						System.out.println("Some thing bad happened!");
						exception.printStackTrace();
					}
				}
			});
			Session.setActiveSession(session);
			session.openForRead(request);

			// Session.openActiveSession(this, true, new StatusCallback() {
			//
			// @SuppressWarnings("deprecation")
			// @Override
			// public void call(Session session, SessionState state,
			// Exception exception) {
			// System.out.println("State= " + state);
			//
			// if (session.isOpened()) {
			// System.out.println("Token=" + session.getAccessToken());
			// Request.executeMeRequestAsync(session,
			// new GraphUserCallback() {
			// @Override
			// public void onCompleted(GraphUser user,
			// Response response) {
			// if (user != null) {
			//
			// fbId = user.getId();
			// fName = user.getFirstName();
			// lName = user.getLastName();
			// email = user.asMap().get("email")
			// .toString();
			// getInfo task = new getInfo();
			// task.execute((Object[]) null);
			//
			// }
			// if (response != null) {
			// }
			// }
			// });
			// }
			// if (exception != null) {
			// System.out.println("Some thing bad happened!");
			// exception.printStackTrace();
			// }
			// }
			// });
		}
	}

	private class getInfo extends AsyncTask<Object, Object, Object> {
		private boolean success = false;

		@Override
		protected void onPreExecute() {
		}

		@Override
		protected Object doInBackground(Object... params) {
			UserActions userFunction = new UserActions();

			// Get the response from Ponypark619159
			// server to see if is actual user
			JSONObject json = userFunction.verifyFacebookUser(fName, lName,
					email, fbId);
			try {
				fName = json.getJSONObject("UserInfo").get("FirstName")
						.toString();

				lName = json.getJSONObject("UserInfo").get("LastName")
						.toString();
				email = json.getJSONObject("UserInfo").get("Email").toString();
				userId = json.getJSONObject("UserInfo").get("UserID")
						.toString();
				// Create login session for
				// sessino control

			} catch (JSONException e) {
				// TODO Auto-generated catch
				// block
				e.printStackTrace();
			}
			return null;
		}

		@Override
		protected void onPostExecute(Object result) {
			if (pd != null) {
				pd.dismiss();
			}
			if (email.length() > 0) {
				MainActivity.session.createFacebookSession(

				fName, lName, email, userId, fbId);

				finish();
				welcomeMessageUI("Welcome,  " + fName);
			}
		}
	}

	/**
	 * Logout From Facebook
	 */
	public static void callFacebookLogout(Context context) {
		Session session = Session.getActiveSession();
		if (session != null) {
			if (!session.isClosed()) {
				session.closeAndClearTokenInformation();
			}
		} else {
			session = new Session(context);
			Session.setActiveSession(session);
			session.closeAndClearTokenInformation();
		}
	}

	public static boolean isFacebookConnected() {
		boolean val = false;
		Session session = Session.getActiveSession();
		if (session != null) {
			if (session.isOpened()) {
				val = true;
			} else
				val = false;
		}
		return val;
	}

	public static boolean isGoogleConnected() {
		if (mPlusClient != null)
			return mPlusClient.isConnected();
		else
			return false;

	}

	/**
	 * Logout From Google
	 */
	public static void googleSignOut() {
		if (mPlusClient != null) {
			if (mPlusClient.isConnected()) {
				mPlusClient.clearDefaultAccount();
				mPlusClient.disconnect();
				mPlusClient.connect();
			}
		}
	}
}