app.showbyhash = {
    init: function(el){
        this.el = el;

		var winHash = window.location.hash;
		var hash = el.attr('data-hash');

		console.log(hash);
		console.log(winHash);

		if (winHash === hash) {
			el.show();
		}
    }
};