<?php
class Pharmaceuticals extends Controller
{
  public function __construct()
  {
    /* if(!isLoggedIn()){
        redirect('users/login');
      } */

    $this->pharmaceuticalModel = $this->model('Pharmaceutical');
    $this->userModel = $this->model('User');
  }

  public function index()
  {
    // Get pharmaceuticals
    $pharmaceuticals = $this->pharmaceuticalModel->getPharmaceuticals();

    $data = [
      'pharmaceuticals' => $pharmaceuticals
    ];

    $this->view('pharmaceuticals/index', $data);
  }

  public function add($category_id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'price' => trim($_POST['price']),
        'popular' => trim($_POST['popular']),
        'img' => '',
        'img1' => '',
        'user_id' => $_SESSION['user_id'],
        'category_id' => $category_id,
        'title_err' => '',
        'body_err' => '',
        'price_err' => '',
        'img_err' => '',
        'img1_err' => '',
      ];

      /* ---------------------------------
                  main image 
        -------------------------------- */

      $imgFile = $_FILES['img']['name'];
      $tmp_dir = $_FILES['img']['tmp_name'];
      $imgSize = $_FILES['img']['size'];

      // Validate data
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }
      if (!is_numeric($data['price'])) {
        $data['price_err'] = 'Please enter numeric value';
      }
      if (empty($imgFile)) {
        $data['img_err'] = 'Please select image file';
      } else {

        $upload_dir = UPLOADROOT . '/pharmaceuticals/'; // upload directory

        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

        // rename uploading image
        //$data['img'] = $imgFile . "." . $imgExt;
        $data['img'] = $imgFile;

        // allow valid image file formats
        if (in_array($imgExt, $valid_extensions)) {
          // Check file size '5MB'
          if ($imgSize < 50000000000) {
            move_uploaded_file($tmp_dir, $upload_dir . $data['img']);
          } else {
            $data['img_err'] = "Sorry, your file is too large.";
          }
        } else {
          $data['img_err'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
      }

      /* ---------------------------------
                  secondary image 
        -------------------------------- */

      $img1File = $_FILES['img1']['name'];
      $tmp_dir1 = $_FILES['img1']['tmp_name'];
      $img1Size = $_FILES['img1']['size'];

      // Validate data
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }
      if (empty($img1File)) {
        $data['img1_err'] = 'Please select image file';
      } else {

        $upload_dir = UPLOADROOT . '/pharmaceuticals/'; // upload directory

        $img1Ext = strtolower(pathinfo($img1File, PATHINFO_EXTENSION)); // get image extension

        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

        // rename uploading image
        //$data['img1'] = $img1File . "." . $img1Ext;
        $data['img1'] = $img1File;

        // allow valid image file formats
        if (in_array($img1Ext, $valid_extensions)) {
          // Check file size '5MB'
          if ($img1Size < 50000000000) {
            move_uploaded_file($tmp_dir1, $upload_dir . $data['img1']);
          } else {
            $data['img1_err'] = "Sorry, your file is too large.";
          }
        } else {
          $data['img1_err'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
      }

      // Make sure no errors
      if (
        empty($data['title_err']) && empty($data['body_err'])  && empty($data['price_err'])
        && empty($data['img_err']) && empty($data['img1_err'])
      ) {
        // Validated
        if ($this->pharmaceuticalModel->addPharmaceutical($data)) {
          flash('pharmaceutical_message', 'Pharmaceutical Added');
          redirect('pharmaceuticals');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('pharmaceuticals/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => '',
        'price' => 0.00,
        'popular' => 0,
        'img' => '',
        'img1' => '',
        'category_id' => $category_id,
        'title_err' => '',
        'body_err' => '',
        'price_err' => '',
        'img_err' => '',
        'img1_err' => '',
      ];

      $this->view('pharmaceuticals/add', $data);
    }
  }

  public function edit($id)
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'price' => trim($_POST['price']),
        'popular' => trim($_POST['popular']),
        'img' => '',
        'img1' => '',
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => '',
        'price_err' => '',
        'img_err' => '',
        'img1_err' => '',
      ];

      /* ---------------------------------
                  main image 
        -------------------------------- */

      $imgFile = $_FILES['img']['name'];
      $tmp_dir = $_FILES['img']['tmp_name'];
      $imgSize = $_FILES['img']['size'];

      // Validate data
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }
      if (!is_numeric($data['price'])) {
        $data['price_err'] = 'Please enter numeric value';
      }

      $upload_dir = UPLOADROOT . '/pharmaceuticals/'; // upload directory

      $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

      // valid image extensions
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

      // rename uploading image
      //$data['img'] = $imgFile . "." . $imgExt;
      $data['img'] = $imgFile;

      // allow valid image file formats
      if (in_array($imgExt, $valid_extensions)) {
        // Check file size '5MB'
        if ($imgSize < 50000000000) {
          move_uploaded_file($tmp_dir, $upload_dir . $data['img']);
        } else {
          $data['img_err'] = "Sorry, your file is too large.";
        }
      } else {
        $data['img_err'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      }


      /* ---------------------------------
                  secondary image 
        -------------------------------- */

      $img1File = $_FILES['img1']['name'];
      $tmp_dir1 = $_FILES['img1']['tmp_name'];
      $img1Size = $_FILES['img1']['size'];

      // Validate data
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }

      $upload_dir = UPLOADROOT . '/pharmaceuticals/'; // upload directory

      $img1Ext = strtolower(pathinfo($img1File, PATHINFO_EXTENSION)); // get image extension

      // valid image extensions
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

      // rename uploading image
      //$data['img1'] = $img1File . "." . $img1Ext;
      $data['img1'] = $img1File;

      // allow valid image file formats
      if (in_array($img1Ext, $valid_extensions)) {
        // Check file size '5MB'
        if ($img1Size < 50000000000) {
          move_uploaded_file($tmp_dir1, $upload_dir . $data['img1']);
        } else {
          $data['img1_err'] = "Sorry, your file is too large.";
        }
      } else {
        $data['img1_err'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      }


      // Make sure no errors
      if (
        empty($data['title_err']) && empty($data['body_err'])  && empty($data['price_err'])
        && empty($data['img_err']) && empty($data['img1_err'])
      ) {
        // Validated
        if ($this->pharmaceuticalModel->updatePharmaceutical($data)) {
          flash('pharmaceutical_message', 'Pharmaceutical Updated');
          redirect('pharmaceuticals');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('pharmaceuticals/edit', $data);
      }
    } else {
      // Get existing pharmaceutical from model
      $pharmaceutical = $this->pharmaceuticalModel->getPharmaceuticalById($id);

      // Check for owner
      if ($pharmaceutical->user_id != $_SESSION['user_id']) {
        redirect('pharmaceuticals');
      }

      $data = [
        'id' => $id,
        'title' => $pharmaceutical->title,
        'body' => $pharmaceutical->body,
        'price' => $pharmaceutical->price,
        'popular' => $pharmaceutical->popular,
        'img' => $pharmaceutical->img,
        'img1' => $pharmaceutical->img1
      ];

      $this->view('pharmaceuticals/edit', $data);
    }
  }

  public function show($id)
  {
    $pharmaceutical = $this->pharmaceuticalModel->getPharmaceuticalCatById($id);
    $user = $this->userModel->getUserById($pharmaceutical->user_id);

    $data = [
      'pharmaceutical' => $pharmaceutical,
      'user' => $user
    ];

    $this->view('pharmaceuticals/show', $data);
  }

  public function delete($id)
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get existing pharmaceutical from model
      $pharmaceutical = $this->pharmaceuticalModel->getPharmaceuticalById($id);

      // Check for owner
      if ($pharmaceutical->user_id != $_SESSION['user_id']) {
        redirect('pharmaceuticals');
      }

      if ($this->pharmaceuticalModel->deletePharmaceutical($id)) {
        flash('pharmaceutical_message', 'Pharmaceutical Removed');
        redirect('pharmaceuticals');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('pharmaceuticals');
    }
  }
}
