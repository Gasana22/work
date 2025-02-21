<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="styles.css">
    <script src="path/to/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea#content',
            plugins: 'image media link',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | image media link'
        });
    </script>
</head>
<body>
    <header>
        <h1>Create a New Post</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
    <div class="container">
        <?php
        include 'includes/db.php';
        session_start();

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $user_id = $_SESSION['user_id'];
                $category = $_POST['category'];

                // Handle media file upload
                $media = $_FILES['media']['name'];
                if ($media) {
                    $target_dir = "uploads/";
                    $target_file = $target_dir . basename($_FILES["media"]["name"]);
                    move_uploaded_file($_FILES["media"]["tmp_name"], $target_file);
                }

                $query = "INSERT INTO posts (user_id, title, content, media, category) VALUES ('$user_id', '$title', '$content', '$media', '$category')";
                if (mysqli_query($conn, $query)) {
                    echo "Post created successfully!";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
                preg_match_all('/#(\w+)/', $content, $hashtags);
                foreach ($hashtags[1] as $hashtag) {
            }
        } else {
            echo "Please log in to create a post.";
        }
        ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea id="content" name="content" placeholder="Content" required></textarea>
            <input type="file" name="media">
            <input type="text" name="category" placeholder="Category" required>
            <button type="submit" class="btn">Post</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2025 Blogging Platform. All rights reserved.</p>
    </footer>
</body>
</html>
