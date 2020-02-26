<?php
class Forms extends Controller
{
  public function __construct()
  {
    /* if(!isLoggedIn()){
        redirect('users/login');
      } */

    $this->formModel = $this->model('Form');
    $this->userModel = $this->model('User');
  }

  public function index()
  {
    // Get forms
    $forms = $this->formModel->getForms();

    $data = [
      'forms' => $forms
    ];

    $this->view('forms/index', $data);
  }

  public function add($pharmaceutical_id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'price' => trim($_POST['price']),
        'img' => '',
        'user_id' => $_SESSION['user_id'],
        'pharmaceutical_id' => $pharmaceutical_id,
        'title_err' => '',
        'body_err' => '',
        'price_err' => '',
        'img_err' => '',
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

        $upload_dir = UPLOADROOT . '/forms/'; // upload directory

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

      // Make sure no errors
      if (
        empty($data['title_err']) && empty($data['body_err'])  && empty($data['price_err'])
        && empty($data['img_err'])
      ) {
        // Validated
        if ($this->formModel->addForm($data)) {
          flash('form_message', 'Form Added');
          redirect('forms');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('forms/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => '',
        'price' => 0.00,
        'img' => '',
        'pharmaceutical_id' => $pharmaceutical_id,
        'title_err' => '',
        'body_err' => '',
        'price_err' => '',
        'img_err' => '',
      ];

      $this->view('forms/add', $data);
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
        'img' => '',
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => '',
        'price_err' => '',
        'img_err' => ''
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

      $upload_dir = UPLOADROOT . '/forms/'; // upload directory

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


      // Make sure no errors
      if (
        empty($data['title_err']) && empty($data['body_err'])  && empty($data['price_err'])
        && empty($data['img_err'])
      ) {
        // Validated
        if ($this->formModel->updateForm($data)) {
          flash('form_message', 'Form Updated');
          redirect('forms');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('forms/edit', $data);
      }
    } else {
      // Get existing form from model
      $form = $this->formModel->getFormById($id);

      // Check for owner
      if ($form->user_id != $_SESSION['user_id']) {
        redirect('forms');
      }

      $data = [
        'id' => $id,
        'title' => $form->title,
        'body' => $form->body,
        'price' => $form->price,
        'img' => $form->img
      ];

      $this->view('forms/edit', $data);
    }
  }

  public function show($id)
  {
    $form = $this->formModel->getFormById($id);
    $user = $this->userModel->getUserById($form->user_id);

    $data = [
      'form' => $form,
      'user' => $user
    ];

    $this->view('forms/show', $data);
  }

  public function delete($id)
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get existing form from model
      $form = $this->formModel->getFormById($id);

      // Check for owner
      if ($form->user_id != $_SESSION['user_id']) {
        redirect('forms');
      }

      if ($this->formModel->deleteForm($id)) {
        flash('form_message', 'Form Removed');
        redirect('forms');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('forms');
    }
  }
}
