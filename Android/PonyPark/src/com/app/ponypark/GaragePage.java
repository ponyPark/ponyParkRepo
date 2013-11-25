package com.app.ponypark;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

public class GaragePage extends Activity implements OnClickListener {
	private TextView gName, gAddress, gRating, gRated, dialogName,
			dialogAddress;
	private RadioGroup dialogGroup;
	private Button report, addFav, reportDialog, cancelDialog;
	private String name, address, parkingId, userId, lastRated, latestRating,
			levelSelected, rating;
	private Spinner level;
	private boolean addFavorite, addRate, hasFavorite;
	private Context context;
	private ProgressDialog pd;
	private SharedPreferences mPref;

private	Dialog dialog ;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_garage_page);
		gName = (TextView) findViewById(R.id.garageRatingName);
		gAddress = (TextView) findViewById(R.id.addressPage);
		gRating = (TextView) findViewById(R.id.availabilityPage);
		report = (Button) findViewById(R.id.reportButtonPage);
		addFav = (Button) findViewById(R.id.addToFavsButton);

		context = this;
		report.setOnClickListener(this);
		addFav.setOnClickListener(this);

		Bundle extras = getIntent().getExtras();

		if (extras != null) {
			name = extras.getString("name");
			address = extras.getString("address");
			parkingId = extras.getString("id");
			lastRated = extras.getString("rated");
			latestRating = extras.getString("rating");
		}

		if (MainActivity.getInstance().session.isLoggedIn()) {
			mPref = getSharedPreferences("ponypark", 0);
			userId = mPref.getString(UserActions.KEY_userID, null);
			hasFavorite = false;
			checkFav checkFavs = new checkFav();
			checkFavs.execute((Object[]) null);

		}
		gName.setText(name);
		gAddress.setText(address);
		setRating(latestRating, lastRated);

	}

	private void setRating(String latestRating, String lastRated) {
		switch (Integer.parseInt(latestRating)) {
		case 1:
			gRating.setText("Full" + " apprx " + lastRated);
			break;
		case 2:
			gRating.setText("Scarce (<5 spots)" + " apprx " + lastRated);
			break;
		case 3:
			gRating.setText("Some (5-10 spots)" + " apprx " + lastRated);
			break;
		case 4:
			gRating.setText("Plenty (10+ spots)" + " apprx " + lastRated);
			break;
		default:
			gRating.setText("Empty" + " apprx " + lastRated);
			break;
		}

	}

	public void displayRate() {
	dialog	= new Dialog(this, R.style.FullHeightDialog); // this
																			// is
																			// a
																			// reference
																			// to
																			// the
																			// style
																			// above
		dialog.setContentView(R.layout.report_availability); // I saved the xml
																// file above as
																// yesnomessage.xml
		dialog.setCancelable(true);
		cancelDialog = (Button) dialog.findViewById(R.id.cancelButtonDialog);
		reportDialog = (Button) dialog.findViewById(R.id.reportButtonDialog);
		dialogName = (TextView) dialog
				.findViewById(R.id.garageRatingNameDialog);
		level = (Spinner) dialog.findViewById(R.id.levelPicker);
		dialogGroup = (RadioGroup) dialog.findViewById(R.id.reportGroup);

		dialogName.setText(name);
		cancelDialog.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub

				dialog.dismiss();
			}
		});
		reportDialog.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub

				levelSelected = level.getSelectedItem().toString();
				int id = dialogGroup.getCheckedRadioButtonId();

				switch (id) {
				case R.id.radio0:
					rating = "1";
					break;
				case R.id.radio1:
					rating = "2";
					break;
				case R.id.radio2:
					rating = "3";
					break;
				case R.id.radio3:
					rating = "4";
					break;
				default:
					rating = "5";
					break;
				}
				addRate = true;
				addIt task = new addIt();
				task.execute(true);
					dialog.dismiss();
			}
		});
		dialog.show();
	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		case R.id.reportButtonPage:
			if (MainActivity.getInstance().session.isLoggedIn()) {
				addRate = true;
				displayRate();
				addRate = false;
			} else
				displayLoginMessage();
			break;
		case R.id.addToFavsButton:
			if (MainActivity.getInstance().session.isLoggedIn()) {
				addFavorite = true;
				addIt add = new addIt();
				add.execute(false);
				addFavorite = false;
			} else
				displayLoginMessage();
			break;
		}
	}

	private class addIt extends AsyncTask<Boolean, Void, Void> {
		private boolean success;

		@Override
		protected void onPreExecute() {
			pd = new ProgressDialog(context);
			if (addFavorite)
				pd.setTitle("Adding Favorite...");
			if (addRate)
				pd.setTitle("Posting rating..");
			pd.setMessage("Please wait.");
			pd.setCancelable(false);
			pd.show();
		}

		@Override
		protected Void doInBackground(Boolean... params) {

			UserActions user = new UserActions();
			// If true then add rating
			if (params[0]) {
				user.addRating(userId, parkingId, levelSelected, rating);
			} else {
				user.addFavorite(userId, parkingId);
			}
			success = true;
			return null;
		}

		@Override
		protected void onPostExecute(Void result) {
			if (pd != null) {
				pd.dismiss();
			}
			if (success) {
				// signUpError.setText("Thank You!");
				Toast.makeText(getApplicationContext(), "Successfully added!",
						Toast.LENGTH_SHORT).show();

				addFav.setVisibility(View.INVISIBLE);
				
				// finish();
			} else {
				// signUpError.setText("Error occured in registration");
				Toast.makeText(getApplicationContext(), "An error occured",
						Toast.LENGTH_SHORT).show();
			}
		}
	}

	private class checkFav extends AsyncTask<Object, Object, Object> {

		@Override
		protected Object doInBackground(Object... params) {

			UserActions user = new UserActions();

			JSONObject test = user.hasFavorite(userId, parkingId);
			hasFavorite = false;
			try {
				if (test.get("hasFavorite").equals(true)) {
					hasFavorite = true;

					System.out.println("AAAAAAAAAAAa " + hasFavorite);
				} else
					hasFavorite = false;
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}

			return null;
		}

		@Override
		protected void onPostExecute(Object result) {

			if (hasFavorite) {
				addFav.setVisibility(View.INVISIBLE);
			} else
				addFav.setVisibility(View.VISIBLE);

		}
	}

	private void displayLoginMessage() {
		new AlertDialog.Builder(context).setTitle("Login to continue")
				.setMessage("Please login to continue")
				.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int which) {
						// continue with delete
					}
				}).show();
	}
}
