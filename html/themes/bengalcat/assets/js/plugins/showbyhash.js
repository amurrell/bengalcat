app.showbyhash = {
    init: function (el) {
        this.el = el;

        var winHash = window.location.hash;
        var hash = el.attr('data-hash');

        if (winHash === hash) {
            el.show();
        }
    }
};