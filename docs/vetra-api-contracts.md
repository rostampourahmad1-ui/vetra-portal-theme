# قراردادهای API پوسته VETRA

**وضعیت:** ممیزی source و ثبت قرارداد؛ بدون migration، حذف، انتقال کد یا تغییر رفتار.  
**تاریخ استخراج:** 2026-10-02  
**Repository:** `rostampourahmad1-ui/vetra-portal-theme`  
**Commit بررسی‌شده:** `01860a1cfa9692b8fe0f60fcf15565bd5462af91`  
**نسخه پوسته:** `3.9.1`

## 1. روش و قواعد تفسیر

این سند با جست‌وجوی مستقیم فایل‌های PHP، templateها، JavaScript و تست‌های موجود تهیه شده است. شماره خطوط به source فعلی در commit بالا اشاره می‌کند؛ هر تغییر بعدی ممکن است line number را جابه‌جا کند.

- **priority** و **accepted args** فقط وقتی در ثبت صراحتاً آمده‌اند مقدار غیرپیش‌فرض دارند؛ در غیر این صورت WordPress مقدار پیش‌فرض `10` و `1` را اعمال می‌کند.
- در جدول actions، «خروجی» به معنی اثر hook یا مقدار بازگشتی callback است؛ actionها معمولاً مقدار بازگشتی ندارند.
- nonce برای hookهای read-only/enqueue/render لازم نیست. هر عملیات state-changing باید capability و nonce خود را داشته باشد.
- `escape` در این سند بر اساس source قابل مشاهده ثبت شده است، نه بر اساس تست end-to-end.
- این سند قرارداد پیشنهادی برای APIای که مستند رسمی ندارد ارائه می‌کند، اما هر پیشنهاد با برچسب **پیشنهاد** مشخص شده است.

## 2. خلاصهٔ وابستگی و مالکیت

| دامنه | وضعیت فعلی | تصمیم baseline |
|---|---|---|
| پوسته Vetra | منبع فعلی تمام APIهای این سند | APIهای presentation، enqueue، template و design system در پوسته بمانند |
| Vetra Plus | در repository کد اجرایی ندارد | APIهای stateful پروژه، role، restriction، PWA، sample pages و plugin manager نامزد انتقال هستند؛ فعلاً منتقل نمی‌شوند |
| Elementor | optional؛ با `ELEMENTOR_VERSION` تشخیص داده می‌شود | fallback پوسته حفظ شود؛ integration سنگین در افزونه، در صورت نیاز |
| Gravity Forms | optional؛ با `GFForms`/`gravity_form` تشخیص داده می‌شود | adapter کوچک بماند؛ dependency اجباری نشود |
| Contact Form 7 | فقط shortcode probe در `inc/integrations.php` | وابستگی رسمی نیست؛ پیش از هر تصمیم migration باید قرارداد محصول تعیین شود |
| WooCommerce | در production code وجود ندارد | اضافه نشود |
| WPBakery / Codevz | در production code وجود ندارد | اضافه نشود |

## 3. جدول Actions مورد بررسی

### 3.1 مدیریت و داشبورد

| Hook | محل و خط | Callback | priority / args | وابستگی و ورودی/خروجی | امنیت |
|---|---|---|---|---|---|
| `admin_init` | `functions.php:40` | `vetra_register_private_portal_setting` | 10 / 1 | ثبت setting گروه `vetra_portal_options` و option `vetra_private_portal`؛ خروجی ندارد | callback sanitizer/authorization در `register_setting` و WordPress Settings API؛ runtime باید بررسی شود |
| `admin_init` | `functions.php:58` | `vetra_restrict_dashboard` | **1 / 1** | درخواست admin را برای کاربران غیرمجاز محدود می‌کند؛ `$_SERVER['PHP_SELF']`/کاربر فعلی را می‌خواند | capability و استثناهای login/admin باید از source/runtime بررسی شوند؛ خروجی redirect یا `wp_die` |
| `admin_init` | `inc/plugin-manager.php:144` | `vetra_plugin_manager_handle_actions` | 10 / 1 | `$_POST['vetra_plugin_action']`؛ نصب/فعال‌سازی plugin و redirect | `is_admin`, `install_plugins`, `check_admin_referer`, `sanitize_key`; عملیات خارجی و مجوز filesystem حساس |
| `admin_init` | `inc/projects.php:324` | `vetra_project_admin_actions` | 10 / 1 | داده‌های فرم پروژه، `project_id`, upload و action؛ insert/update/delete | capabilityهای `vetra_manage_projects`/`vetra_delete_projects`، nonce `vetra_project_admin`، sanitize داده، `$wpdb->delete`/upsert؛ خروجی redirect/`wp_die` |
| `admin_init` | `inc/projects.php:576` | `vetra_roles_admin_actions` | 10 / 1 | افزودن/به‌روزرسانی role و capability از `$_POST` | `manage_options`، nonce `vetra_role_action`، `sanitize_key` و allow-list capability؛ redirect/بدون خروجی |
| `admin_menu` | `inc/admin/class-dashboard.php:5` | `Dashboard::menu` | **12 / 1** | ثبت صفحه `themes.php?page=vetra-portal-settings`؛ callback render | capability `manage_options` در `add_theme_page` و دوباره در render |
| `admin_menu` | `inc/site-features.php:65` | `vetra_theme_admin_page` | 10 / 1 | ثبت ابزار `vetra-site-tools`؛ خروجی از callback page | capability `manage_options` در menu؛ فرم داخلی nonce دارد |
| `admin_menu` | `inc/plugin-manager.php:77` | `vetra_plugin_manager_admin_menu` | 10 / 1 | ثبت صفحه plugin manager با slug `vetra-portal-plugins` | capability `manage_options`; عملیات فرم جداگانه محافظت شده است |
| `admin_menu` | `inc/projects.php:293` | `vetra_project_admin_menu` | 10 / 1 | menu/submenu پروژه با capabilityهای `vetra_manage_projects` | capability در registration و callback؛ خروجی HTML escaped در page |
| `admin_menu` | `inc/projects.php:524` | `vetra_roles_admin_menu` | 10 / 1 | صفحات role با `manage_options` | capability در menu/page و nonce در فرم‌ها |
| `admin_notices` | `inc/core/class-updater.php:11` | `Vetra_Portal_GitHub_Updater::notice` | 10 / 1 | update transient را می‌خواند و notice HTML چاپ می‌کند | `update_themes`، `esc_html`/`esc_url`; read-only |
| `admin_bar_menu` | `inc/customizer.php:655` | closure: `$bar->remove_node('customize')` | **999 / 1** | `WP_Admin_Bar $bar`؛ node سفارشی‌سازی را حذف می‌کند | state تغییر نمی‌دهد؛ capability اضافی در closure نیست، اما hook UI است |
| `admin_enqueue_scripts` | `inc/admin/class-dashboard.php:5` | `Dashboard::assets` | 10 / 1 | `$hook`; فقط `appearance_page_vetra-portal-settings` را هدف می‌گیرد و CSS/JS enqueue می‌کند | read-only؛ load سراسری انجام نمی‌شود |
| `admin_enqueue_scripts` | `inc/projects.php:583` | `vetra_project_admin_assets` | 10 / 1 | `$hook`; فقط صفحات project/role مرتبط را هدف می‌گیرد | read-only؛ scoped enqueue |
| `admin_enqueue_scripts` | `inc/vetra-icons.php:273` | `vetra_icons_enqueue_assets` | 10 / 1 | بدون ورودی معنادار؛ stylesheet آیکن admin را enqueue می‌کند | read-only؛ ممکن است در همه admin screens اجرا شود |
| `admin_post_vetra_admin_action` | `inc/admin/class-dashboard.php:5` | `Dashboard::handle` | 10 / 1 | `vetra_operation`, `vetra_admin_nonce`, upload JSON؛ clear CSS/export/import | `manage_options`، nonce متناسب با operation، sanitize، file type/ext MIME check، normalize تنظیمات؛ redirect/download JSON |
| `admin_post_vetra_delete_project` | `inc/projects.php:418` | `vetra_project_admin_post_handlers` → `vetra_project_admin_actions` | 10 / 1 | `project_id` و فرم حذف | nonce `vetra_project_admin` و capability delete/manage در handler داخلی |
| `admin_post_vetra_project_moderate` | `inc/projects.php:431` | `vetra_project_moderate_action` | 10 / 1 | `project_id`, `project_status`؛ تغییر pending/approved/rejected | `vetra_approve_projects`، `check_admin_referer('vetra_project_moderate_'.$id)`، allow-list status |
| `admin_post_vetra_role_delete` | `inc/projects.php:547` | `vetra_role_delete_action` | 10 / 1 | `role_slug`؛ حذف role سفارشی | `manage_options`، prefix `vetra_`، protected-role allow/deny-list، nonce اختصاصی |
| `admin_post_vetra_save_project` | `inc/projects.php:417` | `vetra_project_admin_post_handlers` → `vetra_project_admin_actions` | 10 / 1 | داده‌های project و featured image | nonce `vetra_project_admin`، capability، `vetra_project_sanitize_data`, upload validation، output redirect |

### 3.2 bootstrap، presentation و lifecycle

| Hook | محل و خط | Callback | priority / args | وابستگی و ورودی/خروجی | امنیت |
|---|---|---|---|---|---|
| `after_setup_theme` | `functions.php:96` | `vetra_portal_setup` | 10 / 1 | theme support، menus، image sizes و textdomain ثبت می‌کند | setup read-only؛ textdomain/path از ثابت theme |
| `after_setup_theme` | `inc/core/class-theme.php:9` | `Theme::setup` | **5 / 1** | theme supports استاندارد | read-only |
| `after_setup_theme` | `inc/projects.php:68` | `vetra_project_install_schema` | **20 / 1** | ایجاد/upgrade جدول `{$wpdb->prefix}vetra_projects` و schema option | migration-like behavior؛ version option `vetra_projects_schema_version`; در این فاز تغییر نمی‌کند |
| `after_setup_theme` | `inc/projects.php:116` | `vetra_project_roles` | 10 / 1 | ساخت/همگام‌سازی role و capability | role/capability stateful؛ نیازمند migration contract در انتقال |
| `after_switch_theme` | `inc/projects.php:75` | `vetra_project_flush_rewrites` | 10 / 1 | schema install/flush behavior پس از فعال‌سازی theme | عملیات DB/state؛ باید در plugin migration جایگزین versioned داشته باشد |
| `add_meta_boxes` | `inc/layouts.php:40` | `vetra_layout_meta_boxes` | 10 / 1 | meta box برای `vetra_layout` و page | nonce در render و save handler |
| `add_meta_boxes_page` | `inc/site-features.php:14` | `vetra_page_access_meta_box` | 10 / 1 | meta box نقش‌های مجاز برگه | nonce `vetra_page_roles` در فرم |
| `customize_register` | `inc/layouts.php:116` | `vetra_layout_customizer_register` | **20 / 1** | ثبت کنترل‌های layout در Customizer؛ object `WP_Customize_Manager` | registration/read-only؛ setting callbacks باید sanitize شوند |
| `customize_register` | `inc/customizer.php:450` | `vetra_customizer_register` | 10 / 1؛ در `inc/customizer.php:645` remove می‌شود | schema و controlهای Customizer را ثبت می‌کند؛ در bootstrap فعلی disable path دارد | setting sanitizerهای `vetra_sanitize_*`؛ دسترسی مستقیم Customizer با redirect محدود شده است |
| `customize_register` | `inc/customizer.php:646` | `vetra_disable_customizer` | **999 / 1** | sectionهای core و `vetra_*` را از `$wp_customize` حذف می‌کند | read-only UI control؛ capability در redirect/bypass مسیر جداگانه بررسی می‌شود |
| `customize_save_after` | `inc/core/settings-schema.php:39` | `vetra_clear_dynamic_css_cache` | 10 / 1 | پس از save، option cache version و transient CSS را invalidate می‌کند | state change داخلی؛ از Customizer save lifecycle می‌آید و nonce را WP مدیریت می‌کند |
| `customize_controls_enqueue_scripts` | `inc/customizer.php:568` | `vetra_customizer_controls_assets` | 10 / 1 | CSS/JS کنترل‌های Customizer را enqueue می‌کند | read-only؛ dependencyهای `jquery` و `customize-controls` |
| `init` | `inc/core/class-theme.php:10` | `Theme::register_components` | **20 / 1** | action داخلی `vetra_theme_components_registered` را fire می‌کند | read-only extension point |
| `init` | `inc/layouts.php:30` | `vetra_register_layout_post_type` | 10 / 1 | ثبت CPT `vetra_layout`، public=false، REST/UI فعال | registration read-only؛ post capabilityهای WP |
| `init` | `inc/customizer.php:577` | `vetra_pwa_add_rewrite` | 10 / 1 | endpointهای `vetra-manifest` و `vetra-sw` | rewrite registration؛ runtime flush لازم است |
| `init` | `inc/site-features.php:123` | `vetra_register_company_patterns` | 10 / 1 | block pattern category/pattern ثبت می‌کند | static registration |
| `load-customize.php` | `inc/customizer.php:656` | closure redirect به `themes.php?page=vetra-portal-settings` | 10 / 1 | query `vetra_force_customizer` و capability را می‌خواند؛ redirect/exit | `current_user_can('manage_options')`; ورودی query فقط برای bypass admin |
| `save_post_page` | `inc/layouts.php:89` | `vetra_save_layout_meta` | **20 / 1** | meta انتخاب header/footer صفحه | nonce `vetra_layout_meta`; autosave/revision/capability check در handler |
| `save_post_page` | `inc/site-features.php:30` | `vetra_save_page_roles` | 10 / 1 | `vetra_allowed_roles[]` و `_vetra_allowed_roles` | nonce، autosave، `edit_page`، `sanitize_key` |
| `save_post_vetra_layout` | `inc/layouts.php:88` | `vetra_save_layout_meta` | 10 / 1 | `_vetra_layout_type` و layout meta | nonce، autosave/capability/allow-list در handler |
| `template_redirect` | `functions.php:49` | `vetra_private_portal_gate` | **1 / 1** | option `vetra_private_portal`, login/feed/REST/cron و `pagenow`; redirect مهمان | opt-in؛ باید login/REST/cron استثناها حفظ شوند |
| `template_redirect` | `inc/site-features.php:41` | `vetra_enforce_page_access` | **0 / 1** | roleهای `_vetra_allowed_roles`؛ redirect/404 برای دسترسی | role check و `wp_safe_redirect`; state تغییر نمی‌دهد |
| `template_redirect` | `inc/site-features.php:102` | `vetra_maintenance_response` | **0 / 1** | `vetra_site_options.site_mode`; status 503/HTML | admin و login bypass؛ خروجی باید escaped باشد |
| `template_redirect` | `inc/projects.php:464` | `vetra_project_frontend_process` | 10 / 1 | POST فرم project و upload | login، capabilityهای project، nonce `vetra_project_frontend`، sanitize/upload validation |
| `template_redirect` | `inc/customizer.php:630` | `vetra_pwa_manifest_template` | 10 / 1 | query vars `vetra-sw`/`vetra-manifest`؛ خروجی JS یا JSON و exit | PWA option gate؛ headerهای content type؛ داده‌ها از optionهای sanitize شده |
| `vetra_after_header` | `functions.php:236` | `vetra_render_user_bar` | 10 / 1 | action داخلی پس از header؛ HTML نوار user | escape خروجی و login state؛ read-only |
| `wp_enqueue_scripts` | `functions.php:165` | `vetra_portal_enqueue_assets` | 10 / 1 | frontend enqueue و data localization | scoped بر اساس template/options؛ URLs escaped by WP enqueue |
| `wp_enqueue_scripts` | `inc/vetra-design-system.php:87` | `vetra_ds_enqueue_assets` | **20 / 1** | Design System CSS/JS و `vetraDS` localization؛ action داخلی `vetra_ds_assets_enqueued` | read-only؛ localization از option mode normalize شده |
| `wp_enqueue_scripts` | `inc/vetra-icons.php:272` | `vetra_icons_enqueue_assets` | **20 / 1** | icon stylesheet | read-only |
| `wp_enqueue_scripts` | `inc/integrations.php:14` | `vetra_integration_enqueue_forms` | **30 / 1** | page content و وجود `gravity_form` یا `contact-form-7`؛ forms.css enqueue | read-only probe؛ plugin dependency optional |
| `wp_footer` | `functions.php:274` | `vetra_render_bottom_bar` | **5 / 1** | bottom mobile bar HTML و icon API | output escaping/icon renderer |
| `wp_footer` | `inc/site-features.php:111` | `vetra_cookie_banner` | **90 / 1** | `vetra_site_options`؛ banner HTML | text via `esc_html`, URL via `esc_url`; read-only |
| `wp_footer` | `inc/site-features.php:116` | `vetra_pwa_install_banner` | **95 / 1** | PWA options؛ banner HTML | `esc_html`; read-only |

## 4. جدول Filters مورد بررسی

| Filter | محل و خط | Callback / مصرف | priority / args | ورودی و خروجی | امنیت/قرارداد |
|---|---|---|---|---|---|
| `body_class` | `functions.php:186` | `vetra_portal_body_classes` | 10 / 1 | `$classes` آرایه؛ آرایه با کلاس‌های theme برمی‌گردد | `sanitize_html_class`/کلاس‌های ثابت؛ presentation |
| `excerpt_length` | `functions.php:191` | `vetra_portal_excerpt_length` | 10 / 1 | مقدار طول excerpt؛ عدد `18` | read-only؛ **پیشنهاد:** callbackهای مصرف‌کننده باید عدد مثبت برگردانند |
| `rest_prepare_page` | `inc/site-features.php:47` | `vetra_protect_rest_pages` | **10 / 3** | `$response,$post,$request`؛ `WP_Error` 401/403 یا response | role check؛ دادهٔ restriction از post meta؛ no nonce چون REST read protection است |
| `pre_set_site_transient_update_themes` | `inc/core/class-updater.php:10` | `Vetra_Portal_GitHub_Updater::inject_update` | 10 / 1 | transient object؛ update response را برمی‌گرداند | remote GitHub response sanitize/URL validation؛ updater capability در notice |
| `template_include` | `functions.php:118` | `vetra_load_page_template` | 10 / 1 | template path؛ path سفارشی یا original | slug allow-list؛ `is_readable` |
| `template_include` | `inc/vetra-design-system.php:267` | `vetra_ds_load_preview_template` | 10 / 1 | template path؛ design-system preview یا original | template slug ثابت و `is_readable` |
| `theme_page_templates` | `functions.php:109` | `vetra_register_page_templates` | 10 / 1 | `$templates`؛ اضافه‌کردن full-width/landing/distraction-free | labels ثابت؛ presentation |
| `theme_page_templates` | `inc/vetra-design-system.php:249` | `vetra_ds_register_preview_template` | 10 / 1 | `$templates`؛ اضافه‌کردن design-system preview | label ثابت |
| `upgrader_source_selection` | `inc/core/class-updater.php:12` | `Vetra_Portal_GitHub_Updater::fix_source_directory` | **10 / 4** | `$source,$remote_source,$upgrader,$hook_extra`؛ source path صحیح یا original | فقط theme slug ثابت `vetra-portal-theme`; filesystem move و runtime updater حساس |
| `wp_nav_menu_objects` | `inc/site-features.php:56` | `vetra_filter_menu_by_role` | 10 / 1 | items آرایه؛ حذف pageهای غیرمجاز | role check؛ read-only |
| `vetra_featured_project_detail_url` | `inc/projects.php:682` | **مصرف با `apply_filters`، نه `add_filter`** | call-site؛ 3 مقدار شامل hook، URL پایه، project | URL پیش‌فرض `?vetra_project_id=ID`؛ URL نهایی را برمی‌گرداند | callback خارجی باید `esc_url`/allow-list داشته باشد؛ **پیشنهاد:** مستندسازی رسمی و URL validation |
| `vetra_featured_projects_count` | — | پیدا نشد | — | در source فعلی `apply_filters`/`add_filter` برای این نام وجود ندارد | **تأییدنشده:** فقط در گزارش قبلی آمده؛ در این فاز ایجاد نشده است |

### Filters واقعی اضافی که در گزارش اولیه فهرست نشده بودند

| Hook | محل | نوع | قرارداد فعلی |
|---|---|---|---|
| `vetra_project_read` | `inc/projects.php:166` | `apply_filters` | پس از read پروژه؛ args: `$project`, `$id`؛ خروجی row یا null؛ permission filtering پیش از filter انجام می‌شود |
| `vetra_ds_assets_enqueued` | `inc/vetra-design-system.php:72` | `do_action` | پس از enqueue/localize؛ args: `VETRA_DS_HANDLE`؛ برای integration بدون تغییر asset chain |
| `vetra_theme_components_registered` | `inc/core/class-theme.php:18` | `do_action` | پس از register components؛ بدون arg؛ lifecycle extension point |

## 5. جدول Shortcodeها

| Shortcode | محل ثبت | Callback | accepted args / ورودی | خروجی و دسترسی | nonce/capability/sanitize/escape |
|---|---|---|---|---|---|
| `[vetra_project_form]` | `inc/projects.php:486` | `vetra_project_form_shortcode` | `id`؛ `shortcode_atts`, سپس `absint` | فرم ثبت/ویرایش پروژه یا پیام عدم دسترسی؛ فرم HTML و nonce | login + `vetra_submit_projects`; nonce `vetra_project_frontend`; field sanitize در submit، output `esc_attr`/`esc_html` |
| `[vetra_project_list]` | `inc/projects.php:506` | `vetra_project_list_shortcode` | `number` پیش‌فرض 12؛ `absint` | کارت‌های پروژه از query؛ HTML | query permission-aware؛ IDs absint، متن esc_html، URL esc_url، icon renderer |
| `[vetra_project_detail]` | `inc/projects.php:518` | `vetra_project_detail_shortcode` | `id` یا `vetra_project_id` query؛ `absint` | article جزئیات فقط برای approved یا editor مجاز | `vetra_project_can_edit`/status gate؛ متن esc_html، description `wp_kses_post` + `wpautop`، تصویر WP renderer |
| `[vetra_restrict]` | `inc/site-features.php:62` | `vetra_restricted_shortcode` | `roles` comma-separated؛ `sanitize_key` و allow role check؛ enclosed `$content` | nested content با `do_shortcode` یا empty string | read-only access gate؛ **پیشنهاد:** اگر در آینده state-changing nested shortcode اضافه شد، nonce/capability باید متعلق به همان shortcode باشد |
| `[vetra_icon]` | `inc/vetra-icons.php:254` | `vetra_icon_shortcode` | `name,class,size,label,title,base_color,accent_color,decorative,rtl_flip` | SVG inline؛ fallback icon برای name ناشناخته | name whitelist، size/color sanitizer، `esc_attr`/`esc_html`، SVG tag allow-list؛ nonce لازم نیست چون state تغییر نمی‌کند |

## 6. APIهای عمومی PHP، کلاس‌ها و namespaceها

### 6.1 Namespace و کلاس‌ها

| Symbol | فایل/خط | visibility و نقش | تصمیم مالکیت |
|---|---|---|---|
| `Vetra\\Theme\\Theme` | `inc/core/class-theme.php:2-20` | `final class`؛ `boot`, `setup`, `register_components` | پوسته؛ lifecycle/theme setup |
| `Vetra\\Theme\\Admin\\Dashboard` | `inc/admin/class-dashboard.php:2-29` | `final class`؛ menu/assets/render/handle | پوسته برای UI؛ handlerهای stateful ممکن است در Vetra Plus بازطراحی شوند |
| `Vetra_Portal_GitHub_Updater` | `inc/core/class-updater.php:5-80` | global final class؛ updater GitHub | پوسته؛ به theme package وابسته است |
| `Vetra_Customize_Control_Checkbox_Multiple` | `inc/customizer.php:456-480` | global WP Customizer control subclass | پوسته؛ تا تصمیم رسمی جایگزین Customizer حذف نشود |

### 6.2 توابع عمومی بر اساس فایل

این فهرست تمام declarationهای global با prefix `vetra_` و توابع component عمومی را از source فعلی ثبت می‌کند. توابع private داخل کلاس‌ها جداگانه public API محسوب نمی‌شوند مگر اینکه در جدول کلاس‌ها آمده باشند.

| فایل | توابع عمومی فعلی |
|---|---|
| `functions.php` | `vetra_register_private_portal_setting`, `vetra_private_portal_gate`, `vetra_restrict_dashboard`, `vetra_portal_setup`, `vetra_primary_menu_fallback`, `vetra_register_page_templates`, `vetra_load_page_template`, `vetra_portal_enqueue_assets`, `vetra_portal_body_classes`, `vetra_portal_excerpt_length`, `vetra_render_user_bar`, `vetra_render_bottom_bar` |
| `inc/helpers.php` | `vetra_brand_logo_url`, `vetra_image_url`, `vetra_custom_icon_url`, `vetra_image_or_class` |
| `inc/integrations.php` | `vetra_integration_is_active`, `vetra_integration_enqueue_forms` |
| `inc/core/settings-schema.php` | `vetra_settings_schema`, `vetra_settings_schema_version`, `vetra_settings_defaults`, `vetra_settings_normalize`, `vetra_operational_settings`, `vetra_clear_dynamic_css_cache`, `vetra_theme_css_fingerprint` |
| `inc/customizer.php` | `vetra_customizer_defaults`, `vetra_option`, `vetra_sanitize_checkbox`, `vetra_sanitize_number`, `vetra_sanitize_color`, `vetra_sanitize_mode`, `vetra_sanitize_bg_apply_mode`, `vetra_sanitize_bg_type`, `vetra_sanitize_bg_size`, `vetra_sanitize_bg_repeat`, `vetra_sanitize_bg_attachment`, `vetra_sanitize_pattern`, `vetra_sanitize_font_stack`, `vetra_sanitize_pwa_display`, `vetra_sanitize_pages_array`, `vetra_sanitize_custom_css`, `vetra_add_text`, `vetra_add_color`, `vetra_add_media`, `vetra_add_toggle`, `vetra_add_select`, `vetra_add_pages_multi`, `vetra_customizer_register`, `vetra_customizer_css`, `vetra_portal_customizer_css`, `vetra_customizer_controls_assets`, `vetra_pwa_add_rewrite`, `vetra_pwa_manifest_template`, `vetra_pwa_manifest_link`, `vetra_disable_customizer` |
| `inc/layouts.php` | `vetra_register_layout_post_type`, `vetra_layout_types`, `vetra_layout_meta_boxes`, `vetra_layout_type_meta_box`, `vetra_layout_choices`, `vetra_page_layouts_meta_box`, `vetra_save_layout_meta`, `vetra_layout_option_key`, `vetra_selected_layout_id`, `vetra_render_layout`, `vetra_layout_customizer_register` |
| `inc/projects.php` | `vetra_projects_table`, `vetra_project_schema_version`, `vetra_project_install_schema`, `vetra_project_flush_rewrites`, `vetra_project_roles`, `vetra_project_fields`, `vetra_project_field_ids`, `vetra_project_get_field`, `vetra_project_extra_fields`, `vetra_project_get`, `vetra_project_query`, `vetra_project_sanitize_data`, `vetra_project_upload_image`, `vetra_project_upsert`, `vetra_project_formats`, `vetra_project_can_edit`, `vetra_project_set_status`, `vetra_project_admin_menu`, `vetra_project_admin_actions`, `vetra_project_admin_page`, `vetra_project_status_label`, `vetra_project_render_field`, `vetra_project_render_fields`, `vetra_project_admin_form_page`, `vetra_project_admin_post_handlers`, `vetra_project_moderate_action`, `vetra_project_frontend_process`, `vetra_project_form_shortcode`, `vetra_project_list_shortcode`, `vetra_project_detail_shortcode`, `vetra_roles_admin_menu`, `vetra_role_delete_page`, `vetra_role_delete_action`, `vetra_roles_admin_page`, `vetra_roles_admin_actions`, `vetra_project_admin_assets`, `vetra_featured_projects_get`, `vetra_project_status_meta`, `vetra_featured_project_card`, `vetra_featured_projects_fallback` |
| `inc/site-features.php` | `vetra_user_has_any_role`, `vetra_page_access_meta_box`, `vetra_page_roles_meta_box`, `vetra_save_page_roles`, `vetra_enforce_page_access`, `vetra_protect_rest_pages`, `vetra_filter_menu_by_role`, `vetra_restricted_shortcode`, `vetra_theme_admin_page`, `vetra_theme_options`, `vetra_theme_admin_page_render`, `vetra_handle_sample_pages`, `vetra_maintenance_response`, `vetra_cookie_banner`, `vetra_pwa_install_banner`, `vetra_register_company_patterns` |
| `inc/plugin-manager.php` | `vetra_plugin_manifest`, `vetra_plugin_is_active`, `vetra_plugin_is_installed`, `vetra_install_plugin_package`, `vetra_plugin_manager_admin_menu`, `vetra_plugin_manager_handle_actions`, `vetra_plugin_manager_page` |
| `inc/vetra-design-system.php` | `vetra_ds_enqueue_assets`, `vetra_ds_default_theme_mode`, `vetra_ds_class`, `vetra_ds_button`, `vetra_ds_badge`, `vetra_ds_alert`, `vetra_ds_status_values`, `vetra_ds_register_preview_template`, `vetra_ds_load_preview_template` |
| `inc/vetra-icon-library.php` | `vetra_icon_library`, `vetra_icon_resolve_name`, `vetra_icon_exists`, `vetra_icon_get`, `vetra_icon_names`, `vetra_icon_aliases`, `vetra_icon_validate_markup`, `vetra_icon_sanitize_color`, `vetra_icon_render_element`, `vetra_icon_sprite_markup`, `vetra_icon_inner` |
| `inc/vetra-icons.php` | `vetra_icon`, `vetra_icon_sanitize_size`, `vetra_inline_icon`, `vetra_icon_data_uri`, `vetra_icon_shortcode`, `vetra_icons_enqueue_assets` |
| `template-parts/components.php` | `vetra_component_breadcrumbs`, `vetra_component_post_meta`, `vetra_component_share`, `vetra_component_related` |

### 6.3 Constants

| Constant | محل تعریف | مقدار/نقش |
|---|---|---|
| `VETRA_PORTAL_VERSION` | `functions.php:12` | `3.9.1` |
| `VETRA_PORTAL_DIR` | `functions.php:13` | template directory |
| `VETRA_PORTAL_URI` | `functions.php:14` | template URI |
| `VETRA_ICON_FALLBACK` | `inc/vetra-icons.php:20` | `info` |
| `VETRA_ICONS_VERSION` | `inc/vetra-icon-library.php:24` | `1.0.0` |
| `VETRA_ICONS_DIR`, `VETRA_ICONS_URI` | `inc/vetra-icon-library.php:25-26` | asset paths |
| `VETRA_DS_VERSION` | `inc/vetra-design-system.php:29` | `1.0.0` |
| `VETRA_DS_DIR`, `VETRA_DS_URI`, `VETRA_DS_HANDLE` | `inc/vetra-design-system.php:30-32` | design-system paths/handle |
| `Vetra_Portal_GitHub_Updater::THEME_SLUG` | `inc/core/class-updater.php:6` | `vetra-portal-theme` |
| `Vetra_Portal_GitHub_Updater::REPOSITORY` | `inc/core/class-updater.php:7` | `rostampourahmad1-ui/vetra-portal-theme` |

## 7. APIهای نامزد انتقال به Vetra Plus

این‌ها فقط تصمیم معماری baseline هستند؛ در این فاز انتقال انجام نشده است.

| API/دامنه | دلیل انتقال | قرارداد حفظ‌شونده |
|---|---|---|
| `inc/projects.php` و تمام `vetra_project_*` | داده، جدول، CRUD، moderation و role stateful هستند و نباید با تغییر theme حذف شوند | table name، schema version، field IDs، statuses، capabilities و shortcodeهای فعلی باید backward-compatible بمانند |
| `vetra_project_editor`/`vetra_project_manager` و capabilityهای project | role/capability متعلق به محصول/داده است نه presentation | prefix، capability names و permission gates حفظ شوند |
| `vetra_restrict` و page-role restrictions | access policy باید با تغییر theme باقی بماند | `_vetra_allowed_roles`، REST behavior و menu filtering حفظ شود |
| `inc/layouts.php` | CPT و meta assignment stateful است | `vetra_layout` و `_vetra_*_layout` rename نشوند |
| `inc/site-features.php` | maintenance، cookie، sample pages و company patterns stateful/operational هستند | option/meta keys و sample marker `_vetra_sample_key` حفظ شوند |
| PWA در `inc/customizer.php`/`site-features.php` | endpoint و service worker behavior محصولی است | `vetra-manifest`، `vetra-sw` و optionهای PWA versioned migrate شوند |
| `inc/plugin-manager.php` | نصب/فعال‌سازی افزونه یک مسئولیت مدیریتی مستقل است | manifest و capability/nonce contract بازبینی امنیتی شود |
| `vetra_project_*` shortcodes | data/product API هستند | ابتدا در پوسته compatibility shim بمانند؛ سپس delegation اختیاری به Vetra Plus |
| Design System و icon API | shared presentation است و می‌تواند مستقل version شود | `vetra_icon`, `vetra_ds_*`, constants و assets با version contract حفظ شوند |

## 8. موارد تأییدنشده یا پیشنهادشده

| مورد | وضعیت |
|---|---|
| `vetra_featured_projects_count` | **تأییدنشده:** در report آمده، اما در source فعلی هیچ `add_filter` یا `apply_filters` با این نام وجود ندارد؛ در این فاز ساخته نشد |
| مقدار واقعی options/theme-modها | **تأییدنشده:** دیتابیس/backup معتبر در دسترس نیست |
| قرارداد runtime Elementor | **تأییدنشده:** فقط `ELEMENTOR_VERSION` و fallback source-level دیده شد |
| رفتار runtime Gravity Forms/Contact Form 7 | **تأییدنشده:** plugin فعال و runtime موجود نیست |
| signature رسمی `vetra_featured_project_detail_url` | **پیشنهاد:** callback باید `(string $url, object $project)` را بپذیرد و URL نهایی safe برگرداند |
| versioning برای Vetra Plus | **پیشنهاد:** افزونه باید migration registry مستقل با version option داشته باشد و ابتدا legacy keys/tableها را read کند |
| namespace توابع global فعلی | **وضعیت فعلی:** بیشتر توابع global ولی prefixدار هستند؛ rename در این فاز ممنوع است چون backward compatibility را می‌شکند |

## 9. بررسی امنیتی APIها

### نقاط مثبت source فعلی

- عملیات project و role با capabilityهای اختصاصی و nonce محافظت می‌شوند.
- page-role meta box با nonce، autosave guard، capability و sanitize key ذخیره می‌شود.
- icon API name whitelist، geometry allow-list، color/size sanitizer و escaping دارد.
- dashboard import از Settings API/nonce، MIME/ext check و normalization استفاده می‌کند.
- outputهای template عمدتاً با `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` یا renderer امن icon محافظت می‌شوند.
- هیچ coupling production به WooCommerce، WPBakery یا Codevz مشاهده نشد.

### ریسک‌های نیازمند بررسی قبل از production/migration

1. **خطای line-level در import/export:** مقادیر theme-mod و option باید قبل از import بر اساس schema و capability کامل validate شوند؛ runtime test لازم است.
2. **Custom Admin Handlers:** چند handler در `admin_init` با `$_POST` کار می‌کنند و باید با WordPress Coding Standards و test integration واقعی بازبینی شوند.
3. **Updater خارجی:** GitHub response، ZIP asset و filesystem move به شبکه و permission واقعی وابسته‌اند.
4. **PWA endpoint:** خروج مستقیم با `readfile`/`exit` به rewrite و headers صحیح runtime وابسته است.
5. **Shortcode nested content:** `vetra_restrict` `do_shortcode` را روی content اجرا می‌کند؛ هر shortcode state-changing باید nonce/capability مستقل داشته باشد.
6. **Global namespace:** global functionهای prefixدار public contract هستند؛ انتقال مستقیم به namespace بدون compatibility wrapper breaking change است.
7. **Hook ordering:** priorityهای 0/1/5/12/20/30/90/95/999 رفتاری هستند؛ انتقال باید همین ordering را حفظ یا versioned deprecate کند.
8. **Database ownership:** ایجاد schema روی `after_setup_theme` و role sync با lifecycle theme coupling دارد؛ Vetra Plus باید activation/upgrade/deactivation policy شفاف داشته باشد.
9. **Unverified external integrations:** Elementor، Gravity Forms و Contact Form 7 فقط source probes هستند و integration runtime تست نشده است.
10. **Frontend output:** responsive/RTL/accessibility باید در browser واقعی تست شود؛ static source به‌تنهایی کافی نیست.

## 10. نتیجه فاز

- همهٔ hookها، filterها و shortcodeهای درخواستی با source واقعی بررسی شدند.
- موارد report‌شده اما غایب، به‌طور صریح با برچسب **تأییدنشده** ثبت شدند.
- APIهای واقعی اضافه که در گزارش اولیه نبودند نیز ثبت شدند.
- فهرست کلاس‌ها، namespaceها، constants و توابع عمومی فعلی ثبت شد.
- APIهای نامزد انتقال به Vetra Plus و قراردادهای حفظ‌شونده مشخص شدند.
- هیچ migration، حذف API، rename، انتقال کد یا تغییر behavior انجام نشد.
- تنها فایل ایجادشده در این فاز همین سند است.
