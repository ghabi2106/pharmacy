<?php require APPROOT . '/views/inc/header.php'; ?>
  <?php flash('form_message'); ?>
  <div class="row mb-3">
    <div class="col-md-6">
      <h1>Forms</h1>
    </div>
    <?php if (isset($_SESSION['user_id'])) : ?>
      <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/forms/add" class="btn btn-primary pull-right">
          <i class="fa fa-pencil"></i> Add Form
        </a>
      </div>
      <?php endif; ?>
  </div>
  <?php foreach($data['forms'] as $form) : ?>
    <div class="card card-body mb-3">
      <h4 class="card-title"><?php echo $form->title; ?></h4>
      <div class="bg-light p-2 mb-3">
        Written by <?php echo $form->name; ?> on <?php echo $form->formCreated; ?>
      </div>
      <p class="card-text"><?php echo $form->body; ?></p>
      <a href="<?php echo URLROOT; ?>/forms/show/<?php echo $form->formId; ?>" class="btn btn-dark">More</a>
    </div>
    <?php endforeach; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>