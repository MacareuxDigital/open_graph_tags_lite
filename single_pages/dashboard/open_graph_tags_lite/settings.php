<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<form method="post" id="site-form" action="<?php echo $this->action('save_settings'); ?>" enctype="multipart/form-data">

    <?php echo $this->controller->token->output('save_settings'); ?>

    <fieldset>
        <legend><?php echo t('Default Thumbnail'); ?></legend>
        <div class="form-group">
            <?php echo $form->label('og_thumbnail_id', 'og:image') ?>
            <?php $al = Loader::helper('concrete/asset_library'); ?>
            <?php echo $al->image('og-thumbnail-id', 'og_thumbnail_id', 'Select Default Thumbnail', $imageObject); ?>
            <span class="help-block">
                <?php echo t('This image is used for og:image by default. See %sSharing Best Practices for Websites%s', '<a href="https://developers.facebook.com/docs/sharing/best-practices#sharing-best-practices-for-websites">', '</a>'); ?>
            </span>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo t('Facebook Setting'); ?></legend>
        <div class="form-group">
            <?php echo $form->label('fb_app_id', 'fb:app_id'); ?>
            <div class="input-group">
                <?php echo $form->text('fb_app_id', $fb_app_id); ?>
            </div>
            <span class="help-block">
                <?php echo t("In order to use %sFacebook Insights%s you must add the app ID to your page. Insights lets you view analytics for traffic to your site from Facebook. Find the app ID in your %sApp Dashboard%s.",
                    '<a href="https://developers.facebook.com/docs/sharing/referral-insights" target="_blank">', '</a>', '<a href="https://developers.facebook.com/apps/redirect/dashboard" target="_blank">', '</a>'); ?>
            </span>
        </div>
        <div class="form-group">
            <?php echo $form->label('fb_admin', 'fb:admins'); ?>
            <?php echo $form->text('fb_admin', $fb_admin); ?>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo t('Twitter Cards Setting'); ?></legend>
        <div class="form-group">
            <?php echo $form->label('twitter_site', t('Twitter Username')); ?>
            <div class="input-group">
                <div class="input-group-addon input-group-text">@</div>
                <?php echo $form->text('twitter_site', $twitter_site); ?>
            </div>
            <span class="help-block">
                <?php echo t("Twitter @username of website. This value is required if you'd like to activate Twitter Card integration."); ?>
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
                    <td><?php echo t('The type of the entity. If this value is empty, "website" used instead.'); ?></td>
                </tr>
                <tr>
                    <th><?php echo 'og_image'; ?></th>
                    <td><?php echo t('An image that represents the entity. If this value is empty, "thumbnail" attribute used instead.'); ?></td>
                </tr>
                <tr>
                    <th><?php echo 'twitter_card'; ?></th>
                    <td><?php echo t('The card type, which will be one of "summary", "summary_large_image", "app", or "player". If this value is empty, "summary" or "summary_large_image" used instead.'); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo t('Validator'); ?></div>
        <div class="panel-body">
            <ul>
                <li><a href="https://developers.facebook.com/tools/debug/"
                       target="_blank"><?php echo t('Facebook Sharing Debugger'); ?></a></li>
                <li><a href="https://cards-dev.twitter.com/validator"
                       target="_blank"><?php echo t('Twitter Card validator'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button class="float-end btn btn-success" type="submit"><?php echo t('Save') ?></button>
        </div>
    </div>

</form>
