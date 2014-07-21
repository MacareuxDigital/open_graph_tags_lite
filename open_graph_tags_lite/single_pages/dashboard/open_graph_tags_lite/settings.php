<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Open Graph Tags Settings'), t('For optimize to Facebook, please input these settings.'), 'span10 offset1', false); ?>
<form method="post" id="site-form" action="<?php echo $this->action('save_settings'); ?>"  enctype="multipart/form-data" class="form-horizontal">

<?php echo $this->controller->token->output('save_settings'); ?>

<div class="ccm-pane-body">
	<fieldset>
		<legend><?php echo t('Default Thumbnail'); ?></legend>
		<div class="control-group">
			<?php echo $form->label('og_thumbnail_id', 'og:image')?>
			<div class="controls">
				<?php $al = Loader::helper('concrete/asset_library'); ?>
				<?php echo $al->image('og-thumbnail-id', 'og_thumbnail_id', 'Select Default Thumbnail', $imageObject); ?>
				<span class="help-block">
					<?php echo t('Image referenced by og:image must be at least 600x315 pixels.'); ?>
				</span>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php echo t('Facebook Setting'); ?></legend>
		<div class="control-group">
			<?php echo $form->label('fb_admin', 'fb:admins'); ?>
			<div class="controls">
			<?php echo $form->text('fb_admin', $fb_admin, array('class' => 'input-xlarge')); ?>
			</div>
		</div>
		<div class="control-group">
			<?php echo $form->label('fb_app_id', 'fb:app_id'); ?>
			<div class="controls">
			<?php echo $form->text('fb_app_id', $fb_app_id, array('class' => 'input-xlarge')); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<h4><?php echo t('Page Attribute Reference'); ?></h4>
				<table class="table">
					<thead>
						<tr>
							<th><?php echo t('Handle'); ?></th>
							<th><?php echo t('Description'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?php echo 'og_title'; ?></th>
							<td><?php echo t('The title of the entity. If this value is empty, "meta_title" attribute or page name used instead.'); ?></td>
						</tr>
						<tr>
							<th><?php echo 'og_type'; ?></th>
							<td><?php echo t('The type of the entity. If this value is empty, "article" used instead.'); ?></td>
						</tr>
						<tr>
							<th><?php echo 'og_image'; ?></th>
							<td><?php echo t('An image that represents the entity. If this value is empty, "page_thumbnail" attribute used instead.'); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php echo t('Twitter Cards Setting'); ?></legend>
		<div class="control-group">
			<?php echo $form->label('twitter_site', t('Twitter Username')); ?>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on">@</span><?php echo $form->text('twitter_site', $twitter_site, array('class' => 'input-xlarge')); ?>
				</div>
				<span class="help-block">
					<?php echo t('Twitter handle of the person to contact'); ?>
				</span>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<h4><?php echo t('Page Attribute Reference'); ?></h4>
				<table class="table">
					<thead>
						<tr>
							<th><?php echo t('Handle'); ?></th>
							<th><?php echo t('Description'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?php echo 'twitter_card'; ?></th>
							<td><?php echo t('The card type, which will be one of "summary", "photo", or "player". If this value is empty, "summary" used instead.'); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</fieldset>
</div>
<div class="ccm-pane-footer">
	<?php print $interface->submit(t('Save'), 'site-form', 'right', 'primary'); ?>
</div>

</form>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>
