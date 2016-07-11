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

    var $postManagementPopup = $container.find('#post_management_popup');
    var $postEditPopup       = $container.find('#post_edit_popup');
    var $postDeletePopup     = $container.find('#post_delete_popup');

    var $postManagementButton = $container.find('a.post_management_button');
    var $postEditButton       = $container.find('a.post_edit_button');
    var $postDeleteButton     = $container.find('a.post_delete_button');

    var $postEditCancelButton   = $container.find("button[data-id='post_edit_cancel']");
    var $postDeleteCancelButton = $container.find("button[data-id='post_delete_cancel']");

    $postManagementButton.click(function () {
        $postManagementPopup.popup('open', {"transition": "slideup"});
    });
    $postEditButton.click(function () {
        $.mobile.switchPopup($postManagementPopup, $postEditPopup, false);

        var refId     = $postManagementButton.data('ref');
        var $editForm = $postEditPopup.find('form');

        $editForm.find('textarea').val($container.find('#' + refId).text());
        $editForm.attr('action', $postManagementButton.attr('data-edit'));
    });
    $postDeleteButton.click(function () {
        $.mobile.switchPopup($postManagementPopup, $postDeletePopup, false);

        var $deleteForm = $postDeletePopup.find('form');

        $deleteForm.attr('action', $postManagementButton.attr('data-delete'));
    });
    $postEditCancelButton.click(function () {
        $postEditPopup.popup('close');
    });
    $postDeleteCancelButton.click(function () {
        $postDeletePopup.popup('close');
    });
});


// forum_show
$document.on("pagecreate", "#forum_show", function () {
    var $container = $(this);

    var $topicManagementPopup = $container.find('#topic_management_popup');
    var $topicEditPopup       = $container.find('#topic_edit_popup');
    var $topicDeletePopup     = $container.find('#topic_delete_popup');

    var $topicManagementButton = $container.find('a.topic_management_button');
    var $topicEditButton       = $container.find('a.topic_edit_button');
    var $topicDeleteButton     = $container.find('a.topic_delete_button');

    var $topicEditCancelButton   = $container.find("button[data-id='topic_edit_cancel']");
    var $topicDeleteCancelButton = $container.find("button[data-id='topic_delete_cancel']");

    $topicManagementButton.click(function () {
        $topicManagementPopup.popup('open', {"transition": "slideup"});
    });
    $topicEditButton.click(function () {
        $.mobile.switchPopup($topicManagementPopup, $topicEditPopup, false);

        var refId     = $topicManagementButton.data('ref');
        var $editForm = $topicEditPopup.find('form');

        $editForm.find('textarea').val($container.find('#' + refId).find('span.topic_title').text());
        $editForm.attr('action', $topicManagementButton.attr('data-edit'));
    });
    $topicDeleteButton.click(function () {
        $.mobile.switchPopup($topicManagementPopup, $topicDeletePopup, false);

        var $deleteForm = $topicDeletePopup.find('form');

        $deleteForm.attr('action', $topicManagementButton.attr('data-delete'));
    });
    $topicEditCancelButton.click(function () {
        $topicEditPopup.popup('close');
    });
    $topicDeleteCancelButton.click(function () {
        $topicDeletePopup.popup('close');
    });
});
