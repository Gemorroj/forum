"use strict";

var $document = $(document);


// авторизация
$document.on("pagecreate", function (e) {
    var $container = $(e.target);

    $container.find("#btn-authorization").click(function () {
        $container.find("#popup-authorization").popup("open", {"transition": "pop", "positionTo": "window"});
    });
});


// topic
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


// topics
$document.on("pagecreate", "#forum_show", function () {
    var $container = $(this);
    var $editTopicsPopup = $container.find('#edit_topics_popup');
    var $deleteTopicsPopup = $container.find('#delete_topics_popup');

    $container.find('a.topic_delete_button').click(function () {
        $deleteTopicsPopup.popup('open', {"transition": "slideup"});
        var $this = $(this);
        var $deleteForm = $deleteTopicsPopup.find('form');
        $deleteForm.attr('action', $this.attr('data-url'));
    });
    $container.find('a.topic_edit_button').click(function () {
        $editTopicsPopup.popup('open', {"transition": "slideup"});
        var $this = $(this);
        var $editForm = $editTopicsPopup.find('form');
        var refId = $this.data('ref');
        $editForm.find('textarea').val($container.find('#' + refId).text());
        $editForm.attr('action', $this.attr('data-url'));
    });
    $container.find("button[data-id='topic_delete_cancel']").click(function () {
        $deleteTopicsPopup.popup('close');
    });
    $container.find("button[data-id='topic_edit_cancel']").click(function () {
        $editTopicsPopup.popup('close');
    });
});
