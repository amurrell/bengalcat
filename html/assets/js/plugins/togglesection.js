app.togglesection = {
    init: function(el){
        this.el = el;

		var vars = this.getVars(el);

		vars.section.hide();
		this.el.addClass(vars.toggleClass);

        this.loadListener();
    },
    loadListener: function(){
        var _this = this;
		this.el.on('click', function(){
			var el = $(this);
			var vars = _this.getVars(el);
			if (vars.section.is(':visible')) {
				vars.section.hide();
				el.addClass(vars.toggleClass);
			} else {
				vars.section.fadeIn();
				el.removeClass(vars.toggleClass);
			}
        });
    },
	getVars: function(el) {
		var vars = {};
		vars.toggleClass = el.attr('data-toggle-class');
		vars.switch = el.attr('data-toggle-switch');
		vars.selector = '[data-toggle=' + vars.switch + ']';
		vars.section = $(vars.selector);
		return vars;
	}
};