$(document).ready(function() {
    $('body').on('click','#addWidget', function(e) {
        var title       = $('#title').val();
        var text        = $('#text').val();
        var button_url  = $('#button_url').val();
        var button_text = $('#button_text').val();

        var code = 'var u = API.users.get({"user_ids":Args.uid});' +
            'var name = u[0].first_name;' +
            'var username = u[0].first_name + " " + u[0].last_name;' +
            'var text = "' + text + '";' +
            'var title = "' + title + '";' +
            'var newText = text.split("%username%");' +
            'if(newText.length > 1) {text=""; var i=0; while(i < newText.length - 1) {text = text + newText[i] + username + newText[i+1]; i = i + 1;}};' +
            'newText = text.split("%name%");' +
            'if(newText.length > 1) {text=""; var i=0; while(i < newText.length - 1) {text = text + newText[i] + name + newText[i+1]; i = i + 1;}};' +
            'newText = title.split("%username%");' +
            'if(newText.length > 1) {title=""; var i=0; while(i < newText.length - 1) {title = title + newText[i] + username + newText[i+1]; i = i + 1;}};' +
            'newText = title.split("%name%");' +
            'if(newText.length > 1) {title=""; var i=0; while(i < newText.length - 1) {title = title + newText[i] + name + newText[i+1]; i = i + 1;}};' +
            'return {"title": title, ' +
            '"rows": [{"title":text,"button":"' + button_text + '","button_url":"' + button_url + '", "icon_id":"id" + Args.uid}]};';

        VK.callMethod('showAppWidgetPreviewBox', 'list', code);
    });
});
