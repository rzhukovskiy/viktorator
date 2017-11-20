$(document).ready(function() {
    $('body').on('click','#addWidget', function(e) {
        VK.callMethod(
            'showAppWidgetPreviewBox',
            'list',
            'var u = API.users.get({"user_ids":Args.uid});' +
            'var name = u[0].first_name + " " + u[0].last_name;' +
            'return {"title": "' + $('#title').val() + '", ' +
            '"rows": [{"title":name,"button":"узнать подробнее","button_url":"https://vk.com","text":"' + $('#text').val() + '", "icon_id":"id" + Args.uid}]};'
        );
    });
});
