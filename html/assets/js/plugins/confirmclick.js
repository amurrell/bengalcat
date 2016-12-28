app.confirmclick = {
    init: function(el){
        this.el = el;

        this.loadListener();
    },
    loadListener: function(){
        this.el.on('click', function(event){
			var el = $(this);
			var text = $(this).attr('data-confirm-text');

			if (!el.hasClass('clicked')) {
				event.preventDefault();
				if (text.length) {
					el.text(text);
				} else {
					confirm("Are you sure you want to do this? If so, you'll need to do it one more time to prove it!");
				}
				el.addClass('clicked');
			}
        });
    }
};