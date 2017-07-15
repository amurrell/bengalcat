app.removeonclick = {
    init: function (el) {
        this.el = el;

        this.loadListener();
    },
    loadListener: function () {
        this.el.on('click', function (event) {
            event.preventDefault();
            $(this).remove();
        });
    }
};