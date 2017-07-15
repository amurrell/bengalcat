window.app = {
    init: function () {
        var _this = this;

        this.getPluginsData();
        this.loadNextPlugin();

    },
    initPlugins: function () {
        var _this = this;

        this.pluginsData.pluginEls.filter('[data-plugins-json]').each(function () {
            var el = $(this);
            var plugins = JSON.parse(el.attr('data-plugins-json'));

            $.each(plugins, function (index, plugin) {

                if ($.inArray(plugin, _this.pluginsData.failed) === -1) {
                    _this.initPlugin(plugin, el);
                }

            });

        });

        $('body').trigger('pluginsInitialized');

    },
    initPlugin: function (plugin, el) {
        var _this = this;

        if (_this[plugin].dependencies === undefined) {
            _this[plugin].init(el);
            return;
        }

        $.each(_this[plugin].dependencies, function (index, dependency) {
            _this.pluginsData.pluginsToLoad.push(dependency);
            _this.pluginsData.dependencies.push(dependency);
        });

        delete _this[plugin].dependencies;
        this.loadNextPlugin();
    },
    getPluginsData: function () {
        var _this = this;

        if (this.pluginsData === undefined) {
            this.pluginsData = {
                loaded: [],
                failed: [],
                plugins: [],
                pluginsBundled: [],
                pluginsToLoad: [],
                dependencies: [],
                pluginEls: $('[data-plugin]').not('[data-plugins-json]')
            };
        } else {
            this.pluginsData.pluginEls = $('[data-plugin]').not('[data-plugins-json]');
        }

        this.pluginsData.pluginEls.each(function () {

            var el = $(this);
            var pluginsAttr = el.attr('data-plugin');

            if (pluginsAttr.length < 1) {
                console.log('missing data plugin values for:');
                console.log(el);
                return;
            }

            var pluginsArr = pluginsAttr.split(",");

            el.attr('data-plugins-json', JSON.stringify(pluginsArr));

            _this.addPlugins(pluginsArr);
        });

    },
    addPlugins: function (add) {
        var _this = this;

        $.each(add, function (index, plugin) {
            if ($.inArray(plugin, _this.pluginsData.plugins) === -1) {
                _this.pluginsData.plugins.push(plugin);
            } else {
                return;
            }

            if (_this[plugin] !== undefined) {
                _this.pluginsData.pluginsBundled.push(plugin);
                return;
            }

            _this.pluginsData.pluginsToLoad.push(plugin);
        });
    },
    loadNextPlugin: function () {
        if (!this.doneLoadingPlugins()) {
            this.loadPlugin();
        }
    },
    loadPlugin: function () {
        var base = this.getDomain() + '/themes/bengalcat/assets/build/js/plugins/';
        var plugin = this.pluginsData.pluginsToLoad.splice(0, 1)[0];
        var is_dep = !($.inArray(plugin, this.pluginsData.dependencies) === -1);
        var vendor = is_dep ? 'vendor/' : '';
        var script = base + vendor + plugin + '.js';

        if (plugin.length) {
            this.loadScript(script, plugin);
        }
    },
    loadScript: function (script, plugin) {
        var _this = this;

        _this.pluginsData.loaded.push(plugin);

        $.getScript(script, function () {
            _this.loadNextPlugin();
        }).fail(function () {
            _this.pluginsData.failed.push(plugin);
            _this.loadNextPlugin();
            console.log(
                    'Failed to load plugin: '
                    + plugin
                    + '. Check paths.js, or that script exists in plugins folder'
                    );
        });
    },
    doneLoadingPlugins: function () {
        if (this.pluginsData.pluginsToLoad.length < 1) {
            this.initPlugins();
            return true;
        }
        return false;
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

