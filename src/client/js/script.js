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

				var regex = /^[a-z0-9]+$/;
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
			$(".create-thread-content .system-message.error p").text("Title shouldn't contain numbers or special characters.");
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

	/* Create Thread */
	$(".btn-create-thread").click((e) =>{
		e.preventDefault();
		var formattedURL = $("#create-thread-name").val();
		var regex = /^[a-zA-Z0-9\s]+$/;
		
		if (!regex.test(formattedURL)) {
			$("span.error-message").text("Title shouldn't contain special characters.");
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
	
	/* Create Post */
	$(".btn-create-post").click((e) => {
		e.preventDefault();
		var postTitle = $("#create-post-name").val();
		var youtubeLink = $("#create-post-text-url").val();
		var titleRegex = /^[a-zA-Z0-9\s]+$/;
		var youtubeRegex = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/

		// Title validation
		if (postTitle.length == 0) {
			$("span.error-message").text("You cannot have an empty title.");
			$(".system-message").removeClass("d-none");
			return;
		} else if (postTitle.length < 5 && !titleRegex.test(postTitle)) {
			$("span.error-message").text("The post title should be at least 5 characters and cannot contain any special characters.");
			$(".system-message").removeClass("d-none");
			return;
		} else if (postTitle.length > 75 && !titleRegex.test(postTitle)) {
			$("span.error-message").text("The post title should be less than 75 characters and cannot contain any special characters.");
			$(".system-message").removeClass("d-none");
			return;
		} else if (postTitle.length < 5) {
			$("span.error-message").text("The post title should be at least 5 characters.");
			$(".system-message").removeClass("d-none");
			return;
		} else if (postTitle.length > 75) { 
			$("span.error-message").text("The post title should be less than 75 characters.");
			$(".system-message").removeClass("d-none");
			return;
		} else if (!titleRegex.test(postTitle)) {
			$("span.error-message").text("The post title shouldn't contain special characters.");
			$(".system-message").removeClass("d-none");
			return;
		} else {
			$(".system-message").addClass("d-none");
		}

		if (youtubeLink.length > 0 && !youtubeRegex.test(youtubeLink)) {
			$("span.error-message").text("The YouTube link is invalid.");
			$(".system-message").removeClass("d-none");
			return;
		}

		if ($("#create-post-image").get(0).files.length == 0 && youtubeLink.length == 0 && $("#create-post-text").val().length == 0) {
			$("span.error-message").text("Post Body Text cannot remain empty if you are not uploading an image or a YouTube link.");
			$(".system-message").removeClass("d-none");
			return;
		} 
		
		// get current url
		var threadUrl = window.location.pathname.split("/")[2];

		var form_data = new FormData();
		form_data.append("postTitle", postTitle);
		form_data.append("postBody", $("#create-post-text").val());
		form_data.append("postImage", $("#create-post-image").get(0).files[0]);
		form_data.append("postYoutubeLink", youtubeLink);
		form_data.append("threadUrl", threadUrl);

		$.ajax({
			url: `http://${$(location).attr('host')}/server/middlewares/PostMiddleware.class.php`,
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

		if (postTitle.length > 75) {
			$(".create-post-content .system-message.error p").text("* Title should contain less than 75 characters.");
			$(".create-post-content .system-message.error p").removeClass("d-none");
				
			if (e.key != "Backspace") {
				return e.preventDefault();
			}
		}
		
		if (postTitle.length <= 75)
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

	$(".search-page-input-box").on("input", (e) => {

		if (e.target.value.length === 0) {
			$(".search-result-query").text("All");
		} else {
			$(".search-result-query").text(e.target.value);
		}

		if (($("#threads-option").prop("checked") || !$("#threads-option").prop("checked")) && !$("#posts-option").prop("checked") && !$("#comments-option").prop("checked")) {
			$.ajax({
				url: `http://${$(location).attr('host')}/server/middlewares/ThreadMiddleware.class.php`,
				dataType: "json",
				contentType: "application/json;charset=utf-8",
				type: "GET",
				data: {
					query: e.target.value,
					threadSearch: true
				},
				success: function (result) {
					$(".search-results-block").html("");
					if (parseInt(result["response"]) !== 400 && !jQuery.isEmptyObject(result)) {
						$.each(result, (_, element) => {
							var result = `<div class="search-result thread mb-3 p-3 bg-white">`;
							var url = `http://${$(location).attr('host')}/server/uploads/thread_backgrounds/${element['thread_background_picture']}`;
							result += `<div class="img-thread-background ${element['thread_url']}"></div>`;
							result += `<div class="img-thread-search-cover d-flex">`;
							result += `<img id="img-thread-profile" src="http://${$(location).attr('host')}/server/uploads/thread_profile/${element['thread_cover_picture']}" alt="thread_profile_picture" class="img-thumbnail">`;
							result += `<div>`;
							result += `<h3 class="">${element['thread_title']}</h3>`;
							result += `<a href="/t/${element['thread_url']}" class="thread-sm-link">t/${element['thread_url']}</a>`;
							result += `</div></div></div>`;
							$(".search-results-block").append(result);
							$(`.${element['thread_url']}`).css('backgroundImage','url("' + url +'")'); 
						});
						return;
					}
					$(".search-results-block").html(`<div class="system-message error-content text-center bg-none p-3 mt-2"><img src="http://${$(location).attr('host')}/client/img/error-empty-content.svg" alt="no content available" class="d-block no-content mx-auto"><p class="pt-5">It's a little bit lonely here. We couldn't find anything...</p></div>`);
					return;
				}
			});
		}
	});

	$("#posts-option, #threads-option, #comments-option").change((e) => {
		
		if (!$("#threads-option").prop("checked") && $("#posts-option").prop("disabled") && $("#comments-option").prop("disabled")) {
			$("#posts-option").prop("disabled", false);
			$("#threads-option").prop("disabled", false);
			$("#comments-option").prop("disabled", false);
		} else if ($("#threads-option").prop("checked") && !$("#posts-option").prop("disabled") && !$("#comments-option").prop("disabled")) {
			$("#posts-option").prop("disabled", true);
			$("#threads-option").prop("disabled", false);
			$("#comments-option").prop("disabled", true);
			$("#posts-option").prop("checked", false);
			$("#comments-option").prop("checked", false);
			$(".search-result-options").text("Threads");
		} else if (!$("#threads-option").prop("checked") && $("#posts-option").prop("checked") && !$("#comments-option").prop("checked")) {
			$(".search-result-options").text("Posts");
		} else if (!$("#threads-option").prop("checked") && !$("#posts-option").prop("checked") && $("#comments-option").prop("checked")) {
			$(".search-result-options").text("Comments");
		} else if (!$("#threads-option").prop("checked") && $("#posts-option").prop("checked") && $("#comments-option").prop("checked")) {
			$(".search-result-options").text("Posts, Comments");
		} else if (!$("#threads-option").prop("checked") && !$("#posts-option").prop("checked") && !$("#comments-option").prop("checked")) {
			$(".search-result-options").text("Threads");
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
				setTimeout(() => { window.location = "/"; }, 3000);
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

	$("#profile-settings-picture").change( (e) => {
		e.preventDefault();
		var form_data = new FormData();
		form_data.append("img_profile", $("#profile-settings-picture").get(0).files[0]);
		$.ajax({
			url: `http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`,
			type: 'POST',
			data: form_data,
			contentType: false,
			cache: false,
			processData: false,
			success: function (result) {
				if (parseInt(result["response"]) === 200 || parseInt(result["response"]) === 403) {
				
					$(".system-message-content i").removeClass("fa-ban");
					$(".system-message-content i").addClass("fa-check");
					$(".system-message div h5").text("Success");
					$(".system-message div:last-child p").text("Image has been updated");
					$(".system-message").removeClass("d-none");
					$(".system-message").removeClass("bg-danger");
					$(".system-message").addClass("bg-success");
					setTimeout(() => { window.location = "/account/edit"; }, 3000);
					return;
				}
			
				$(".reason").text(result["data"]["message"]);
				$(".system-message").removeClass("d-none");
				return;
			},
		});
	});

	$(".btn-account-update").click((e) => {

		e.preventDefault();

		if ($("#profile-settings-username").val().length == 0 && $("#profile-settings-oldpassword").val().length == 0 &&  $("#profile-settings-newpassword").val().length == 0) {
			$(".system-message.bg-danger div:last-child p").text("Fields are empty. Nothing to update.");
	
			$(".system-message.bg-danger").removeClass("d-none");
			
			return;
		}

		if (($("#profile-settings-username").val().length < 3 || $("#profile-settings-username").val().length > 8) && $("#profile-settings-oldpassword").val().length == 0 &&  $("#profile-settings-newpassword").val().length == 0) {
			$(".system-message.bg-danger div:last-child p").text("Username should be between 3 to 8 characters.");
	
			$(".system-message.bg-danger").removeClass("d-none");
			
			return;	
		}
		
		var regex = /^[a-z0-9]+$/;

		if (!regex.test($("#profile-settings-username").val()) && $("#profile-settings-oldpassword").val().length == 0 &&  $("#profile-settings-newpassword").val().length == 0) {
			$(".system-message.bg-danger div:last-child p").text("Only small letters and numbers are allowed.");
	
			$(".system-message.bg-danger").removeClass("d-none");
			
			return;
		}
		
		if (regex.test($("#profile-settings-username").val()) && 
				($("#profile-settings-username").val().length >= 3 || $("#profile-settings-username").val().length <= 8) &&
				$("#profile-settings-oldpassword").val().length == 0 &&  $("#profile-settings-newpassword").val().length == 0) {
			
			$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
				aUsername: $("#profile-settings-username").val()
			}).done(function (result) {
				if (parseInt(result["response"]) === 200) {
					$(".system-message-content i").removeClass("fa-ban");
					$(".system-message-content i").addClass("fa-check");
					$(".system-message div h5").text("Success");
					$(".system-message div:last-child p").text("Account details has been updated.");
					$(".system-message").removeClass("d-none");
					$(".system-message").removeClass("bg-danger");
					$(".system-message").addClass("bg-success");
					$("#profile-settings-username").val("");
					setTimeout(() => { window.location = "/account/edit"; }, 3000);
					return;
				} else if (parseInt(result["response"]) === 403) {
					$(".system-message div:last-child p").text("Invalid action.");
					$(".system-message").removeClass("d-none");
					return;
				}
			
				$(".system-message div:last-child p").text(result["data"]["message"]);
				$(".system-message").removeClass("d-none");
				return;
			});
		} 
		
		if ($("#profile-settings-username").val().length === 0) {
			var filter = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{8,}$/;
			if (!filter.test($("#profile-settings-oldpassword").val())) {
				$(".system-message.bg-danger div:last-child p").text("Password must be minimum 8 characters, one uppercase letter, and one special symbol.");
				$(".system-message.bg-danger").removeClass("d-none");
				return;
			}

			if (!filter.test($("#profile-settings-newpassword").val())) {
				$(".system-message.bg-danger div:last-child p").text("Password must be minimum 8 characters, one uppercase letter, and one special symbol.");
				$(".system-message.bg-danger").removeClass("d-none");
				return;
			}

			$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
				aOldPassword: $("#profile-settings-oldpassword").val(),
				aNewPassword: $("#profile-settings-newpassword").val()
			}).done(function (result) {
				if (parseInt(result["response"]) === 200) {
					$(".system-message-content i").removeClass("fa-ban");
					$(".system-message-content i").addClass("fa-check");
					$(".system-message div h5").text("Success");
					$(".system-message div:last-child p").text("Account details has been updated.");
					$(".system-message").removeClass("d-none");
					$(".system-message").removeClass("bg-danger");
					$(".system-message").addClass("bg-success");
					$("#profile-settings-oldpassword").val("");
					$("#profile-settings-newpassword").val("");
					setTimeout(() => { window.location = "/account/edit"; }, 3000);
					return;
				} else if (parseInt(result["response"]) === 403) {
					$(".system-message div:last-child p").text("Invalid action.");
					$(".system-message").removeClass("d-none");
					return;
				}
			
				$(".system-message div:last-child p").text(result["data"]["message"]);
				$(".system-message").removeClass("d-none");
				return;
			});
		}

		if ($("#profile-settings-username").val().length != 0 && $("#profile-settings-oldpassword").val().length != 0 &&  $("#profile-settings-newpassword").val().length != 0) {

			if (!regex.test($("#profile-settings-username").val()) || ($("#profile-settings-username").val().length < 3 || $("#profile-settings-username").val().length > 8)) {
				$(".system-message.bg-danger div:last-child p").text("Username should be between 3 to 8 characters with small letters or numbers.");
				$(".system-message.bg-danger").removeClass("d-none");
				return;
			}

			var filter = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{8,}$/;
			if (!filter.test($("#profile-settings-oldpassword").val())) {
				$(".system-message.bg-danger div:last-child p").text("Password must be minimum 8 characters, one uppercase letter, and one special symbol.");
				$(".system-message.bg-danger").removeClass("d-none");
				return;
			}

			if (!filter.test($("#profile-settings-newpassword").val())) {
				$(".system-message.bg-danger div:last-child p").text("Password must be minimum 8 characters, one uppercase letter, and one special symbol.");
				$(".system-message.bg-danger").removeClass("d-none");
				return;
			}

			$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
				aUsername: $("#profile-settings-username").val(),
				aOldPassword: $("#profile-settings-oldpassword").val(),
				aNewPassword: $("#profile-settings-newpassword").val()
			}).done(function (result) {
				if (parseInt(result["response"]) === 200) {
					$(".system-message-content i").removeClass("fa-ban");
					$(".system-message-content i").addClass("fa-check");
					$(".system-message div h5").text("Success");
					$(".system-message div:last-child p").text("Account details has been updated.");
					$(".system-message").removeClass("d-none");
					$(".system-message").removeClass("bg-danger");
					$(".system-message").addClass("bg-success");
					$("#profile-settings-username").val("");
					$("#profile-settings-oldpassword").val("");
					$("#profile-settings-newpassword").val("");
					setTimeout(() => { window.location = "/account/edit"; }, 3000);
					return;
				} else if (parseInt(result["response"]) === 403) {
					$(".system-message div:last-child p").text("Invalid action.");
					$(".system-message").removeClass("d-none");
					return;
				}
			
				$(".system-message div:last-child p").text(result["data"]["message"]);
				$(".system-message").removeClass("d-none");
				return;
			});
		}
	});

	$(".btn-account-delete").click((e) => {
		e.preventDefault();

		$.post(`http://${$(location).attr('host')}/server/middlewares/UserMiddleware.class.php`, {
				deleteAccount: true
			}).done(function (result) {
				if (parseInt(result["response"]) === 200) {
					$(".system-message-content i").removeClass("fa-ban");
					$(".system-message-content i").addClass("fa-check");
					$(".system-message div h5").text("Success");
					$(".system-message div:last-child p").text("Account has been disabled.");
					$(".system-message").removeClass("d-none");
					$(".system-message").removeClass("bg-danger");
					$(".system-message").addClass("bg-success");
					setTimeout(() => { window.location = "/logout"; }, 3000);
					return;
				} else if (parseInt(result["response"]) === 403) {
					$(".system-message div:last-child p").text("Invalid action.");
					$(".system-message").removeClass("d-none");
					return;
				}
			
				$(".system-message div:last-child p").text(result["data"]["message"]);
				$(".system-message").removeClass("d-none");
				return;
			});
	});


	$(".search-input-box").on("input", (e) => {
	
		var regex = /^[a-z0-9]+$/;

		if (!regex.test($(".search-input-box").val()) && $(".search-input-box").val().length != 0) {
			$(".admin-search-users-table").addClass("d-none");
			return;
		}

		$(".admin-search-users-table").removeClass("d-none");

		$.get(`http://${$(location).attr('host')}/server/middlewares/AdminMiddleware.class.php`, {
			query: $(".search-input-box").val()
		}).done(function (result) {
			if (parseInt(result["response"]) === 200) {
				$("tbody").html("");
				$.each(result["data"], (_, value) => {
					
					$.each(value, function(_ , element) {

						var result = `<tr><td scope="row">${element["id"]}</td><td><a href="/account/${element["id"]}">${element["username"]}</a></td><td>${element["regdate"]}</td><td>${element["email"]}</td>`;
						
						result += (element["is_email_confirmed"] == 1) ? `<td><span class="bg-success rounded p-1 text-light">Confirmed</span></td>` : `<td><span class="bg-danger rounded p-1 text-light">Not Confirmed</span></td>`;
			
						result += (element["is_admin"] == 1) ? "<td><span class=\"admin-user-status true p-1 rounded text-light\">Yes</span></td>" : "<td><span class=\"admin-user-status false p-1 rounded text-light\">No</span></td>";

						result += (element["is_account_disabled"] == 1) ? `<td><button class="admin-users-act-block" data-id=\"${element["id"]}\" data-status=\"unblock\">Unblock</button><br>` : `<td><button class="admin-users-act-block" data-id=\"${element["id"]}\" data-status=\"block\">Block</button><br>`;

						result += (element["is_admin"] == 1) ? `<button class="admin-users-act-admin" data-id=\"${element["id"]}\" data-status=\"demote-admin\">Demote Admin</button>` : `<button class="admin-users-act-admin" data-id=\"${element["id"]}\" data-status=\"new-admin\">Make Admin</button>`;

						result += `</td></tr>`;
					
						$("tbody").append(result);
					});
				});
				$(".admin-search-users-table").removeClass("d-none");
				return;
			} else if (parseInt(result["response"]) === 403) {
				$("tbody").html("");
				$(".admin-search-users-table").addClass("d-none");
				return;
			} else if (parseInt(result["response"]) === 400) {
				$("tbody").html("");
				$(".admin-search-users-table").addClass("d-none");
				return;
			}
			$("tbody").html("");
			$.each(result, (_, element) => {
				var result = `<tr><td scope="row">${element["id"]}</td><td><a href="/account/${element["id"]}">${element["username"]}</a></td><td>${element["regdate"]}</td><td>${element["email"]}</td>`;
					
				result += (element["is_email_confirmed"] == 1) ? `<td><span class="bg-success rounded p-1 text-light">Confirmed</span></td>` : `<td><span class="bg-danger rounded p-1 text-light">Not Confirmed</span></td>`;
	
				result += (element["is_admin"] == 1) ? "<td><span class=\"admin-user-status true p-1 rounded text-light\">Yes</span></td>" : "<td><span class=\"admin-user-status false p-1 rounded text-light\">No</span></td>";

				result += (!element["is_account_disabled"] == 1) ? `<td><button class="admin-users-act-block" data-id=\"${element["id"]}\" data-status=\"unblock\">Unblock</button><br>>` : `<td><button class="admin-users-act-block" data-id=\"${element["id"]}\" data-status=\"block\">Block</button><br>`;

				result += (element["is_admin"] == 1) ? `<button class="admin-users-act-admin" data-id=\"${element["id"]}\" data-status=\"demote-admin\">Demote Admin</button>` : `<button class="admin-users-act-admin" data-id=\"${element["id"]}\" data-status=\"new-admin\">Make Admin</button>`;

				result += `</td></tr>`;
			
				$("tbody").append(result)
			});
			$(".admin-search-users-table").removeClass("d-none");
			return;
		});
	});

	$(document).on("click",".admin-users-act-block", (e) => {
		e.preventDefault();

		var action = ($(".admin-users-act-block").data("status") == "block") ? true : false;
		
		$.post(`http://${$(location).attr('host')}/server/middlewares/AdminMiddleware.class.php`, {
			action: action,
			userId: $(".admin-users-act-block").data("id")
		}).done(function (_) {
			setTimeout(() => { window.location = "/admin/users"; }, 1000);
			return;
		});

	});

	$(document).on("click",".admin-users-act-admin", (e) => {
		e.preventDefault();

		var action = ($(".admin-users-act-admin").data("status") === "new-admin") ? true : false;

		$.post(`http://${$(location).attr('host')}/server/middlewares/AdminMiddleware.class.php`, {
			actionAdmin: action,
			userId: $(".admin-users-act-admin").data("id")
		}).done(function (result) {
			setTimeout(() => { window.location = "/admin/users"; }, 1000);
			return;
		});

	});


	$(".search-input-thread").on("input", (e) => {
		var regex = /^[a-zA-Z0-9\s]+$/;

		if (!regex.test($(".search-input-thread").val()) && $(".search-input-thread").val().length != 0) {
			$(".admin-search-threads-table ").addClass("d-none");
			return;
		}

		$(".admin-search-threads-table").removeClass("d-none");

		$.get(`http://${$(location).attr('host')}/server/middlewares/AdminMiddleware.class.php`, {
			queryThread: $(".search-input-thread").val()
		}).done(function (result) {
			if (parseInt(result["response"]) === 200) {
				$("tbody").html("");
				$.each(result["data"], (_, value) => {
					
					$.each(value, function(_ , element) {
						
						var result = `<tr><td scope='row'>${element['thread_id']}</td>`;
						result += `<td>${element['thread_title']}</td>`;
						result += `<td><a href=\"/t/${element['thread_url']}\">/t/${element['thread_url']}/</a></td>`;
						result += `<td>${element['created_date']}</td>`;
						result += `<td><a href=\"/account/${element['ownerId']}\">${element['ownerName']}</a></td>`;
						if (element['is_locked'] != 1 && element['is_deleted'] != 1)
							result += `<td><span class=\"bg-success rounded p-1 text-light\">Active</span></td>`;
						else if (element['is_locked'] == 1 && element['is_deleted'] != 1)
							result += `<td><span class=\"bg-warning rounded p-1 text-light\">Hidden</span></td>`;
						else 
							result += `<td><span class=\"bg-danger rounded p-1 text-light\">Deleted</span></td>`;
						
						result += `<td>${element['members']}</td><td>`;

						if (element['is_locked'] != 1 && element['is_deleted'] != 1)
							result += `<button class=\"admin-threads-act-delete\" data-id=\"${element['thread_id']}\" data-status=\"delete\">Delete</button><br><button class=\"admin-threads-act-hide hide\" data-id=\"${element['thread_id']}\" data-status=\"hide\">Hide</button><br>`
						else 
							result += `<button class=\"admin-threads-act-restore\" data-id=\"${element['thread_id']}\" data-status=\"restore\">Restore</button>`

						result += `</td></tr>`;
						$("tbody").append(result);
					});
				});
				$(".admin-search-threads-table").removeClass("d-none");
				return;
			} else if (parseInt(result["response"]) === 403) {
				$("tbody").html("");
				$(".admin-search-threads-table").addClass("d-none");
				return;
			} else if (parseInt(result["response"]) === 400) {
				$("tbody").html("");
				$(".admin-search-threads-table").addClass("d-none");
				return;
			}
			$("tbody").html("");
			$.each(result, (_, element) => {
				var result = `<tr><td scope='row'>${element['thread_id']}</td>`;
				result += `<td>${element['thread_title']}</td>`;
				result += `<td><a href=\"/t/${element['thread_url']}\">/t/${element['thread_url']}/</a></td>`;
				result += `<td>${element['created_date']}</td>`;
				result += `<td><a href=\"/account/${element['ownerId']}\">${element['ownerName']}</a></td>`;
				if (element['is_locked'] != 1 && element['is_deleted'] != 1)
					result += `<td><span class=\"bg-success rounded p-1 text-light\">Active</span></td>`;
				else if (element['is_locked'] == 1 && element['is_deleted'] != 1)
					result += `<td><span class=\"bg-warning rounded p-1 text-light\">Hidden</span></td>`;
				else 
					result += `<td><span class=\"bg-danger rounded p-1 text-light\">Deleted</span></td>`;
				
				result += `<td>${element['members']}</td><td>`;

				if (element['is_locked'] != 1 && element['is_deleted'] != 1)
					result += `<button class=\"admin-threads-act-delete\" data-id=\"${element['thread_id']}\" data-status=\"delete\">Delete</button><br><button class=\"admin-threads-act-hide hide\" data-id=\"${element['thread_id']}\" data-status=\"hide\">Hide</button><br>`
				else 
					result += `<button class=\"admin-threads-act-restore\" data-id=\"${element['thread_id']}\" data-status=\"restore\">Restore</button>`

				result += `</td></tr>`;
				$("tbody").append(result);
			});
			$(".admin-search-threads-table").removeClass("d-none");
			return;
		});
	});

	$(document).on("click",".admin-threads-act-delete", (e) => {
		e.preventDefault();
	
		console.log($(".admin-threads-act-delete").data("status"));
		console.log($(".admin-threads-act-delete").data("id"));

		$.post(`http://${$(location).attr('host')}/server/middlewares/AdminMiddleware.class.php`, {
			actionTypeDelete: $(".admin-threads-act-delete").data("status"),
			threadId: $(".admin-threads-act-delete").data("id")
		}).done(function (_) {
			setTimeout(() => { window.location = "/admin/threads"; }, 1000);
			return;
		});

	});

	$(document).on("click",".admin-threads-act-hide", (e) => {
		e.preventDefault();
		$.post(`http://${$(location).attr('host')}/server/middlewares/AdminMiddleware.class.php`, {
			actionTypeHide: $(".admin-threads-act-hide").data("status"),
			threadId: $(".admin-threads-act-hide").data("id")
		}).done(function (_) {
			setTimeout(() => { window.location = "/admin/threads"; }, 1000);
			return;
		});
	});

	$(document).on("click",".admin-threads-act-restore", (e) => {
		e.preventDefault();
		$.post(`http://${$(location).attr('host')}/server/middlewares/AdminMiddleware.class.php`, {
			actionTypeRecover: $(".admin-threads-act-restore").data("status"),
			threadId: $(".admin-threads-act-restore").data("id")
		}).done(function (_) {
			setTimeout(() => { window.location = "/admin/threads"; }, 1000);
			return;
		});
	});
});