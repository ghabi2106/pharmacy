<?php

use voku\helper\Paginator;

class Categories extends Controller
{
  public function __construct()
  {
    /* if (!isLoggedIn()) {
      redirect('users/login');
    } */

    $this->categoryModel = $this->model('Category');
    $this->pharmaceuticalModel = $this->model('Pharmaceutical');
    $this->userModel = $this->model('User');
  }

  public function index()
  {
    // Get categories
    $categories = $this->categoryModel->getCategories();
    $news = $this->pharmaceuticalModel->getPharmaceuticalsNew(4);
    $populars = $this->pharmaceuticalModel->getPharmaceuticalsPopular(8);

    $data = [
      'categories' => $categories,
      'news' => $news,
      'populars' => $populars
    ];

    $this->view('categories/index', $data);
  }

  public function add()
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
        'img' => '',
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => '',
        'img_err' => '',
      ];

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
      if (empty($imgFile)) {
        $data['img_err'] = 'Please select image file';
      } else {

        $upload_dir = UPLOADROOT . '/categories/'; // upload directory

        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

        // rename uploading image
        $data['img'] = rand(1000, 1000000) . "." . $imgExt;

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
      if (empty($data['title_err']) && empty($data['body_err']) && empty($data['img_err'])) {
        // Validated
        if ($this->categoryModel->addCategory($data)) {
          flash('category_message', 'Category Added');
          redirect('categories');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('categories/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => '',
        'img' => ''
      ];

      $this->view('categories/add', $data);
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
        'id' => $id,
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => ''
      ];

      // Validate data
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }

      // Make sure no errors
      if (empty($data['title_err']) && empty($data['body_err'])) {
        // Validated
        if ($this->categoryModel->updateCategory($data)) {
          flash('category_message', 'Category Updated');
          redirect('categories');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('categories/edit', $data);
      }
    } else {
      // Get existing category from model
      $category = $this->categoryModel->getCategoryById($id);

      // Check for owner
      if ($category->user_id != $_SESSION['user_id']) {
        redirect('categories');
      }

      $data = [
        'id' => $id,
        'title' => $category->title,
        'body' => $category->body
      ];

      $this->view('categories/edit', $data);
    }
  }

  public function show($id)
  {
    // create a new object
    $pages = new Paginator('10', 'p');

    // set the total records, calling a method to get the number of records from a model
    $total = $this->pharmaceuticalModel->get_search_count($id);
    $pages->set_total($total);

    // calling a method to get the records with the limit set
    $pharmaceuticals = $this->pharmaceuticalModel->get_search($id, $pages->get_limit());

    // create the nav menu
    $page_links = $pages->page_links();

    // $pharmaceuticals = $this->pharmaceuticalModel->getPharmaceuticalsByCategoryId($id);
    $category = $this->categoryModel->getCategoryById($id);
    $categories = $this->categoryModel->getCategories();
    $user = $this->userModel->getUserById($category->user_id);

    $data = [
      'categories' => $categories,
      'category' => $category,
      'user' => $user,
      'pharmaceuticals' => $pharmaceuticals,
      'page_links' => $page_links
    ];

    $this->view('categories/show', $data);
  }

  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get existing Category from model
      $category = $this->categoryModel->getCategoryById($id);

      // Check for owner
      if ($category->user_id != $_SESSION['user_id']) {
        redirect('categories');
      }

      if ($this->categoryModel->deleteCategory($id)) {
        flash('category_message', 'Category Removed');
        redirect('categories');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('categories');
    }
  }
}
