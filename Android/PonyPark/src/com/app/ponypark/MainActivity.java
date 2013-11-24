/*
 * Justin Trantham
 * 11/23/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import java.util.HashMap;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;

import tabadapter.TabAdapter;
import android.app.ActionBar;
import android.app.ActionBar.OnNavigationListener;
import android.app.ActionBar.Tab;
import android.app.AlertDialog;
import android.app.FragmentTransaction;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.content.res.Resources;
import android.graphics.Typeface;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.support.v4.view.ViewPager;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.SubMenu;
import android.widget.TextView;

public class MainActivity extends FragmentActivity implements
		ActionBar.TabListener, OnNavigationListener {
	private ViewPager viewPager;
	private TabAdapter mAdapter;
	private ActionBar actionBar;
	private Context context;
	private HashMap<String, String> user;
	private SubMenu sub;
	private MenuInflater inflater;
	private static MainActivity instance;
	// Session Manager Class
	public static SessionControl session;
	private boolean loginShown = false;
	private double curLat = 0.0;
	private double curLong = 0.0;
	static Location location;
	private static Criteria criteria;
	private static LocationManager locationManager;
	// Tab titles
	private String[] tabs = { "Map View", "List View", "Favs" };

	public static MainActivity getInstance() {
		return instance;
	}

	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		// Sets the screen orientation to portrait
		setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
		if (!isNetworkAvailable())
			noNetwork();

		// Session class instance
		session = new SessionControl(getApplicationContext());
		viewPager = (ViewPager) findViewById(R.id.pager);
		actionBar = getActionBar();
		mAdapter = new TabAdapter(getSupportFragmentManager());
		context = this;
		instance = this;
		viewPager.setAdapter(mAdapter);
		actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);
		
		// Adding Tabs
		for (String tab_name : tabs) {
			actionBar.addTab(actionBar.newTab().setText(tab_name)
					.setTabListener(this));
		}
		// View pager listener
		viewPager.setOnPageChangeListener(new ViewPager.OnPageChangeListener() {

			@Override
			public void onPageSelected(int position) {
				// on changing the page
				// make respected tab selected
				actionBar.setSelectedNavigationItem(position);
			}

			@Override
			public void onPageScrolled(int arg0, float arg1, int arg2) {
			}

			@Override
			public void onPageScrollStateChanged(int arg0) {
			}
		});
		// Setting up title
		int actionBarTitle = Resources.getSystem().getIdentifier(
				"action_bar_title", "id", "android");
		TextView actionBarTitleView = (TextView) getWindow().findViewById(
				actionBarTitle);
		Typeface robotoBoldCondensedItalic = Typeface.createFromAsset(
				getAssets(), "bahaus.ttf");
		if (actionBarTitleView != null) {
			actionBarTitleView.setTypeface(robotoBoldCondensedItalic);
		}

		locationManager = (LocationManager) this
				.getSystemService(Context.LOCATION_SERVICE);
		criteria = new Criteria();
		String provider = locationManager.getBestProvider(criteria, false);
		location = locationManager.getLastKnownLocation(provider);
		if (locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)
				&& location != null) {
			curLat = location.getLongitude();
			curLong = location.getLatitude();
		}
	}

	public static double getLat() {

		String provider = locationManager.getBestProvider(criteria, false);
		location = locationManager.getLastKnownLocation(provider);
		return location.getLatitude();
	}

	public static double getLong() {

		String provider = locationManager.getBestProvider(criteria, false);
		location = locationManager.getLastKnownLocation(provider);
		return location.getLongitude();
	}

	@Override
	public void onTabReselected(Tab tab, FragmentTransaction ft) {
	}

	@Override
	public void onTabSelected(Tab tab, FragmentTransaction ft) {
		// on tab selected
		// show respected fragment view

		if (!isNetworkAvailable()) {
			displayAlert();
		} else {
			if (tab.getText().toString().equals("List View")) {
				ListViewFrag.getInstance().clearData();
				ListViewFrag.getInstance().startNewAsyncTask();
				viewPager.setCurrentItem(tab.getPosition());
			} else if (tab.getText().toString().equals("Favs")) {
				if(session.isLoggedIn()){

					FavoritesFrag.getInstance().clearData();
					FavoritesFrag.getInstance().startNewAsyncTask();
					viewPager.setCurrentItem(tab.getPosition());	
				}
				else
					displayLoginMessage();
			} else if (tab.getText().toString().equals("Map View"))
				viewPager.setCurrentItem(tab.getPosition());
		}
	}
	private void displayLoginMessage() {
		new AlertDialog.Builder(context).setTitle("Login to continue")
				.setMessage("Please login to continue")
				.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int which) {
						// Nothing
					}
				}).show();
	}
	@Override
	public void onTabUnselected(Tab tab, FragmentTransaction ft) {
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu items for use in the action bar
		inflater = getMenuInflater();
		sub = menu.addSubMenu("More");
		sub.getItem().setShowAsAction(MenuItem.SHOW_AS_ACTION_ALWAYS);

		sub.getItem().setIcon(R.drawable.ic_action_person);
		if (session.isLoggedIn()) {
			sub.add("" + user.get(UserActions.KEY_fName));
			inflater.inflate(R.menu.account_actions, sub);
		} else {
			inflater.inflate(R.menu.non_account, sub);
		}
		return super.onCreateOptionsMenu(sub);
	}

	public boolean onPrepareOptionsMenu(Menu menu) {
		menu.clear();
		inflater = getMenuInflater();
		sub = menu.addSubMenu("More");
		sub.getItem().setShowAsAction(MenuItem.SHOW_AS_ACTION_ALWAYS);

		sub.getItem().setIcon(R.drawable.ic_action_person);
		// Show different dropdown menu depending if user is already logged in
		// or not
		if (session.isLoggedIn()) {
			sub.add("" + user.get(UserActions.KEY_fName));
			inflater.inflate(R.menu.account_actions, sub);
		} else {
			inflater.inflate(R.menu.non_account, sub);
		}
		return super.onPrepareOptionsMenu(sub);
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle presses on the action bar items
		switch (item.getItemId()) {

		case R.id.action_about:
			AboutBox helpDialog = new AboutBox(this);
			helpDialog.setTitle("PonyPark (Ver 1.0.0)");
			helpDialog.show();
			return true;
		case R.id.action_accountNon:
			if (isNetworkAvailable()) {
				Intent i = new Intent(getApplicationContext(), Login.class);
				startActivity(i);
				invalidateOptionsMenu();
				loginShown = true;
			} else {
				displayAlert();
			}
			return true;
		case R.id.action_logout:
			if (user.get(UserActions.KEY_loginMethod).equals("Facebook"))
				Login.getInstance().callFacebookLogout(context);
			session.logoutUser();
			if (user.get(UserActions.KEY_loginMethod).equals("Google")) {
				Login.getInstance().googleSignOut();
				session.logoutUser();
			}
			if (user.get(UserActions.KEY_loginMethod).equals("Regular"))
				MainActivity.session.logoutUser();
			invalidateOptionsMenu();
			return true;
		default:
			return super.onOptionsItemSelected(item);
		}
	}

	@Override
	public boolean onNavigationItemSelected(int itemPosition, long itemId) {
		// TODO Auto-generated method stub
		return false;
	}

	// Checks to see if there is a network connection before logging in
	private boolean isNetworkAvailable() {
		ConnectivityManager connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
		NetworkInfo activeNetworkInfo = connectivityManager
				.getActiveNetworkInfo();
		return activeNetworkInfo != null && activeNetworkInfo.isConnected();
	}

	private void displayAlert() {
		new AlertDialog.Builder(this).setTitle("No network connection")
				.setMessage("Please enable internet access to continue.")
				.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int which) {
						// continue with delete
					}
				}).show();
	}

	void noNetwork() {
		new AlertDialog.Builder(this).setTitle("No network connection")
				.setMessage("Please enable internet access to continue.")
				.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int which) {

						finish();
					}
				}).show();
	}
	void noPlay() {
		new AlertDialog.Builder(this).setTitle("Google Play Services not found.")
				.setMessage("Please install Google Play Services")
				.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog, int which) {

						finish();
					}
				}).show();
	}

	@Override
	protected void onResume() {
		super.onResume();
		// get user data from session
		int status = GooglePlayServicesUtil.isGooglePlayServicesAvailable(getApplicationContext());
		if(status != ConnectionResult.SUCCESS) {

			noPlay();
		}
	
		if (!isNetworkAvailable()) {
			displayAlert();
		} else {
			user = session.getUserDetails();
			invalidateOptionsMenu();
		}
	}

	public static boolean isLoggedIn() {
		return session.isLoggedIn();
	}
}
