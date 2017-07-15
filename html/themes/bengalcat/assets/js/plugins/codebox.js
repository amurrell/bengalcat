app.codebox = {
    init: function (el) {
        this.box = el;
        
        var lines = this.box.text().split('\n');
        var code = this.getCode(lines);
        
        this.box.html(code);
    },
    getCode: function(lines) {
        if (!lines.length) {
            return;
        }
        
        var html = $('<pre></pre>');
        
        for(var i = 0; i < lines.length; i++){
            var line = lines[i].length ? lines[i] : '<br>';
            html.append('<code>' + line + '</code>');
        }
        
        return html.html();
    }
};
