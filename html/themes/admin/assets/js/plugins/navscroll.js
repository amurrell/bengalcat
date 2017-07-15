app.navscroll = {
    init: function (el) {
        this.el = el;

        this.loadListener();
    },
    loadListener: function () {
        this.el.on('click', function (event) {

            if (this.hash === undefined) {
                this.hash = $(this).attr('href').replace(/.*?#/, '#');
            }

            if (this.hash !== undefined) {
                var hash = this.hash;

                var offset = isNaN($(this).attr('data-offset')) ? 0 : $(this).attr('data-offset');
                var selector = '[data-hash="' + hash + '"]';

                var goTo = $(selector);

                if (goTo.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: goTo.offset().top - parseInt(offset)
                    }, 800);

                }

            }
        });
    }
};