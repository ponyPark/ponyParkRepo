/*
 * Justin Trantham
 * 11/23/13
 * PonyPark by BAM Software
 * Latest version for Iteration 3 12/7/13
 */
package com.app.ponypark;

import java.util.ArrayList;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

public class FavListAdapter extends ArrayAdapter<GarageEntry> {

	private ArrayList<GarageEntry> itemList;
	private Context context;

	public FavListAdapter(ArrayList<GarageEntry> itemList, Context ctx) {
		super(ctx, android.R.layout.simple_list_item_1, itemList);
		this.itemList = itemList;
		this.context = ctx;
	}

	public int getCount() {
		if (itemList != null)
			return itemList.size();
		return 0;
	}

	public GarageEntry getItem(int position) {
		if (itemList != null)
			return itemList.get(position);
		return null;
	}

	public long getItemId(int position) {
		if (itemList != null)
			return itemList.get(position).hashCode();
		return 0;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {

		View v = convertView;
		if (v == null) {
			LayoutInflater inflater = (LayoutInflater) context
					.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
			v = inflater.inflate(R.layout.list_item, null);
		}

		GarageEntry c = itemList.get(position);
		String lRated = c.getLRated();
		String lRating = c.getLRating();
		TextView garageName = (TextView) v.findViewById(R.id.garageName);
		garageName.setText(c.getName());

		TextView garageAddress = (TextView) v.findViewById(R.id.garageAddress);
		garageAddress.setText(c.getAddress());
		TextView garageLRated = (TextView) v.findViewById(R.id.garageLRated);
		v.setTag(c);
		garageLRated.setText(lRated);
		TextView garageLRating = (TextView) v.findViewById(R.id.garageLRating);

		switch (Integer.parseInt(lRating)) {
		case 1:
			garageLRating.setText("Full");
			break;
		case 2:
			garageLRating.setText("Scarce");
			break;
		case 3:
			garageLRating.setText("Some");
			break;
		case 4:
			garageLRating.setText("Plenty");
			break;
		default:
			garageLRating.setText("Empty");
			break;
		}

		// TODO distance to garage
		TextView garageDist = (TextView) v.findViewById(R.id.garageDist);
		garageDist.setText("");

		return v;

	}

	public ArrayList<GarageEntry> getItemList() {
		return itemList;
	}

	public void setItemList(ArrayList<GarageEntry> itemList) {
		this.itemList = itemList;
	}

}