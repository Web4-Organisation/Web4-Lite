# Reactions Feature Documentation

## Overview

The reactions feature allows users to react to posts with 6 different emoji reactions instead of just simple likes. Users can express their feelings with:

- 👍 Like (reaction type 0)
- ❤️ Love (reaction type 1) 
- 😂 Laugh (reaction type 2)
- 😮 Surprise (reaction type 3)
- 😢 Sad (reaction type 4)
- 😡 Angry (reaction type 5)

## Implementation Details

### Database

The feature uses a dedicated `reactions` table instead of the existing `likes` table:

```sql
CREATE TABLE IF NOT EXISTS reactions (
    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    toUserId int(11) UNSIGNED DEFAULT 0,
    fromUserId int(11) UNSIGNED DEFAULT 0,
    itemId int(11) UNSIGNED DEFAULT 0,
    reactionType int(11) UNSIGNED NOT NULL DEFAULT 0,
    notifyId int(11) UNSIGNED DEFAULT 0,
    createAt int(11) UNSIGNED DEFAULT 0,
    removeAt int(11) UNSIGNED DEFAULT 0,
    ip_addr CHAR(32) NOT NULL DEFAULT '',
    PRIMARY KEY (id)
);
```

### API Endpoints

#### reactions.react (New)
- **URL**: `/api/v2/method/reactions.react`
- **Method**: POST
- **Parameters**:
  - `accountId`: User ID
  - `accessToken`: Authentication token
  - `itemId`: Post/item ID
  - `reaction`: Reaction type (0-5)

#### reactions.get (Existing)
- **URL**: `/api/v2/method/reactions.get`
- **Method**: POST
- **Parameters**:
  - `account_id`: User ID
  - `access_token`: Authentication token
  - `item_id`: Post/item ID
  - `reaction`: Filter by reaction type (optional)

### Backend Classes

#### reactions Class
- **File**: `sys/class/class.reactions.inc.php`
- **Key Methods**:
  - `react($itemId, $reaction)`: Set/update a reaction
  - `make($itemId, $reaction)`: Legacy method (alias)
  - `count($itemId)`: Get reaction counts by type
  - `get($itemId, $reactionId, $reaction)`: Get paginated reactions list

### Frontend

#### JavaScript
- **File**: `public/js/reactions.js`
- **Key Functions**:
  - `Reactions.make(itemId, reaction)`: Submit reaction
  - `Reactions.show(itemId)`: Show reaction picker on hover
  - `Reactions.hide()`: Hide reaction picker
  - `Reactions.more(itemId, reactionId, reaction)`: Load reaction details

#### Template Partial
- **File**: `html/template-partials/post-reactions.inc.php`
- **Usage**: Include this file in post templates to display reactions

### Icons
- **Location**: `public/img/reactions/`
- **Files**: `0.png` through `5.png` for each reaction type

## Installation

1. **Run Database Migration**:
   ```sql
   -- Execute the SQL from migrations/20250701_add_reactions_table.sql
   ```

2. **Include JavaScript**:
   ```html
   <script src="/public/js/reactions.js"></script>
   ```

3. **Add Template Partial**:
   ```php
   // In your post template
   $post = array('id' => $postId, /* other post data */);
   include_once("../html/template-partials/post-reactions.inc.php");
   ```

## Usage Example

```php
// Display a post with reactions
$post = array(
    'id' => 123,
    'fromUserId' => 456,
    'post' => 'This is a sample post...'
);

// Include the reactions template
include_once("../html/template-partials/post-reactions.inc.php");
```

## Features

- **Single Reaction per User**: Each user can only have one reaction per post
- **Reaction Replacement**: Selecting a different reaction replaces the current one
- **Reaction Removal**: Clicking the same reaction removes it
- **Real-time Counts**: Reaction counts update immediately
- **Hover Picker**: Hover over reaction button to see all options
- **Modal View**: Click reaction counts to see who reacted
- **Authentication**: All reactions require valid user authentication
- **Notifications**: Users receive notifications when their posts get reactions

## Security

- All API endpoints require authentication via `accountId` and `accessToken`
- Input validation and sanitization on all parameters
- Database prepared statements prevent SQL injection
- IP address logging for audit trails

## Performance

- Indexed database queries for fast lookups
- Pagination for reaction lists
- Efficient JavaScript with event delegation
- Minimal DOM updates for smooth UX

## Customization

- Modify reaction icons in `public/img/reactions/`
- Customize CSS in the template partial
- Add new reaction types by extending the database and frontend
- Integrate with existing notification systems