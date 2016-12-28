app.fadein = {
    init: function(el){
        var _this = this;

		this.el = el;
		this.timeout = this.el.attr('data-timeout');
		this.duration = this.el.attr('data-duration');

		setTimeout(function(){
			_this.el.fadeIn(_this.duration);
		}, _this.timeout);
    }
};