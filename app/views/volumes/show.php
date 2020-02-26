<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/volumes" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
<h1><?php echo $data['volume']->title; ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
  Written by <?php echo $data['user']->name; ?> on <?php echo $data['volume']->created_at; ?>
</div>
<p><?php echo $data['volume']->body; ?></p>

<?php if (isset($_SESSION['user_id'])) : ?>
  <hr>
  <a href="<?php echo URLROOT; ?>/volumes/edit/<?php echo $data['volume']->id; ?>" class="btn btn-dark">Edit</a>

  <form class="pull-right" action="<?php echo URLROOT; ?>/volumes/delete/<?php echo $data['volume']->id; ?>" method="POST">
    <input type="submit" value="Delete" class="btn btn-danger">
  </form>
<?php endif; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>