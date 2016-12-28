app.goscroll = {
    init: function(el){
        this.el = el;
        this.hash = el.attr('data-hash');
        this.offset = el.attr('data-offset');
		this.windowHash = window.location.hash;

        this.loadListener();
    },
    loadListener: function(){
		if (this.hash === this.windowHash && this.hash.length) {

			var selector = '[data-hash="' + this.hash + '"]';
			var scrollTo = $(selector).offset().top - this.offset;

			$('html, body').animate({
                scrollTop: scrollTo
            }, 800);

		}
    }
};