(function(document, Nette, grecaptcha) {
	var init = false;
	Nette.recaptcha = function (grecaptcha) {
		var items = document.getElementsByClassName('g-recaptcha');
		var length = items.length;

		if (length > 0) {
			grecaptcha.ready(function () {
				for (var i = 0; i < length; i++) {
					grecaptcha.render(items[i]);
				};
				grecaptcha.execute().then(function (token) {
					var inputs = document.getElementsByClassName('g-recaptcha-response');
					for (var i = 0; i < items.length; i++) {
						inputs[i].value = token;
					};
				});
			});
			init = true;
		}
	};

	if (!init) {
		Nette.recaptcha(grecaptcha);
	}
})(document, Nette, grecaptcha);