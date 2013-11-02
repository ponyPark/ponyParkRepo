/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package com.app.ponypark;

import com.example.ponypark.R;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

public class FavoritesFrag extends Fragment {

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {

		View rootView = inflater.inflate(R.layout.fragment_myfavs,
				container, false);

		return rootView;
	}

}
