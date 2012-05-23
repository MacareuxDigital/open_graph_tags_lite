<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Open Graph Tags Setting'), t('For optimize to Facebook, please input these settings.'), 'span12 offset2', false); ?>
<div class="ccm-pane-body">
	<form method="post" id="site-form" action="<?php echo $this->action('save_settings'); ?>"  enctype="multipart/form-data">

	<?php echo $this->controller->token->output('save_settings'); ?>
	<fieldset>
		<legend><?php echo t('Default Thumbnail'); ?></legend>
		<div class="clearfix">
			<label for="thumbnail_upload"><?php echo t('og:image'); ?></label>
			<div class="input">
				<?php $al = Loader::helper('concrete/asset_library'); ?>
				<?php echo $al->image('og-thumbnail-id', 'og_thumbnail_id', 'Select Thumbnail', $imageObject); ?>
				<span class="help-block">
					<?php echo t('Image referenced by og:image must be at least 200px in both dimensions.'); ?>
				</span>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php echo t('Facebook Setting'); ?></legend>
		<div class="clearfix">
			<?php echo $form->label('fb_admin', t('fb:admins')); ?>
			<div class="input">
			<?php echo $form->text('fb_admin', $fb_admin, array('class' => 'span8')); ?>
			</div>
		</div>
		<div class="clearfix">
			<?php echo $form->label('fb_app_id', t('fb:app_id')); ?>
			<div class="input">
			<?php echo $form->text('fb_app_id', $fb_app_id, array('class' => 'span8')); ?>
			</div>
		</div>
		<div class="clearfix">
			<label></label>
			<div class="input">
				<?php
				print $interface->submit(t('Save'), 'site-form', 'left');
				?>
			</div>
		</div>
	</fieldset>
	
	</form>
</div>
<div class="ccm-pane-footer">
	<h4><?php echo t('Page Attribute Reference'); ?></h4>
	<dl>
		<dt><?php echo t('og_title'); ?></dt>
		<dd><?php echo t('The title of the entity. If this value is empty, "meta_title" attribute or page name used instead.'); ?></dd>
		<dt><?php echo t('og_type'); ?></dt>
		<dd><?php echo t('The type of the entity. If this value is empty, "article" used instead.'); ?></dd>
		<dt><?php echo t('og_image'); ?></dt>
		<dd><?php echo t('An image that represents the entity. If this value is empty, "page_thumbnail" attribute used instead.'); ?></dd>
	</dl>
</div>
</form>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>
