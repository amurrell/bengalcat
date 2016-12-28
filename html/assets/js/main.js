window.app = {
	init: function () {
		var _this = this;
		$('[data-plugin]').each(function () {
			var el = $(this);
			var pluginsAttr = el.attr('data-plugin');

			if (pluginsAttr === undefined || pluginsAttr.length < 1) {
				console.log('missing data plugin values');
				return;
			}

			var plugins = pluginsAttr.split(",");

			_this.loadPlugins(plugins, el);
		});
	},
	loadPlugins: function (plugins, el) {
		var _this = this;
		
		var time = 0;
		var transition = (el.attr('data-transition-time') === undefined)
			? 200
			: parseInt(el.attr('data-transition-time'));

		$.each(plugins, function (index, plugin) {
			time += transition;
			setTimeout(function(){
				_this.loadPlugin(plugin, el, false);
			}, time);
		});
	},
	loadPlugin: function (plugin, el, fail) {
		if (this.checkPluginIsLoaded(plugin, fail, el)) {
			this.initPlugin(plugin, el);
		}
	},
	initPlugin: function (plugin, el) {
		this[plugin].init(el);
	},
	checkPluginIsLoaded: function (plugin, fail, el) {
		if (!this[plugin] && !fail) {
			var script = this.getDomain() + '/assets/build/js/plugins/' + plugin + '.js';
			this.loadScript(script, plugin, el);
			return false;
		} else if (!this[plugin]) {
			console.log('check paths.js, or that script exists in plugins folder');
			return false;
		} else {
			return true;
		}
	},
	loadScript: function (script, plugin, el) {
		var _this = this;
		$.getScript(script, function () {
			_this.loadPlugin(plugin, el, true);
		});
	},
	getDomain: function () {
		return document.location.protocol + '//' + document.location.host;
	},
	getSupportedPropertyName: function (properties) {
		for (var i = 0; i < properties.length; i++) {
			if (typeof document.body.style[properties[i]] !== "undefined") {
				return properties[i];
			}
		}
		return null;
	}
};

$(document).ready(function () {
	app.init();
});