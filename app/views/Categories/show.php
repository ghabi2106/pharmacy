<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/categories" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
<h1><?php echo $data['category']->title; ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
  Written by <?php echo $data['user']->name; ?> on <?php echo $data['category']->created_at; ?>
</div>
<p><?php echo $data['category']->body; ?></p>

<hr>
<?php if (isset($_SESSION['user_id'])) : ?>
  <a href="<?php echo URLROOT; ?>/categories/edit/<?php echo $data['category']->id; ?>" class="btn btn-dark">Edit</a>
  <a href="<?php echo URLROOT; ?>/pharmaceuticals/add/<?php echo $data['category']->id; ?>" class="btn btn-dark">Add Pharmaceutical</a>
  <form class="pull-right" action="<?php echo URLROOT; ?>/categories/delete/<?php echo $data['category']->id; ?>" method="POST">
    <input type="submit" value="Delete" class="btn btn-danger">
  </form>
<?php endif; ?>
<?php foreach ($data['pharmaceuticals'] as $pharmaceutical) : ?>
  <div class="card card-body mb-3">
    <h4 class="card-title"><?php echo $pharmaceutical->title; ?></h4>
    <p class="card-text"><?php echo $pharmaceutical->body; ?></p>
    <img src="<?php echo URLROOT; ?>/img/uploads/pharmaceuticals/<?php echo $pharmaceutical->img; ?>" alt="img" height="100" width="100">
    <a href="<?php echo URLROOT; ?>/pharmaceuticals/show/<?php echo $pharmaceutical->id; ?>" class="btn btn-dark">More</a>
  </div>
<?php endforeach; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>