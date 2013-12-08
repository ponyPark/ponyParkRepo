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

import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.webkit.WebView;
import android.webkit.WebViewClient;
public class AboutBox extends Dialog {

	private static Context mContext = null;

	public AboutBox(Context context) {
		super(context);
		mContext = context;
	}

	@Override
	public void onCreate(Bundle savedInstanceState) {
		setContentView(R.layout.activity_about);
		WebView help = (WebView)findViewById(R.id.helpView);
		help.setWebViewClient(new WebViewClient(){
			@Override
			public boolean shouldOverrideUrlLoading(WebView view, String url) {
				
				if(url.startsWith("http:")){
					Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://ponypark.floccul.us/"));
					mContext.startActivity(browserIntent);
				}else{
					view.loadUrl(url);
				}
				
				return true;
			}
		});

		String helpText = readRawTextFile(R.raw.helpcontent);
		help.loadData(helpText, "text/html; charset=utf-8", "utf-8");
	}

	private String readRawTextFile(int id) {
		InputStream inputStream = mContext.getResources().openRawResource(id);
		InputStreamReader in = new InputStreamReader(inputStream);
		BufferedReader buf = new BufferedReader(in);
		String line;
		StringBuilder text = new StringBuilder();
		try {
			while (( line = buf.readLine()) != null) 
				text.append(line);
		} catch (IOException e) {
			return null;
		}
		return text.toString();
	}
}
