<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package bb
 */

$cc_link = '<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons СС-BY-SA 3.0</a>';
$tst = __("Teplitsa of social technologies", 'rdc');
$banner = get_template_directory_uri().'/assets/images/te-st-logo-10x50';
$footer_text = get_theme_mod('footer_text');
?>
</div><!--  #site_content -->

<div id="bottom_bar" class="bottom-bar"><div class="container">
	<div class="frame frame-wide">
		<div class="bit lg-12">
			<?php if(!is_page('subscribe'))	{ ?>
				<h5 class="frame frame-wide frame-title1"><?php _e('Subscribe to our newsletter', 'rdc');?></h5>
				<div class="newsletter-form in-footer md-6">
					<!-- Begin MailChimp Signup Form -->
                        <div id="mc_embed_signup" class="">
                            <form action="//ecobarnaul.us13.list-manage.com/subscribe/post?u=3fa19660475e5365a4dc3dfcd&amp;id=8d6009c169" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                <div id="mc_embed_signup_scroll">
                                    <div class="rdc-textfield frm_form_field rdc-textfield frm_form_field  frm_required_field frm_top_container">
                                        <input type="email" value="" name="EMAIL" class="rdc-textfield__input" id="mce-EMAIL" placeholder="Введите ваш адрес электронной почты" required>
                                    </div>
                                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                	<div style="position: absolute; left: -5000px;" aria-hidden="true">
										<input type="text" name="b_3fa19660475e5365a4dc3dfcd_8d6009c169" tabindex="-1" value="">
									</div>
                                    <div class="frm_submit">
                                        <input type="submit" value="Подписаться" name="subscribe" id="mc-embedded-subscribe" class="button">
                                    </div>
                                </div>
                            </form>
                        </div>

<!--End mc_embed_signup-->
				</div>
			<?php } else { ?>
				&nbsp;
			<?php  }?>
		</div>


	</div>
</div></div>

<footer class="site-footer"><div class="container">

	<div class="widget-area _with-padding">
		<div class="col-sm-3 line-heighted">
			<a href="<?=get_option('priem_vtorsirya')?>" target="_blank">Пункты приема вторсырья</a>
		</div>
		<div class="col-sm-2 line-heighted">
			<a href="<?=get_option('field_ecofront')?>" target="_blank">Экофронт</a>
		</div>
		<div class="col-sm-3">
			<a href="mailto:<?=get_option('email_adress')?>">
				<span class="icon email"><svg class="svg-icon icon-email">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-mail"></use>
				</svg></span>
			<span class="text hidden-xs"><?=get_option('email_adress')?></span></a>
		</div>
		<div class="col-sm-2">
			<a href="<?=get_option('vk_adress')?>" target="_blank">
				<span class="icon vk"><svg class="svg-icon icon-vk">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-vk"></use>
				</svg></span>
			<span class="text hidden-xs">Вконтакте</span></a>
		</div>
		<div class="col-sm-2">
			<a href="<?=get_option('ok_adress')?>" target="_blank">
				<span class="icon ok"><svg class="svg-icon icon-ok">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-ok"></use>
				</svg></span>
			<span class="text hidden-xs">Одноклассники</span></a>
		</div>








		<?php //dynamic_sidebar( 'footer-sidebar' );?></div>
	<div class="hr"></div>
	<div class="sf-cols">

		<div class="sf-cols-8">

			<div class="copy">
				<?php echo apply_filters('rdc_the_content', $footer_text); ?>
				<p><?php printf(__('All materials of the site are avaliabe under license %s', 'rdc'), $cc_link);?></p>
			</div>

		</div>

		<div class="sf-cols-4">
			<div class="te-st-bn">
				<p class="support">Сайт сделан <br>при поддержке</p>
				<a title="<?php echo $tst;?>" href="http://te-st.ru/" class="rdc-banner">
					<svg class="rdc-icon"><use xlink:href="#icon-te-st" /></svg>
				</a>
			</div>
		</div>
	</div>


</div></footer>

<?php wp_footer(); ?>
</body>
</html>
