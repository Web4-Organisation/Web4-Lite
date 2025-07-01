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

// Template partial for post reactions bar
// This component displays the reaction buttons and counts for a post
// Parameters expected: $post (post data array)

$reactionIcons = array(
    0 => '👍', // Like
    1 => '❤️', // Love  
    2 => '😂', // Laugh
    3 => '😮', // Surprise
    4 => '😢', // Sad
    5 => '😡'  // Angry
);

$reactionLabels = array(
    0 => $LANG['reaction_like'] ?? 'Like',
    1 => $LANG['reaction_love'] ?? 'Love', 
    2 => $LANG['reaction_laugh'] ?? 'Laugh',
    3 => $LANG['reaction_surprise'] ?? 'Surprise',
    4 => $LANG['reaction_sad'] ?? 'Sad',
    5 => $LANG['reaction_angry'] ?? 'Angry'
);

// Get reaction counts for this post
$reactions = new reactions($dbo);
$reactionData = $reactions->count($post['id']);

// Determine user's current reaction
$userReaction = 0;
$reactionButtonActive = '';
if (auth::isSession()) {
    $userReactionInfo = $reactions->is_exists($post['id'], auth::getCurrentUserId());
    if ($userReactionInfo['exists']) {
        $userReaction = $userReactionInfo['type'];
        $reactionButtonActive = 'active';
    }
}

?>

<div class="reactions-bar" data-post-id="<?php echo $post['id']; ?>">
    <!-- Main reaction button -->
    <div class="reaction-button-container">
        <button class="item-reaction-button <?php echo $reactionButtonActive; ?>" 
                data-id="<?php echo $post['id']; ?>" 
                data-value="<?php echo $userReaction; ?>"
                title="<?php echo $reactionLabels[$userReaction]; ?>">
            <span class="reaction-icon"><?php echo $reactionIcons[$userReaction]; ?></span>
            <span class="reaction-label"><?php echo $reactionLabels[$userReaction]; ?></span>
        </button>
    </div>

    <!-- Reaction counts display -->
    <?php if ($reactionData['count'] > 0): ?>
    <div class="reaction-counts" data-bs-toggle="modal" data-bs-target="#reactionsModal" data-id="<?php echo $post['id']; ?>">
        <?php for ($i = 0; $i <= 5; $i++): ?>
            <?php if ($reactionData['type_' . $i] > 0): ?>
                <span class="reaction-count-item">
                    <span class="reaction-emoji"><?php echo $reactionIcons[$i]; ?></span>
                    <span class="reaction-count"><?php echo $reactionData['type_' . $i]; ?></span>
                </span>
            <?php endif; ?>
        <?php endfor; ?>
        <span class="total-reactions"><?php echo $reactionData['count']; ?> reactions</span>
    </div>
    <?php endif; ?>

    <!-- Reaction picker (hidden by default, shown on hover) -->
    <div class="reaction-picker" style="display: none;">
        <?php for ($i = 0; $i <= 5; $i++): ?>
            <button class="reaction-option" 
                    data-reaction="<?php echo $i; ?>" 
                    data-post-id="<?php echo $post['id']; ?>"
                    title="<?php echo $reactionLabels[$i]; ?>">
                <img src="/public/img/reactions/<?php echo $i; ?>.png" 
                     alt="<?php echo $reactionLabels[$i]; ?>" 
                     class="reaction-icon-img">
            </button>
        <?php endfor; ?>
    </div>
</div>

<style>
.reactions-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px 0;
}

.reaction-button-container .item-reaction-button {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s;
}

.reaction-button-container .item-reaction-button:hover {
    background: #f5f5f5;
}

.reaction-button-container .item-reaction-button.active {
    background: #1877f2;
    color: white;
    border-color: #1877f2;
}

.reaction-counts {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    color: #65676b;
    font-size: 13px;
}

.reaction-count-item {
    display: flex;
    align-items: center;
    gap: 2px;
}

.reaction-emoji {
    font-size: 16px;
}

.reaction-picker {
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    border-radius: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 5px;
    display: flex;
    gap: 5px;
    z-index: 1000;
}

.reaction-option {
    border: none;
    background: none;
    padding: 5px;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.2s;
}

.reaction-option:hover {
    transform: scale(1.2);
}

.reaction-icon-img {
    width: 24px;
    height: 24px;
}
</style>