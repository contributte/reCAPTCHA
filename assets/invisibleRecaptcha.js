/**
 * This code works only for non-ajax forms
 */
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
					grecaptcha.execute();
				};
			};

			/**
			 * Try to submit form using first submit button so in backend we can check for `$form[button]->isSubmittedBy()`
			 * @param {DOMElement} form
			 * @returns {void}
			 */
			var submitForm = function(form) {
				var btn = form.querySelector('[type=submit]');
				if (btn) {
					btn.click();
				} else {
					form.submit();
				}
			}

			var form;
			for (var i = 0; i < length; i++) {
				form = items[i].closest('form');
				form.addEventListener('submit', submitListenerFactory(form));

				grecaptcha.render(items[i], {
					callback: function(token) { 
						resolved = true;

						// reCaptcha token expires after 2 minutes; make it 5 seconds earlier just in case network is slow
						setTimeout(function(){ resolved = false; }, (2 * 60 - 5) * 1000);

						var inputs = document.getElementsByClassName('g-recaptcha-response');
						for (var i = 0; i < inputs.length; i++) {
							inputs[i].value = token;
						}

						submitForm(form);
					}
				});
			}
		});
		init = true;
	};

	if (!init) {
		Nette.recaptcha(grecaptcha);
	}
})(document, Nette, grecaptcha);
