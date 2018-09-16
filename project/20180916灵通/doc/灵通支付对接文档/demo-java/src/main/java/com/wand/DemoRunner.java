package com.wand;

import org.springframework.boot.Banner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.builder.SpringApplicationBuilder;
import org.springframework.boot.web.servlet.support.SpringBootServletInitializer;
import org.springframework.scheduling.annotation.EnableScheduling;


@SpringBootApplication
@EnableScheduling
public class DemoRunner extends SpringBootServletInitializer {

	@Override
	protected SpringApplicationBuilder configure(SpringApplicationBuilder application) {
		return application.sources(DemoRunner.class);
	}

	public static void main(String[] args) throws Exception {
		SpringApplication app = new SpringApplication(DemoRunner.class);
		app.setBannerMode(Banner.Mode.OFF);
		app.run(args);
	}

}