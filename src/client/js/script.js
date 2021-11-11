$(document).ready(() => {
	var username = "";
	var email = "";
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
							<div class="inputs mb-4"><label for="${blockList["step-three"].fields.fieldNames[i]}Input" class="form-label text-uppercase">${blockList["step-three"].fields.inputFieldPlaceHolders[i + 1]}</label><input type="${blockList["step-three"].fields.fieldNames[i]}" id="${blockList["step-three"].fields.fieldNames[i]}RepeatRegisterInput" placeholder="${blockList["step-three"].fields.inputFieldPlaceHolders[i + 1]}">
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
						</div><div class="system-message bg-danger d-inline-flex px-3 py-2 fade-in-text w-100 d-none">
						
						<div class="align-self-center">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
						<div class="ms-3 mt-1 align-self-center">
							<h5>Oops...</h5>
							<p></p>
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
			case "step-one": {
				if ($("#userNameRegisterInput").val().length < 3 || $("#userNameRegisterInput").val().length > 8) {
					$(".system-message.bg-danger div:last-child p").text("Username should be between 3 to 8 characters.");
			
					$(".system-message.bg-danger").removeClass("d-none");
					break;	
				}

				var regex = /^[a-z0-9\s]+$/;
				if (!regex.test($("#userNameRegisterInput").val())) {
					$(".system-message.bg-danger div:last-child p").text("Only small letters and numbers are allowed.");
			
					$(".system-message.bg-danger").removeClass("d-none");
					break;
				}

				$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
					username: $("#userNameRegisterInput").val()
				}).done(function (result) {
					if (parseInt(result["response"]) === 200) {
						$(".system-message.bg-danger").addClass("d-none");
						username = $("#userNameRegisterInput").val();
						goNextFunction("step-two");
						
					} else if (parseInt(result["response"]) === 403) {
						$(location).prop('href', '/');
					} else {
					
						$(".system-message.bg-danger div:last-child p").text(result["data"]["message"]);
		
						$(".system-message.bg-danger").removeClass("d-none");
					}
				});
				break;
			};
			case "step-two": {
				var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				
				if (!filter.test($("#emailRegisterInput").val())) {
					$(".system-message.bg-danger div:last-child p").text("Email format is not valid.");
					$(".system-message.bg-danger").removeClass("d-none");
					return;
				}

				$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
					email: $("#emailRegisterInput").val()
				}).done(function (result) {
					if (parseInt(result["response"]) === 200) {
						$(".system-message.bg-danger").addClass("d-none");
						email = $("#emailRegisterInput").val();
						goNextFunction("step-three");
						
					} else if (parseInt(result["response"]) === 403) {
						$(location).prop('href', '/');
					} else {
					
						$(".system-message.bg-danger div:last-child p").text(result["data"]["message"]);
		
						$(".system-message.bg-danger").removeClass("d-none");
					}
				});
				break;
			};
			case "step-final": {

				var filter = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{8,}$/;
				if (!filter.test($("#passwordRegisterInput").val())) {
					$(".system-message.bg-danger div:last-child p").text("Password must be minimum 8 characters, one uppercase letter, and one special symbol.");
					$(".system-message.bg-danger").removeClass("d-none");
					return;
				}

				if ($("#passwordRegisterInput").val() != $("#passwordRepeatRegisterInput").val()) {
					$(".system-message.bg-danger div:last-child p").text("Passwords don't match.");
					$(".system-message.bg-danger").removeClass("d-none");
					return;
				}
				
				$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
					username: username,
					email: email,
					password: $("#passwordRegisterInput").val(),
					repeatpassword: $("#passwordRepeatRegisterInput").val()
				}).done(function (result) {
					if (parseInt(result["response"]) === 200) {
						$(".system-message.bg-danger").addClass("d-none");
						
						goNextFunction("step-final");
						
					} else if (parseInt(result["response"]) === 403) {
						$(location).prop('href', '/');
					} else {
					
						$(".system-message.bg-danger div:last-child p").text(result["data"]["message"]);
		
						$(".system-message.bg-danger").removeClass("d-none");
					}
				});

				break;
			}
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

	// Create Thread
	$(".btn-create-thread").click((e) =>{
		e.preventDefault();
		var formattedURL = $("#create-thread-name").val();
		var regex = /^[a-zA-Z0-9\s]+$/;
		
		if (!regex.test(formattedURL)) {
			$("span.error-message").text("Title shouldn't contain numbers or special characters.");
			$(".system-message").removeClass("d-none");
			return;
		}

		if (formattedURL.length > 12) {
			$("span.error-message").text("Title should contain less than 12 characters.");
			$(".system-message").removeClass("d-none");
			return;
		}
		
		if (formattedURL.length <= 12)
			$(".system-message").addClass("d-none");
			
		var URL = formattedURL.split("-").join(" ");
		URL = formattedURL.split(" ").join("-").toLowerCase();

		var form_data = new FormData();
		form_data.append("threadBackground", $("#create-thread-upload-cover").get(0).files[0]);
		form_data.append("threadProfile", $("#create-thread-upload-photo").get(0).files[0]);
		form_data.append("title", formattedURL);
		form_data.append("url", URL);

		$.ajax({
			url: `http://${$(location).attr('host')}/server/middlewares/ThreadMiddleware.class.php`,
			type: 'POST',
			data: form_data,
			contentType: false,
			cache: false,
			processData: false,
			success: (result) => {
				if (parseInt(result["response"]) === 200) {
					$(".system-message").addClass("d-none");
					$(location).prop('href', `/thread/${URL}`);
				} else if (parseInt(result["response"]) === 403) {
					$(location).prop('href', '/');
				} else {
					$(".system-message").removeClass("d-none");
					$(".system-message div:last-child p").text(result["data"]["message"]);
				}
			},
		});
	});																						

	/* Create Post Title */
	$("#create-post-name").on("keyup, keydown", (e) => {
		var postTitle = e.target.value;
		var regex = /^[a-zA-Z0-9\s]+$/;
		if (!regex.test(postTitle) && postTitle != '') {
			$(".create-post-content .system-message.error p").text("* Title shouldn't contain numbers or special characters.");
			$(".create-post-content .system-message.error p").removeClass("d-none");
			
			if (e.key != "Backspace") {
				return e.preventDefault();
			}
		}

		if (postTitle.length > 15) {
			$(".create-post-content .system-message.error p").text("* Title should contain less than 15 characters.");
			$(".create-post-content .system-message.error p").removeClass("d-none");
				
			if (e.key != "Backspace") {
				return e.preventDefault();
			}
		}
		
		if (postTitle.length <= 15)
			$(".create-post-content .system-message.error p").addClass("d-none");
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

	$("#create-post-image").change((event) => {
		if(event.target.files.length > 0) {
			var src = URL.createObjectURL(event.target.files[0]);
			var preview = $("#profile-post-create-preview");
			preview.attr('src', src);
			preview.addClass("create-post-cover-pic");
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

	$(".search-input-box").on("keyup", (e) => {
		return (e.target.value.length === 0) ? $(".search-result-query").text("All") : $(".search-result-query").text(e.target.value);
	});

	$("#posts-option, #threads-option, #comments-option").change((e) => {
		if ($("#posts-option").prop("checked") && $("#threads-option").prop("checked") && $("#comments-option").prop("checked")) {
			$(".search-result-options").text("Posts, Threads, Comments");
		} else if ($("#posts-option").prop("checked") && !$("#threads-option").prop("checked") && !$("#comments-option").prop("checked")) {
			$(".search-result-options").text("Posts");
		} else if (!$("#posts-option").prop("checked") && $("#threads-option").prop("checked") && !$("#comments-option").prop("checked")) {
			$(".search-result-options").text("Threads");
		} else if (!$("#posts-option").prop("checked") && !$("#threads-option").prop("checked") && $("#comments-option").prop("checked")) {
			$(".search-result-options").text("Comments");
		} else if ($("#posts-option").prop("checked") && $("#threads-option").prop("checked") && !$("#comments-option").prop("checked")) {
			$(".search-result-options").text("Posts, Threads");
		} else if ($("#posts-option").prop("checked") && !$("#threads-option").prop("checked") && $("#comments-option").prop("checked")) {
			$(".search-result-options").text("Posts, Comments");
		} else if (!$("#posts-option").prop("checked") && $("#threads-option").prop("checked") && $("#comments-option").prop("checked")) {
			$(".search-result-options").text("Threads, Comments");
		} else if ($("#posts-option").prop("checked") && !$("#threads-option").prop("checked") && $("#comments-option").prop("checked")) {
			$(".search-result-options").text("Posts, Comments");
		} else {
			$(".search-result-options").text("Posts, Threads, Comments");
		}
	});


	$("#profile-settings-picture").change((event) => {
		if(event.target.files.length > 0) {
			var src = URL.createObjectURL(event.target.files[0]);
			var preview = $(".account-updated-profile-picture");
			preview.attr('src', src);
		}
	});

	$(".login-btn-login").click((event) => {
		event.preventDefault();

		if ($("#emailLoginInput").val().length === 0 || $("#passwordLoginInput").val().length === 0) {
			$(".form-login .system-message div:last-child p").text("Fields \"Email\" and \"Password\" shouldn't be empty");
			$(".form-login .system-message").removeClass("d-none");
			return;
		}

		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		
		if (!filter.test($("#emailLoginInput").val())) {
			$(".form-login .system-message div:last-child p").text("Email format is not valid.");
			$(".form-login .system-message").removeClass("d-none");
			return;
		}

		if ($("#passwordLoginInput").val().length < 6) {
			$(".form-login .system-message div:last-child p").text("Password should be longer than 5 letters.");
			$(".form-login .system-message").removeClass("d-none");
			return;
		}

		$.ajax({
			url: `http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`,
			dataType: "json",
			contentType: "application/json;charset=utf-8",
			type: "GET",
			data: {
				email: $("#emailLoginInput").val(), 
				password: $("#passwordLoginInput").val()
			},
			success: function (result) {
				if (parseInt(result["response"]) === 200 || parseInt(result["response"]) === 403) {
					$(location).prop('href', '/');
					return;
				}
			
				$(".form-login .system-message div:last-child p").text(result["data"]["message"]);
				$(".form-login .system-message").removeClass("d-none");
				return;
			}
		});

	});

	$(".register-confirm-final").click((e) => {
		e.preventDefault();
		var code = $("#codeRegisterConfirmInput").val();

		if (!$.isNumeric(code)) {
			
			$(".system-message div:last-child p").text("Confirmation code must be numeric.");
			$(".system-message").removeClass("d-none");
			return;
		}

		if (parseInt(code) < 1000 || parseInt(code) > 99999) {
			$(".system-message div:last-child p").text("Invalid code.");
			$(".system-message").removeClass("d-none");
			return;
		}

		$(".system-message.bg-danger").addClass("d-none");
		var token = new URLSearchParams(window.location.search).get('token');

		$.post(`http://${$(location).attr('host')}/server/middlewares/TokenMiddleware.class.php`, {
			code: code,
			token: token
		}).done(function (result) {
			if (parseInt(result["response"]) === 200 || parseInt(result["response"]) === 403) {
				$(location).prop('href', '/');
				return;
			}
		
			$(".system-message div:last-child p").text(result["data"]["message"]);
			$(".system-message").removeClass("d-none");
			return;
		});
	});

	$(".recover-confirm-final").click((e) => {
		e.preventDefault();
		var filter = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{8,}$/;
		if (!filter.test($("#passwordNewInput").val())) {
			$(".system-message.bg-danger div:last-child p").text("Password must be minimum 8 characters, one uppercase letter, and one special symbol.");
			$(".system-message.bg-danger").removeClass("d-none");
			return;
		}

		if ($("#passwordNewInput").val() != $("#passwordNewConfirmInput").val()) {
			$(".system-message.bg-danger div:last-child p").text("Passwords don't match.");
			$(".system-message.bg-danger").removeClass("d-none");
			return;
		}

		$(".system-message.bg-danger").addClass("d-none");

		var token = new URLSearchParams(window.location.search).get('token');

		$.post(`http://${$(location).attr('host')}/server/middlewares/TokenMiddleware.class.php`, {
			password: $("#passwordNewInput").val(),
			repeatpassword: $("#passwordNewConfirmInput").val(),
			token: token
		}).done(function (result) {
			if (parseInt(result["response"]) === 200 || parseInt(result["response"]) === 403) {
				$("#passwordNewInput").val("");
				$("#passwordNewConfirmInput").val("");
				$(".system-message div h5").text("Success");
				$(".system-message div:last-child p").text("Password has been recovered.");
				$(".system-message").removeClass("d-none");
				$(".system-message").removeClass("bg-danger");
				$(".system-message").addClass("bg-success");
				setTimeout(function(){ window.location = "/"; }, 3000);
				return;
			}
		
			$(".system-message div:last-child p").text(result["data"]["message"]);
			$(".system-message").removeClass("d-none");
			return;
		});
		
	});

	$(".recover-confirm").click((e) => {
		e.preventDefault();

		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!filter.test($("#emailConfirmRecoverInput").val())) {
			$(".system-message.bg-danger div:last-child p").text("Email format is not valid.");
			$(".system-message.bg-danger").removeClass("d-none");
			return;
		}

		$(".system-message.bg-danger").addClass("d-none");

		$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
			email: $("#emailConfirmRecoverInput").val(),
			state: false
		}).done(function (result) {
			if (parseInt(result["response"]) === 200 || parseInt(result["response"]) === 403) {
				
				$(".system-message div h5").text("Success");
				$(".system-message div:last-child p").text("Recovery email has been sent to your email address.");
				$(".system-message").removeClass("d-none");
				$(".system-message").removeClass("bg-danger");
				$(".system-message").addClass("bg-success");
				return;
			}
		
			$(".system-message div:last-child p").text(result["data"]["message"]);
			$(".system-message").removeClass("d-none");
			return;
		});
	});
	/* Check if the URL is a Valid YouTube URL */
	// $("#create-post-text-url").on("change", (e) => {
    //     var youtubeURL = e.target.value;
    //     var regex = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
    //     if (!regex.test(youtubeURL) && youtubeURL != '') {
    //         $(".create-post-content-post-url .system-message.error p").text("* Please enter a valid YouTube URL.");
    //         $(".create-post-content-post-url .system-message.error p").removeClass("d-none");

    //         if (e.key != "Backspace") {
    //             return e.preventDefault();
    //         }
    //     }
    // });
});