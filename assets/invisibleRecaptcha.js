(function(document, Nette, grecaptcha) {
	var init = false;

	// polyfill for closest() - method for traversing DOM and finding the closest parent matching given selector (form, in our case)
	// @see https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
	if (!Element.prototype.matches) {
		Element.prototype.matches =
			Element.prototype.msMatchesSelector ||
			Element.prototype.mozMatchesSelector ||
			Element.prototype.webkitMatchesSelector;
	}

	if (!Element.prototype.closest) {
		Element.prototype.closest = function(s) {
			var el = this;

			do {
				if (Element.prototype.matches.call(el, s)) return el;
				el = el.parentElement || el.parentNode;
			} while (el !== null && el.nodeType === 1);
			return null;
		};
	}

	Nette.recaptcha = function (grecaptcha) {
		var items = document.getElementsByClassName('g-recaptcha');
		var length = items.length;

		if (length === 0) {
			return;
		}

		grecaptcha.ready(function () {
			var resolved = false;
			var submitListenerFactory = function(form) {
				return function (e) {
					// we already have reCaptcha response, or the form is invalid - or submission is prevented for some other, unknown reason
					if (resolved || e.defaultPrevented) {
						return;
					}

					e.preventDefault();

					grecaptcha.execute().then(function (token) {
						resolved = true;

						// reCaptcha token expires after 2 minutes; make it 5 seconds earlier just in case network is slow
						setTimeout(function(){ resolved = false; }, (2 * 60 - 5) * 1000);

						var inputs = document.getElementsByClassName('g-recaptcha-response');
						for (var i = 0; i < inputs.length; i++) {
							inputs[i].value = token;
						}

						form.submit();
					});
				};
			};

			var form;
			for (var i = 0; i < length; i++) {
				grecaptcha.render(items[i]);

				form = items[i].closest('form');
				form.addEventListener('submit', submitListenerFactory(form));
			}
		});
		init = true;
	};

	if (!init) {
		Nette.recaptcha(grecaptcha);
	}
})(document, Nette, grecaptcha);
