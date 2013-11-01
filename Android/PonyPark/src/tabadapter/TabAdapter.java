/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 */
package tabadapter;

import com.app.ponypark.HomeFrag;
import com.app.ponypark.ListViewFrag;
import com.app.ponypark.FavoritesFrag;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;

public class TabAdapter extends FragmentPagerAdapter {

	public TabAdapter(FragmentManager fm) {
		super(fm);
	}

	@Override
	public Fragment getItem(int index) {

		switch (index) {
		case 0:
			//Goto mapview frag
			return new HomeFrag();
		case 1:
			//Goto the ListView Frag
			return new ListViewFrag();
		case 2:
			//Goto to the myFavorites list
			return new FavoritesFrag();
		}
		return null;
	}

	@Override
	public int getCount() {
		// get item count - equal to number of tabs
		return 3;
	}
}
