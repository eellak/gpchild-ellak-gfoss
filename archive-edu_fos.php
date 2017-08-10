<?php
/**
 * The template for displaying github_contributor custom post types.
 *
 *
 * @package Generate
 */

get_header(); ?>

	<div id="primary" <?php generate_content_class();?>>
		<main id="main" <?php generate_main_class(); ?> itemprop="mainContentOfPage" role="main">
                    <?php do_action('generate_before_main_content'); ?>
                    <div class='inside-article'>
                        <div class='ellak-edu_fos title-wrapper'>
                            <div class='ellak-edu_fos title text'>Ελεύθερα διαθέσιμο εκπαιδευτικό λογισμικό</div>
                            <div class='ellak-edu_fos title controls'>
                                <?php
                                //retrieve the taxonomy terms tha will fill the select boxes.
                                $thematiki_terms=get_terms('edu_fos_thematiki');
                                $antikimeno_terms=get_terms('edu_fos_antikimeno');
                                $vathmida_terms=get_terms('edu_fos_vathmida');
                                ?>
                                <div class='ellak-edu_fos sort-controls'>
                                    <form id='main-form' method='post' name='main-form' action='<?php echo esc_url(admin_url('admin-post.php')); ?>'>
                                        <div class='ellak-edu_fos sort-controls ellak-label'>
                                            Ταξινόμηση κατά: 
                                        </div>
                                        <div class='ellak-edu_fos sort-controls'>
                                            <a href='/?post_type=edu_fos&contr_order=contributions' class='ellak-edu_fos sort-controls by-contributions ellak-disabled'>
                                                <!--<span class='text'>θεματική</span>-->
                                            </a>
                                            <label for='thematiki-select'>θεματική:</label>
                                            <select id='thematiki-select' class='ellak-edu_fos fos-category-select' name='thematiki'>
                                                <option value='null_option'>ΚΑΝΕΝΑ ΦΙΛΤΡΟ</option>
                                                <?php foreach($thematiki_terms as $thematiki_term):?>
                                                <option value='<?php echo $thematiki_term->slug;?>'><?php echo $thematiki_term->name;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class='ellak-edu_fos sort-controls'>
                                            <a href='/?post_type=edu_fos&contr_order=followers' class='ellak-edu_fos sort-controls by-followers ellak-disabled'>
                                                <!--<span class='text'>γν. αντικείμενο</span>-->
                                            </a>
                                            <label for='antikimeno-select'>αντικείμενο:</label>
                                            <select id='antikimeno-select' class='ellak-edu_fos fos-category-select' name='antikimeno'>
                                                <option value='null_option'>ΚΑΝΕΝΑ ΦΙΛΤΡΟ</option>
                                                <?php foreach($antikimeno_terms as $antikimeno_term):?>
                                                <option value='<?php echo $antikimeno_term->slug;?>'><?php echo $antikimeno_term->name;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class='ellak-edu_fos sort-controls by-language'>
                                            <a href='<?php echo (add_query_arg(array('post_type'=>'edu_fos', 'contr_order'=>'language'), 'https://edu.ellak.gr/'));?>' class='ellak-edu_fos sort-controls by-language ellak-disabled'>
                                                <!--<span class='text'>εκπ. βαθμίδα</span>-->
                                            </a>
                                            <label for='vathmida-select'>βαθμίδα:</label>
                                            <select id='vathmida-select' class='ellak-edu_fos fos-category-select' name='vathmida'>
                                                <option value='null_option'>ΚΑΝΕΝΑ ΦΙΛΤΡΟ</option>
                                                <?php foreach($vathmida_terms as $vathmida_term):?>
                                                <option value='<?php echo $vathmida_term->slug;?>'><?php echo $vathmida_term->name;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <input type='hidden' name='action' value='handle_edu_fos_query'>
                                        <button type='submit' value='submit' class='ellak-edu_fos sort-controls filter_submit'>Υποβολή</button>
                                    </form>    
                                </div>
                            </div>
                        </div>
                        
                        <div class='ellak-edu_fos fos-entry-set main-wrapper'>
                            <?php
                            if(have_posts()):
                                while(have_posts()):
                                    the_post();
                                    if(get_the_title()!==null && strcmp(get_the_title(), '')):?>
                                        <div class='ellak-edu_fos fos-entry main-wrapper'>
                                            <div class='ellak-edu_fos fos-entry title-text-wrapper'>
                                                <div class='ellak-edu_fos fos-entry title-text' role='button' data-toggle='collapse' data-target='#<?php the_ID(); ?>-details'>
                                                    <?php the_title();?>
                                                </div>
                                            </div>
                                            <div id='<?php the_ID(); ?>-details' class='ellak-edu_fos fos-entry details-container collapse'>
                                                <div class='ellak-edu_fos fos-entry details-wrapper'>
                                                    <?php $tmp=get_the_content();
                                                    if(isset($tmp) && strcmp($tmp, '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>Περιγραφή: </span>
                                                        <span class='ellak-edu_fos fos-entry details-value'><?php the_content(); ?></span>
                                                    </div>
                                                    <?php endif?>
                                                    <?php
                                                    if(isset(get_post_meta(get_the_ID(), 'edu_fos_url')[0]) && strcmp(get_post_meta(get_the_ID(), 'edu_fos_url')[0], '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>URL: </span>
                                                        <a href='<?php echo get_post_meta(get_the_ID(), 'edu_fos_url')[0]; ?>' target='_blank'>
                                                            <span class='ellak-edu_fos fos-entry details-value'><?php echo get_post_meta(get_the_ID(), 'edu_fos_url')[0]; ?></span>
                                                        </a>
                                                    </div>
                                                    <?php endif?>
                                                    <?php $tmp;
                                                    if(isset(get_the_terms(get_the_ID(), 'edu_fos_thematiki')[0]->name) && strcmp(get_the_terms(get_the_ID(), 'edu_fos_thematiki')[0]->name, '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>Θεματική: </span>
                                                        <span class='ellak-edu_fos fos-entry details-value'><?php echo get_the_terms(get_the_ID(), 'edu_fos_thematiki')[0]->name; ?></span>
                                                    </div>
                                                    <?php endif?>
                                                    <?php
                                                    if(isset(get_the_terms(get_the_ID(), 'edu_fos_antikimeno')[0]->name) && strcmp(get_the_terms(get_the_ID(), 'edu_fos_antikimeno')[0]->name, '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>Γν. Αντικείμενο: </span>
                                                        <span class='ellak-edu_fos fos-entry details-value'><?php echo get_the_terms(get_the_ID(), 'edu_fos_antikimeno')[0]->name; ?></span>
                                                    </div>
                                                    <?php endif?>
                                                    <?php
                                                    if(isset(get_the_terms(get_the_ID(), 'edu_fos_vathmida')[0]->name) && strcmp(get_the_terms(get_the_ID(), 'edu_fos_vathmida')[0]->name, '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>Εκπ. Βαθμίδα: </span>
                                                        <span class='ellak-edu_fos fos-entry details-value'><?php echo get_the_terms(get_the_ID(), 'edu_fos_vathmida')[0]->name; ?></span>
                                                    </div>
                                                    <?php endif?>
                                                    <?php
                                                    if(isset(get_the_terms(get_the_ID(), 'edu_fos_adia')[0]->name) && strcmp(get_the_terms(get_the_ID(), 'edu_fos_adia')[0]->name, '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>Άδεια: </span>
                                                        <span class='ellak-edu_fos fos-entry details-value'><?php echo get_the_terms(get_the_ID(), 'edu_fos_adia')[0]->name; ?></span>
                                                    </div>
                                                    <?php endif?>
                                                    <?php
                                                    if(isset(get_the_terms(get_the_ID(), 'edu_fos_idos')[0]->name) && strcmp(get_the_terms(get_the_ID(), 'edu_fos_idos')[0]->name, '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>Είδος: </span>
                                                        <span class='ellak-edu_fos fos-entry details-value'><?php echo get_the_terms(get_the_ID(), 'edu_fos_idos')[0]->name; ?></span>
                                                    </div>
                                                    <?php endif?>
                                                    <?php
                                                    if(isset(get_the_terms(get_the_ID(), 'edu_fos_litourgiko')[0]->name) && strcmp(get_the_terms(get_the_ID(), 'edu_fos_litourgiko')[0]->name, '')):
                                                    ?>
                                                    <div class='ellak-edu_fos fos-entry details-entry'>
                                                        <span class='ellak-edu_fos fos-entry details-label'>Λειτουργικό: </span>
                                                        <span class='ellak-edu_fos fos-entry details-value'><?php echo get_the_terms(get_the_ID(), 'edu_fos_litourgiko')[0]->name; ?></span>
                                                    </div>
                                                    <?php endif?>
                                                </div>
                                            </div>
                                        </div> <!-- main-wrapper -->
                                        <?php endif ?>
                            <?php
                                endwhile;
                            endif;
                            ?>
                        </div>
                                <div class='ellak-edu_fos paging-buttons ellak-container'>
                                    <div class='ellak-edu_fos paging-buttons ellak-main-wrapper'>
                                        <div class='ellak-edu_fos paging-buttons ellak-button'>
                                        <?php echo paginate_links(); ?>
                                        </div>
                                    </div>
                                </div>
                    </div><!-- inside-article -->           
                                    
			<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
do_action('generate_sidebars');
get_footer();
