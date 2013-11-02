/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import com.example.ponypark.R;
import com.google.android.gms.maps.CameraUpdate;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.OnMarkerClickListener;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

public class HomeFrag extends Fragment implements OnMarkerClickListener {
	// Google Map
	private SupportMapFragment fragment;
	private GoogleMap map;
	private Marker myMarker;
	private Context contex;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		contex = container.getContext();
		View rootView = inflater.inflate(R.layout.fragment_home, container,
				false);
		FragmentManager fm = getChildFragmentManager();
		fragment = (SupportMapFragment) fm.findFragmentById(R.id.map);

		if (fragment == null) {
			fragment = SupportMapFragment.newInstance();
			fm.beginTransaction().replace(R.id.map, fragment).commit();
		}

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
			CameraUpdate center = CameraUpdateFactory.newLatLng(new LatLng(
					latitude, longitude));
			CameraUpdate zoom = CameraUpdateFactory.zoomTo(15);
			map.moveCamera(center);
			map.animateCamera(zoom);
			myMarker = map.addMarker(new MarkerOptions().position(
					new LatLng(latitude, longitude)).title("SMU"));
		}
	}

	@Override
	public boolean onMarkerClick(Marker marker) {
		if (myMarker.equals(marker)) {
			Toast.makeText(contex, "SMU", Toast.LENGTH_LONG).show();
		}
		return false;
	}
}
