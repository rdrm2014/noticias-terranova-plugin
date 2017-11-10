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
  $rss = fetch_feed('http://www.terranova.pt/noticias/rss/');
	$maxitems = 0;
	// max number of news slots
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary
  $max_length = $options['chars'];

	if (!is_wp_error($rss)){
	    $maxitems = $rss->get_item_quantity( $max_news );
	    $rss_items = $rss->get_items( 0, $maxitems );
	}
	echo "<ul>";
		if($maxitems == 0 ){
			echo "<li>" . _e( 'No items', 'my-text-domain' ) . "</li>";
		} else{
			foreach ($rss_items as $item){
				echo "<li>";
				echo "<strong><a href='" . esc_url( $item->get_permalink()) . "'>";
				echo esc_html($item->get_title());
				echo "</a></strong>";
				echo "</li>";
			}
		}
	echo "</ul>";
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
