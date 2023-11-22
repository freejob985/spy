<?php
/*
Plugin Name: spy
Description: spy
Version: 1.0
Author: harry potter
*/


/*
بداية السيشن 
*/

if (!session_id()) {
    session_start();
}
function display_errors_using_hook()
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        if (!defined('WP_DEBUG_DISPLAY')) {
            define('WP_DEBUG_DISPLAY', true);
        }
        if (!defined('WP_DISABLE_FATAL_ERROR_HANDLER')) {
            define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
        }
        if (!defined('SCRIPT_DEBUG')) {
            define('SCRIPT_DEBUG', true);
        }
        @ini_set('display_errors', 1);
    }
}
add_action('init', 'display_errors_using_hook');



add_action('template_redirect', 'custom_function_for_register_page');

function custom_function_for_register_page()
{
    if (is_page('step1') or is_page('step2') or is_page('registration') ) {
           unset($_SESSION['new_user']);
    }
}
/*
عمل صفحة بها نموذج بريد الكتروني 
*/
// إنشاء صفحة جديدة عند تفعيل البلوجين
function step1()
{
    $url = plugins_url('/step1.php', __FILE__);
    $page_title = 'step1'; // عنوان الصفحة الجديدة
    $page_content = '
    <div class="container">
    [step1]
    </div>
';
    // إنشاء الصفحة الجديدة
    $new_page = array(
        'post_title' => $page_title,
        'post_content' => $page_content,
        'post_status' => 'publish',
        'post_type' => 'page',
    );

    $page_id = wp_insert_post($new_page); // إدراج الصفحة الجديدة في قاعدة البيانات


}
function step2()
{
    $url = plugins_url('/step2.php', __FILE__);
    $page_title = 'step2'; // عنوان الصفحة الجديدة
    $page_content = '
    <div class="container">
    [step2]
    </div>
';

    // إنشاء الصفحة الجديدة
    $new_page = array(
        'post_title' => $page_title,
        'post_content' => $page_content,
        'post_status' => 'publish',
        'post_type' => 'page',
    );

    $page_id = wp_insert_post($new_page); // إدراج الصفحة الجديدة في قاعدة البيانات


}
register_activation_hook(__FILE__, 'step1');
register_activation_hook(__FILE__, 'step2');

/*
عند الضغط علي النموذج الأول يوجه الي الصفحة الأخري
خطوة الكود
*/
add_action('admin_post_add_step1', 'prefix_admin_add_foobar');
add_action('admin_post_add_step2', 'prefix_admin_add_foobar2');
add_action('admin_post_nopriv_add_step2', 'prefix_admin_add_foobar2');
function prefix_admin_add_foobar2()
{
    try {
        check_admin_referer('name_of_my_action', 'name_of_nonce_field');
        if ($_POST['action'] === 'add_step2') {
            $user_email = $_SESSION['user_email'];

            $user = get_user_by('email', $user_email);
            $confirmation_code_saved = get_user_meta($user->id, 'confirmation_code', true);
            if ($confirmation_code_saved && isset($_POST['code']) && $_POST['code'] === $confirmation_code_saved) {

                wp_redirect(home_url('/') . "/register/");
            } else {
                wp_die('رمز التأكيد غير صحيح.');
            }

        }
    } catch (Exception $e) {
        echo 'حدث خطأ: ' . $e->getMessage();
    } finally {
        echo 'هذا الجزء يتم تنفيذه دائمًا.';
    }
}
function prefix_admin_add_foobar()
{
    if ($_POST['action'] === 'add_step1') {
        $user_email = isset($_POST['email']) ? $_POST['email'] : '';
        $user_data = array(
            'user_login' => 'Stage_1_user' . rand(1, 10000),
            'user_pass' => wp_generate_password(),
            'user_email' => $user_email,
        );

        $user_id = wp_insert_user($user_data);
        $user = get_user_by('email', $user_email);
        if ($user) {
            $confirmation_code = wp_generate_password(3, false);
            update_user_meta($user->id, 'confirmation_code', $confirmation_code);
            $_SESSION['user_email'] = $user_email;
            // echo $user->id;
            wp_redirect(home_url('/') . "/step2/");
        } else {
            echo 'المستخدم غير موجود.';
        }

    }
}
// ======================================================================================
/*
عند ايقاف تنشيط البلاجن احذف الصفحة
*/
register_deactivation_hook(__FILE__, 'delete_plugin_generated_page');
function delete_plugin_generated_page()
{
    $page_title = 'step1'; // عنوان الصفحة التي تم إنشاؤها

    // البحث عن الصفحة باستخدام عنوانها
    $page = get_page_by_title($page_title);

    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page_title = 'step2'; // عنوان الصفحة التي تم إنشاؤها

    $page = get_page_by_title($page_title);

    if ($page) {
        wp_delete_post($page->ID, true);
    }
}
// =======================================================================================
/*
انشاء جدول
*/
register_activation_hook(__FILE__, 'my_plugin_activation');
function my_plugin_activation()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'registration';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
        Code VARCHAR(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Deactivation Hook
register_deactivation_hook(__FILE__, 'my_plugin_deactivation');

function my_plugin_deactivation()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'registration';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}

/*
 */
function step1_pag()
{
    $html_content = require_once 'email.php';
    return $html_content;
}
add_shortcode('step1', 'step1_pag');


function step2_pag()
{
    $html_content = require_once 'code.php';
    return $html_content;
}
add_shortcode('step2', 'step2_pag');











?>