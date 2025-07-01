<?php

/*!
 * Linkspreed UG
 * Web4 Lite published under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License. (BY-NC-SA 4.0)
 *
 * https://linkspreed.com
 * https://web4.one
 *
 * Copyright (c) 2025 Linkspreed UG (hello@linkspreed.com)
 * Copyright (c) 2025 Marc Herdina (marc.herdina@linkspreed.com)
 * 
 * Web4 Lite (c) 2025 by Linkspreed UG & Marc Herdina is licensed under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/.
 */

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class reactions extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';
    private $table = 'reactions';
    private $itemType = ITEM_TYPE_POST;

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);

        $this->table = 'reactions';
	}

    public function allCount()
    {
        $sql = "SELECT max(id) FROM ".$this->table;

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count($itemId)
    {
        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "count" => 0,
            "type_0" => 0,
            "type_1" => 0,
            "type_2" => 0,
            "type_3" => 0,
            "type_4" => 0,
            "type_5" => 0
        );

        $sql = "SELECT id, reactionType FROM $this->table WHERE itemId = (:itemId) AND removeAt = 0";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $result['count']++;
                    $result['type_' . $row['reactionType']]++;
                }
            }
        }

        return $result;
    }

    public function is_exists($itemId, $fromUserId)
    {
        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "exists" => false,
            "type" => 0,
        );

        $sql = "SELECT id, reactionType FROM $this->table WHERE fromUserId = (:fromUserId) AND itemId = (:itemId) AND removeAt = 0 LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":fromUserId", $fromUserId, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            $result['exists'] = true;
            $result['type'] = $row['reactionType'];
        }

        return $result;
    }

    public function get($itemId, $reactionId = 0, $reaction = 100)
    {

        if ($reactionId == 0) {

            $reactionId = 1000000;
        }

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "reactionId" => $reactionId,
            "reactions" => $this->count($itemId),
            "items" => array()
        );

        if ($reaction == 100) {

            $sql = "SELECT * FROM $this->table WHERE itemId = (:itemId) AND id < (:reactionId) AND removeAt = 0 ORDER BY id DESC LIMIT 20";

        } else {

            $sql = "SELECT * FROM $this->table WHERE itemId = (:itemId) AND id < (:reactionId) AND removeAt = 0 AND reactionType = $reaction ORDER BY id DESC LIMIT 20";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':reactionId', $reactionId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['fromUserId']);
                    $profile->setRequestFrom($this->getRequestFrom());
                    $profileInfo = $profile->getVeryShort();
                    $profileInfo['reaction'] = $row['reactionType'];
                    unset($profile);

                    array_push($result['items'], $profileInfo);

                    $result['reactionId'] = $row['id'];
                }
            }
        }

        return $result;
    }

    public function make($itemId, $reaction)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $item = new post($this->db);
        $item->setRequestFrom($this->getRequestFrom());

        $gcm_notify_type = GCM_NOTIFY_LIKE;
        $notify_type = NOTIFY_TYPE_LIKE;

        $itemInfo = $item->info($itemId);

        if ($itemInfo['error']) {

            return $result;
        }

        if ($itemInfo['removeAt'] != 0) {

            return $result;
        }

        $reaction_info = $this->is_exists($itemId, $this->getRequestFrom());

        if ($reaction_info['exists']) {

            $removeAt = time();

            $sql = "UPDATE $this->table SET removeAt = (:removeAt) WHERE itemId = (:itemId) AND fromUserId = (:fromUserId) AND removeAt = 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
            $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
            $stmt->bindParam(":removeAt", $removeAt, PDO::PARAM_INT);
            $stmt->execute();

            $notify = new notify($this->db);
            $notify->removeNotify($itemInfo['fromUserId'], $this->getRequestFrom(), $notify_type, $itemId);
            unset($notify);

            $itemInfo['likesCount'] = $itemInfo['likesCount'] - 1;
            $itemInfo['myLike'] = false;
            $itemInfo['myLikeType'] = 0;

            if ($reaction_info['type'] != $reaction) {

                $this->add($itemInfo, $reaction);

                $itemInfo['likesCount'] = $itemInfo['likesCount'] + 1;
                $itemInfo['myLike'] = true;
                $itemInfo['myLikeType'] = $reaction;
            }

        } else {

            $this->add($itemInfo, $reaction);

            $itemInfo['likesCount'] = $itemInfo['likesCount'] + 1;
            $itemInfo['myLike'] = true;
            $itemInfo['myLikeType'] = $reaction;
        }

        $item->recalculate($itemId);

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "likesCount" => $itemInfo['likesCount'],
            "commentsCount" => $itemInfo['commentsCount'],
            "rePostsCount" => 0,
            "myLike" => $itemInfo['myLike'],
            "myLikeType" => $itemInfo['myLikeType']
        );

        $result['rePostsCount'] = $itemInfo['rePostsCount'];

        return $result;
    }

    private function add($itemInfo, $reaction)
    {
        $createAt = time();
        $ip_addr = helper::ip_addr();

        $gcm_notify_type = GCM_NOTIFY_LIKE;
        $notify_type = NOTIFY_TYPE_LIKE;

        $sql = "INSERT INTO $this->table (toUserId, fromUserId, itemId, reactionType, createAt, ip_addr) value (:toUserId, :fromUserId, :itemId, :reactionType, :createAt, :ip_addr)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":toUserId", $itemInfo['fromUserId'], PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemInfo['id'], PDO::PARAM_INT);
        $stmt->bindParam(":reactionType", $reaction, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $createAt, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->execute();

        if ($itemInfo['fromUserId'] != $this->getRequestFrom()) {

            $blacklist = new blacklist($this->db);
            $blacklist->setRequestFrom($itemInfo['fromUserId']);

            if (!$blacklist->isExists($this->getRequestFrom())) {

                $fcm = new fcm($this->db);
                $fcm->setRequestFrom($this->getRequestFrom());
                $fcm->setRequestTo($itemInfo['fromUserId']);
                $fcm->setType($gcm_notify_type);
                $fcm->setTitle("You have new reaction");
                $fcm->setItemId($itemInfo['id']);
                $fcm->prepare();
                $fcm->send();
                unset($fcm);

                $notify = new notify($this->db);
                $notifyId = $notify->createNotify($itemInfo['fromUserId'], $this->getRequestFrom(), $notify_type, $itemInfo['id']);
                $notify->setNotifySubType($notifyId, $reaction);
                unset($notify);
            }

            unset($blacklist);
        }
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setRequestFrom($requestFrom)
    {
        $this->requestFrom = $requestFrom;
    }

    public function getRequestFrom()
    {
        return $this->requestFrom;
    }

    public function react($itemId, $reaction)
    {
        // react method is an alias for make method to maintain API compatibility
        return $this->make($itemId, $reaction);
    }
}
