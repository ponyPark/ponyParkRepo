/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.location.Address;
import android.location.Geocoder;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.TextView;

import com.google.android.gms.maps.CameraUpdate;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.InfoWindowAdapter;
import com.google.android.gms.maps.GoogleMap.OnInfoWindowClickListener;
import com.google.android.gms.maps.GoogleMap.OnMarkerClickListener;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

public class HomeFrag extends Fragment implements OnMarkerClickListener {
	// Google Map
	private SupportMapFragment fragment;
	private GoogleMap map;
	private Context contex;
	LayoutInflater infl;
	FragmentManager fm;
	private Geocoder geocoder;
	private View view;
	private ProgressDialog dialog;
	private ArrayList<GarageEntry> result;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		infl = inflater;
		View rootView = inflater.inflate(R.layout.fragment_home, container,
				false);
		geocoder = new Geocoder(rootView.getContext());
		view = rootView;
		contex = container.getContext();
		fm = getChildFragmentManager();
		fragment = (SupportMapFragment) fm.findFragmentById(R.id.map);

		if (fragment == null) {
			fragment = SupportMapFragment.newInstance();
			map = fragment.getMap();
			fm.beginTransaction().replace(R.id.map, fragment).commit();
		}
		result = new ArrayList<GarageEntry>();
		dialog = new ProgressDialog(getActivity());
		rootView.findViewById(R.id.refreshMap).setOnClickListener(
				new OnClickListener() {

					@Override
					public void onClick(View v) {
						(new AsyncMapViewLoader()).execute("");
					}

				});
		// Execute background thread to load data on map
		(new AsyncMapViewLoader()).execute("");
		// Perform any camera updates here
		return rootView;
	}

	@Override
	public void onDestroyView() {
		super.onDestroyView();
		try {
			SupportMapFragment fragment = (SupportMapFragment) getActivity()
					.getSupportFragmentManager().findFragmentById(R.id.map);
			if (fragment != null)
				getFragmentManager().beginTransaction().remove(fragment)
						.commit();

		} catch (IllegalStateException e) {
		}
	}

	@Override
	public void onResume() {
		super.onResume();
		if (map == null) {
			// lat and long of SMU
			double latitude = 32.841844;
			double longitude = -96.782348;
			map = fragment.getMap();
			// Default zoom to SMU
			CameraUpdate center = CameraUpdateFactory.newLatLng(new LatLng(
					latitude, longitude));
			CameraUpdate zoom = CameraUpdateFactory.zoomTo(15);
			map.moveCamera(center);
			map.animateCamera(zoom);
			// Setting a custom info window adapter for the google map
			map.setInfoWindowAdapter(new CustomInfoWindowAdapter(result));
		}
	}

	@Override
	public boolean onMarkerClick(Marker marker) {
		marker.showInfoWindow();
		return false;
	}

	private class AsyncMapViewLoader extends
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
			// super.onPostExecute(result);
			dialog.dismiss();
			// Default to the smu campus
			double latitude = 32.841844;
			double longitude = -96.782348;
			List<Address> addresses;
			for (int i = 0; i < result.size(); i++) {
				try {
					addresses = geocoder.getFromLocationName(result.get(i)
							.getAddress() + " , Dallas, TX", 1);
					if (addresses.size() > 0) {

						latitude = addresses.get(0).getLatitude();
						longitude = addresses.get(0).getLongitude();
					}
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				// Add the all the markers to the map
				map.addMarker(new MarkerOptions().position(
						new LatLng(latitude, longitude)).title(
						result.get(i).getName()));

			}
			final ArrayList<GarageEntry> temp = result;
			map.setInfoWindowAdapter(new CustomInfoWindowAdapter(result));
			map.setOnInfoWindowClickListener(new OnInfoWindowClickListener() {
				public void onInfoWindowClick(Marker marker) {
					Intent garagePage = new Intent(view.getContext(),
							GaragePage.class);
					for (int i = 0; i < temp.size(); i++) {
						// Using the tag that was set earlier to get the current
						// view's selected item
						if (view.getTag().equals(temp.get(i))) {
							garagePage.putExtra("name", temp.get(i).getName());
							garagePage.putExtra("address", temp.get(i)
									.getAddress());
							garagePage.putExtra("id", temp.get(i)
									.getParkingId());
							garagePage.putExtra("rating", temp.get(i)
									.getLRating());
							garagePage.putExtra("rated", temp.get(i)
									.getLRated());
							break;
						}
					}
					// Launch the garage page with the given information
					startActivity(garagePage);
				}
			});

		}

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			result.clear();
			dialog.setMessage("Getting latest info...");
			dialog.show();
		}

		@Override
		protected ArrayList<GarageEntry> doInBackground(String... params) {

			JSONArray jArray;
			try {
				json = userFunction.getGarages();
				// Getting Array of the parking options
				jArray = json.getJSONArray("ParkingLocations");

				// looping through all the parking locations
				for (int i = 0; i < jArray.length(); i++) {
					JSONObject c = jArray.getJSONObject(i);

					// Storing each json item in variable
					name = c.getString(UserActions.KEY_Name);
					lastRated = c.getString(UserActions.KEY_LRated);
					latestRating = c.getString(UserActions.KEY_LRating);
					address = c.getString(UserActions.KEY_Adress);
					result.add(convert(c));
				}

				return result;
			} catch (JSONException e) {
				e.printStackTrace();
			}
			return null;
		}

		private GarageEntry convert(JSONObject c) throws JSONException {
			name = c.getString(UserActions.KEY_Name);
			lastRated = c.getString(UserActions.KEY_LRated);
			latestRating = c.getString(UserActions.KEY_LRating);
			address = c.getString(UserActions.KEY_Adress);
			id = c.getString("ParkingID");
			return new GarageEntry(name, address, lastRated, latestRating, id,
					"");
		}
	}

	private class CustomInfoWindowAdapter implements InfoWindowAdapter {

		ArrayList<GarageEntry> resul;

		public CustomInfoWindowAdapter(ArrayList<GarageEntry> input) {
			view = infl.inflate(R.layout.map_info, null);
			resul = input;
		}

		@Override
		public View getInfoWindow(Marker arg0) {

			View layout = infl.inflate(R.layout.map_info, null);
			// Getting the position from the marker
			LatLng latLng = arg0.getPosition();

			// Reference to the name of the parking garage
			TextView name = (TextView) view.findViewById(R.id.infoName);

			// Reference to set latest rating time
			TextView lRated = (TextView) view.findViewById(R.id.infoLRated);

			// Reference to set rating of garage
			TextView lRating = (TextView) view.findViewById(R.id.infoLRating);

			for (int i = 0; i < resul.size(); i++) {
				if (resul.get(i).getName().equals(arg0.getTitle())) {

					name.setText(resul.get(i).getName());
					lRated.setText(resul.get(i).getLRated());
					// Used the tag approach so I can distinguish the item for
					// the window view
					view.setTag(result.get(i));
					switch (Integer.parseInt(resul.get(i).getLRating())) {
					case 1:
						lRating.setText("Full");
						break;
					case 2:
						lRating.setText("Scarce (<5 spots)");
						break;
					case 3:
						lRating.setText("Some (5-10 spots)");
						break;
					case 4:
						lRating.setText("Plenty (10+ spots)");
						break;
					default:
						lRating.setText("Empty");
						break;
					}
					break;
				}

			}
			return view;
		}

		// Defines the contents of the InfoWindow
		@Override
		public View getInfoContents(Marker arg0) {
			return view;
		}
	}
}
