<?php
/*
	Template Name: Mitroo Nomikon Template
*/
global $wpdb;

get_header(); ?>
	<script>
	$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 300;  // How many characters are shown by default
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
					
				<?php
                                $lawyers_query="SELECT * FROM `wpcentral_postmeta`, `wpcentral_posts`
WHERE `wpcentral_postmeta`.`post_id`=`wpcentral_posts`.`ID`
AND `wpcentral_posts`.`ID` in (SELECT DISTINCT `post_id` FROM `wpcentral_postmeta` WHERE `meta_key`='_form_id' AND `meta_value`=52)
AND `wpcentral_posts`.`post_type`='nf_sub'";
                                
                                //replaces links in a plain text to <a> elements
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
                                
                                $lawyer_submissions_result_set=$wpdb->get_results($lawyers_query);
                                //the callback function used by usort in order to sort 
                                //the result set accordingly to the 
                                //manual ranking field (_field_782)
                                function build_sorter($key){
                                    return function($a, $b) use ($key){   
                                        if($a==$b){
                                            return 0;
                                        }
                                        return ($a<$b) ? 1 : -1;
                                    };
                                }
                                
                                //function for sorting the GROUPED ARRAY, not
                                //the one immediately retrieved from the database
                                //-- bromoiras.14.10.16
                                function name_sorter($a, $b){
                                    return strcmp($a['lawyer_surname'], $b['lawyer_surname']);
                                }
                                
                                function custom_mb_ucfirst($string, $encoding){
                                    $strlen = mb_strlen($string, $encoding);
                                    $firstChar = mb_substr($string, 0, 1, $encoding);
                                    $then = mb_substr($string, 1, $strlen - 1, $encoding);
                                    return mb_strtoupper($firstChar, $encoding) . $then;
                                }
                                
                                function replace_small_sigma($string){
                                    $sigma_location=mb_strrpos($string, 'σ', 0, 'UTF-8');
                                    if ($sigma_location){
                                        $string[$sigma_location]='ς';
                                        return $string;
                                    }
                                    else 
                                        return $string;  
                                }
                                
                                //Η παρακάτω μέθοδος δε λειτουργεί όπως προβλέπεται, θέλει bug fix -- bromoiras.12.10.16
                                //usort($school_submissions_result_set, build_sorter('_field_782'));
                                
                                if (isset($lawyer_submissions_result_set)){
                                $entryID=$lawyer_submissions_result_set[0];
                                $entryID=$entryID->ID;
                                //echo $entryID."to ID";
                                $schoo;
                                $school_address;
                                $school_email;
                                $school_stem_robotics;
                                $school_open_software;
                                $school_responsible_name;
                                $school_responsible_surname;
                                $school_entry_date;
                                
                                $grouped_result_array=array();
                                $current_entry=array();
                                foreach($lawyer_submissions_result_set as $result_entry): 
                                    //var_dump($result_entry);
                                    if ($entryID!=$result_entry->ID){
                                        //echo "check ".$entryID;
                                        $grouped_result_array[]=$current_entry;
                                        $entryID=$result_entry->ID;
                                    }
                                    $current_entry['lawyer_id']=$result_entry->ID;
                                    if($result_entry->meta_key=='_field_225'){
                                        //$schoo=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['lawyer_name']=$result_entry->meta_value;
                                        
                                        //the entry date is the same on all the submission fields,
                                        //we choose to acquire it from the first available field.
                                        $current_entry['lawyer_entry_date']=$result_entry->post_date;
                                        //echo '790';
                                    }
                                    if($result_entry->meta_key=='_field_226'){
                                        //$schoo=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['lawyer_surname']=$result_entry->meta_value;
                                        //echo '791';
                                    }
                                    if($result_entry->meta_key=='_field_227'){
                                        //$schoo=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['lawyer_email']=$result_entry->meta_value;
                                        //echo '791';
                                    }
                                    if($result_entry->meta_key=='_field_235'){
                                        //$schoo=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['lawyer_address']=$result_entry->meta_value;
                                        //echo '792';
                                    }
                                    if($result_entry->meta_key=='_field_228'){
                                        //$schoo=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['lawyer_telephone']=$result_entry->meta_value;
                                        //echo '792';
                                    }
                                    if($result_entry->meta_key=='_field_229'){
                                        //$schoo=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['lawyer_legal_entity']=$result_entry->meta_value;
                                        //echo '792';
                                    }
                                    if($result_entry->meta_key=='_field_240'){
                                        //$school_email=$result_entry->meta_value;
                                        $current_entry['lawyer_social']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_237'){
                                        //$school_address=$result_entry->meta_value;
                                        $current_entry['lawyer_website']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_232'){
                                        //$school_stem_robotics=$result_entry->meta_value;
                                        $current_entry['lawyer_cv']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_233'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['lawyer_services']=htmlspecialchars_decode(parse_links($result_entry->meta_value));
                                    }
                                    if($result_entry->meta_key=='_field_259'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['lawyer_volunteer']=htmlspecialchars_decode(parse_links($result_entry->meta_value));
                                    }
                                    if($result_entry->meta_key=='_field_293'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['lawyer_volunteer_more']=htmlspecialchars_decode(parse_links($result_entry->meta_value));
                                    }
                                    //var_dump($curent_entry);
                                    //$grouped_result_array[]=(['ID'=>$entryID, 'schoo'=>$schoo, 'school_email'=>$school_email, 'school_address'=>$school_address, 'school_stem_robotics'=>$school_stem_robotics, 'school_open_software'=>$school_open_software]);
                                endforeach;
                                $grouped_result_array[]=$current_entry;
                                //var_dump($grouped_result_array);
                                
                                //apply alphabetic usort on $grouped_result_array
                                usort($grouped_result_array, 'name_sorter');
                                //unset($grouped_result_array[0]);
                                ?>
                                            
                                <?php
                                //echo '';
                                $ids_query='SELECT DISTINCT `wpcentral_postmeta`.`post_id` FROM `wpcentral_postmeta` WHERE `wpcentral_postmeta`.`meta_key`="_nf_form"';
                                ?>      
                                <!-- creating the container div for each list entry -->
                                <h4>Μέχρι στιγμής έχουν ανταποκριθεί στο κάλεσμα <?php echo count($grouped_result_array);?> νομικοί.</h4>
                                <?php
                                    $aa_counter=0;
                                    foreach($grouped_result_array as $lawyer_entry):
                                        $aa_counter++;
                                ?>
                                <div class="lawyer-container">
                                    <div class="btn btn-primary lawyer-container visible-label"  role="button" data-toggle="collapse" data-target="<?php echo '#'.$lawyer_entry['lawyer_id']; ?>">
                                        <?php 
                                            $l_name=$lawyer_entry['lawyer_name'];
                                            $l_surname=$lawyer_entry['lawyer_surname'];
                                            $l_surname=custom_mb_ucfirst(mb_strtolower($l_surname), 'UTF-8');
                                            $l_name=custom_mb_ucfirst(mb_strtolower($l_name), 'UTF-8');
                                            //$l_name=replace_small_sigma($l_name);
                                            //$l_surname=replace_small_sigma($l_surname);
                                            $l_name=mb_ereg_replace('(\x{03C3}\b)', 'ς', $l_name);
                                            $l_surname=mb_ereg_replace('\x{03C3}\b', 'ς', $l_surname);
                                            
                                            //echoing the lawyer name and surname
                                            echo  $aa_counter.'. '.$l_surname.", ".$l_name;
                                            
                                        ?>
                                    </div>
                                    <div id="<?php echo $lawyer_entry['lawyer_id']; ?>" class="collapse">
                                        <div class="lawyer-container hidden-details">
                                            <?php if (/*is_user_logged_in() && */mb_strlen($lawyer_entry['lawyer_email'])>0): ?>
                                            <div class="hidden-details-block lawyer-email">
                                                <span class="details-label lawyer-email-details-label">Ηλ. αλληλογραφία: </span>
                                                <div class="details-content more"><?php echo $lawyer_entry['lawyer_email']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (/*is_user_logged_in() && */mb_strlen($lawyer_entry['lawyer_telephone'])>0): ?>
                                            <div class="hidden-details-block lawyer-social">
                                                <span class="details-label lawyer-social-details-label">Τηλέφωνο: </span>
                                                <div class="details-content more"><?php echo $lawyer_entry['lawyer_telephone']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($lawyer_entry['lawyer_address'])>0): ?>
                                            <div class="hidden-details-block school-name">
                                                <span class="details-label school-name-label">Διεύθυνση: </span>
                                                <div class="details-content"><?php echo $lawyer_entry['lawyer_address']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($lawyer_entry['lawyer_social'])>0): ?>
                                            <div class="hidden-details-block lawyer-school-address">
                                                <span class="details-label lawyer-school-address-details-label">Κοινωνική Δικτύωση: </span>
                                                <div class="details-content more"><?php echo $lawyer_entry['lawyer_social']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($lawyer_entry['lawyer_website'])>0): ?>
                                            <div class="hidden-details-block lawyer-field">
                                                <span class="details-label stem-robotics-details-label">Ιστοσελίδα: </span>
                                                <div class="details-content more"><?php echo $lawyer_entry['lawyer_website']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($lawyer_entry['lawyer_cv'])>0): ?>
                                            <div class="hidden-details-block lawyer-field">
                                                <span class="details-label lawyer-field-label">Βιογραφικό: </span>
                                                <div class="details-content"><?php echo $lawyer_entry['lawyer_cv']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($lawyer_entry['lawyer_services'])>0): ?>
                                            <div class="hidden-details-block lawyer-field">
                                                <span class="details-label lawyer-field-label">Παρεχόμενες Υπηρεσίες: </span>
                                                <div class="details-content"><?php echo $lawyer_entry['lawyer_services']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($lawyer_entry['lawyer_volunteer'])>0): ?>
                                            <div class="hidden-details-block lawyer-field">
                                                <span class="details-label lawyer-field-label">Εθελοντικές Δράσεις: </span>
                                                <div class="details-content"><?php echo $lawyer_entry['lawyer_volunteer']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($lawyer_entry['lawyer_volunteer_more'])>0): ?>
                                            <div class="hidden-details-block lawyer-field">
                                                <span class="details-label lawyer-field-label">Άλλες Εθελοντικές Δράσεις: </span>
                                                <div class="details-content"><?php echo $lawyer_entry['lawyer_volunteer_more']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (mb_strlen($lawyer_entry['lawyer_entry_date'])>0): ?>
                                            <div class="hidden-details-block entry-date">
                                                <span class="details-label entry-date-label">Καταχωρήθηκε στις: </span>
                                                <div class="details-content"><?php $dt=new DateTime($lawyer_entry['lawyer_entry_date']); echo $dt->format('d/m/Y'); ?></div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
                                endforeach;
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
