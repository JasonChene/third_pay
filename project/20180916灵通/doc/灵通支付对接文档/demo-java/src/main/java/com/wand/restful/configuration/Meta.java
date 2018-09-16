package com.wand.restful.configuration;

public class Meta {
	private String name;
	private String content;

	public Meta(String string, String string2) {
		this.name = string;
		this.content = string2;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}
}
