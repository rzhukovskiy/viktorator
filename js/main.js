$(document).ready(function() {
    $('body').on('click','#addWidget', function(e) {
        VK.callMethod('showAppWidgetPreviewBox', 'text', 'return {"title": "Цитата", "text": "Текст цитаты"};');
    });
});