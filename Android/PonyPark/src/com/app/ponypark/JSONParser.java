/*
 * Justin Trantham
 * 11/1/13
 * PonyPark by BAM Software
 * Used example at http://www.androidhive.info/ for
 * help. 
 * Latest version for Iteration 3 12/7/13
 *
 */
package com.app.ponypark;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import android.util.Log;

public class JSONParser {

	static InputStream is = null;
	static JSONObject jObj = null;
	private boolean checkFavorite = false, hasFavorite = false;
	static String json = "";

	// constructor
	public JSONParser() {
	}

	public JSONObject getJSONFromUrl(String url, List<NameValuePair> paramss) {
		// Check to see if were checking to see if the user has a favorite
		try {
			if (url.contains("hasFavorite")) {
				checkFavorite = true;
			} else {
				checkFavorite = false;
			}

			DefaultHttpClient httpClient = new DefaultHttpClient();
			HttpPost httpPost = new HttpPost(url);
			if (paramss != null)
				httpPost.setEntity(new UrlEncodedFormEntity(paramss));

			HttpResponse httpResponse = httpClient.execute(httpPost);
			HttpEntity httpEntity = httpResponse.getEntity();
			is = httpEntity.getContent();

		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		} catch (ClientProtocolException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		boolean emailExists = false;
		try {
			BufferedReader reader = new BufferedReader(new InputStreamReader(
					is, "iso-8859-1"), 8);
			StringBuilder sb = new StringBuilder();
			String line = null;
			while ((line = reader.readLine()) != null) {
				sb.append(line + "\n");
				if (line.equals("Email already exists")) {
					emailExists = true;
					break;
				}

				if (checkFavorite) {
					if (line.equals("False"))
						hasFavorite = false;
					else
						hasFavorite = true;
				}
			}
			is.close();
			json = sb.toString();

			Log.e("JSON", json);
		} catch (Exception e) {
			Log.e("Buffer Error", "Error converting result " + e.toString());
		}

		// Parse string to JSON object
		try {
			if (checkFavorite) {
				jObj = new JSONObject();
				jObj.put("hasFavorite", hasFavorite);
				System.out.println("CHECKING ");
			}
			// See if the email already exists and if so create seperate JSON
			// object to return
			else if (emailExists) {
				jObj = new JSONObject();
				jObj.put("emailAlready", true);
			} else
				jObj = new JSONObject(json);

		} catch (JSONException e) {
			Log.e("JSON Parser", "Error parsing data " + e.toString());
		}
		// return JSON object
		return jObj;
	}
}
