var $infobox = $('#info-box');

$(document).ready(function() {

    const $themeSwitchButton = $('#switch-theme-button');
    const theme = document.querySelector("#theme-css");

    // Change system theme if theme not specified earlier in the settings

    if (typeof $.cookie('theme-night') === 'undefined') {

        switchTheme(true);
    }

    updateSwitchButton();

    $(document).on("change", "input[name='switch-theme-button']", function () {

        switchTheme(false)
    });

    function switchTheme(s) {

        if (typeof s == "boolean") {

            if (s) {

                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {

                    theme.href = "/css/root-night.css";

                } else {

                    theme.href = "/css/root.css";
                }

                $.removeCookie('theme-night', { path: '/' });

            } else {

                if (theme.getAttribute("href") == "/css/root.css") {

                    // ... then switch it to night theme
                    theme.href = "/css/root-night.css";
                    $.cookie("theme-night", true, { path: '/'});

                } else {

                    // ... switch it to default theme
                    theme.href = "/css/root.css";
                    $.cookie("theme-night", false, { path: '/'});
                }
            }
        }

        updateSwitchButton();
    }

    function updateSwitchButton() {

        if (theme.getAttribute("href") === "/css/root.css") {

            // ... then switch it to night theme
            $themeSwitchButton.removeAttr('checked');

        } else {

            // ... switch it to default theme
            $themeSwitchButton.attr('checked','checked')
        }
    }

    //

    if ($('#content').length != 0 && $('#sidenav').length != 0 && $('#backdrop').length != 0) {

        if ($('.burger-icon').length != 0) {

            if ($('.burger-icon').hasClass('hidden')) {

                $('.burger-icon').removeClass('hidden');
            }
        }

        var sidenav = new Sidenav({

            content: document.getElementById("content"),
            sidenav: document.getElementById("sidenav"),
            backdrop: document.getElementById("backdrop")
        });
    }

    $(document).on("click", ".menu-toggle", function() {

        if (sidenav.isOpened) {

            sidenav.close();

        } else {

            var content = $('.sidebar-menu').html();

            $('#sidenav').find('div.sidenav-content').html(content);

            sidenav.open()
        }
    });

    $(document).on('click', '.dropdown__content', function (e) {

        e.stopPropagation();
    });

    $('#close-button').click(function() {

        $(this).parents('.dropdown').find('#settings-button').dropdown('toggle')
    });

    if ($('div.header-message').length > 0) {

        $("div.header-message").removeClass( "gone" );
    }

    $(document).on("click", "button.close-message-button", function() {

        $("div.header-message").addClass("gone");

        return false;
    });

    $(document).on("click", "button.close-privacy-message", function() {

        $("div.header-message").remove();

        $.cookie("privacy", "close", { expires : 7, path: '/' });

        return false;
    });

    $(document).on("click", "a.tooltip-close", function() {

        var value = $(this).attr('data-id');

        $('div.tooltip-card[data-id=' + value + ']').remove();

        $.cookie(value, "close", { expires : 3, path: '/' });

        return false;
    });

    $("#item-image-upload").fileupload({
        formData: {accountId: account.id, accessToken: account.accessToken},
        name: 'image',
        url: "/api/" + options.api_version + "/method/items.uploadImg",
        dropZone:  '',
        dataType: 'json',
        singleFileUploads: true,
        multiple: false,
        maxNumberOfFiles: 1,
        maxFileSize: constants.MAX_FILE_SIZE,
        acceptFileTypes: "", // or regex: /(jpeg)|(jpg)|(png)$/i
        "files":null,
        minFileSize: null,
        messages: {
            "maxNumberOfFiles":"Maximum number of files exceeded",
            "acceptFileTypes":"File type not allowed",
            "maxFileSize": "File is too big",
            "minFileSize": "File is too small"},
        process: true,
        start: function (e, data) {

            console.log("start");

            $('div.item-actions').addClass("hidden");
            $('div.item-image-progress').removeClass("hidden");

            $("#item-image-upload").trigger('start');
        },
        processfail: function(e, data) {

            console.log("processfail");

            if (data.files.error) {

                $infobox.find('#info-box-message').text(data.files[0].error);
                $infobox.modal('show');
            }
        },
        progressall: function (e, data) {

            console.log("progressall");

            var progress = parseInt(data.loaded / data.total * 100, 10);

            $('div.item-image-progress').find('.progress-bar').attr('aria-valuenow', progress).css('width', progress + '%').text(progress + '%');
        },
        done: function (e, data) {

            console.log("done");

            var result = jQuery.parseJSON(data.jqXHR.responseText);

            if (result.hasOwnProperty('error')) {

                if (result.error === false) {

                    if (result.hasOwnProperty('imgUrl')) {

                        Profile.addPostImg(result.imgUrl);

                        $("div.img_container").show();

                        $('div.item-actions').removeClass("hidden");
                        $('div.item-image-progress').addClass("hidden");

                        if ($("div.new-post-img-item").length >= options.post_max_images ) {

                            $('div.item-add-image').addClass("hidden");
                        }
                    }

                } else {

                    $infobox.find('#info-box-message').text(result.error_description);
                    $infobox.modal('show');
                }
            }

            $("#item-image-upload").trigger('done');
        },
        fail: function (e, data) {

            console.log(data.errorThrown);
        },
        always: function (e, data) {

            console.log("always");

            $('div.item-actions').removeClass("hidden");
            $('div.item-image-progress').addClass("hidden");

            if ($("div.new-post-img-item").not(".new-post-video-item").length < options.post_max_images ) {

                $('div.item-add-image').removeClass("hidden");
            }

            $("#item-image-upload").trigger('always');
        }
    });

    $("#item-video-upload").fileupload({
        formData: {accountId: account.id, accessToken: account.accessToken},
        name: 'image',
        url: "/api/" + options.api_version + "/method/gallery.uploadVideo",
        dropZone:  '',
        dataType: 'json',
        singleFileUploads: true,
        multiple: false,
        maxNumberOfFiles: 1,
        maxFileSize: constants.VIDEO_FILE_MAX_SIZE,
        acceptFileTypes: "", // or regex: /(jpeg)|(jpg)|(png)$/i
        "files":null,
        minFileSize: null,
        messages: {
            "maxNumberOfFiles":"Maximum number of files exceeded",
            "acceptFileTypes":"File type not allowed",
            "maxFileSize": "File is too big",
            "minFileSize": "File is too small"},
        process: true,
        start: function (e, data) {

            console.log("start");

            $('div.item-actions').addClass("hidden");
            $('div.item-image-progress').removeClass("hidden");

            $("#item-video-upload").trigger('start');
        },
        processfail: function(e, data) {

            console.log("processfail");

            if (data.files.error) {

                $infobox.find('#info-box-message').text(data.files[0].error);
                $infobox.modal('show');
            }
        },
        progressall: function (e, data) {

            console.log("progressall");

            var progress = parseInt(data.loaded / data.total * 100, 10);

            $('div.item-image-progress').find('.progress-bar').attr('aria-valuenow', progress).css('width', progress + '%').text(progress + '%');
        },
        done: function (e, data) {

            console.log("done");

            var result = jQuery.parseJSON(data.jqXHR.responseText);

            if (result.hasOwnProperty('error')) {

                if (result.error === false) {

                    if (result.hasOwnProperty('videoFileUrl')) {

                        Profile.addPostVideo(result.videoFileUrl);

                        $("div.img_container").show();

                        $('div.item-actions').removeClass("hidden");
                        $('div.item-image-progress').addClass("hidden");

                        if ($("div.new-post-video-item").length > 0) {

                            $('div.item-add-video').addClass("hidden");
                        }
                    }

                } else {

                    $infobox.find('#info-box-message').text(result.error_description);
                    $infobox.modal('show');
                }
            }

            $("#item-video-upload").trigger('done');
        },
        fail: function (e, data) {

            console.log(data.errorThrown);
        },
        always: function (e, data) {

            console.log("always");

            $('div.item-actions').removeClass("hidden");
            $('div.item-image-progress').addClass("hidden");

            if ($("div.new-post-video-item").length == 0) {

                $('div.item-add-video').removeClass("hidden");
            }

            $("#item-video-upload").trigger('always');
        }
    });

    $("#market-item-image-upload").fileupload({
        formData: {accountId: account.id, accessToken: account.accessToken},
        name: 'image',
        url: "/api/" + options.api_version + "/method/market.uploadImg",
        dropZone:  '',
        dataType: 'json',
        singleFileUploads: true,
        multiple: false,
        maxNumberOfFiles: 1,
        maxFileSize: constants.MAX_FILE_SIZE,
        acceptFileTypes: "", // or regex: /(jpeg)|(jpg)|(png)$/i
        "files":null,
        minFileSize: null,
        messages: {
            "maxNumberOfFiles":"Maximum number of files exceeded",
            "acceptFileTypes":"File type not allowed",
            "maxFileSize": "File is too big",
            "minFileSize": "File is too small"},
        process: true,
        start: function (e, data) {

            console.log("start");

            $('div.market-upload-button-container').addClass("hidden");
            $('div.item-image-progress').removeClass("hidden");

            $("#market-item-image-upload").trigger('start');
        },
        processfail: function(e, data) {

            console.log("processfail");

            if (data.files.error) {

                $infobox.find('#info-box-message').text(data.files[0].error);
                $infobox.modal('show');
            }
        },
        progressall: function (e, data) {

            console.log("progressall");

            var progress = parseInt(data.loaded / data.total * 100, 10);

            $('div.item-image-progress').find('.progress-bar').attr('aria-valuenow', progress).css('width', progress + '%').text(progress + '%');
        },
        done: function (e, data) {

            console.log("done");

            var result = jQuery.parseJSON(data.jqXHR.responseText);

            if (result.hasOwnProperty('error')) {

                if (result.error === false) {

                    if (result.hasOwnProperty('imgUrl')) {

                        Market.addItemImg(result.imgUrl);

                        $("div.img_container").show();

                        $('div.item-actions').removeClass("hidden");
                        $('div.item-image-progress').addClass("hidden");

                        if ($("div.new-post-img-item").length >= options.post_max_images ) {

                            $('div.item-add-image').addClass("hidden");
                        }
                    }

                } else {

                    $infobox.find('#info-box-message').text(result.error_description);
                    $infobox.modal('show');
                }
            }

            $("#market-item-image-upload").trigger('done');
        },
        fail: function (e, data) {

            console.log(data.errorThrown);
        },
        always: function (e, data) {

            console.log("always");

            $('div.item-image-progress').addClass("hidden");

            if ($("div.new-post-img-item").length == 0) {

                $('div.market-upload-button-container').removeClass("hidden");

            } else {

                $('div.market-upload-button-container').addClass("hidden");
            }

            $("#market-item-image-upload").trigger('always');
        }
    });


    $("textarea[name=postText]").autosize();
    $("textarea#market-item-desc").autosize();

    $("textarea[name=postText]").bind('keyup mouseout', function() {

        var max_char = 1000;

        var count = $("textarea[name=postText]").val().length;

        $("span#word_counter").empty();
        $("span#word_counter").html(max_char - count);

        event.preventDefault();
    });

    $(document).on('click', '.emoji-item', function() {

        if (options.pageId === "chat") {

            $editor = $("input[name=message_text]");

        } else if (options.pageId === "post" || options.pageId === "image") {

            $editor = $("input[name=comment_text]");

        } else {

            $editor = $("textarea[name=postText]");
        }

        $editor.val($editor.val() + $(this).text());

        $editor.change();

        if (options.pageId === "chat") {

            $(".btn-emoji-picker").dropdown('toggle');
        }

        return false;
    });

    $(document).on('click', '.emoji-items', function() {

        return false;
    });

    $(document).on('click', '.sticker-items', function() {

        return false;
    });
});