<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	
	<title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
	
	<?php do_action( 'presentation_head' ) ?>
</head>
 
<body>
 
<?php $slides = PostPresentations::get_slides(); ?>

<?php if ( ! empty( $slides )) : ?>
	
<div class="reveal">
	<div class="slides">

	<?php foreach ( $slides as $slide ) : ?>

		<section><?php echo wpautop ( $slide ) ?></section>
		
	<?php endforeach ?>
	</div>
</div>

<?php endif ?>

<?php do_action( 'presentation_footer' ) ?>
 
</body>
</html>	