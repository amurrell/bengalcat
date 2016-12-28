app.addclass = {
    init: function(el){
        var _this = this;

		this.el = el;
		this.timeout = this.el.attr('data-timeout');
		this.class = this.el.attr('data-class');

		setTimeout(function(){
			_this.el.addClass(_this.class);
		}, _this.timeout);
    }
};