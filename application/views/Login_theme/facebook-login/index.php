<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$form_attribute = array('class' => 'login');
$submit_attr = 'class="login-submit"';

$login = array(
    'name' => 'login',
    'id' => 'login',
    'class' => 'login-input',
    'placeholder' => "Email Address",
    'value' => set_value('login'),
    'maxlength' => 80,
    'size' => 30,
    'autofocus' => ''
);
if ($login_by_username AND $login_by_email) {
    $login_label = 'Email or login';
} else if ($login_by_username) {
    $login_label = 'Login';
} else {
    $login_label = 'Email';
}

$password = array(
    'name' => 'password',
    'id' => 'password',
    'class' => 'login-input',
    'placeholder' => "Password",
    'size' => 30,
);
$remember = array(
    'name' => 'remember',
    'id' => 'remember',
    'value' => 1,
    'checked' => set_value('remember'),
    'style' => 'margin:0;padding:0',
);
$captcha = array(
    'name' => 'captcha',
    'id' => 'captcha',
    'maxlength' => 8,
);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="lt-ie9 lt-ie8 lt-ie7" lang="en">
    <![endif]-->
<!--[if IE 7]>
<html class="lt-ie9 lt-ie8" lang="en">
    <![endif]-->
<!--[if IE 8]>
<html class="lt-ie9" lang="en">
    <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="<?= $login_theme_asset_url ?>css/style.css">
        <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    </head>
    <body>
        <?php echo form_open($this->uri->uri_string(), $form_attribute); ?>
        <h1><?= $SITE['name'] ?></h1>
        <?php echo form_input($login); ?>
        <?php echo form_password($password); ?>
        <table>
            <?php
            if ($show_captcha) {
                if ($use_recaptcha) {
                    ?>
                    <tr>
                        <td colspan="2">
                            <div id="recaptcha_image"></div>
                        </td>
                        <td>
                            <a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
                            <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
                            <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="recaptcha_only_if_image">Enter the words above</div>
                            <div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
                        </td>
                        <td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
                        <td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
                        <?php echo $recaptcha_html; ?>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td colspan="3">
                            <p>Enter the code exactly as it appears:</p>
                            <?php echo $captcha_html; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
                        <td><?php echo form_input($captcha); ?></td>

                    </tr>
                    <tr>
                        <td colspan="3" style="color: red;"><?php echo form_error($captcha['name']); ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

            <tr>
                <td colspan="3">
                    <?php echo form_checkbox($remember); ?>
                    <?php echo form_label('Remember me', $remember['id']); ?>
                </td>
            </tr>
        </table>

        <?php echo form_submit('submit', 'Let me in', $submit_attr); ?>
        <p class="login-help"><?php echo anchor('/auth/forgot_password/', 'Forgot password'); ?></p>
        <?php echo form_close(); ?>
        <section class="about">
            <p class="about-links">
                <?= anchor($SITE['website'], "Main Site", 'target="_blank"') ?>
                <?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('#', 'Register'); ?>
            </p>
            <p class="about-author">
                &copy; <?= date("Y") ?> <?= anchor($SITE['website'], $SITE['name'], 'target="_blank"') ?><br>
                Developed by <?= anchor($DEVELOPER['website'], $DEVELOPER['name'], 'target="_blank"') ?>
            </p>
        </section>
    </body>
</html>