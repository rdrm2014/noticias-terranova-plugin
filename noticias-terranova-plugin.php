<?php
/*
Plugin Name: Noticias TerraNova Plugin
Plugin URI: http://www.terranova.pt/noticias/rss
Description: O plugin Noticias TerraNova adiciona um widget ao teu blog que mostra as ultimas noticias da Radio TerraNova. Pode ser integrado em qualquer sitio no do teu site.
Version: 1.0
Author: Ricardo Mendes
Author URI: http://ricardo-mendes.com
License: GPL3
*/

function noticiasterranova(){
	$options = get_option("widget_noticiasterranova");
	if (!is_array($options)){
		$options = array(
			'title' => 'Notícias TerraNova',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed
  $rss = simplexml_load_file(
  'http://www.terranova.pt/noticias/rss');
	//$rss = new DOMDocument();
	//$rss->loadXML('http://www.terranova.pt/noticias/rss');
  ?>
  <ul>
  <?php
  // max number of news slots
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary
  $max_length = $options['chars'];

  $cnt = 0;
  foreach($rss->channel->item as $i) {
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?>
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a>
    <?php
    // Description
    $description = $i->description;
    // Length of description
    $length = strlen($description);
    // if the description is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($description > $max_length){
      $description = substr($description, 0, $max_length)."...";
    }
    ?>
    <p><?=$description?></p>
    </li>
    <?php
    $cnt++;
  }
  ?>
  </ul>
<?php
}

function widget_noticiasterranova($args){
	extract($args);
  $options = get_option("widget_noticiasterranova");
  if (!is_array($options)){
    $options = array(
		'title' => 'Notícias TerraNova',
		'news' => '5',
		'chars' => '30'
    );
  }

  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  noticiasterranova();
  echo $after_widget;
}

function noticiasterranova_control(){
  $options = get_option("widget_noticiasterranova");
  if (!is_array($options)){
    $options = array(
			'title' => 'Notícias TerraNova',
      'news' => '5',
      'chars' => '30'
    );
  }

  if($_POST['noticiasterranova-Submit']){
		$options['title'] = htmlspecialchars($_POST['noticiasterranova-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['noticiasterranova-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['noticiasterranova-CharCount']);
    update_option("widget_noticiasterranova", $options);
  }
?>
  <p>
		<label for="noticiasterranova-WidgetTitle">Widget Title: </label>
    <input type="text" id="noticiasterranova-WidgetTitle" name="noticiasterranova-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="noticiasterranova-NewsCount">Max. News: </label>
    <input type="text" id="noticiasterranova-NewsCount" name="noticiasterranova-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="noticiasterranova-CharCount">Max. Characters: </label>
    <input type="text" id="noticiasterranova-CharCount" name="noticiasterranova-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="noticiasterranova-Submit"  name="noticiasterranova-Submit" value="1" />
  </p>
<?php
}

function noticiasterranova_init(){
	wp_register_sidebar_widget('noticiasterranova','Noticias TerraNova', 'widget_noticiasterranova');
  	wp_register_widget_control('noticiasterranova','Noticias TerraNova', 'noticiasterranova_control', 300, 200);
}
add_action("plugins_loaded", "noticiasterranova_init");
?>
