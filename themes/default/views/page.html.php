<? defined("SYSPATH") or die("No direct script access."); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Tranisitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title><?= _("Browse Photos") ?> :: <?= $item->title ?></title>
    <link rel="stylesheet" type="text/css" href="<?= url::file("lib/yui/reset-fonts-grids.css") ?>"
          media="screen,print,projection" />
    <link rel="stylesheet" type="text/css" href="<?= url::file("lib/yui/base-min.css") ?>"
          media="screen,print,projection" />
    <link rel="stylesheet" type="text/css" href="<?= $theme->url("css/screen.css") ?>"
          media="screen,print,projection" />
    <?= $theme->block(dynamic_block::HEAD_LINK) ?>
    <script src="<?= url::file("lib/jquery.js") ?>" type="text/javascript"></script>
    <script src="<?= url::file("lib/jquery.form.js") ?>" type="text/javascript"></script>
    <?= $theme->block(dynamic_block::HEAD_SCRIPT) ?>
    <!-- The following scripts would be added via the theme block mechanism -->
    <script src="<?= $theme->url("js/user.js") ?>" type="text/javascript"></script>
    <script src="<?= $theme->url("js/comment.js") ?>" type="text/javascript"></script>
    <? if ($user): ?>
    <script src="<?= url::file("lib/jquery.jeditable.js") ?>" type="text/javascript"></script>
    <? endif; ?>
  </head>

  <body>
    <div id="doc4" class="yui-t5 gView">
      <div id="hd">
        <div id="gHeader">
          <?= $theme->display("header.html") ?>
        </div>
      </div>
      <div id="bd">
        <div id="yui-main">
          <div class="yui-b">
            <div id="gContent" class="yui-g">
              <?= $content ?>
            </div>
          </div>
        </div>
        <div id="gSidebar" class="yui-b">
          <?= $theme->display("sidebar.html") ?>
        </div>
      </div>
      <div id="ft">
        <div id="gFooter">
          <?= $theme->display("footer.html") ?>
        </div>
      </div>
    </div>
    <? if ($user): ?>
    <?= $theme->in_place_edit(); ?>
    <? endif; ?>
  </body>
</html>
