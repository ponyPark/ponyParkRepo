/*
 * Justin Trantham
 * 11/23/13
 * PonyPark by BAM Software
 * Latest version for Iteration 3 12/7/13
 */
package com.app.ponypark;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.ProtocolException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import android.app.ProgressDialog;
import android.content.Intent;
import android.location.Address;
import android.location.Geocoder;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.ListFragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.TextView;

public class ListViewFrag extends ListFragment {
	private ProgressDialog dialog;
	private GarageListAdapter adpt;
	public static ListViewFrag instance;
	private ArrayList<GarageEntry> result = new ArrayList<GarageEntry>();

	private Geocoder geocoder;
	private double latitude, longitude;
	private String distanceTo = "";
	private TextView garageDist;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		View rootView = inflater.inflate(R.layout.fragment_listview, container,
				false);
		dialog = new ProgressDialog(getActivity());
		adpt = new GarageListAdapter(new ArrayList<GarageEntry>(),
				getActivity());

		geocoder = new Geocoder(rootView.getContext());
		setListAdapter(adpt);
		rootView.findViewById(R.id.refreshList).setOnClickListener(
				new OnClickListener() {

					@Override
					public void onClick(View v) {
						// TODO Auto-generated method stub
						startNewAsyncTask();
						result.clear();
					}
				});
		instance = this;
		return rootView;
	}

	public static ListViewFrag getInstance() {
		return instance;
	}

	public static void clearData() {
		if(instance.result.size()>0)
		instance.result.clear();
	}

	/*
	 * Used to call or refresh the data within the list view
	 */
	public void startNewAsyncTask() {
		clearData();
		(new AsyncListsViewLoader()).execute("");
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
	 * Load all the data in the background to display in the list view tab
	 */
	private class AsyncListsViewLoader extends
			AsyncTask<String, Void, ArrayList<GarageEntry>> {

		UserActions userFunction = new UserActions();
		JSONObject json;
		String name;
		String lastRated;
		String latestRating;
		String address;
		String id;

		@Override
		protected void onPostExecute(ArrayList<GarageEntry> result) {
			super.onPostExecute(result);
			dialog.dismiss();
			adpt.setItemList(result);
			adpt.notifyDataSetChanged();
		}

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			dialog.setMessage("Getting latest info...");
			dialog.show();
		}

		@Override
		protected ArrayList<GarageEntry> doInBackground(String... params) {
			JSONArray jArray;

			try {
				json = userFunction.getGarages();
				// Getting array of parking locations
				jArray = json.getJSONArray("ParkingLocations");

				// looping through all the locations
				for (int i = 0; i < jArray.length(); i++) {
					JSONObject c = jArray.getJSONObject(i);
					// Storing each json item in variable
					name = c.getString(UserActions.KEY_Name);
					lastRated = c.getString(UserActions.KEY_LRated);
					latestRating = c.getString(UserActions.KEY_LRating);
					address = c.getString(UserActions.KEY_Adress);
					id = c.getString("ParkingID");
					
					
					//Distance
					/*
					List<Address> addresses;
					try {

						addresses = geocoder.getFromLocationName(address
								+ " , Dallas, TX", 1);
						if (addresses.size() > 0) {
							latitude = addresses.get(0).getLatitude();
							longitude = addresses.get(0).getLongitude();
							(new getDistance()).execute("");

							// garageDist.setText(distanceTo );
						}

					} catch (IOException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}*/
					result.add(convertTo(c));
				}

				return result;
			} catch (JSONException e) {
				e.printStackTrace();
			}
			return null;
		}

		private GarageEntry convertTo(JSONObject c) throws JSONException {
			// String name = obj.getString("name");
			name = c.getString(UserActions.KEY_Name);
			lastRated = c.getString(UserActions.KEY_LRated);
			latestRating = c.getString(UserActions.KEY_LRating);
			address = c.getString(UserActions.KEY_Adress);
			id = c.getString("ParkingID");
			return new GarageEntry(name, address, lastRated, latestRating, id,
					"");
		}
	}
	
	/*
	 * CODE TO SHOW DISTANCE FOR FUTURE
	 */
/*
	private class getDistance extends
			AsyncTask<String, Void, ArrayList<GarageEntry>> {
		JSONObject distance;

		@Override
		protected void onPostExecute(ArrayList<GarageEntry> result) {
			super.onPostExecute(result);

		}

		@Override
		protected void onPreExecute() {
			super.onPreExecute();

		}

		@Override
		protected ArrayList<GarageEntry> doInBackground(String... params) {

			try {
				StringBuilder urlString = new StringBuilder();
				urlString
						.append("http://maps.googleapis.com/maps/api/directions/json?");
				urlString.append("origin=");// from
				urlString.append(MainActivity.getLat());
				urlString.append(",");
				urlString.append(MainActivity.getLong());
				urlString.append("&destination=");// to
				urlString.append(latitude);
				urlString.append(",");
				urlString.append(longitude);
				urlString.append("&mode=driving&sensor=true");
				System.out.println(MainActivity.getLat() + " "
						+ MainActivity.getLong());

				// get the JSON And parse it to get the directions data.
				HttpURLConnection urlConnection = null;
				URL url = null;

				try {
					url = new URL(urlString.toString());

					urlConnection = (HttpURLConnection) url.openConnection();
					urlConnection.setRequestMethod("GET");
					urlConnection.setDoOutput(true);
					urlConnection.setDoInput(true);
					urlConnection.connect();

					InputStream inStream = urlConnection.getInputStream();
					BufferedReader bReader = new BufferedReader(
							new InputStreamReader(inStream));

					String temp, response = "";
					while ((temp = bReader.readLine()) != null) {
						// Parse data
						response += temp;
					}
					// Close the reader, stream & connection
					bReader.close();
					inStream.close();
					urlConnection.disconnect();
					System.out.println(response);
					// Sortout JSONresponse
					JSONObject object = (JSONObject) new JSONTokener(response)
							.nextValue();
					JSONArray array = object.getJSONArray("routes");
					// Log.d("JSON","array: "+array.toString());

					// Routes is a combination of objects and arrays
					JSONObject routes = array.getJSONObject(0);
					// Log.d("JSON","routes: "+routes.toString());

					// String summary = routes.getString("summary");
					// Log.d("JSON","summary: "+summary);
					JSONArray legs = routes.getJSONArray("legs");
					// Log.d("JSON","legs: "+legs.toString());

					JSONObject steps = legs.getJSONObject(0);
					// Log.d("JSON","steps: "+steps.toString());

					distance = steps.getJSONObject("distance");
					// Log.d("JSON","distance: "+distance.toString());

					distanceTo = distance.getString("text");
				} catch (MalformedURLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (ProtocolException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				return null;
			} catch (JSONException e) {
				e.printStackTrace();
			}
			return null;
		}
	}
	*/
}
