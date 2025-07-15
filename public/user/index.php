<?php require_once 'inc/header.php';
require_once 'inc/connection.php';


$numPostsQuery = "SELECT COUNT(id) as total From posts";
$resQuery = mysqli_query($connection, $numPostsQuery);
$totalPosts = mysqli_fetch_assoc($resQuery)['total'];
if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}
$limit  = 3;
$offset = ($page - 1) * $limit;
$numberOfPages = ceil($totalPosts / $limit);
if ($page < 1) {
  header('location:index.php?page=1');
} else if ($page > $numberOfPages) {
  header("location:index.php?page=$numberOfPages");
}

$query = "SELECT * FROM posts LIMIT $limit OFFSET $offset";
$result = mysqli_query($connection, $query);
$numberOfPosts = mysqli_num_rows($result);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!-- Page Content -->
<!-- Banner Starts Here -->
<div class="banner header-text">
  <div class="owl-banner owl-carousel">
    <div class="banner-item-01">
      <div class="text-content">
        <!-- <h4>Best Offer</h4> -->
        <!-- <h2>New Arrivals On Sale</h2> -->
      </div>
    </div>
    <div class="banner-item-02">
      <div class="text-content">
        <!-- <h4>Flash Deals</h4> -->
        <!-- <h2>Get your best products</h2> -->
      </div>
    </div>
    <div class="banner-item-03">
      <div class="text-content">
        <!-- <h4>Last Minute</h4> -->
        <!-- <h2>Grab last minute deals</h2> -->
      </div>
    </div>
  </div>
</div>
<!-- Banner Ends Here -->

<div class="latest-products">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-heading">
          <h2>Latest Posts</h2>
          <!-- <a href="products.html">view all products <i class="fa fa-angle-right"></i></a> -->
        </div>
        <?php if (isset($_SESSION['success'])) {
        ?> <div class="alert alert-success">
            <?php echo $_SESSION['success']; ?>
          </div><?php
              }
              unset($_SESSION['success']); ?>
      </div>
      <?php if ($numberOfPosts > 0) {
        foreach ($posts as $post) { ?>
          <div class="col-md-4">
            <div class="product-item">
              <a href="#"><img src="assets/images/postImage/<?= $post['image'] ?>" alt=""></a>
              <div class="down-content">
                <a href="#">
                  <h4><?= $post['title'] ?></h4>
                </a>
                <h6><?= $post['created_at'] ?></h6>
                <p><?= $post['body'] ?></p>
                <div class="d-flex justify-content-end">
                  <a href="viewPost.php?id=<?= $post['id'] ?>" class="btn btn-info "> view</a>
                </div>
              </div>
            </div>
          </div>
        <?php }
      } else {
        ?> <img src="assets/images/1-10.webp"> <?php
                                              } ?>
    </div>
  </div>
  <nav aria-label="Page navigation example" class="d-flex justify-content-center">
    <ul class="pagination">
      <li class="page-item <?php if($page == 1) echo 'disabled' ?>"><a class="page-link" href="index.php?page=<?= $page - 1 ?>">Previous</a></li>
      <?php for($i = 1;$i <= $numberOfPages; $i++){ ?>
      <li class="page-item"><a class="page-link" href="index.php?page=<?= $i ?>"><?= $i ?></a></li>
      <?php } ?>
      <li class="page-item <?php if($page == $numberOfPages) echo 'disabled' ?>"><a class="page-link" href="index.php?page=<?= $page +1 ?>">Next</a></li>
    </ul>
  </nav>
</div>



<?php require_once 'inc/footer.php' ?>