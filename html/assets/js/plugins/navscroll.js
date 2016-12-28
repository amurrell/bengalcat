app.navscroll = {
    init: function(el){
        this.el = el;

        this.loadListener();
    },
    loadListener: function(){
        this.el.on('click', function(event){
            if (this.hash !== "") {
                event.preventDefault();

                var hash = this.hash;
				var offset = isNaN( $(this).attr('data-offset') ) ? 0 : $(this).attr('data-offset');

                $('html, body').animate({
                    scrollTop: $(hash).offset().top - parseInt(offset)
                }, 800);
            }
        });
    }
};