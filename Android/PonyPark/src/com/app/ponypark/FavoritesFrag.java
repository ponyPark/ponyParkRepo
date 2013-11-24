/*
 * Justin Trantham
 * PonyPark by BAM software
 * Iteration 2 Release
 * 11/23/13
 */
package com.app.ponypark;

import java.util.ArrayList;
import java.util.Comparator;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.ListFragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemLongClickListener;
import android.widget.ListView;

public class FavoritesFrag extends ListFragment {

	private ArrayList<GarageEntry> result = new ArrayList<GarageEntry>();
	private ProgressDialog dialog;
	private FavListAdapter adpt;
	public static FavoritesFrag instance;
	private String userId, favId;
	private Context context;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		super.onActivityCreated(savedInstanceState);
		View rootView = inflater.inflate(R.layout.fragment_myfavs, container,
				false);

		context = rootView.getContext();
		dialog = new ProgressDialog(getActivity());
		adpt = new FavListAdapter(new ArrayList<GarageEntry>(), getActivity());

		setListAdapter(adpt);
	//	startNewAsyncTask();
		rootView.findViewById(R.id.refreshFavList).setOnClickListener(
				new OnClickListener() {
					@Override
					public void onClick(View v) {
						startNewAsyncTask();
						result.clear();
					}
				});
		instance = this;
		return rootView;
	}

	public static FavoritesFrag getInstance() {
		return instance;
	}

	/*
	 * Clear the
	 */
	public static void clearData() {
		instance.result.clear();
	}



	/*
	 * Used to start a refresh or initiate the favorites list with content.
	 */
	public void startNewAsyncTask() {
		if (MainActivity.session.isLoggedIn()) {

			userId = MainActivity.session.getUserDetails().get(
					UserActions.KEY_userID);
			// Getting latest favs
			(new AsyncFavoritesLoader()).execute("");
		}
	}

	/*
	 * Click listener for each item within the list, once clicked will display
	 * garage page
	 */
	@Override
	public void onListItemClick(ListView l, View v, int position, long id) {
		Intent intent = new Intent(l.getContext(), GaragePage.class);
		for (int i = 0; i < result.size(); i++) {
			if (v.getTag().equals(result.get(i))) {
				intent.putExtra("name", result.get(i).getName());
				intent.putExtra("address", result.get(i).getAddress());
				intent.putExtra("id", result.get(i).getParkingId());
				intent.putExtra("rating", result.get(i).getLRating());
				intent.putExtra("rated", result.get(i).getLRated());
				break;
			}
		}
		startActivity(intent);
	}

	/*
	 * Delete a favorite in background thread.
	 */
	private class deleteFav extends AsyncTask<Void, Object, Void> {

		@Override
		protected void onPreExecute() {
			dialog.setMessage("Deleting favorite...");
			dialog.show();
		}

		@Override
		protected Void doInBackground(Void... params) {
			UserActions user = new UserActions();
			user.deleteFavorite(favId);
			return null;
		}

		@Override
		protected void onPostExecute(Void results) {
			dialog.dismiss();
			adpt.setItemList(result);

			adpt.sort(new Comparator<GarageEntry>() {
				@Override
				public int compare(GarageEntry arg0, GarageEntry arg1) {
					return -arg0.getName().compareTo(arg1.getName());
				}
			});

			setListAdapter(adpt);
			adpt.notifyDataSetChanged();
		}
	}

	/*
	 * Background loader that will load all user's favorites into list
	 */
	private class AsyncFavoritesLoader extends
			AsyncTask<String, Void, ArrayList<GarageEntry>> {
		UserActions userFunction = new UserActions();
		JSONObject json, parkingInfoJson;
		String gName, lastRated, latestRating, address, parkingId;

		// Phone number is agin JSON Object
		@Override
		protected void onPostExecute(ArrayList<GarageEntry> result) {
			super.onPostExecute(result);
			dialog.dismiss();
			adpt.setItemList(result);
			// Sort the list by name
			adpt.sort(new Comparator<GarageEntry>() {
				public int compare(GarageEntry arg0, GarageEntry arg1) {
					System.out.println(arg0.getName());
					return arg0.getName().compareTo(arg1.getName());
				}
			});
			setListAdapter(adpt);
			adpt.notifyDataSetChanged();
			// Click listener for the fav list
			getListView().setOnItemLongClickListener(
					new OnItemLongClickListener() {

						@Override
						public boolean onItemLongClick(AdapterView<?> arg0,
								View arg1, int arg2, long arg3) {
							displayMenu(gName, favId, arg2);
							return true;
						}
					});
		}

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			dialog.setMessage("Getting latest favorites...");
			dialog.show();
		}

		@Override
		protected ArrayList<GarageEntry> doInBackground(String... params) {

			JSONArray jArray;
			try {
				json = userFunction.getFavorites(userId);
				// Getting Array of the parking options
				jArray = json.getJSONArray("Favorites");
				// looping through all the parking locations
				for (int i = 0; i < jArray.length(); i++) {
					JSONObject currentFav = jArray.getJSONObject(i);
					favId = currentFav.getString(UserActions.KEY_favID);
					parkingId = currentFav.getString(UserActions.KEY_parkingID);
					parkingInfoJson = userFunction.getParkingInfo(parkingId);
					// Get the parking information for this garage
					JSONObject currentInfo = parkingInfoJson
							.getJSONObject("ParkingInfo");
					// Storing each json item in variable
					gName = currentInfo.getString(UserActions.KEY_Name);
					lastRated = currentInfo.getString(UserActions.KEY_LRated);
					latestRating = currentInfo
							.getString(UserActions.KEY_LRating);
					address = currentInfo.getString(UserActions.KEY_Adress);
					result.add(new GarageEntry(gName, address, lastRated,
							latestRating, parkingId, favId, userId));

				}

				return result;
			} catch (JSONException e) {
				e.printStackTrace();
			}
			return null;
		}
	}

	/*
	 * Custom menu for each item that is long pressed to delete it.
	 */
	private void displayMenu(String gName, String favId, final int pos) {
		try {
			AlertDialog.Builder dialog = new AlertDialog.Builder(context);
			dialog.setTitle("Delete " + gName + "?");

			dialog.setPositiveButton("OK",
					new DialogInterface.OnClickListener() {
						// do something when the button is clicked
						public void onClick(DialogInterface arg0, int arg1) {
							// TODO Auto-generated method stub
							result.remove(pos);
							(new deleteFav()).execute();
						}
					});

			dialog.setNegativeButton("Cancel",
					new DialogInterface.OnClickListener() {
						// do something when the button is clicked
						public void onClick(DialogInterface arg0, int arg1) {
							// TODO Auto-generated method stub
							arg0.dismiss();
						}
					});

			dialog.show();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}
