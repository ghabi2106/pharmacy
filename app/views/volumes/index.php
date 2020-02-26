<?php require APPROOT . '/views/inc/header.php'; ?>
  <?php flash('volume_message'); ?>
  <div class="row mb-3">
    <div class="col-md-6">
      <h1>Volumes</h1>
    </div>
    <?php if (isset($_SESSION['user_id'])) : ?>
      <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/volumes/add" class="btn btn-primary pull-right">
          <i class="fa fa-pencil"></i> Add Volume
        </a>
      </div>
      <?php endif; ?>
  </div>
  <?php foreach($data['volumes'] as $volume) : ?>
    <div class="card card-body mb-3">
      <h4 class="card-title"><?php echo $volume->title; ?></h4>
      <div class="bg-light p-2 mb-3">
        Written by <?php echo $volume->name; ?> on <?php echo $volume->volumeCreated; ?>
      </div>
      <p class="card-text"><?php echo $volume->body; ?></p>
      <a href="<?php echo URLROOT; ?>/volumes/show/<?php echo $volume->volumeId; ?>" class="btn btn-dark">More</a>
    </div>
    <?php endforeach; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>