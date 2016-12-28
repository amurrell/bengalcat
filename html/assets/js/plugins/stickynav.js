app.stickynav = {
    init: function(el){
        this.el = el;
		this.fixedClass = el.attr('data-fixed-class');
		this.offset = el.attr('data-offset').length ? el.attr('data-offset') : 0;
		this.topPos = this.el.position().top;

		this.previousScroll = 0;
        this.loadListener();
    },
    loadListener: function(){
		var _this = this;
        $(window).scroll(function(){
			var currentScroll = $(this).scrollTop();
			if (currentScroll > _this.previousScroll) {
				_this.down();
			} else {
				_this.up();
			}
			_this.previousScroll = currentScroll;
		}).scroll();
    },
	down: function() {
		this.toggleStickiness('down');
	},
	up: function(){
		this.toggleStickiness('up');
	},
	toggleStickiness: function(direction){
		if (direction === 'down' && window.scrollY > this.topPos - this.offset) {
			this.el.addClass(this.fixedClass);
		}
		if (direction === 'up' && window.scrollY < this.topPos + this.offset) {
			this.el.removeClass(this.fixedClass);
		}
	}
};