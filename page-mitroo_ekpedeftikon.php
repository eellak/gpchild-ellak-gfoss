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
					<h3>Για να δείτε το κάλεσμα και να εγγραφείτε στον κατάλογο, πατήστε <a href="https://edu.ellak.gr/2016/10/12/kalesma-dimiourgias-mitroou-ekpedeftikon-pou-chrisimopioun-anichto-logismiko-stin-ekpedeftiki-diadikasia/">εδώ</a></h3>
					
				<?php
                                $teachers_query="SELECT * FROM `wpcentral_postmeta`, `wpcentral_posts`
WHERE `wpcentral_postmeta`.`post_id`=`wpcentral_posts`.`ID`
AND `wpcentral_posts`.`ID` in (SELECT DISTINCT `post_id` FROM `wpcentral_postmeta` WHERE `meta_key`='_form_id' AND `meta_value`=160)
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
                                
                                $teacher_submissions_result_set=$wpdb->get_results($teachers_query);
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
                                    return strcmp($a['teacher_surname'], $b['teacher_surname']);
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
                                
                                if (isset($teacher_submissions_result_set)){
                                $entryID=$teacher_submissions_result_set[0];
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
                                foreach($teacher_submissions_result_set as $result_entry): 
                                    //var_dump($result_entry);
                                    if ($entryID!=$result_entry->ID){
                                        //echo "check ".$entryID;
                                        $grouped_result_array[]=$current_entry;
                                        $entryID=$result_entry->ID;
                                    }
                                    $current_entry['teacher_id']=$result_entry->ID;
                                    if($result_entry->meta_key=='_field_790'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['teacher_name']=$result_entry->meta_value;
                                        
                                        //the entry date is the same on all the submission fields,
                                        //we choose to acquire it from the first available field.
                                        $current_entry['teacher_entry_date']=$result_entry->post_date;
                                        //echo '790';
                                    }
                                    if($result_entry->meta_key=='_field_791'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['teacher_surname']=$result_entry->meta_value;
                                        //echo '791';
                                    }
                                    if($result_entry->meta_key=='_field_792'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['teacher_school_name']=$result_entry->meta_value;
                                        //echo '792';
                                    }
                                    if($result_entry->meta_key=='_field_796'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['teacher_social']=$result_entry->meta_value;
                                        //echo '792';
                                    }
                                    if($result_entry->meta_key=='_field_810'){
                                        //$school_name=mb_strtoupper($result_entry->meta_value);
                                        $current_entry['teacher_email']=$result_entry->meta_value;
                                        //echo '792';
                                    }
                                    if($result_entry->meta_key=='_field_794'){
                                        //$school_email=$result_entry->meta_value;
                                        $current_entry['teacher_school_address']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_805'){
                                        //$school_address=$result_entry->meta_value;
                                        $current_entry['teacher_field_1']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_819'){
                                        //$school_stem_robotics=$result_entry->meta_value;
                                        $current_entry['teacher_field_2']=$result_entry->meta_value;
                                    }
                                    if($result_entry->meta_key=='_field_816'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['teacher_field_3']=parse_links($result_entry->meta_value);
                                    }
                                    if($result_entry->meta_key=='_field_817'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['teacher_field_4']=parse_links($result_entry->meta_value);
                                    }
                                    if($result_entry->meta_key=='_field_811'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['teacher_content_1']=parse_links($result_entry->meta_value);
                                    }
                                    if($result_entry->meta_key=='_field_812'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['teacher_content_2']=parse_links($result_entry->meta_value);
                                    }
                                    if($result_entry->meta_key=='_field_813'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['teacher_content_3']=parse_links($result_entry->meta_value);
                                    }
                                    if($result_entry->meta_key=='_field_814'){
                                        //$school_open_software=$result_entry->meta_value;
                                        $current_entry['teacher_content_4']=parse_links($result_entry->meta_value);
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
                                <!-- creating the container div for each list entry -->
                                <h4>Μέχρι στιγμής έχουν ανταποκριθεί στο κάλεσμα <?php echo count($grouped_result_array);?> εκπαιδευτικοί.</h4>
                                <?php
                                    $aa_counter=0;
                                    foreach($grouped_result_array as $teacher_entry):
                                        $aa_counter++;
                                ?>
                                <div class="teacher-container">
                                    <div class="btn btn-primary teacher-container visible-label"  role="button" data-toggle="collapse" data-target="<?php echo '#'.$teacher_entry['teacher_id']; ?>">
                                        <?php 
                                            $t_name=$teacher_entry['teacher_name'];
                                            $t_surname=$teacher_entry['teacher_surname'];
                                            $t_surname=custom_mb_ucfirst(mb_strtolower($t_surname), 'UTF-8');
                                            $t_name=custom_mb_ucfirst(mb_strtolower($t_name), 'UTF-8');
                                            //$t_name=replace_small_sigma($t_name);
                                            //$t_surname=replace_small_sigma($t_surname);
                                            $t_name=mb_ereg_replace('(\x{03C3}\b)', 'ς', $t_name);
                                            $t_surname=mb_ereg_replace('\x{03C3}\b', 'ς', $t_surname);
                                            
                                            //echoing the teacher name and surname
                                            echo  $aa_counter.'. '.$t_surname.", ".$t_name;
                                            
                                        ?>
                                    </div>
                                    <div id="<?php echo $teacher_entry['teacher_id']; ?>" class="collapse">
                                        <div class="teacher-container hidden-details">
                                            <?php if (is_user_logged_in() && mb_strlen($teacher_entry['teacher_email'])>0): ?>
                                            <div class="hidden-details-block teacher-email">
                                                <span class="details-label teacher-email-details-label">Ηλ. αλληλογραφία: </span>
                                                <div class="details-content more"><?php echo $teacher_entry['teacher_email']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (is_user_logged_in() && mb_strlen($teacher_entry['teacher_social'])>0): ?>
                                            <div class="hidden-details-block teacher-social">
                                                <span class="details-label teacher-social-details-label">Κοινωνική δικτύωση: </span>
                                                <div class="details-content more"><?php echo $teacher_entry['teacher_social']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($teacher_entry['teacher_school_name'])>0): ?>
                                            <div class="hidden-details-block school-name">
                                                <span class="details-label school-name-label">Σχολείο: </span>
                                                <div class="details-content"><?php echo $teacher_entry['teacher_school_name']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($teacher_entry['teacher_school_address'])>0): ?>
                                            <div class="hidden-details-block teacher-school-address">
                                                <span class="details-label teacher-school-address-details-label">Διεύθυνση σχολείου: </span>
                                                <div class="details-content more"><?php echo $teacher_entry['teacher_school_address']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($teacher_entry['teacher_content_1'])>0): ?>
                                            <div class="hidden-details-block teacher-field">
                                                <span class="details-label stem-robotics-details-label"><?php echo $teacher_entry['teacher_field_1']; ?>: </span>
                                                <div class="details-content more"><?php echo $teacher_entry['teacher_content_1']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($teacher_entry['teacher_content_2'])>0): ?>
                                            <div class="hidden-details-block teacher-field">
                                                <span class="details-label teacher-field-label"><?php echo $teacher_entry['teacher_field_2']; ?>: </span>
                                                <div class="details-content"><?php echo $teacher_entry['teacher_content_2']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($teacher_entry['teacher_content_3'])>0): ?>
                                            <div class="hidden-details-block teacher-field">
                                                <span class="details-label teacher-field-label"><?php echo $teacher_entry['teacher_field_3']; ?>: </span>
                                                <div class="details-content"><?php echo $teacher_entry['teacher_content_3']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (mb_strlen($teacher_entry['teacher_content_4'])>0): ?>
                                            <div class="hidden-details-block teacher-field">
                                                <span class="details-label teacher-field-label"><?php echo $teacher_entry['teacher_field_4']; ?>: </span>
                                                <div class="details-content"><?php echo $teacher_entry['teacher_content_4']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (mb_strlen($teacher_entry['teacher_entry_date'])>0): ?>
                                            <div class="hidden-details-block entry-date">
                                                <span class="details-label entry-date-label">Καταχωρήθηκε στις: </span>
                                                <div class="details-content"><?php $dt=new DateTime($teacher_entry['teacher_entry_date']); echo $dt->format('d/m/Y'); ?></div>
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
