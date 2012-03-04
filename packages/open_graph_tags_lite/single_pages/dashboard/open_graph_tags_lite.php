<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Open Graph Tags Setting'), t('For optimize to Facebook, please input these settings.'), 'span12 offset2', false); ?>
<div class="ccm-pane-body">
	<form method="post" id="thumbnail-form" action="<?=$this->action('update_thumbnail')?>" enctype="multipart/form-data" >

	<?=$this->controller->token->output('update_thumbnail')?>
	<input id="remove-existing-thumbnail" name="remove_thumbnail" type="hidden" value="0" />
	<fieldset>
		<legend><?=t('Default Thumbnail')?></legend>

	<?
	if($thumbnailID){
		$f = File::getByID($thumbnailID);
		?>
		<div class="clearfix">
		<label><?=t('Selected Thumbnail')?></label>
		<div class="input">
			<img src="<?=$f->getRelativePath() ?>" />
		</div>
		</div>
		<div class="clearfix">
		<label></label>
		<div class="input">
			<a href="javascript:void(0)" class="btn danger" onclick="removeThumbnail()"><?=t('Remove')?></a>
		</div>
		</div>
		
		<script>
		function removeThumbnail(){
			document.getElementById('remove-existing-thumbnail').value=1;
			$('#thumbnail-form').get(0).submit();
		}
		</script>
	<? }else{ ?>
		<div class="clearfix">
			<label for="thumbnail_upload"><?=t('Upload File')?></label>
			<div class="input">
				<input id="thumbnail_upload" type="file" class="input-file" name="thumbnail_file"/>
				<div><?php echo t('Images must be at least 50 pixels by 50 pixels. Square images work best, but you are allowed to use images up to three times as wide as they are tall.'); ?></div>
			</div>
		</div>

		<div class="clearfix">
			<label></label>
			<div class="input">
				<?
				print $interface->submit(t('Upload'), 'thumbnail-form', 'left');
				?>
			</div>
		</div>

	<? } ?>
	</fieldset>

	</form>
	
	<form method="post" id="site-form" action="<?=$this->action('save_settings')?>">
	<?=$this->controller->token->output('save_settings')?>
	<fieldset>
		<legend>Facebook Setting</legend>
		<div class="clearfix">
			<?=$form->label('fb_admin', t('Fb Admin ID'))?>
			<div class="input">
			<?=$form->text('fb_admin', $fb_admin, array('class' => 'span8'))?>
			</div>
		</div>
		<div class="clearfix">
			<?=$form->label('fb_app_id', t('Fb App ID'))?>
			<div class="input">
			<?=$form->text('fb_app_id', $fb_app_id, array('class' => 'span8'))?>
			</div>
		</div>
		<div class="clearfix">
			<label></label>
			<div class="input">
				<?
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
<?=Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>
