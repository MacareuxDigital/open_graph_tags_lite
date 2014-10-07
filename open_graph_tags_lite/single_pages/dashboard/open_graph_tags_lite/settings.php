<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<form method="post" id="site-form" action="<?php echo $this->action('save_settings'); ?>"  enctype="multipart/form-data">

<?php echo $this->controller->token->output('save_settings'); ?>

	<fieldset>
		<legend><?php echo t('Default Thumbnail'); ?></legend>
		<div class="form-group">
			<?php echo $form->label('og_thumbnail_id', 'og:image')?>
			<?php $al = Loader::helper('concrete/asset_library'); ?>
			<?php echo $al->image('og-thumbnail-id', 'og_thumbnail_id', 'Select Default Thumbnail', $imageObject); ?>
			<span class="help-block">
				<?php echo t('Image referenced by og:image must be at least 600x315 pixels.'); ?>
			</span>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php echo t('Facebook Setting'); ?></legend>
		<div class="form-group">
			<?php echo $form->label('fb_admin', 'fb:admins'); ?>
			<?php echo $form->text('fb_admin', $fb_admin); ?>
		</div>
		<div class="form-group">
			<?php echo $form->label('fb_app_id', 'fb:app_id'); ?>
			<?php echo $form->text('fb_app_id', $fb_app_id); ?>
		</div>
	</fieldset>
	<fieldset>
		<legend><?php echo t('Twitter Cards Setting'); ?></legend>
		<div class="form-group">
			<?php echo $form->label('twitter_site', t('Twitter Username')); ?>
				<div class="input-group">
					<div class="input-group-addon">@</div>
					<?php echo $form->text('twitter_site', $twitter_site); ?>
				</div>
			<span class="help-block">
				<?php echo t('Twitter handle of the person to contact'); ?>
			</span>
		</div>
	</fieldset>
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo t('Page Attribute Reference'); ?></div>
		<div class="panel-body">
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
					<tr>
						<th><?php echo 'twitter_card'; ?></th>
						<td><?php echo t('The card type, which will be one of "summary", "photo", or "player". If this value is empty, "summary" used instead.'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="ccm-dashboard-form-actions-wrapper">
	<div class="ccm-dashboard-form-actions">
		<button class="pull-right btn btn-success" type="submit" ><?=t('Save')?></button>
	</div>
	</div>

</form>
