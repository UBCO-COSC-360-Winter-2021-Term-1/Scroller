$(document).ready(() => {
	var blockList = {
		"step-one": {
			fields: {
				fieldNames: ["username"],
				inputFieldPlaceHolders: ["scroller"],
			},
			systemMessageTitle: "Your Username",
			systemMessageDescription: "Our community guidelines say that your username should not be longer than 8 characters and doesn't include any special characters.",
		},
		"step-two": {
			fields: {
				fieldNames: ["email"],
				inputFieldPlaceHolders: ["test@scroller.ca"],
			},
			systemMessageTitle: "Your Email",
			systemMessageDescription: "Our community guidelines say that your email should not be longer than 25 characters."
		},
		"step-three": {
			fields: {
				fieldNames: ["password", "password"],
				inputFieldPlaceHolders: ["Secret Password", "Repeat Secret Password"],
			},
			systemMessageTitle: "Your Password",
			systemMessageDescription: "We recommend to create a password with minimum length of 8 characters, one uppercase letter, one special symbol."
		}
	};

	var goNextFunction = (step) => {
		
		$("form").animate({ opacity: 0}, {
			duration: 1000,
			specialEasing: {
				width: "linear",
				height: "easeOutBounce"
			},
			complete: () =>  {
				$(".inputs").remove();
				$(".system-message").remove();

				var content = "";
	
				switch(step) {
					case "step-two": {
						$(".go-next").removeClass("step-one");
						$(".go-next").addClass("step-two");
						for (var i = 0; i < blockList[step].fields.fieldNames.length; i++) {
							content += `<div class="inputs mb-4"><label for="${blockList[step].fields.fieldNames[i]}Input" class="form-label text-uppercase">${blockList[step].fields.fieldNames[i]}</label><input type="${blockList[step].fields.fieldNames[i]}" id="${blockList[step].fields.fieldNames[i]}RegisterInput" placeholder="${blockList[step].fields.inputFieldPlaceHolders[i]}"></div>`;
						}		
						$("form").css('opacity', 1);
					$("form").before(`${content}`);
						break;
					}
					case "step-three": {
						$(".go-next").removeClass("step-two");
						$(".go-next").addClass("step-final");
						for (var i = 0; i < blockList[step].fields.fieldNames.length / 2; i++) {
							content += `<div class="inputs mb-4"><label for="${blockList["step-three"].fields.fieldNames[i]}Input" class="form-label text-uppercase">${blockList["step-three"].fields.fieldNames[i]}</label><input type="${blockList["step-three"].fields.fieldNames[i]}" id="${blockList["step-three"].fields.fieldNames[i]}RegisterInput" placeholder="${blockList["step-three"].fields.inputFieldPlaceHolders[i]}"></div>
							<div class="inputs mb-4"><label for="${blockList["step-three"].fields.fieldNames[i]}Input" class="form-label text-uppercase">${blockList["step-three"].fields.fieldNames[i]}</label><input type="${blockList["step-three"].fields.fieldNames[i]}" id="${blockList["step-three"].fields.fieldNames[i]}RepeatRegisterInput" placeholder="${blockList["step-three"].fields.inputFieldPlaceHolders[i]}">
							</div>`;
						}
						$("form").css('opacity', 1);
						$("form").before(`${content}`);
						$(".go-next").text("Register");
						break;
					}
					case "step-final": {
						$("form").remove();
						$(".system-message").remove();
						$("h4.mb-5").text("Thanks for Joining Our Community.");
						$("h4.mb-5").after(`<p class="success-register-step-one">We are almost done. Check your email to verify your account creation.</p>
						<div class="btn-container text-uppercase w-100 mt-5 mb-4">
							<a href="/" class="register-take-back-btn">Take me back</a>
						</div>
						`);
					}
				}
				if (step != "step-final") {
					$("form").after(`<div class="system-message mt-3 bg-info-custom d-inline-flex px-3 py-1 fade-in-text mb-4">	
							<div class="align-self-center">
								<i class="fas fa-info"></i>
							</div>
							<div class="ms-3 mt-1 align-self-center">
								<h5>${blockList[step].systemMessageTitle}</h5>
								<p>${blockList[step].systemMessageDescription}</p>
							</div>
						</div>`);
				}
			}
		});
	};

	$(".go-back").click((e) => {
		e.preventDefault();
		window.location.href = "/register";
	});

	/* Register Button */
	$(".go-next").click((e) => {
		e.preventDefault();
		
		switch(e.target.classList[1]) {
			case "step-one": goNextFunction("step-two"); break;
			case "step-two": goNextFunction("step-three"); break;
			case "step-final": goNextFunction("step-final"); break;
		}
	});

	/* Create Thread Title */
	$("#create-thread-name").on('keyup keydown', (e) => {
		var formattedURL = e.target.value;

		var regex = /^[a-zA-Z0-9\s]+$/;
		
		if (!regex.test(formattedURL) && formattedURL != '') {
			$(".create-thread-content .system-message.error p").text("* Title shouldn't contain numbers or special characters.");
			$(".create-thread-content .system-message.error p").removeClass("d-none");
			
			if (e.key != "Backspace") {
				return e.preventDefault();
			}
		}

		if (formattedURL.length > 12) {
			$(".create-thread-content .system-message.error p").text("* Title should contain less than 12 characters.");
			$(".create-thread-content .system-message.error p").removeClass("d-none");
				
			if (e.key != "Backspace") {
				return e.preventDefault();
			}
		}
		
		if (formattedURL.length <= 12)
			$(".create-thread-content .system-message.error p").addClass("d-none");
			
		formattedURL = formattedURL.split("-").join(" ");
		formattedURL = formattedURL.split(" ").join("-").toLowerCase();
		
		$("#create-thread-suggest-url").text(formattedURL);
	});

	$("#create-thread-upload-photo").change((event) => {
		if(event.target.files.length > 0) {
			var src = URL.createObjectURL(event.target.files[0]);
			var preview = $("#profile-thread-create-preview");
			preview.attr('src', src);
			preview.addClass("create-thread-profile-pic");
			preview.removeClass("d-none");
		}
	});

	$("#create-thread-upload-cover").change((event) => {
		if(event.target.files.length > 0) {
			var src = URL.createObjectURL(event.target.files[0]);
			var preview = $("#profile-thread-create-cover");
			preview.attr('src', src);
			preview.removeClass("d-none");
		}
	});
});