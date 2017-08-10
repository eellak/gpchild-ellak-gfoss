<?php
/*
Template Name: Submissions Template
*/

get_header(); ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<style>
	.morecontent span {
	display: none;
	}
	.morelink {
		display: inline-block;
	}
	.clear::before, [class*="content"]::before {
    content: "";
    display: table-caption;
	}
	.tbl-cont {
			background: #EFEFEF;
			padding: 20px 15px;
			margin: 10px 0;
			border: 1px solid #3A3A3A;
			border-radius: 3px;
	}
	.inside-article {
			text-align: justify;
	}
	</style>
	<script>
	$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 500;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "περισσότερα";
    var lesstext = "λιγότερα";


    $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
	</script>

	<div id="primary" <?php generate_content_class();?>>
		<main id="main" <?php generate_main_class(); ?> itemprop="mainContentOfPage" role="main">
			<?php do_action('generate_before_main_content'); ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php //get_template_part( 'content', 'page' ); ?>
			    <div class="inside-article">

			    <h1 class="entry-title"><?php the_title(); ?></h1> <!-- Page Title -->
					<br/>
					<h3>Για να δείτε το κάλεσμα και να εγγραφείτε στον κατάλογο, πατήστε <a href="https://ellak.gr/2015/12/katalogos-nomikon-pou-parechoun-ipiresies-schetikes-me-anichtes-technologies/">εδώ</a></h3>
					<div class="tbl-cont">
				<?php

				function parse_links($str)
				{
						$str = str_replace('https://www.', 'https://', $str);
						$str = str_replace('http://www.', 'http://', $str);
				    $str = str_replace('www.', 'http://', $str);
				    $str = preg_replace('|http://([a-zA-Z0-9-./]+)|', '<a href="http://$1">$1</a>', $str);
						$str = preg_replace('|https://([a-zA-Z0-9-./]+)|', '<a href="https://$1">$1</a>', $str);
				    $str = preg_replace('/(([a-z0-9+_-]+)(.[a-z0-9+_-]+)*@([a-z0-9-]+.)+[a-z]{2,6})/', '<a href="mailto:$1">$1</a>', $str);
				    return $str;
				}

					//print_r(Ninja_Forms()->form( 52 )->fields);
					$args = array(
					  'form_id'   => 52
					);
					// This will return an array of sub objects.
					$subs = Ninja_Forms()->subs()->get( $args );
					$names = Ninja_Forms()->subs()->get( $args );
                                        echo var_dump($subs);
					function cmp($a, $b)
						{
						    return strcmp($a->get_field( 226 ), $b->get_field( 226 ));
						}
						usort($names, "cmp");
					//ksort($names, SORT_STRING);
					usort($subs, "cmp");
					//natcasesort($names->get_field( 226 ));
					// This is a basic example of how to interact with the returned objects.
					// See other documentation for all the methods and properties of the submission object.
					$n = 1;
					foreach ( $names as $name ) {
					  $form_ids = $name->form_id;
					  $user_ids = $name->user_id;
					  // Returns an array of [field_id] => [user_value] pairs
					  $all_fieldss = $name->get_all_fields();
							if($name->get_field( 225 ) && $name->get_field( 226 )) {
								$fullname = $name->get_field( 226 )." ".$name->get_field( 225 );
								echo "<a href='#".$name->get_field( 227 )."'>".$n.". ".$fullname."</a><br/>";
							}
							$n++;
					}
					echo "</div>";
					echo "<br/>";


					foreach ( $subs as $sub ) {
					  $form_id = $sub->form_id;
					  $user_id = $sub->user_id;
					  // Returns an array of [field_id] => [user_value] pairs
					  $all_fields = $sub->get_all_fields();
					  // / Echoes out the submitted value for a field
					 if($sub->get_field( 225 )) {
					  echo "<a id='".$sub->get_field( 227 )."'></a><b>Όνομα:</b> ".$sub->get_field( 225 )."<br/>";
					 }
					 if($sub->get_field( 226 )) {
						echo "<b>Επίθετο:</b> ".$sub->get_field( 226 )."<br/>";
					 }
					 if($sub->get_field( 227 )) {
						echo "<b>Email:</b> ".$sub->get_field( 227 )."<br/>";
					 }
					 if($sub->get_field( 235 )) {
						echo "<b>Διεύθυνση:</b> ".$sub->get_field( 235 )."<br/>";
					 }
					 if($sub->get_field( 228 )) {
						echo "<b>Τηλέφωνο:</b> ".$sub->get_field( 228 )."<br/>";
					 }
					 if($sub->get_field( 229 )) {
						echo "<b>Νομικό Πρόσωπο:</b> ".$sub->get_field( 229 )."<br/>";
					 }
					 if($sub->get_field( 240 )) {
						echo "<b>Μέσα Κοινωνικής Δικτύωσης:</b> ".parse_links($sub->get_field( 240 ))."<br/>";
					 }
					 if($sub->get_field( 237 )) {
						echo "<b>Ιστοσελίδα:</b> ".parse_links($sub->get_field( 237 ))."<br/>";
					 }
					 if(strlen($sub->get_field( 232 )) > 2) {
						echo "<div class=\"more\"><b>Βιογραφικό Σημείωμα:</b> ".$sub->get_field( 232 )."</div><br/>";
					 }
					 if(strlen($sub->get_field( 233 )) > 2) {
						echo "<div class=\"more\"><b>Περιγραφή προσφερόμενων υπηρεσιών:</b> ".htmlspecialchars_decode(parse_links($sub->get_field( 233 )))."</div><br/>";
					 }
					 if($sub->get_field( 259 )) {
						//str_replace(" ", "Άλλες δράσεις (Αν το επιλέξετε συμπληρώστε και το παρακάτω πεδίο)");
						$dr = implode(". ",$sub->get_field( 259 ));
						$dr_ = str_replace("(Αν το επιλέξετε συμπληρώστε και το παρακάτω πεδίο)", "", $dr);
						if (strlen($dr_)) {
							echo "<div class=\"more\"><b>Εθελοντικές δράσεις:</b> ".htmlspecialchars_decode($dr_)."</div><br/>";
						}
					 }
					 if(strlen($sub->get_field( 293 )) > 2) {
							echo "<div class=\"more\"><b>Άλλες εθελοντικές δράσεις:</b> ".htmlspecialchars_decode($sub->get_field( 293 ))."</div><br/>";
					 }
					 echo "<hr><br/>";
				}
				?>
			</div>

			<?php endwhile; // end of the loop. ?>

			<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action('generate_sidebars');
get_footer();
