<?php

/*!
 * Example: How to integrate post-reactions.inc.php template partial
 * 
 * This example shows how to include the reactions feature in any post display template.
 * You can use this pattern in any file that displays posts or content items.
 */

// Example integration in a post display template:

/*
// Within your post display loop or single post view:

// 1. Ensure you have the post data available in $post array
$post = array(
    'id' => 123,                    // Post ID
    'fromUserId' => 456,           // Author user ID  
    'post' => 'This is a post...',  // Post content
    // ... other post fields
);

// 2. Include the reactions template partial
include_once("../html/template-partials/post-reactions.inc.php");

// That's it! The reactions bar will be rendered with:
// - Reaction button showing user's current reaction
// - Count display for each reaction type
// - Hover/click functionality for selecting reactions
// - Modal integration for viewing who reacted
*/

// Example for a feed or list view:
?>

<!-- Example HTML structure for a post in a feed -->
<div class="post-container" data-post-id="<?php echo $post['id']; ?>">
    
    <!-- Post header with author info -->
    <div class="post-header">
        <img src="<?php echo $authorPhoto; ?>" class="author-avatar" />
        <div class="author-info">
            <h4><?php echo $authorName; ?></h4>
            <span class="post-time"><?php echo $postTime; ?></span>
        </div>
    </div>
    
    <!-- Post content -->
    <div class="post-content">
        <?php echo $post['post']; ?>
        
        <?php if (!empty($post['imgUrl'])): ?>
            <img src="<?php echo $post['imgUrl']; ?>" class="post-image" />
        <?php endif; ?>
    </div>
    
    <!-- Reactions bar - include the template partial -->
    <div class="post-reactions">
        <?php include_once("../html/template-partials/post-reactions.inc.php"); ?>
    </div>
    
    <!-- Comments section -->
    <div class="post-comments">
        <!-- Comments would go here -->
    </div>
    
</div>

<!-- Required JavaScript and CSS for reactions -->
<script src="/public/js/reactions.js"></script>

<!-- Modal for viewing reaction details (should be included once per page) -->
<div class="modal fade" id="reactionsModal" tabindex="-1">
    <div class="modal-dialog reactions-dlg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="loader-content">Loading...</div>
                
                <!-- Reaction filter buttons -->
                <div class="reaction-filters">
                    <button class="button-reactions reactions-100" data-value="100">All</button>
                    <button class="button-reactions reactions-0 hidden" data-value="0">
                        👍 <span>0</span>
                    </button>
                    <button class="button-reactions reactions-1 hidden" data-value="1">
                        ❤️ <span>0</span>
                    </button>
                    <button class="button-reactions reactions-2 hidden" data-value="2">
                        😂 <span>0</span>
                    </button>
                    <button class="button-reactions reactions-3 hidden" data-value="3">
                        😮 <span>0</span>
                    </button>
                    <button class="button-reactions reactions-4 hidden" data-value="4">
                        😢 <span>0</span>
                    </button>
                    <button class="button-reactions reactions-5 hidden" data-value="5">
                        😡 <span>0</span>
                    </button>
                </div>
                
                <!-- Reactions list -->
                <div class="reactions-content-list"></div>
                <div class="reactions-content-list-page"></div>
            </div>
        </div>
    </div>
</div>

<?php

/*
 * Usage Notes:
 * 
 * 1. Database Setup:
 *    - Run the migration: migrations/20250701_add_reactions_table.sql
 *    - This creates the dedicated reactions table
 * 
 * 2. API Endpoints Available:
 *    - /api/v2/method/reactions.react - Set/update a reaction
 *    - /api/v2/method/reactions.get   - Get reactions for an item
 *    - /api/v2/method/reactions.make  - Alternative endpoint (legacy)
 * 
 * 3. Reaction Types:
 *    - 0: 👍 Like
 *    - 1: ❤️ Love
 *    - 2: 😂 Laugh  
 *    - 3: 😮 Surprise
 *    - 4: 😢 Sad
 *    - 5: 😡 Angry
 * 
 * 4. Authentication:
 *    - All API calls require accountId and accessToken
 *    - Users can only have one reaction per post
 *    - Clicking same reaction removes it, different reaction replaces it
 * 
 * 5. Frontend:
 *    - reactions.js handles all JavaScript functionality
 *    - Hover to show reaction picker
 *    - Click to select reaction
 *    - Modal to view all reactions and filter by type
 * 
 * 6. Styling:
 *    - Basic CSS included in post-reactions.inc.php
 *    - Customize styles to match your theme
 *    - Icons are PNG files in /public/img/reactions/
 */

?>