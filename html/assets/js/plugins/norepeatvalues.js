app.norepeatvalues = {
    init: function(el){
        this.el = el;
        this.loadListener();
    },
    loadListener: function(){
		var _this = this;

        this.el.on('focusout', function(){
			var el = $(this);
			if ($.inArray( el.val(), _this.getValues(el)) > -1) {
				el.val('');
				el.attr('placeholder', 'No Repeat Values!');
			}
        });
    },
	getValues: function(el){
		var values = el.attr('data-values');
		var arr = (values !== undefined) ? values.split(",") : [];

		return arr;
	}
};