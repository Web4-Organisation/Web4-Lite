function auth () {

    if (account.id != 0) {

        return true;
    }

    return false;
}

window.App || ( window.App = {} );

App.hTimer = 0;
App.time_ms = 7000;

App.hChatTimer = 0;
App.chat_time_ms = 2000;

App.init = function() {

    if (App.hTimer) clearTimeout(App.hTimer);

    App.run();
};

App.run = function() {

    $.ajax({
        type: "POST",
        url: "/api/" + options.api_version + "/method/account.getSettings",
        data: "accountId=" + account.id + "&accessToken=" + account.accessToken,
        success: function(response) {

            if (response.error === false) {

                if (response.hasOwnProperty('notificationsCount')) {

                    if (response.notificationsCount < 1) {

                        $("span.notifications-badge").addClass("hidden");
                        $('span.notifications-primary-badge').text("");

                    } else {

                        $("span.notifications-badge").removeClass("hidden");
                        $('span.notifications-primary-badge').text(response.notificationsCount);
                    }
                }

                if (response.hasOwnProperty('messagesCount')) {

                    if (response.messagesCount < 1) {

                        $("div.messages-badge").addClass("hidden");
                        $('span.messages-count').text("");
                        $('span.messages-primary-badge').text("");

                    }  else {

                        $("div.messages-badge").removeClass("hidden");
                        $('span.messages-count').text(response.messagesCount);
                        $('span.messages-primary-badge').text(response.messagesCount);
                    }
                }

                if (response.hasOwnProperty('guestsCount')) {

                    if (response.guestsCount < 1) {

                        $("div.guests-badge").addClass("hidden");
                        $('span.guests-count').text("");
                        $('span.guests-primary-badge').text("");

                    }  else {

                        $("div.guests-badge").removeClass("hidden");
                        $('span.guests-count').text(response.guestsCount);
                        $('span.guests-primary-badge').text(response.guestsCount);
                    }
                }

                if (response.hasOwnProperty('friendsCount')) {

                    if (response.friendsCount < 1) {

                        $("div.friends-badge").addClass("hidden");
                        $('span.friends-count').text("");
                        $('span.friends-primary-badge').text("");

                    }  else {

                        $("div.friends-badge").removeClass("hidden");
                        $('span.friends-count').text(response.friendsCount);
                        $('span.friends-primary-badge').text(response.friendsCount);
                    }
                }
            }
        },
        complete: function() {

            // console.log(update.time_ms)
            // Добавляем 4 секунд для следуещего обновления
            App.time_ms = App.time_ms + 4000;

            App.hTimer = setTimeout(function() {

                App.init();

            }, App.time_ms);
        }
    });
};

App.chatInit = function(chat_id, user_id, access_token) {

    if (App.hChatTimer) clearTimeout(App.hChatTimer);
    App.chatRun(chat_id, user_id, access_token);
};

App.chatRun = function(chat_id, user_id, access_token) {

    if (typeof options.pageId !== typeof undefined && options.pageId === "chat") {

        Messages.update(chat_id, user_id, access_token)
    }
};

App.getPromptBox = function() {

    var html = '<div id="promptModal" class="modal fade">';
    html +=' <div class="modal-dialog modal-dialog-centered" role="document">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<h5 class="modal-title" id="reportModal">' + strings.sz_message_prompt_title + '</h5>'
    html += '<button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    html += '</div>'; // modal-header
    html += '<div class="modal-body">';

    html += '<div class="error-summary alert alert-warning">' + strings.sz_message_prompt_like + '</div>';

    html += '</div>'; // modal-body
    html += '<div class="modal-footer">';
    html += '<a href="/" class="button secondary">' + strings.sz_action_login + '</a>';
    html += '<a href="/signup" class="button primary">' + strings.sz_action_signup + '</a>';
    html += '</div>';  // footer
    html += '</div>';  // modal-content
    html += '</div>';  // modal-dialog
    html += '</div>';  // reportModal
    $("#modal-section").html(html);
    $("#promptModal").modal();
};

App.setLanguage = function(language) {

    $('#langModal').modal('toggle');
    $.cookie("lang", language, { expires : 7, path: '/' });
    location.reload();
};

window.Users || ( window.Users = {} );

Users.follow = function (group_id) {

    if (!auth()) {

        App.getPromptBox();

        return;
    }

    $('.js_follow_btn').attr('disabled', 'disabled');

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + '/method/group.follow',
        data: 'accountId=' + account.id + '&accessToken=' + account.accessToken + "&groupId=" + group_id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('.js_follow_btn').removeAttr('disabled');

            if (response.error === false) {

                if (response.hasOwnProperty('follow')) {

                    if (response.follow === false) {

                        $('a.js_follow_btn').removeClass("green yellow").addClass("green").text(strings.sz_action_follow);

                    } else {

                        $('a.js_follow_btn').removeClass("green yellow").addClass("yellow").text(strings.sz_action_unfollow);
                    }
                }


                $(".js_follow_block").html(response.html);

                if (options.pageId === "group") {

                    if (response.hasOwnProperty('followersCount')) {

                        if ($("#stat_followers_count").length != 0) {

                            $("#stat_followers_count").html(response.followersCount);
                        }
                    }
                }
            }
        },
        error: function(xhr, type){


        }
    });
}

window.Report || ( window.Report = {} );

Report.showDialog = function (itemId, itemType) {

    var html = '<div id="reportModal" class="modal fade">';
    html +=' <div class="modal-dialog modal-dialog-centered" role="document">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<h5 class="modal-title" id="reportModal">' + strings.sz_action_report + '</h5>'
    html += '<button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    html += '</div>'; // modal-header
    html += '<div class="modal-body">';

    html += '<a onclick="Report.send(\'' + itemId + '\', \'' + itemType + '\', \'0\'); return false;" class="box-menu-item" href="javascript:void(0)">' + strings.sz_report_reason_1 + '</a>';
    html += '<a onclick="Report.send(\'' + itemId + '\', \'' + itemType + '\', \'1\'); return false;" class="box-menu-item" href="javascript:void(0)">' + strings.sz_report_reason_2 + '</a>';
    html += '<a onclick="Report.send(\'' + itemId + '\', \'' + itemType + '\', \'2\'); return false;" class="box-menu-item" href="javascript:void(0)">' + strings.sz_report_reason_3 + '</a>';
    html += '<a onclick="Report.send(\'' + itemId + '\', \'' + itemType + '\', \'3\'); return false;" class="box-menu-item" href="javascript:void(0)">' + strings.sz_report_reason_4 + '</a>';

    html += '</div>'; // modal-body
    html += '<div class="modal-footer">';
    html += '<button type="button" class="button primary" data-dismiss="modal">' + strings.sz_action_close + '</button>';
    html += '</div>';  // footer
    html += '</div>';  // modal-content
    html += '</div>';  // modal-dialog
    html += '</div>';  // reportModal
    $("#modal-section").html(html);
    $("#reportModal").modal();
};

Report.send = function (itemId, itemType, abuseId) {

    $('#reportModal').modal('toggle');

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + '/method/reports.new',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&itemId=" + itemId + "&itemType=" + itemType + "&abuseId=" + abuseId,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            //
        },
        error: function(xhr, type) {

            //
        }
    });
};

window.Post || ( window.Post = {} );

Post.hide = function (itemId) {

    $('.admin[data-id=' + itemId + ']').hide();

    $.cookie("hidden_item_id", itemId, { expires : 7, path: '/' });
}

Post.remove = function (itemId, allowRestore) {

    $('.card [data-id=' + itemId + ']').hide();

    $.ajax({
        type: 'POST',
        url: '/api/v2/method/items.remove',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&itemId=" + itemId + "&allowRestore=" + allowRestore,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('.card[data-id=' + itemId + ']').remove();

            if (options.pageId === "profile") {

                inbox_loaded = inbox_loaded - 1;
                inbox_all = inbox_all - 1;

                if (inbox_all > 0) {

                    $('#stat_posts_count').html(inbox_all);

                } else {

                    $('#stat_posts_count').html("");
                }
            }
        },
        error: function(xhr, type){

            $('.card[data-id=' + itemId + ']').show();
        }
    });
};

Post.restore = function (itemId) {

    $('.card [data-id=' + itemId + ']').hide();

    $.ajax({
        type: 'POST',
        url: '/api/v2/method/items.restore',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&itemId=" + itemId,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('.card[data-id=' + itemId + ']').remove();
        },
        error: function(xhr, type){

            $('.card[data-id=' + itemId + ']').show();
        }
    });
};

Post.pin = function (itemId) {

    var pinned = false;

    // check if item pinned
    if ($('.card[data-id=' + itemId + ']').find("span.post-pinned-badge").length) {

        pinned = true;
    }

    // remove all previous pinned badges
    $('div.card').find("span.post-pinned-badge").remove();

    // set all drop down menu items text
    $('div.card').find("a.pin-item-action").text(strings.sz_action_pin);

    if (!pinned) {

        // pin item in top list
        $('.card[data-id=' + itemId + ']').prependTo("div.items-list");

        // add pinned badge to item
        $('.card[data-id=' + itemId + ']').find("span.post-item-time").append("<span class=\"user-badge post-pinned-badge ml-1\" rel=\"tooltip\" title=\"\"><i class=\"iconfont icofont-tack-pin\"></i></span>");
        $('.card[data-id=' + itemId + ']').find("a.pin-item-action").text(strings.sz_action_unpin);
    }

    $.ajax({
        type: 'POST',
        url: '/api/v2/method/items.pin',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&itemId=" + itemId,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            if (!pinned) {

                // scroll list to item

                $('html, body').animate({

                    scrollTop:  $('.card[data-id=' + itemId + ']').offset().top

                }, 1000);
            }
        },
        error: function(xhr, type){


        }
    });
};

Post.getRepostBox = function(username, postId, title, myRePost) {

    if (!auth()) {

        App.getPromptBox();

        return;
    }

    if (myRePost == 1) {

        return;
    }

    var url = "/" + username + "/repost/?action=get-box&postId=" + postId;
    $.colorbox({width:"450px", href: url, title: title, fixed:true});
};

Post.rePost = function (username) {

    var postText = $('textarea[name=postText]').val();
    var postImg =  $('input[name=postImg]').val();
    var rePostId =  $('input[name=rePostId]').val();

    postText = $.trim(postText);
    postImg = $.trim(postImg);

    if (postText.length == 0 && postImg == 0 && rePostId == 0) {

        return;
    }

    $.ajax({
        type: 'POST',
        url: '/' + username + "/post",
        data: $("form.repost_form").serialize(),
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            $("a.post-share[data-id=" + rePostId + "]").prop('onclick',null).off('click');
            //$("a.action_share[data-id=" + rePostId + "]").remove();
            $.colorbox.close();
        },
        error: function(xhr, type){

        }
    });
};

window.Item || ( window.Item = {} );

Item.like = function (itemId, itemType) {

    if (!auth()) {

        App.getPromptBox();

        return;
    }

    $.ajax({
        type: 'POST',
        url: '/api/v2/method/likes.like',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&itemId=" + itemId + "&itemType=" + itemType,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (response.myLike) {

                $('.item-like-button[data-id=' + itemId + ']').addClass("active");

            } else {

                $('.item-like-button[data-id=' + itemId + ']').removeClass("active");
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
}

window.Group || ( window.Group = {} );

Group.changePhoto = function(title, accountId, accessToken, groupId) {

    var url = "/account/ajax_photo/?action=get-box";
    $.colorbox({width:"450px", href: url, title: title, overlayClose: false, fixed:true, onComplete: function(){

            $('.cover_input').upload({
                name: 'uploaded_file',
                method: 'post',
                params: {"accountId": accountId, "accessToken": accessToken, "groupId": groupId},
                enctype: 'multipart/form-data',
                action: '/api/v2/method/group.uploadPhoto',
                onComplete: function(text) {

                    var response = JSON.parse(text);

                    if (response.hasOwnProperty('error')) {

                        //alert(response.normalCoverUrl);

                        if (!response.error) {

                            if (response.hasOwnProperty('bigPhotoUrl')) {

                                $('img.user_image').attr("src", response.bigPhotoUrl);
                                $('a.profile_img_wrap').attr("data-img", response.normalPhotoUrl);

                                PhotoExists = true;

                                $.colorbox.close();
                            }
                        }
                    }

                    $("div.file_loader_block").hide();
                    $("div.file_select_block").show();
                },
                onSubmit: function() {

                    $("div.file_select_block").hide();
                    $("div.file_loader_block").show();
                }
            });
        }});
};

window.Search || ( window.Search = {} );

Search.more = function (offset, online, gender, photo) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/search/name',
        data: 'userId=' + offset + "&loaded=" + items_loaded + "&query=" + query + "&online=" + online + "&gender=" + gender + "&photo=" + photo,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.grid-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

Search.communitiesMore = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/search/groups',
        data: 'itemId=' + offset + "&loaded=" + items_loaded + "&query=" + query,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

Search.marketMore = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/search/market',
        data: 'itemId=' + offset + "&loaded=" + items_loaded + "&query=" + query,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.Hashtags || ( window.Hashtags = {} );

Hashtags.more = function (offset, hashtag) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/search/hashtag/?src=' + hashtag,
        data: 'postId=' + offset + "&loaded=" + inbox_loaded + "&hashtag=" + hashtag,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("ul.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            inbox_loaded = response.inbox_loaded;
            inbox_all = response.inbox_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

window.Followers || ( window.Followers = {} );

Followers.more = function (profile, offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/' + profile + '/followers',
        data: 'id=' + offset + "&loaded=" + friends_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            friends_loaded = response.friends_loaded;
            friends_all = response.friends_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.Guests || ( window.Guests = {} );

Guests.clear = function (access_token) {

    $.ajax({
        type: "POST",
        url: "/account/guests",
        data: "act=clear" + "&access_token=" + access_token,
        success : function(response) {

            $('div.content-list').remove();
            $('header.loading-banner').remove();
            $('div.page-title-content-extra').remove();
            $('div.content-list-page').append('<div class="card information-banner">\n' +
                '                                    <div class="card-header">\n' +
                '                                        <div class="card-body">\n' +
                '                                            <header class="top-banner info-banner empty-list-banner">\n' +
                '\n' +
                '                                            </header>\n' +
                '                                        </div>\n' +
                '                                    </div>\n' +
                '                                </div>');

            items_all = 0;
            items_loaded = 0;
        }
    });
}

Guests.more = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/guests',
        data: 'itemId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.Following || ( window.Following = {} );

Following.more = function (profile, offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/' + profile + '/following',
        data: 'id=' + offset + "&loaded=" + friends_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("ul.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            friends_loaded = response.friends_loaded;
            friends_all = response.friends_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.Likers || ( window.Likers = {} );

Likers.more = function (profile, postId, offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/' + profile + '/post/' + postId + '/people',
        data: 'likeId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.items-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.Chats || ( window.Chats = {} );

Chats.more = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/messages',
        data: 'messageCreateAt=' + offset + "&loaded=" + chats_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            chats_loaded = response.chats_loaded;
            chats_all = response.chats_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.BlackList || ( window.BlackList = {} );

BlackList.more = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/settings/blacklist',
        data: 'itemId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("ul.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.Groups || ( window.Groups = {} );

Groups.myGroupsMore = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/groups',
        data: 'itemId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

Groups.managedGroupsMore = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/managed_groups',
        data: 'itemId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

window.Notifications || ( window.Notifications = {} );

Notifications.moreAnswers = function (offset) {

    $('a.more_link').hide();
    $('a.loading_link').show();

    $.ajax({
        type: 'POST',
        url: '/account/notifications/answers',
        data: 'createAt=' + offset + "&loaded=" + answers_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('div.more_cont').remove();

            if ( response.hasOwnProperty('html') ){

                $("div.notifications_cont").append(response.html);
            }

            answers_loaded = response.answers_loaded;
            answers_all = response.answers_all;
        },
        error: function(xhr, type){

            $('a.more_link').show();
            $('a.loading_link').hide();
        }
    });
}

Notifications.clear = function (access_token) {

    $.ajax({
        type: "POST",
        url: "/account/notifications",
        data: "act=clear" + "&access_token=" + access_token,
        success : function(response) {

            $('div.cards-list').remove();
            $('header.loading-banner').remove();
            $('div.page-title-content-extra').remove();
            $('div.content-list-page').append('<div class="card information-banner"><div class="card-header"><div class="card-body"><h5 class="m-0">' + strings.sz_message_empty_list + '</h5></div></div></div>');

            notifications_all = 0;
            notifications_loaded = 0;
        }
    });
}

Notifications.moreAll = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/notifications',
        data: 'notifyId=' + offset + "&loaded=" + notifications_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            notifications_loaded = response.notifications_loaded;
            notifications_all = response.notifications_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

Notifications.moreLikes = function (offset) {

    $('a.more_link').hide();
    $('a.loading_link').show();

    $.ajax({
        type: 'POST',
        url: '/account/notifications/likes',
        data: 'createAt=' + offset + "&loaded=" + likes_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('div.more_cont').remove();

            if ( response.hasOwnProperty('html') ){

                $("div.notifications_cont").append(response.html);
            }

            likes_loaded = response.likes_loaded;
            likes_all = response.likes_all;
        },
        error: function(xhr, type){

            $('a.more_link').show();
            $('a.loading_link').hide();
        }
    });
}

window.Video || ( window.Video = {} );

Video.playYouTube = function(container, video) {

    video = "https://www.youtube.com/v/" + video + "?autoplay=1&start=0";

    $(container).parent().html("<object width=\"100%\" height=\"400\"><param name=\"movie\" value=\"" + video + "\"><param name=\"allowFullScreen\" value=\"true\"><param name=\"wmode\" value=\"transparent\"><param name=\"allowscriptaccess\" value=\"always\"><embed src=\"" + video + "\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" width=\"100%\" height=\"300\"></object>");
};

window.Background || ( window.Background = {} );

Background.set = function(img, hash) {

    $.ajax({
        type: "POST",
        url: "/account/settings/profile/background",
        data: "number=" + img + "&access_token=" + hash + "&act=setBackground",
        success : function(text) {

            if (text.length > 0) {

                $('body').css("background-image", "url(" + text + ")");
            }
        }
    });
};

window.Profile || ( window.Profile = {} );

Profile.getBlockBox = function(profile_id) {

    if (!auth()) {

        return;
    }

    var attr = $("a.js_block_btn").attr("data-action");

    if (typeof attr !== typeof undefined) {

        if (attr === "block") {

            $('#profile-block-dlg').modal('show');

        } else {

            $("a.js_block_btn").text(strings.sz_action_block);
            $("a.js_block_btn").attr("data-action", "block");

            Profile.unBlock(profile_id);
        }
    }
};

Profile.block = function(profile_id) {

    $("a.js_block_btn").text(strings.sz_action_unblock);
    $("a.js_block_btn").attr("data-action", "unblock");

    $.ajax({
        type: 'POST',
        url: "/api/" + options.api_version + "/method/blacklist.add",
        data: "accountId=" + account.id + "&accessToken=" + account.accessToken + "&profileId=" + profile_id,
        dataType: 'json',
        timeout: 30000,
        success: function (response) {


        },
        error: function (xhr, type) {

        }
    });

    $.colorbox.close();
};

Profile.unBlock = function(profile_id) {

    if (options.pageId === "settings_blacklist") {

        $('li.card-item[data-id=' + profile_id + ']').remove();
    }

    $.ajax({
        type: 'POST',
        url: "/api/" + options.api_version + "/method/blacklist.remove",
        data: "accountId=" + account.id + "&accessToken=" + account.accessToken + "&profileId=" + profile_id,
        dataType: 'json',
        timeout: 30000,
        success: function (response) {


        },
        error: function (xhr, type) {

        }
    });

    $.colorbox.close();
};

Profile.changePostMode = function (access_mode) {

    $("input[name=access_mode]").val(access_mode);
    $("button.change-post-mode-button").find("i").removeClass("icofont-earth").removeClass("icofont-users-alt-3");

    switch (access_mode) {

        case 0: {

            $("button.change-post-mode-button").find("span").text($("a.access-mode-all-button").text());
            $("button.change-post-mode-button").find("i").addClass("icofont-earth");

            break;
        }

        default: {

            $("button.change-post-mode-button").find("span").text($("a.access-mode-friends-button").text());
            $("button.change-post-mode-button").find("i").addClass("icofont-users-alt-3");

            break;
        }
    }
};

Profile.showPostForm = function () {

    $("form.profile_question_form").show();
    $('div.remotivation_block').hide();

    $('input[name=access_mode]').val(0);

    $('textarea[name=postText]').focus();
    $('textarea[name=postText]').css("height", "67px");

    Profile.changePostMode(0);
};

Profile.post = function (username) {

    var postText = $('textarea[name=postText]').val().trim();


    if (postText.length == 0 && $("div.new-post-img-item").length == 0) {

        $('textarea[name=postText]').focus();

        return;
    }

    $.ajax({
        type: 'POST',
        url: '/' + username + "/post",
        data: $("form.profile_question_form").serialize(),
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            $('textarea[name=postText]').val('');
            $("div.img_container").hide();
            $('#word_counter').html('1000');

            $("div.img-items-list-page").html("");

            $("div.item-add-image").removeClass("hidden");
            $("div.item-add-video").removeClass("hidden");

            if (response.hasOwnProperty('html')) {

                if ($("div.information-banner").length != 0) {

                    $("div.information-banner").remove();
                }

                if ($('div.items-list').length == 0) {

                    $("div.content-list-page").prepend("<div class=\"items-list content-list\"></div>");
                }

                $("div.items-list").prepend(response.html);
            }

            if (response.hasOwnProperty('postsCount')) {

                inbox_all = response.postsCount;
                $('#stat_posts_count').html(inbox_all);
            }

            $('form.profile_question_form').hide();
            $('div.remotivation_block').show();
        },
        error: function(xhr, type) {

        }
    });
};

Profile.deletePostImg = function(thisObj) {

    thisObj.parents('div.new-post-img-item').remove();

    if ($("div.new-post-video-item").length == 0 && $("div.new-post-img-item").length == 0) {

        $("div.img_container").hide();
    }

    $('div.item-add-image').removeClass("hidden");
};

Profile.addPostImg = function(url) {

    $('div.img-items-list-page').append('<div class="gallery-item new-post-img-item">\n' +
        '\n' +
        '                        <input name="images[]" value="' + url + '" type="hidden">\n' +
        '\n' +
        '                        <div class="item-inner">\n' +
        '\n' +
        '                            <div class="gallery-item-preview" style="background-image:url(' + url + ')" >\n' +
        '\n' +
        '                                <span title="Delete" class="remove" onclick="Profile.deletePostImg($(this))">×</span>\n' +
        '\n' +
        '                            </div>\n' +
        '\n' +
        '                        </div>\n' +
        '                    </div>')
};

Profile.deletePostVideo = function(thisObj) {

    thisObj.parents('div.new-post-video-item').remove();

    if ($("div.new-post-video-item").length == 0 && $("div.new-post-img-item").length == 0) {

        $("div.img_container").hide();
    }

    $('div.item-add-video').removeClass("hidden");
};

Profile.addPostVideo = function(url) {

    $('div.img-items-list-page').append('<div class="gallery-item new-post-img-item new-post-video-item">\n' +
        '\n' +
        '                        <input name="videoUrl" value="' + url + '" type="hidden">\n' +
        '\n' +
        '                        <div class="item-inner">\n' +
        '\n' +
        '                            <div class="gallery-item-preview" style="background-image:url(/img/video_preview.jpg)" >\n' +
        '\n' +
        '                                <span title="Delete" class="remove" onclick="Profile.deletePostVideo($(this))">×</span>\n' +
        '\n' +
        '                            </div>\n' +
        '\n' +
        '                        </div>\n' +
        '                    </div>')
};

window.Cover || ( window.Cover = {} );

Cover.currentBackgroundPosition = "0px 0px";
Cover.oldBackgroundPosition = "0px 0px";

Cover.edit = function() {

    if (CoverExists === false) {

        Profile.changeCover(event);
        return false;

    } else {

        Cover.oldBackgroundPosition = $('div.profile_cover').css('background-position');

        $("div.profile_cover").css("cursor", "move");
        $('div.profile_add_cover').hide();

        $("div.profile_cover_actions").show();
        $("div.profile_cover_start").hide();

        $('div.profile_cover').backgroundDraggable({axis: 'y',
            done: function() {
                Cover.currentBackgroundPosition = $('div.profile_cover').css('background-position');
                console.log(Cover.currentBackgroundPosition);
            }
        });
    }
};

Cover.save = function(access_token) {

    $("div.profile_cover").css("cursor", "default");
    $("div.profile_cover_actions").hide();
    $("div.profile_cover_start").show();
    $('div.profile_add_cover').hide();

    $('div.profile_cover').backgroundDraggable('disable');

    $.ajax({
        type: "POST",
        url: "/account/ajax_cover",
        data: "position=" + Cover.currentBackgroundPosition + "&action=save-position" + "&accessToken=" + access_token,
        success : function(text) {

            return false;
        }
    });
};

Cover.cancel = function() {

    $('div.profile_cover').css('background-position', Cover.oldBackgroundPosition);

    $("div.profile_cover").css("cursor", "default");

    $('div.profile_add_cover').hide();
    $("div.profile_cover_actions").hide();
    $("div.profile_cover_start").show();

    $('div.profile_cover').backgroundDraggable('disable');
};

Cover.delete = function(access_token) {

    $('div.profile_cover').css('background-position', '0');
    $('div.profile_cover').css('background-image', 'url(/img/cover_add.png)');
    $('div.profile_add_cover').show();

    $("div.profile_cover").css("cursor", "default");

    $("div.profile_cover_actions").hide();
    $("div.profile_cover_start").hide();

    $('div.profile_cover').backgroundDraggable('disable');

    $.ajax({
        type: "POST",
        url: "/account/ajax_cover",
        data: "action=delete-cover" + "&accessToken=" + access_token,
        success : function(text) {

            CoverExists = false;
            return false;
        }
    });
};

window.Messages || ( window.Messages = {} );

Messages.update = function (chat_id, user_id, access_token) {

    var message_id = $("li.message-item").last().attr("data-id");

    $.ajax({
        type: 'POST',
        url: '/account/ajax_chat_update',
        data: 'access_token=' + access_token + "&chat_id=" + chat_id + "&user_id=" + user_id + "&message_id=" + message_id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (response.hasOwnProperty('html')) {

                if ($("header.info-banner").length) {

                    $("header.info-banner").remove();
                }

                $("div.content-list").append(response.html);

                //$(response.html).insertBefore(".comment_form");
            }

            if (response.hasOwnProperty('items_all')) {

                items_all = response.items_all;
                items_loaded = $('li.message-item').length;
            }

            App.chat_time_ms = App.chat_time_ms + 1000;

            App.hChatTimer = setTimeout(function() {

                App.chatInit(chat_id, user_id, access_token);

            }, App.chat_time_ms);
        },
        error: function(xhr, status, error) {

            //var err = eval("(" + xhr.responseText + ")");
            //alert(err.Message);
        }
    });
};

Messages.create = function (chat_id, user_id, access_token) {


    var message_text = $('input[name=message_text]').val();
    var message_img = $('input[name=message_image]').val();
    var message_id = $("li.message-item").last().attr("data-id");

    $.ajax({
        type: 'POST',
        url: '/account/msg',
        data: 'message_text=' + encodeURIComponent(message_text) + '&message_img=' + message_img + '&access_token=' + access_token + "&chat_id=" + chat_id + "&user_id=" + user_id + "&message_id=" + message_id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (response.hasOwnProperty('html')) {

                if ($(".empty-list-banner").length) {

                    $(".empty-list-banner").remove();
                }

                $("div.content-list").append(response.html);

                $("input[name=message_text]").val("");
                $("input[name=message_image]").val("");
                $("img.msg_img_preview").attr("src", "/img/camera.png");
                $("div.image-upload-img").addClass("hidden");
                $("div.image-upload-button").removeClass("hidden");
            }

            if (response.hasOwnProperty('items_all')) {

                items_all = response.items_all;
                items_loaded = $('li.message-item').length;
            }
        },
        error: function(xhr, type){


        }
    });
};

Messages.sendSticker = function (chat_id, user_id, stickerId, stickerImgUrl) {


    var message_id = $("li.message-item").last().attr("data-id");

    $.ajax({
        type: 'POST',
        url: '/account/msg',
        data: "message_id=" + message_id  + "&chat_id=" + chat_id + "&user_id=" + user_id + "&stickerId=" + stickerId + "&stickerImgUrl=" + stickerImgUrl,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (response.hasOwnProperty('html')) {

                if ($(".empty-list-banner").length) {

                    $(".empty-list-banner").remove();
                }

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('items_all')) {

                items_all = response.items_all;
                items_loaded = $('li.message-item').length;
            }
        },
        error: function(xhr, type){


        }
    });
};


Messages.more = function (chat_id, user_id) {

    var message_id = $("li.message-item").first().attr("data-id");

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/msgMore',
        data: "chat_id=" + chat_id + "&user_id=" + user_id + "&message_id=" + message_id + "&messages_loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')) {

                $("div.content-list").prepend(response.html);
            }

            if (response.hasOwnProperty('html2')) {

                $(response.html2).insertBefore("ul.content-list");

                //$("div.content-list-page").prepend(response.html2);
            }

            if (response.hasOwnProperty('items_all')) {

                items_all = response.items_all;
                items_loaded = $('li.message-item').length;
            }
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

Messages.removeChat = function(chat_id, user_id, access_token) {

    $.ajax({
        type: 'POST',
        url: '/account/chatRemove',
        data: 'access_token=' + access_token + "&chat_id=" + chat_id + "&user_id=" + user_id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('li.card-item[data-id=' + chat_id + ']').remove();

            if ($('li.card-item').length == 0 && $('header.loading-banner').length == 0) {

                $('div.content-list').remove();
            }
        },
        error: function(xhr, type){


        }
    });
};

window.Comments || ( window.Comments = {} );

Comments.more = function (itemId, itemType, commentId) {

    $.ajax({
        type: 'POST',
        url: '/ajax/comments/more',
        data: 'itemId=' + itemId + '&itemType=' + itemType + '&commentId=' + commentId + '&accountId=' + account.id + "&accessToken=" + account.accessToken,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            if (response.hasOwnProperty('html')) {

                $("a.get_comments_header[data-id=" + itemId + "]").hide();
                $("div.comments-list[data-id=" + itemId + "]").prepend(response.html);
            }
        },
        error: function(xhr, type){

            //
        }
    });
};

Comments.remove = function (itemId, itemType) {

    $.ajax({
        type: 'POST',
        url: '/api/'+ options.api_version +'/method/comments.remove',
        data: 'accountId=' + account.id + '&accessToken=' + account.accessToken + '&commentId=' + itemId + '&itemType=' + itemType,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('div.media-comment[data-id=' + itemId + ']').remove();

            if ($(".comments-list[data-id=" + itemId + "]").children('.comment-item').length == 0) {

                $(".get_comments_header[data-id=" + itemId + "]").trigger( "click" );
            }
        },
        error: function(xhr, type){

        }
    });
};

Comments.reply = function(replyToUserId, replyToUserUsername, replyToUserFullname) {

    window.replyToUserId = replyToUserId;

    $("input[name=comment_text]").val("@" + replyToUserUsername + ", ");
    $("input[name=comment_text]").focus();
}

Comments.create = function (itemId, itemType) {

    var commentText = $("input[data-id=" + itemId + "]").val();

    $.ajax({
        type: 'POST',
        url: '/ajax/comments/new/',
        data: 'accountId=' + account.id + '&accessToken=' + account.accessToken + '&itemId=' + itemId + '&itemType=' + itemType + '&commentText=' + commentText + '&replyToUserId=' + replyToUserId,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (response.hasOwnProperty('html')) {

                $('.comments-list').append(response.html);

                //$(response.html).insertBefore(".comment_form[data-id=" + postId + "]");
                $("input[data-id=" + itemId + "]").val("");
                replyToUserId = 0;
            }
        },
        error: function(xhr, type){

        }
    });
};

$(document).ready(function() {

    $.support.cors = true;

    if (auth()) {

        App.init();
    }

    $(document).on("click", "img.user_image", function() {

        var url = $(this).parent().attr("data-img");

        if (url.length != 0) {

            $.colorbox({maxWidth:"80%", maxHeight:"80%", href:url, title: "", photo: true});
        }

        return false;
    });

    $(document).on("click", "div.gallery_img > img", function() {

        var url = $(this).attr("data-img");
        // alert(ask_id);
        // var url = $("img[data-id="+ask_id+"].answer-photo").attr("data-full");
        $.colorbox({maxWidth:"80%", maxHeight:"80%", href:url, title: "", photo: true});
        return false;
    });
});

window.Images || ( window.Images = {} );


Images.getLikers = function (profile, postId, offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/' + profile + '/image/' + postId + '/people',
        data: 'likeId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("ul.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.Items || ( window.Items = {} );

Items.more = function (url, offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: url,
        data: 'itemId=' + offset + "&loaded=" + inbox_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.items-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            inbox_loaded = response.inbox_loaded;
            inbox_all = response.inbox_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

window.Gallery || (window.Gallery = {});

Gallery.add = function (itemImg, itemPreviewImg, itemOriginImg) {

    itemImg = $.trim(itemImg);

    $.ajax({
        type: 'POST',
        url: '/api/v2/method/gallery.add',
        data: 'accessToken=' + account.accessToken + "&accountId=" + account.id + "&imgUrl=" + itemImg + "&previewImgUrl=" + itemPreviewImg + "&originImgUrl=" + itemOriginImg,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            location.reload();
        },
        error: function(xhr, type){

        }
    });
};

Gallery.remove = function (itemId) {

    $('div.gallery-item[data-id=' + itemId + ']').hide();

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + "/method/gallery.remove",
        data: 'accountId=' + account.id + '&accessToken=' + account.accessToken + '&itemId=' + itemId,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('div.gallery-item[data-id=' + itemId + ']').remove();

            if (options.pageId === "gallery" && response.hasOwnProperty('html')) {

                //
            }
        },
        error: function(xhr, type){

            $('div.gallery-item[data-id=' + itemId + ']').show();
        }
    });
};


Gallery.more = function (username, offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/' + username + '/gallery',
        data: 'itemId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.grid-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-block").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

window.Market || (window.Market = {});

Market.newItem = function () {

    var marketTitleElement = $('input[name=title]');
    var marketDescElement = $('textarea[name=description]');
    var marketPriceElement = $('input[name=price]');

    var marketTitle = marketTitleElement.val();
    var marketDesc =  marketDescElement.val();
    var marketPrice =  marketPriceElement.val();
    var marketImg =  $('input[name=imgUrl]').val();

    marketTitle = $.trim(marketTitle);
    marketDesc = $.trim(marketDesc);
    marketImg = $.trim(marketImg);
    marketPrice = $.trim(marketPrice);

    if (marketTitle.length == 0) {

        marketTitleElement.focus();

        return;
    }

    if (marketDesc.length == 0) {

        marketDescElement.focus();

        return;
    }

    if (marketPrice.length == 0) {

        marketPriceElement.focus();

        return;
    }

    var price = parseInt(marketPrice);

    if (price < 1 || Number.isNaN(price)) {

        marketPriceElement.focus();

        return;
    }

    if ($('input[name=imgUrl]').length == 0) {

        return;
    }

    $.ajax({
        type: 'POST',
        url: "/api/" + options.api_version + "/method/market.newItem",
        data: $("form.new-market-item").serialize(),
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            $('#newItemModal').modal('toggle')

            location.reload();
        },
        error: function(xhr, type){

        }
    });
};

Market.deleteItem = function (itemId) {

    $.ajax({
        type: 'POST',
        url: "/api/" + options.api_version + "/method/market.removeItem",
        data: "accountId=" + account.id + "&accessToken=" + account.accessToken + "&itemId=" + itemId,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            $('div.extended-list-item[data-id=' + itemId + ']').remove();

            if (options.pageId === "market_item") {

                window.location.href = '/search/market'
            }
        },
        error: function(xhr, type){

        }
    });
};

Market.deleteItemImg = function(thisObj) {

    thisObj.parents('div.new-post-img-item').remove();

    if ($("div.new-post-img-item").length == 0) {

        $("div.img_container").hide();
    }

    $("div.market-upload-button-container").removeClass('hidden');
};

Market.addItemImg = function(url) {

    $('div.img-items-list-page').append('<div class="gallery-item new-post-img-item">\n' +
        '\n' +
        '                        <input name="imgUrl" value="' + url + '" type="hidden">\n' +
        '\n' +
        '                        <div class="item-inner">\n' +
        '\n' +
        '                            <div class="gallery-item-preview" style="background-image:url(' + url + ')" >\n' +
        '\n' +
        '                                <span title="Delete" class="remove" onclick="Market.deleteItemImg($(this))">×</span>\n' +
        '\n' +
        '                            </div>\n' +
        '\n' +
        '                        </div>\n' +
        '                    </div>')
};

Market.productsMore = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/products',
        data: 'itemId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if (response.hasOwnProperty('html')){

                $("div.content-list").append(response.html);
            }

            if (response.hasOwnProperty('banner')){

                $("div.content-list-page").append(response.banner);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
}

window.NearbyItems || (window.NearbyItems = {});

NearbyItems.more = function (url, offset) {

    if ($('button.loading-button').length > 0) {

        $('button.loading-button').attr("disabled", "disabled");
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: 'itemId=' + offset + "&loaded=" + items_loaded,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('header.loading-banner').remove();

            if ($('.empty-list-banner').length > 0) {

                $('.empty-list-banner').remove();
            }

            if (response.hasOwnProperty('html')){

                $("div.items-view").append(response.html);

            } else {

                $("div.content-list-page").append("<header class=\"top-banner info-banner empty-list-banner\"></header>");
            }

            if (response.hasOwnProperty('html2')){

                $("div.items-container").append(response.html2);
            }

            items_loaded = response.items_loaded;
            items_all = response.items_all;
        },
        error: function(xhr, type) {

            if ($('button.loading-button').length > 0) {

                $('button.loading-button').removeAttr("disabled");
            }
        }
    });
};