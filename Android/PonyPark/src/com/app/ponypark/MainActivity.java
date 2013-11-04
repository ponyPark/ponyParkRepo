/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import java.util.HashMap;

import com.example.ponypark.R;

import tabadapter.TabAdapter;
import android.app.ActionBar;
import android.app.ActionBar.OnNavigationListener;
import android.app.ActionBar.Tab;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager.OnBackStackChangedListener;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.graphics.Typeface;
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
import android.widget.Toast;

public class MainActivity extends FragmentActivity implements
		ActionBar.TabListener, OnNavigationListener {
	private ViewPager viewPager;
	private TabAdapter mAdapter;
	private ActionBar actionBar;
	private Context context;
	private HashMap<String, String> user;
	private SubMenu sub;
	private MenuInflater inflater;
	// Session Manager Class
	SessionControl session;
	// Tab titles
	private String[] tabs = { "Map View", "List View", "Favs" };

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		// Session class instance
		session = new SessionControl(getApplicationContext());
		// Initilization
		viewPager = (ViewPager) findViewById(R.id.pager);
		actionBar = getActionBar();
		mAdapter = new TabAdapter(getSupportFragmentManager());
		context = this;
		viewPager.setAdapter(mAdapter);
		actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);

		// Adding Tabs
		for (String tab_name : tabs) {
			actionBar.addTab(actionBar.newTab().setText(tab_name)
					.setTabListener(this));
		}
		//View pager listener
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
		int actionBarTitle = Resources.getSystem().getIdentifier(
				"action_bar_title", "id", "android");
		TextView actionBarTitleView = (TextView) getWindow().findViewById(
				actionBarTitle);
		Typeface robotoBoldCondensedItalic = Typeface.createFromAsset(
				getAssets(), "bahaus.ttf");
		if (actionBarTitleView != null) {
			actionBarTitleView.setTypeface(robotoBoldCondensedItalic);
		}

	}

	@Override
	public void onTabReselected(Tab tab, FragmentTransaction ft) {
	}

	@Override
	public void onTabSelected(Tab tab, FragmentTransaction ft) {
		// on tab selected
		// show respected fragment view
		viewPager.setCurrentItem(tab.getPosition());
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
			sub.add("" + user.get(Register.KEY_fName));
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
		//Show different dropdown menu depending if user is already logged in or not
		if (session.isLoggedIn()) {
			sub.add("" + user.get(Register.KEY_fName));
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
			Toast.makeText(context,"About Box", Toast.LENGTH_SHORT).show();
			return true;
		case R.id.action_accountNon:
			if (isNetworkAvailable()) {
				Intent i = new Intent(getApplicationContext(), Login.class);
				startActivity(i);
			} else {
				displayAlert();
			}
			return true;
		case R.id.action_logout:
			session.logoutUser();
			invalidateOptionsMenu();
			return true;
		case R.id.action_account:
			Toast.makeText(context,"My Account", Toast.LENGTH_SHORT).show();
			return true;
		case R.id.action_fav:
			Toast.makeText(context,"My Favorites", Toast.LENGTH_SHORT).show();
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
	
	//Checks to see if there is a network connection before logging in
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

	@Override
	protected void onResume() {
		super.onResume();
		// get user data from session
		user = session.getUserDetails();	
		invalidateOptionsMenu();
	}

}
