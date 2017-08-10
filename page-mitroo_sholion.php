<?php
/*
	Template Name: Mitroo Sholion Template
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
					<h3>Για να δείτε το κάλεσμα και να εγγραφείτε στον κατάλογο, πατήστε <a href="https://ellak.gr/katagrafi-sto-mitroo-scholion-pou-chrisimopioun-anichtes-technologies-stin-ekpedeftiki-diadikasia/">εδώ</a></h3>
					
				<?php
                                $schools_query="SELECT * FROM `wpcentral_postmeta`, `wpcentral_posts`
WHERE `wpcentral_postmeta`.`post_id`=`wpcentral_posts`.`ID`
AND `wpcentral_posts`.`ID` in (SELECT DISTINCT `post_id` FROM `wpcentral_postmeta` WHERE `meta_key`='_form_id' AND `meta_value`=153)
AND `wpcentral_posts`.`post_type`='nf_sub'";
                                
                                //replaces links in a plain text to <a> elements
                                function parse_links($str)
				{
						$str = str_replace('https://www.', 'https://', $str);
						$str = str_replace('http://www.', 'http://', $str);
				    $str = str_replace('www.', 'http://', $str);
				    $str = preg_replace('|http://([a-zA-Z0-9-./]+)|', '<a href="http://$1" target="_blank">$1</a>', $str);
						$str = preg_replace('|https://([a-zA-Z0-9-./]+)|', '<a href="https://$1" target="_blank">$1</a>', $str);
				    $str = preg_replace('/(([a-z0-9+_-]+)(.[a-z0-9+_-]+)*@([a-z0-9-]+.)+[a-z]{2,6})/', '<a href="mailto:$1">$1</a>', $str);
				    return $str;
				}
                                
                                $school_submissions_result_set=$wpdb->get_results($schools_query);
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
                                    return strcmp($a['school_name'], $b['school_name']);
                                }
                                
                                //Η παρακάτω μέθοδος δε λειτουργεί όπως προβλέπεται, θέλει bug fix -- bromoiras.12.10.16
                                //usort($school_submissions_result_set, build_sorter('_field_782'));
                                
                                if (isset($school_submissions_result_set)){
                                $entryID=$school_submissions_result_set[0];
                                $entryID=$entryID->ID;
                                //echo $entryID."to ID";
                                $school_name;
                                $school_address;
                                $school_email;
                                $school_stem_robotics;
                                $school_open_software;
                                $school_responsible_name;
                                $school_responsible_surname;
                                $school_entry_date;
                                
                                $grouped_result_array=array();
                                $current_entry=array();
                                foreach($school_submissions_result_set as $result_entry): 
                                    //var_dump($result_entry);
                                    if ($entryID!=$result_entry->ID){
                                        //echo "check ".$entryID;
                                        $grouped_result_array[]=$current_entry;
                                        $entryID=$result_entry->ID;
                                    }
                                    $current_entry['school_id']=$result_entry->ID;
                                    if($result_entry->meta_key=='_field_767'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['school_represent_name']=$result_entry->meta_value;
                                        //the entry date is the same on all the submission fields,
                                        //we choose to acquire it from the first available field.
                                        $current_entry['school_entry_date']=$result_entry->post_date;
                                        //echo '767';
                                    }
                                    if($result_entry->meta_key=='_field_768'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['school_represent_surname']=$result_entry->meta_value;
                                        //echo '768';
                                    }
                                    if($result_entry->meta_key=='_field_780'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['school_responsible_full_name']=$result_entry->meta_value;
                                        //echo '768';
                                    }
                                    if($result_entry->meta_key=='_field_781'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['school_responsible_email']=$result_entry->meta_value;
                                        //echo '768';
                                    }
                                    if($result_entry->meta_key=='_field_769'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['school_name']=$result_entry->meta_value;
                                        //echo '769';
                                    }
                                    if($result_entry->meta_key=='_field_770'){
                                        //$school_email=$result_entry->meta_value;
                                        $current_entry['school_email']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_771'){
                                        //$school_address=$result_entry->meta_value;
                                        $current_entry['school_address']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_773'){
                                        //$school_address=$result_entry->meta_value;
                                        $tmp_str=str_replace(" ", PHP_EOL, $result_entry->meta_value);
                                        //$current_entry['school_social_networking']=parse_links($result_entry->meta_value);
                                        $current_entry['school_social_networking']=parse_links($tmp_str);
                                    }
                                    if($result_entry->meta_key=='_field_774'){
                                        //$school_address=$result_entry->meta_value;
                                        $current_entry['school_sites']=parse_links($result_entry->meta_value);
                                    }
                                    if($result_entry->meta_key=='_field_775'){
                                        //$school_stem_robotics=$result_entry->meta_value;
                                        $current_entry['school_stem_robotics']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_779'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['school_open_software']=parse_links($result_entry->meta_value);
                                    }
                                    //var_dump($curent_entry);
                                    //$grouped_result_array[]=(['ID'=>$entryID, 'school_name'=>$school_name, 'school_email'=>$school_email, 'school_address'=>$school_address, 'school_stem_robotics'=>$school_stem_robotics, 'school_open_software'=>$school_open_software]);
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
                                        
                                <h4>Μέχρι στιγμής έχουν ανταποκριθεί στο κάλεσμα <?php echo count($grouped_result_array);?> σχολεία.</h4>
                                <!-- creating the container div for each list entry -->
                                
                                <?php
                                    $aa_counter=0;
                                    foreach($grouped_result_array as $school_entry):
                                        $aa_counter++;
                                ?>
                                <div class="school-container">
                                    <div class="btn btn-primary school-container visible-label"  role="button" data-toggle="collapse" data-target="<?php echo '#'.$school_entry['school_id']; ?>">
                                        <?php echo $aa_counter.'. '.$school_entry['school_name']; ?>
                                    </div>
                                    <div id="<?php echo $school_entry['school_id']; ?>" class="collapse">
                                        <div class="school-container hidden-details">
                                            <!-- <div class="hidden-details-block email">
                                                <span class="details-label email-label">Ηλ. αλληλογραφία: </span>
                                                <div class="details-content"><?php echo $school_entry['school_email']; ?></div>
                                            </div> -->
                                            <?php if (mb_strlen($school_entry['school_open_software'])>0): ?>
                                            <div class="hidden-details-block open-software-list">
                                                <span class="details-label open-software-details-label">Χρήση EΛ/ΛΑΚ: </span>
                                                <div class="details-content more"><?php echo $school_entry['school_open_software']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($school_entry['school_stem_robotics'])>0): ?>
                                            <div class="hidden-details-block stem-robotics">
                                                <span class="details-label stem-robotics-details-label">Δράσεις ρομποτικής/STEM με Ανοιχτές Τεχνολογίες: </span>
                                                <div class="details-content more"><?php echo $school_entry['school_stem_robotics']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($school_entry['school_address'])>0): ?>
                                            <div class="hidden-details-block address">
                                                <span class="details-label address-label">Διεύθυνση: </span>
                                                <div class="details-content"><?php echo $school_entry['school_address']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php
                                                if(mb_strlen($school_entry['school_responsible_full_name'])>0){
                                                    $full_name_string=$school_entry['school_responsible_full_name'];
                                                }
                                                else if (mb_strlen($school_entry['school_represent_surname'])>0||mb_strlen($school_entry['school_represent_name']>0)){
                                                    $full_name_string=$school_entry['school_represent_surname'].", ".$school_entry['school_represent_name'];
                                                }
                                                if(mb_strlen($full_name_string)>0): 
                                            ?>
                                            <div class="hidden-details-block responsible">
                                                <span class="details-label address-label">Υπεύθυνος: </span>
                                                <div class="details-content"><?php echo $full_name_string; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (is_user_logged_in() && mb_strlen($school_entry['school_responsible_email'])>0): ?>
                                            <div class="hidden-details-block responsible-email">
                                                <span class="details-label email-label">Ηλ. αλληλογραφία: </span>
                                                <div class="details-content"><?php echo $school_entry['school_responsible_email']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (is_user_logged_in() && mb_strlen($school_entry['school_social_networking'])>0): ?>
                                            <div class="hidden-details-block social-networking">
                                                <span class="details-label social-label">Κοινωνικά δίκτυα: </span>
                                                <div class="details-content"><?php echo $school_entry['school_social_networking']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($school_entry['school_sites'])>0): ?>
                                            <div class="hidden-details-block shool-sites">
                                                <span class="details-label sites-label">Ιστότοποι σχολείου/δράσεων: </span>
                                                <div class="details-content"><?php echo $school_entry['school_sites']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($school_entry['school_entry_date'])>0): ?>
                                            <div class="hidden-details-block entry-date">
                                                <span class="details-label entry-date-label">Καταχωρήθηκε στις: </span>
                                                <div class="details-content"><?php $dt=new DateTime($school_entry['school_entry_date']); echo $dt->format('d/m/Y'); ?></div>
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
