package com.wand.restful.web;

import org.springframework.boot.web.servlet.error.ErrorController;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class WebErrorController implements ErrorController {

	private static final String ERROR_PATH = "error";

	@RequestMapping(value = ERROR_PATH)
	public String handleError() {
		return ERROR_PATH;
	}

	@Override
	public String getErrorPath() {
		return ERROR_PATH;
	}


}