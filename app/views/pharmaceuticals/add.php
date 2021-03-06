<?php require APPROOT . '/views/inc/header.php'; ?>
  <a href="<?php echo URLROOT; ?>/pharmaceuticals" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
  <div class="card card-body bg-light mt-5">
    <h2>Add Pharmaceutical</h2>
    <p>Create a pharmaceutical with this form</p>
    <form action="<?php echo URLROOT; ?>/pharmaceuticals/add/<?php echo $data['range_id']; ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">Title: <sup>*</sup></label>
        <input type="text" name="title" class="form-control form-control-lg <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>">
        <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
      </div>
      <div class="form-group">
        <label for="price">Price: <sup>*</sup></label>
        <input type="text" name="price" class="form-control form-control-lg <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price']; ?>">
        <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
      </div>
      <div class="form-group">
        <label for="popular">Popular: </label>
        <input type="checkbox" name="popular" value="1" />
      </div>
      
      <div class="form-group">
        <label for="body">Body: <sup>*</sup></label>
        <textarea name="body" class="form-control form-control-lg <?php echo (!empty($data['body_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['body']; ?></textarea>
        <span class="invalid-feedback"><?php echo $data['body_err']; ?></span>
      </div>
      <div class="form-group">
        <label for="img">Image: <sup>*</sup></label>
        <input type="file" name="img" class="form-control form-control-lg <?php echo (!empty($data['img_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['img']; ?>">
        <span class="invalid-feedback"><?php echo $data['img_err']; ?></span>
      </div>
      <div class="form-group">
        <label for="img1">Image: <sup>*</sup></label>
        <input type="file" name="img1" class="form-control form-control-lg <?php echo (!empty($data['img1_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['img1']; ?>">
        <span class="invalid-feedback"><?php echo $data['img1_err']; ?></span>
      </div>
      <input type="submit" class="btn btn-success" value="Submit">
    </form>
  </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>