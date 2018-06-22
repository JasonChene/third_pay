package com.ekapay.util;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class HttpUtil {

	public static String get(String url){
		  String result ="";
		  try{
					URL getUrl = new URL(url);
					HttpURLConnection urlConnection  = (HttpURLConnection)getUrl.openConnection();
					BufferedReader in  = new BufferedReader(
				            new InputStreamReader(
				            		urlConnection.getInputStream()));
					
					result = in.readLine().toString();
				}catch(Exception e){
					e.printStackTrace();
				}
		    return result;
	}
}
