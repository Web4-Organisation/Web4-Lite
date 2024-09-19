window.Friends || ( window.Friends = {} );

Friends.remove = function (profile_id) {

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + '/method/friends.remove',
        data: 'friendId=' + profile_id + "&accessToken=" + account.accessToken + "&accountId=" + account.id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (options.pageId === "profile") {

                $("a#btn-friend-action").removeClass("red yellow green blue").addClass('blue').html(strings.sz_action_add_to_friends);
                $("a#btn-friend-action").attr('onClick', 'Friends.sendRequest(\'' + profile_id +  '\'); return false;');
            }
        },
        error: function(xhr, type){

        }
    });
};


Friends.sendRequest = function (profile_id) {

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + '/method/friends.sendRequest',
        data: 'profileId=' + profile_id + "&accessToken=" + account.accessToken + "&accountId=" + account.id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (options.pageId === "profile") {

                $("a#btn-friend-action").removeClass("red yellow green blue").addClass('yellow').html(strings.sz_action_cancel_friends_request);
                $("a#btn-friend-action").attr('onClick', 'Friends.cancelRequest(\'' + profile_id +  '\'); return false;');
            }
        },
        error: function(xhr, type){

        }
    });
};

Friends.cancelRequest = function (profile_id) {

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + '/method/friends.sendRequest',
        data: 'profileId=' + profile_id + "&accessToken=" + account.accessToken + "&accountId=" + account.id,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

            if (options.pageId === "profile") {

                $("a#btn-friend-action").removeClass("red yellow green blue").addClass('blue').html(strings.sz_action_add_to_friends);
                $("a#btn-friend-action").attr('onClick', 'Friends.sendRequest(\'' + profile_id +  '\'); return false;');

            } else {

                $('li.card-item[data-id=' + profile_id + ']').remove();
            }
        },
        error: function(xhr, type){

        }
    });
};

Friends.acceptRequest = function (id, profile_id) {

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + '/method/friends.acceptRequest',
        data: 'friendId=' + profile_id + "&accessToken=" + account.accessToken + "&accountId=" + account.id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('li.card-item[data-id=' + id + ']').remove();
        },
        error: function(xhr, type){

        }
    });
};

Friends.rejectRequest = function (id, profile_id) {

    $.ajax({
        type: 'POST',
        url: '/api/' + options.api_version + '/method/friends.rejectRequest',
        data: 'friendId=' + profile_id + "&accessToken=" + account.accessToken + "&accountId=" + account.id,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            $('li.card-item[data-id=' + id + ']').remove();
        },
        error: function(xhr, type){

        }
    });
};

Friends.more = function (profile, offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/' + profile + '/friends',
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
};

Friends.moreOutboxRequests = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/friends/outbox',
        data: 'id=' + offset + "&loaded=" + friend_requests_loaded,
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

            friend_requests_loaded = response.friend_requests_loaded;
            friend_requests_all = response.friend_requests_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};

Friends.moreInboxRequests = function (offset) {

    $('button.loading-button').attr("disabled", "disabled");

    $.ajax({
        type: 'POST',
        url: '/account/friends/inbox',
        data: 'id=' + offset + "&loaded=" + friend_requests_loaded,
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

            friend_requests_loaded = response.friend_requests_loaded;
            friend_requests_all = response.friend_requests_all;
        },
        error: function(xhr, type){

            $('button.loading-button').removeAttr("disabled");
        }
    });
};