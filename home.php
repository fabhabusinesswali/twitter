<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'uepvgtlqk6yu0', 'oqijcfrag4o1', 'dbyvflmrtu1mce');
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Twitter Clone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #0F1419;
            display: flex;
            min-height: 100vh;
        }

        /* Left Sidebar */
        .sidebar {
            width: 275px;
            padding: 0 12px;
            position: fixed;
            height: 100%;
            border-right: 1px solid #EFF3F4;
        }

        .logo {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #1D9BF0;
            margin: 12px;
            border-radius: 50%;
            cursor: pointer;
        }

        .logo:hover {
            background-color: rgba(29, 155, 240, 0.1);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 50px;
            color: #0F1419;
            text-decoration: none;
            font-size: 20px;
            transition: 0.2s;
        }

        .nav-link:hover {
            background-color: #E8F5FD;
        }

        .nav-link i {
            margin-right: 20px;
            font-size: 24px;
        }

        .tweet-btn {
            background-color: #1D9BF0;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 0;
            width: 90%;
            font-size: 17px;
            font-weight: 700;
            margin: 15px auto;
            cursor: pointer;
            display: block;
        }

        .tweet-btn:hover {
            background-color: #1A8CD8;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 275px;
            margin-right: 350px;
            border-right: 1px solid #EFF3F4;
        }

        .header {
            padding: 15px;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid #EFF3F4;
            position: sticky;
            top: 0;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
        }

        .tweet-box {
            padding: 15px;
            border-bottom: 1px solid #EFF3F4;
        }

        .tweet-editor {
            display: flex;
            gap: 12px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #333;
        }

        .tweet-input-box {
            flex: 1;
        }

        .tweet-input {
            width: 100%;
            background: transparent;
            border: none;
            color: #0F1419;
            font-size: 20px;
            outline: none;
            resize: none;
            height: 60px;
            margin-bottom: 12px;
        }

        .tweet-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #EFF3F4;
        }

        .tweet-icons {
            display: flex;
            gap: 15px;
            color: #1D9BF0;
        }

        .tweet-submit {
            background-color: #1D9BF0;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .tweet-submit:disabled {
            opacity: 0.5;
            cursor: default;
        }

        /* Tweet Feed */
        .tweet {
            padding: 15px;
            border-bottom: 1px solid #EFF3F4;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .tweet:hover {
            background-color: #F7F7F7;
        }

        .tweet-header {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 5px;
        }

        .tweet-user-info {
            flex: 1;
        }

        .tweet-user-name {
            font-weight: 700;
            color: #0F1419;
            text-decoration: none;
            cursor: pointer;
        }

        .tweet-user-name:hover {
            text-decoration: underline;
        }

        .tweet-user-handle, .tweet-time {
            color: #536471;
            margin-left: 5px;
        }

        .tweet-content {
            margin-left: 58px;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .tweet-footer {
            margin-left: 58px;
            display: flex;
            justify-content: space-between;
            max-width: 425px;
            color: #536471;
        }

        .tweet-action-button {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .tweet-action-button:hover {
            color: #1D9BF0;
        }

        /* Right Sidebar */
        .right-sidebar {
            width: 350px;
            position: fixed;
            right: 0;
            padding: 15px;
            background-color: #FFFFFF;
        }

        .search-box {
            background-color: #EFF3F4;
            border-radius: 50px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .search-box input {
            background: transparent;
            border: none;
            color: #0F1419;
            width: 100%;
            outline: none;
            font-size: 15px;
        }

        .trends-box {
            background-color: #F7F9F9;
            border-radius: 16px;
            padding: 15px;
        }

        .trends-header {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .trend-item {
            padding: 10px 0;
            cursor: pointer;
        }

        .trend-item:hover {
            background-color: #1D1F23;
        }

        .trend-category {
            font-size: 13px;
            color: #71767B;
        }

        .trend-name {
            font-weight: 700;
            margin: 3px 0;
        }

        .trend-tweets-count {
            font-size: 13px;
            color: #71767B;
        }

        .logout-section {
            margin-top: 20px;
            padding: 12px;
            border-radius: 50px;
            cursor: pointer;
        }

        .logout-section:hover {
            background-color: #181818;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-name {
            font-weight: 700;
        }

        .user-handle {
            color: #71767B;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
        }

        .modal-content {
            background-color: #000;
            margin: 50px auto;
            padding: 20px;
            border-radius: 16px;
            width: 600px;
            position: relative;
            border: 1px solid #2F3336;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #E7E9EA;
            font-size: 24px;
            cursor: pointer;
        }

        .active {
            font-weight: bold;
        }

        .nav-link:hover {
            background-color: #181818;
        }

        .nav-link.active {
            color: #1D9BF0;
        }

        .follow-btn {
            background-color: #0F1419;
            color: #FFFFFF;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 700;
            cursor: pointer;
            margin-left: auto;
            transition: background-color 0.2s;
        }

        .follow-btn:hover {
            background-color: #272C30;
        }

        .follow-btn.following {
            background-color: #FFFFFF;
            color: #0F1419;
            border: 1px solid #CFD9DE;
        }

        .follow-btn.following:hover {
            background-color: #FFE8E8;
            border-color: #FFD7D7;
            color: #F4212E;
        }
    </style>
</head>
<body>
    <!-- Left Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fab fa-twitter"></i>
        </div>
        <div class="sidebar-menu">
            <a href="home.php" class="nav-link active">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="explore.php" class="nav-link">
                <i class="fas fa-search"></i>
                <span>Explore</span>
            </a>
            <a href="notifications.php" class="nav-link">
                <i class="far fa-bell"></i>
                <span>Notifications</span>
            </a>
            <a href="messages.php" class="nav-link">
                <i class="far fa-envelope"></i>
                <span>Messages</span>
            </a>
            <a href="profile.php?username=<?php echo htmlspecialchars($username); ?>" class="nav-link">
                <i class="far fa-user"></i>
                <span>Profile</span>
            </a>
            <button class="tweet-btn" onclick="openTweetModal()">Tweet</button>
        </div>
        <div class="logout-section" onclick="logout()">
            <div class="user-info">
                <div class="avatar"></div>
                <div>
                    <div class="user-name"><?php echo htmlspecialchars($username); ?></div>
                    <div class="user-handle">@<?php echo htmlspecialchars($username); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">Home</div>
        <div class="tweet-box">
            <div class="tweet-editor">
                <div class="avatar"></div>
                <div class="tweet-input-box">
                    <textarea class="tweet-input" placeholder="What's happening?" maxlength="280"></textarea>
                    <div class="tweet-actions">
                        <div class="tweet-icons">
                            <i class="far fa-image"></i>
                            <i class="far fa-smile"></i>
                            <i class="fas fa-poll"></i>
                            <i class="far fa-calendar"></i>
                        </div>
                        <button class="tweet-submit" onclick="postTweet()">Tweet</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="tweet-feed"></div>
    </div>

    <!-- Right Sidebar -->
    <div class="right-sidebar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search Twitter">
        </div>
        <div class="trends-box">
            <div class="trends-header">Trends for you</div>
            <div class="trend-item">
                <div class="trend-category">Trending in Technology</div>
                <div class="trend-name">#PHP</div>
                <div class="trend-tweets-count">50.4K Tweets</div>
            </div>
            <div class="trend-item">
                <div class="trend-category">Trending in Programming</div>
                <div class="trend-name">#JavaScript</div>
                <div class="trend-tweets-count">32.1K Tweets</div>
            </div>
        </div>
    </div>

    <!-- Add this modal for new tweets -->
    <div id="tweetModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="tweet-editor">
                <div class="avatar"></div>
                <div class="tweet-input-box">
                    <textarea class="tweet-input-modal" placeholder="What's happening?" maxlength="280"></textarea>
                    <div class="tweet-actions">
                        <div class="tweet-icons">
                            <i class="far fa-image"></i>
                            <i class="far fa-smile"></i>
                            <i class="fas fa-poll"></i>
                            <i class="far fa-calendar"></i>
                        </div>
                        <button class="tweet-submit" onclick="postTweetFromModal()">Tweet</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadTweets);

        function createTweetHTML(tweet) {
            const date = new Date(tweet.created_at);
            const timeAgo = getTimeAgo(date);
            
            const followButton = tweet.user_id != <?php echo $_SESSION['user_id']; ?> ? 
                `<button 
                    onclick="followUser(${tweet.user_id}, event)" 
                    class="follow-btn ${tweet.is_following ? 'following' : ''}"
                    data-user-id="${tweet.user_id}">
                    ${tweet.is_following ? 'Following' : 'Follow'}
                </button>` : '';
            
            return `
                <div class="tweet">
                    <div class="tweet-header">
                        <div class="avatar"></div>
                        <div class="tweet-user-info">
                            <a href="profile.php?username=${tweet.username}" class="tweet-user-name">${tweet.username}</a>
                            <span class="tweet-user-handle">@${tweet.username}</span>
                            <span class="tweet-time">· ${timeAgo}</span>
                        </div>
                        ${followButton}
                    </div>
                    <div class="tweet-content">${tweet.content}</div>
                    <div class="tweet-footer">
                        <div class="tweet-action-button">
                            <i class="far fa-comment"></i>
                            <span>0</span>
                        </div>
                        <div class="tweet-action-button">
                            <i class="fas fa-retweet"></i>
                            <span>0</span>
                        </div>
                        <div class="tweet-action-button" onclick="likeTweet(${tweet.id}, event)">
                            <i class="${tweet.user_liked ? 'fas' : 'far'} fa-heart"></i>
                            <span>${tweet.likes}</span>
                        </div>
                        <div class="tweet-action-button">
                            <i class="far fa-share-square"></i>
                        </div>
                    </div>
                </div>
            `;
        }

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            
            let interval = seconds / 31536000;
            if (interval > 1) return Math.floor(interval) + "y";
            
            interval = seconds / 2592000;
            if (interval > 1) return Math.floor(interval) + "mo";
            
            interval = seconds / 86400;
            if (interval > 1) return Math.floor(interval) + "d";
            
            interval = seconds / 3600;
            if (interval > 1) return Math.floor(interval) + "h";
            
            interval = seconds / 60;
            if (interval > 1) return Math.floor(interval) + "m";
            
            return Math.floor(seconds) + "s";
        }

        function loadTweets() {
            fetch('get_tweets.php')
                .then(response => response.json())
                .then(data => {
                    const tweetFeed = document.getElementById('tweet-feed');
                    tweetFeed.innerHTML = '';
                    data.forEach(tweet => {
                        tweetFeed.innerHTML += createTweetHTML(tweet);
                    });
                });
        }

        function postTweet() {
            const content = document.querySelector('.tweet-input').value;
            if (!content.trim()) return;

            const formData = new FormData();
            formData.append('content', content);

            fetch('post_tweet.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.tweet-input').value = '';
                    loadTweets();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to post tweet');
            });
        }

        function likeTweet(tweetId, event) {
            event.stopPropagation();
            fetch('like_tweet.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ tweet_id: tweetId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadTweets();
                }
            });
        }

        function logout() {
            window.location.href = 'logout.php';
        }

        // Enable/disable tweet button based on input
        const tweetInput = document.querySelector('.tweet-input');
        const tweetSubmit = document.querySelector('.tweet-submit');
        
        tweetInput.addEventListener('input', function() {
            tweetSubmit.disabled = !this.value.trim();
        });

        function openTweetModal() {
            const modal = document.getElementById('tweetModal');
            modal.style.display = 'block';
            document.querySelector('.tweet-input-modal').focus();
        }

        function postTweetFromModal() {
            const content = document.querySelector('.tweet-input-modal').value;
            if (!content.trim()) return;

            fetch('post_tweet.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ content: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.tweet-input-modal').value = '';
                    document.getElementById('tweetModal').style.display = 'none';
                    loadTweets();
                }
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('tweetModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Close modal when clicking X
        document.querySelector('.close-modal').onclick = function() {
            document.getElementById('tweetModal').style.display = 'none';
        }

        // Enable/disable tweet button in modal
        const tweetInputModal = document.querySelector('.tweet-input-modal');
        const tweetSubmitModal = document.querySelector('.tweet-submit');

        tweetInputModal.addEventListener('input', function() {
            tweetSubmitModal.disabled = !this.value.trim();
        });

        // Highlight active nav link
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });

        function followUser(userId, event) {
            event.stopPropagation();
            
            fetch('follow_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    follow_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const followBtn = document.querySelector(`[data-user-id="${userId}"]`);
                    if (data.following) {
                        followBtn.textContent = 'Following';
                        followBtn.classList.add('following');
                    } else {
                        followBtn.textContent = 'Follow';
                        followBtn.classList.remove('following');
                    }
                }
            });
        }
    </script>
</body>
</html> 
