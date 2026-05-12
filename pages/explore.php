<?php

session_start();
require_once '../config/db_connection.php';
require_once '../includes/Post.php';

$database = new Database();
$db = $database->getConnection();

$postObj = new Post($db);


$search = isset($_GET['search']) ? $_GET['search'] : null;
$category_id = isset($_GET['cat']) ? $_GET['cat'] : null;






$categories = $postObj->getCategories();



$posts = $postObj->filterPosts($category_id, $search);



$current_category_name = "All Places";
if ($category_id) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $category_id) {
            $current_category_name = $cat['name'];
            break;
        }
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Tangier - Tangier Vibes</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/explore.css?v=1.3">
</head>
<body>

    <?php require '../includes/header.php'; ?>

    <main>
        <!-- Explore Hero -->
        <section class="explore_hero">
            <img src="../assets/img/explore.jpg" alt="Tangier Panorama" class="explore_hero_img" loading="lazy">
            <div class="explore_hero_content">
                <h1 class="explore_hero_title">Explore Tangier</h1>
                <p class="explore_hero_desc">Discover the magic of the Pearl of the North, from historic landmarks to hidden beaches.</p>
            </div>
        </section>

        <!-- Filters Section -->
        <section class="filters_container">
            <div class="category_filters">
                <a href="explore.php" class="cat_filter_btn <?= !$category_id ? 'active' : '' ?>">
                     All
                </a>
                <?php foreach($categories as $cat): ?>
                    <a href="explore.php?cat=<?= $cat['id'] ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="cat_filter_btn <?= $category_id == $cat['id'] ? 'active' : '' ?>">
                       <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if ($search): ?>
                 <div class="search_status">
                    Search: <strong>"<?= htmlspecialchars($search) ?>"</strong>
                </div>
            <?php endif; ?>
        </section>

        <!-- Results Grid -->
        <section class="explore_results">
            <?php if (!empty($posts)): ?>
                <div class="places_grid" id="posts-grid">
                    <?php foreach($posts as $post): ?>
                        <a href="post_detail.php?id=<?= $post['id']; ?>" class="place_card">
                            <img src="<?= htmlspecialchars($post['image']); ?>" class="place_card_img" alt="<?= htmlspecialchars($post['title']); ?>" loading="lazy">
                            <div class="place_card_overlay">
                                <span class="place_card_category">
                                    <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($post['cat_name']); ?>
                                </span>
                                <h3 class="place_card_name"><?= htmlspecialchars($post['title']); ?></h3>
                                <p class="place_card_location">
                                    <i class="fa-solid fa-location-dot"></i> Tangier, Morocco
                                </p>
                                <span class="place_card_btn">Explore <i class="fa-solid fa-arrow-right"></i></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

               <div class="pagination_container">

                    <div class="pagination_btn next">
                           <i class="fas fa-arrow-left"></i> previous 
                        </div>
                    <span class="page_info">1 / 3</span>

                    <div class="pagination_btn next">
                        Next <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

            <?php else: ?>
                <div class="no_results">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h3>No places found</h3>
                    <p>Try adjusting your search or category filters to find what you're looking for.</p>
                    <br>
                    <a href="explore.php" class="hero_btn_primary">Reset All Filters</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php require '../includes/footer.php'; ?>

    <script src="../assets/js/main.js"></script>
</body>
</html>


