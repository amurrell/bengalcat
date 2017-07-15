app.goscroll = {
    init: function (el) {
        this.el = el;
        this.hash = el.attr('data-hash');
        this.offset = this.getOffset(el);
        this.windowHash = window.location.hash;

        this.loadListener();
    },
    loadListener: function () {
        if (this.hash === this.windowHash && this.hash.length) {

            var selector = '[data-hash="' + this.hash + '"]';
            var target = $(selector);
            var scrollTo = target.offset().top - this.offset;

            $('html, body').animate({
                scrollTop: scrollTo
            }, 800);

        }
    },
    getOffset: function (el) {
        return (el.attr('data-offset') !== undefined)
                ? parseInt(el.attr('data-offset'))
                : 0;
    }
};