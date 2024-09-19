
window.Reactions || ( window.Reactions = {} );

Reactions.hTimer = 0;
Reactions.hShowTimer = 0;
Reactions.hHideTimer = 0;
Reactions.hide_reactions_time_ms = 700;
Reactions.show_reactions_time_ms = 500;

Reactions.show = function(data_id) {

    $('div.item-footer-container').find("div.reactions-container").remove();

    Reactions.cancel();

    Reactions.hShowTimer = setTimeout(function() {

        $html = '<div class="reactions-container noselect" data-id="'+ data_id +'"> \
                    <div class="img-box"> \
                        <img class="reactions-icon" data-value="0" src="/img/reactions/0.png"> \
                    </div> \
                    <div class="img-box"> \
                        <img class="reactions-icon" data-value="1" src="/img/reactions/1.png"> \
                    </div> \
                    <div class="img-box"> \
                        <img class="reactions-icon" data-value="2" src="/img/reactions/2.png"> \
                    </div> \
                    <div class="img-box"> \
                        <img class="reactions-icon" data-value="3" src="/img/reactions/3.png"> \
                    </div> \
                    <div class="img-box"> \
                        <img class="reactions-icon" data-value="4" src="/img/reactions/4.png"> \
                    </div> \
                    <div class="img-box"> \
                        <img class="reactions-icon" data-value="5" src="/img/reactions/5.png"> \
                    </div> \
                </div>';

        $('div.item-footer-container[data-id=' + data_id + ']').prepend($html);

    }, Reactions.show_reactions_time_ms);
};

Reactions.hide = function() {

    Reactions.cancel();

    Reactions.hHideTimer = setTimeout(function() {

        $('div.item-footer-container').find("div.reactions-container").fadeOut( "slow", function() {

            $(this).remove();
        });

    }, Reactions.hide_reactions_time_ms);
};

Reactions.remove = function() {

    Reactions.cancel();

    $('div.item-footer-container').find("div.reactions-container").remove();
};

Reactions.cancel = function() {

    if (Reactions.hHideTimer) clearTimeout(Reactions.hHideTimer);
    if (Reactions.hShowTimer) clearTimeout(Reactions.hShowTimer);
};

Reactions.make = function (itemId, reaction) {

    $reactions_button = $('.item-reaction-button[data-id=' + itemId + ']');

    if (account.id == 0) {

        App.getPromptBox();

        return;
    }

    $.ajax({
        type: 'POST',
        url: '/api/v2/method/reactions.make',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&itemId=" + itemId + "&reaction=" + reaction,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            if (response.myLike) {

                $reactions_button.addClass("active");

            } else {

                $reactions_button.removeClass("active");
                reaction = 0;
            }

            $reactions_button.attr('data-value', reaction);

            switch (reaction) {

                case "1": {

                    $reactions_button.html("<i class=\"iconfont icofont-heart-eyes mr-1\"></i>" + strings.sz_reactions_1);

                    break;
                }

                case "2": {

                    $reactions_button.html("<i class=\"iconfont icofont-laughing mr-1\"></i>" + strings.sz_reactions_2);

                    break;
                }

                case "3": {

                    $reactions_button.html("<i class=\"iconfont icofont-open-mouth mr-1\"></i>" + strings.sz_reactions_3);

                    break;
                }

                case "4": {

                    $reactions_button.html("<i class=\"iconfont icofont-crying mr-1\"></i>" + strings.sz_reactions_4);

                    break;
                }

                case "5": {

                    $reactions_button.html("<i class=\"iconfont icofont-angry mr-1\"></i>" + strings.sz_reactions_5);

                    break;
                }

                default: {

                    $reactions_button.html("<i class=\"iconfont icofont-heart mr-1\"></i>" + strings.sz_reactions_0);

                    break;
                }
            }

            var likesCount = 0;
            var commentsCount = 0;
            var repostsCount = 0;

            if (response.hasOwnProperty('likesCount')) {

                likesCount = response.likesCount;
            }

            if (response.hasOwnProperty('commentsCount')) {

                commentsCount = response.commentsCount;
            }

            if (response.hasOwnProperty('rePostsCount')) {

                repostsCount = response.rePostsCount;
            }

            $('.likes-count[data-id=' + itemId + ']').text(likesCount);
            $('.comments-count[data-id=' + itemId + ']').text(commentsCount);
            $('.reposts-count[data-id=' + itemId + ']').text(repostsCount);

            if (likesCount == 0 && commentsCount == 0 && repostsCount == 0) {

                $('.item-counters[data-id=' + itemId + ']').addClass("gone");

            } else {

                $('.item-counters[data-id=' + itemId + ']').removeClass("gone");

                if (likesCount == 0) {

                    $('.item-likes-count[data-id=' + itemId + ']').addClass("gone");

                } else {

                    $('.item-likes-count[data-id=' + itemId + ']').removeClass("gone");
                }

                if (commentsCount == 0) {

                    $('.item-comments-count[data-id=' + itemId + ']').addClass("gone");

                } else {

                    $('.item-comments-count[data-id=' + itemId + ']').removeClass("gone");
                }

                if (repostsCount == 0) {

                    $('.item-reposts-count[data-id=' + itemId + ']').addClass("gone");

                } else {

                    $('.item-reposts-count[data-id=' + itemId + ']').removeClass("gone");
                }
            }
        },
        error: function(xhr, type){

        }
    });
};

Reactions.more = function (itemId, reactionId, reaction) {

    $('.reactions-more-card').addClass('hidden');

    if (reactionId == 0) {

        //$('.reactions-dlg').find(".modal-body").scrollTop()

        $('.reactions-dlg').find(".loader-content").removeClass("hidden");
        $('div.reactions-content-list').html('');

        $('button.reactions-100').removeClass('active');
        $('button.reactions-0').removeClass('active');
        $('button.reactions-1').removeClass('active');
        $('button.reactions-2').removeClass('active');
        $('button.reactions-3').removeClass('active');
        $('button.reactions-4').removeClass('active');
        $('button.reactions-5').removeClass('active');

        $('button.reactions-100').addClass('hidden');
        $('button.reactions-0').addClass('hidden');
        $('button.reactions-1').addClass('hidden');
        $('button.reactions-2').addClass('hidden');
        $('button.reactions-3').addClass('hidden');
        $('button.reactions-4').addClass('hidden');
        $('button.reactions-5').addClass('hidden');
    }

    $.ajax({
        type: 'POST',
        url: '/ajax/reactions/list',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&itemId=" + itemId + "&reactionId=" + reactionId + "&reaction=" + reaction,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            $('.reactions-more-card').remove();

            $('button.reactions-100').removeClass('hidden');

            if (response.hasOwnProperty('reactions')) {

                if (response.reactions.type_0 != 0) {

                    $('button.reactions-0').removeClass('hidden');
                    $('button.reactions-0').find('span').text(response.reactions.type_0)
                }

                if (response.reactions.type_1 != 0) {

                    $('button.reactions-1').removeClass('hidden');
                    $('button.reactions-1').find('span').text(response.reactions.type_1)
                }

                if (response.reactions.type_2 != 0) {

                    $('button.reactions-2').removeClass('hidden');
                    $('button.reactions-2').find('span').text(response.reactions.type_2)
                }

                if (response.reactions.type_3 != 0) {

                    $('button.reactions-3').removeClass('hidden');
                    $('button.reactions-3').find('span').text(response.reactions.type_3)
                }

                if (response.reactions.type_4 != 0) {

                    $('button.reactions-4').removeClass('hidden');
                    $('button.reactions-4').find('span').text(response.reactions.type_4)
                }

                if (response.reactions.type_5 != 0) {

                    $('button.reactions-5').removeClass('hidden');
                    $('button.reactions-5').find('span').text(response.reactions.type_5)
                }
            }

            switch (reaction) {

                case "0": {

                    $('button.reactions-0').addClass('active');

                    break;
                }

                case "1": {

                    $('button.reactions-1').addClass('active');

                    break;
                }

                case "2": {

                    $('button.reactions-2').addClass('active');

                    break;
                }

                case "3": {

                    $('button.reactions-3').addClass('active');

                    break;
                }

                case "4": {

                    $('button.reactions-4').addClass('active');

                    break;
                }

                case "5": {

                    $('button.reactions-5').addClass('active');

                    break;
                }

                default: {

                    $('button.reactions-100').addClass('active');

                    break;
                }
            }

            if (response.hasOwnProperty('html')) {

                $('.reactions-dlg').find(".loader-content").addClass("hidden");

                $('div.reactions-content-list').append(response.html);
            }

            if (response.hasOwnProperty('banner')) {

                $('div.reactions-content-list-page').append(response.banner);
            }

        },
        error: function(xhr, type){

            $('.reactions-more-card').removeClass('hidden');
        }
    });
};

$(document).ready(function() {

    // $("a.item-reaction-button").on({
    //
    //     mouseenter: function () {
    //
    //         Reactions.show($(this).attr("data-id"));
    //     },
    //     mouseleave: function () {
    //
    //         if ($('div.item-footer-container').find("div.reactions-container").length != 0) {
    //
    //             Reactions.hide();
    //
    //         } else {
    //
    //             Reactions.cancel();
    //         }
    //     }
    // });

    $("#reactionsModal").on("show.bs.modal", function(e) {

        var $this = $(this);

        var target = $(e.relatedTarget);
        var itemId = target.attr("data-id");

        $this.attr('data-id', itemId);

        $('.reactions-dlg').find(".loader-content").removeClass("hidden");

        Reactions.more(itemId, 0, 100);
    });

    $("#reactionsModal").on("hidden.bs.modal", function(e) {

        var $this = $(this);
        $('div.reactions-content-list').html('');
    });

    $(document).on("click", "button.button-reactions", function(e) {

        var reaction = $(this).attr("data-value");
        var itemId = $(this).closest('div.reactions-dlg').attr('data-id');

        Reactions.more(itemId, 0, reaction);
    });

    $(document).on({
        mouseenter: function () {

            Reactions.show($(this).attr("data-id"));
        },
        mouseleave: function () {

            if ($('div.item-footer-container').find("div.reactions-container").length != 0) {

                Reactions.hide();

            } else {

                Reactions.cancel();
            }
        }
    }, "a.item-reaction-button");

    // $( "a.item-reaction-button" ).hover(
    //
    //     function() {
    //
    //         Reactions.show($(this).attr("data-id"));
    //
    //     }, function() {
    //
    //         if ($('div.item-footer-container').find("div.reactions-container").length != 0) {
    //
    //             Reactions.hide();
    //
    //         } else {
    //
    //             Reactions.cancel();
    //         }
    //     }
    // );

    $(document).on("mouseleave", "div.reactions-container", function(e) {

        Reactions.hide();
    });

    $(document).on("mouseenter", "div.reactions-container", function(e) {

        Reactions.cancel();
    });

    $(document).on("click", "a.item-reaction-button", function(e) {

        Reactions.remove();

        $reaction = $(this).attr("data-value");
        $item_id = $(this).attr("data-id");

        Reactions.make($item_id, $reaction);
    });


    $(document).on("click", "img.reactions-icon", function(e) {

        Reactions.remove();

        $item_id = $(this).parent().closest('div.reactions-container').attr("data-id");
        $reaction = $(this).attr("data-value");

        Reactions.make($item_id, $reaction);
    });

    $(document).on("touchend", "div.reactions-container", function(e) {

        Reactions.hide();
    });

    $(document).on("touchstart", "div.reactions-container", function(e) {

        Reactions.cancel();
    });

    $(document).on("touchstart", "a.item-reaction-button", function(e) {

        if ($('div.item-footer-container').find("div.reactions-container").length == 0) {

            Reactions.show($(this).attr("data-id"));

        } else {

            Reactions.hide();
        }
    });

    $(document).on("touchend", "a.item-reaction-button", function(e) {

        if ($('div.item-footer-container').find("div.reactions-container").length == 0) {

            Reactions.cancel();
        }
    });

    $(document).on("touchstart", "body", function(e) {

        if ($('div.item-footer-container').find("div.reactions-container").length != 0) {

            Reactions.hide();
        }
    });
});