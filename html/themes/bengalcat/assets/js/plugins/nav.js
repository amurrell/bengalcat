app.nav = {
    init: function (el) {
        this.makeEvents(el);
    },
    makeEvents: function (el) {
        var _this = this;
        var props = this.getProps(el);

        props.toggle.click(function () {
            var action = props.collapse.is(':visible') ? 'off' : 'on';
            _this.toggle(props, action);
        });

        props.collapse.click(function () {
            if (props.toggle.is(':visible')) {
                _this.toggle(props, 'off');
            }
        });

        $(window).resize(function () {
            _this.toggle(props, 'off');
        });
    },
    getProps: function (el) {
        return {
            el: el,
            toggle: el.find('[data-toggle]'),
            collapse: el.find('[data-collapse]'),
            toggleClass: el.find('[data-toggle-class]').attr('data-toggle-class')
        };
    },
    toggle: function (props, action) {
        var method = ((action === 'on') ? 'add' : 'remove') + 'Class';
        props.collapse[method](props.toggleClass);
        props.toggle[method](props.toggleClass);
    }
};