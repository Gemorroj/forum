"use strict";


var $document = $(document);

// http://stackoverflow.com/questions/18429655/jquery-mobile-open-popup-from-popup
$.mobile.switchPopup = function(sourceElement, destinationElement, onswitched) {
    var afterClose = function() {
        destinationElement.popup("open", {"transition": "slideup"});
        sourceElement.off("popupafterclose", afterClose);

        if (onswitched && typeof onswitched === "function"){
            onswitched();
        }
    };

    sourceElement.on("popupafterclose", afterClose);
    sourceElement.popup("close");
};


// topic_show
$document.on("pagecreate", "#topic_show", function () {
    var $container = $(this);
    var $editTopicPopup = $container.find('#edit_topic_popup');
    var $deleteTopicPopup = $container.find('#delete_topic_popup');

    $container.find('a.post_delete_button').click(function () {
        $deleteTopicPopup.popup('open', {"transition": "slideup"});
        var $this = $(this);
        var $deleteForm = $deleteTopicPopup.find('form');
        $deleteForm.attr('action', $this.attr('data-url'));
    });
    $container.find('a.post_edit_button').click(function () {
        $editTopicPopup.popup('open', {"transition": "slideup"});
        var $this = $(this);
        var $editForm = $editTopicPopup.find('form');
        var refId = $this.data('ref');
        $editForm.find('textarea').val($container.find('#' + refId).text());
        $editForm.attr('action', $this.attr('data-url'));
    });
    $container.find("button[data-id='post_delete_cancel']").click(function () {
        $deleteTopicPopup.popup('close');
    });
    $container.find("button[data-id='post_edit_cancel']").click(function () {
        $editTopicPopup.popup('close');
    });
});


// forum_show
$document.on("pagecreate", "#forum_show", function () {
    var $container = $(this);
    var $topicManagementButton = $container.find('a.topic_management_button');
    var $topicManagementPopup  = $container.find('#topic_management_popup');
    var $topicEditPopup        = $container.find('#topic_edit_popup');
    var $topicDeletePopup      = $container.find('#topic_delete_popup');

    $topicManagementButton.click(function () {
        $topicManagementPopup.popup('open', {"transition": "slideup"});
    });
    $container.find('a.topic_edit_button').click(function () {
        $.mobile.switchPopup($topicManagementPopup, $topicEditPopup, false);
        var $editForm = $topicEditPopup.find('form');
        var refId = $topicManagementButton.data('ref');
        $editForm.find('textarea').val($container.find('#' + refId).find('span.topic_title').text());
        $editForm.attr('action', $topicManagementButton.attr('data-edit'));
    });
    $container.find('a.topic_delete_button').click(function () {
        $.mobile.switchPopup($topicManagementPopup, $topicDeletePopup, false);
        var $deleteForm = $topicDeletePopup.find('form');
        $deleteForm.attr('action', $topicManagementButton.attr('data-delete'));
    });
    $container.find("button[data-id='topic_edit_cancel']").click(function () {
        $topicEditPopup.popup('close');
    });
    $container.find("button[data-id='topic_delete_cancel']").click(function () {
        $topicDeletePopup.popup('close');
    });
});
