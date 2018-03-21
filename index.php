<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');


// checking URL for variables
$search_categories = null;
$search_platform = null;
$search_type = null;
$search_grades = null;
$search_keywords = null;
$order_by = "alphabetical";


if (isset($_GET['categories'])) {
  if ($_GET['categories'] !== "-1") {
    $search_categories = $_GET['categories'];
  }
}

if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
}

if (isset($_GET['platform'])) {
  $search_platform = $_GET['platform'];
}

if (isset($_GET['type'])) {
  $search_type = $_GET['type'];
}

if (isset($_GET['grades'])) {
  $search_grades = $_GET['grades'];
}

if (isset($_GET['keywords'])) {
  $search_keywords = $_GET['keywords'];
}

// setting up the page
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("BCLN: LOR");
$PAGE->set_heading("BCLN Learning Material");
$PAGE->set_url(new moodle_url('/local/lor/index.php', array('category' => $search_category)));

echo $OUTPUT->header();



// setting variables
$content = local_lor_get_content($search_type, $search_platform, $search_categories, $search_grades, $order_by, $search_keywords);
$categories = local_lor_get_categories();
$platforms = local_lor_get_platforms();
$types = local_lor_get_types();
$grades = local_lor_get_grades();

?>
<!-- TODO: replace these with better links -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="styles.css">

<h1>BCLN Learning Material</h1>
<div class="container-fluid" id="content-container">


  <!-- Filter settings -->
  <div class="row" id="filters">
    <form action="index.php" method="GET">

      <b>Type:</b>
      <select name="type" id="type-select">
        <option value="-1">All Types</option>
        <?php foreach($types as $type): ?>
          <?php if ($type->id == $search_type): ?>
            <option selected="selected" value="<?=$type->id?>"><?=$type->name?></option>
          <?php else: ?>
            <option value="<?=$type->id?>"><?=$type->name?></option>
          <?php endif ?>
        <?php endforeach ?>
      </select>

      <!-- only visible if type=game -->
      <div id="platform-select">
        <b>Platforms:</b>
        <select name="platform">
          <?php foreach($platforms as $platform): ?>
            <?php if ($platform->id == $search_platform): ?>
              <option selected="selected" value="<?=$platform->id?>"><?=$platform->name?></option>
            <?php else: ?>
              <option value="<?=$platform->id?>"><?=$platform->name?></option>
            <?php endif ?>
          <?php endforeach ?>
        </select>
      </div>

      <b>Categories:</b>
      <?php foreach ($categories as $category): ?>
        <input type="checkbox" name="categories" value="<?=$category->id?>" /><?=$category->name?>
      <?php endforeach ?>

      <b>Grades:</b>
      <?php foreach ($grades as $grade): ?>
        <input type="checkbox" name="grades" value="<?=$grade->grade?>" /><?=$grade->grade?>
      <?php endforeach ?>

      <b>Sort by:</b>
      <select name="order_by">
        <option value="new">Recently Added</option>
        <?php if ($order_by === "new"): ?>
          <option value="new" selected="selected">Recently Added</option>
        <?php else: ?>
          <option value="alphabetical">Alphabetical</option>
        <?php endif ?>
      </select>

      <input type="text" placeholder="Keywords..." name="keywords">

      <button type="submit" class="btn btn-primary">Search</button>
  </form>
  </div>

  <!-- Projects -->
  <div class="row text-center">
    <p id="count"><i><?=count($content)?> items match your search</i></p>

    <?php if (!is_null($content)): ?>
      <?php foreach ($content as $item): ?>
      </div>
      <div class="row text-center item">
        <hr>
      <div class="col-md-4 item-image">
        <a href="https://bclearningnetwork.com/LOR/projects/<?=$item->id?>.pdf" target="_blank">
          <img src="https://bclearningnetwork.com/LOR/projects/<?=$item->id?>.png" width="200" height="150" />
        </a>

      </div>
      <div class="col-md-8 project-about text-left">
        <a href="https://bclearningnetwork.com/LOR/projects/<?=$item->id?>.pdf" target="_blank"><h3><?=$item->description?></h3></a>
        <p><i>Topics: </i><?=$item->topics?></p>
      </div>
    <?php endforeach ?>
    <?php endif ?>
    <hr>
</div>

<script>

// show the platform select if type == game is selected
$("#type-select").change(function() {
  if ($("#type-select option:selected").text() === "Game") {
    $("#platform-select").css("display: inline");
  }
});


</script>


<?php
echo $OUTPUT->footer();
?>
