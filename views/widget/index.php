<?php
    $settings = 'VK.callMethod("users.get", {"user_ids": Args.uid}, function (data) {
        return {
            "title": "Привет, путник",
            "rows": [{
                "title": "title",
                "text": "text",
            }]
        };
    });';
    $code = "VK.callMethod('showAppWidgetPreviewBox', 'text', '$settings')";
?>
<a href="#" onclick="<?= $code ?>">Постановка виджета</a>