# VETRA Theme Baseline

**نوع این فاز:** فقط baseline و validation خواندنی؛ هیچ migration، حذف فایل، انتقال کد یا تغییر رفتار production انجام نشد.  
**تاریخ بررسی:** 2026-10-02  
**Repository:** `rostampourahmad1-ui/vetra-portal-theme`  
**مسیر بررسی:** `/home/ubuntu/vetra-portal-theme`

## 1. وضعیت Git و نسخه

| مورد | مقدار |
|---|---|
| Branch | `main` |
| Working tree | clean؛ `main...origin/main` |
| HEAD | `01860a1cfa9692b8fe0f60fcf15565bd5462af91` |
| آخرین commit | `ci: package releases on version tags` |
| Theme version | `3.9.1` |
| `style.css` version | `3.9.1` |
| `functions.php` constant | `VETRA_PORTAL_VERSION = 3.9.1` |
| `package.json` version | `3.9.1` |
| Release tag | `v3.9.1` |
| PHP CLI | `8.3.6` |
| WordPress CLI | نصب نیست |
| WordPress core version | از workspace قابل تشخیص نیست |

فایل فنی مرجع ممیزی در خود repository وجود ندارد: `docs/vetra-v39-readonly-audit.md`، `docs/BASELINE.md` و `docs/audit-baseline.md` پیش از این فاز موجود نبودند. گزارش خارج از repository در `/home/ubuntu/vetra-v39-readonly-audit.md` به‌عنوان reference supplied استفاده شد؛ آن گزارش به repository کپی یا جعل نشده است.

## 2. ساختار واقعی repository

### ریشه

```text
404.php
CHANGELOG.md
IMPLEMENTATION-REPORT.md
TEST-REPORT-3.9.1.md
archive.php
comments.php
footer.php
front-page.php
functions.php
header.php
index.php
languages/vetra-portal.pot
package-lock.json
package.json
page-about.php
page-contact.php
page-services.php
page.php
plugins/README.md
readme.md
rtl.css
screenshot.png
search.php
sidebar.php
single.php
style.css
theme.json
```

### مسیرهای داخلی

```text
.github/workflows/ci.yml
assets/css/
assets/icons/
assets/images/
assets/js/
docs/
inc/
inc/admin/
inc/core/
template-parts/
templates/
tests/
tools/
```

### شمارش فعلی

| دسته | تعداد |
|---|---:|
| PHP | 37 |
| CSS در `assets/css/` | 12 |
| JavaScript در `assets/js/` | 5 |
| تست Node | 4 |
| ابزار build | 1 |
| فونت local (`woff`, `woff2`, `ttf`, `otf`) | 0 |
| فایل‌های تصویر | 6 در `assets/images/` + `screenshot.png` |

### فهرست CSS واقعی

```text
assets/css/admin-dashboard.css
assets/css/corporate.css
assets/css/customizer.css
assets/css/forms.css
assets/css/project-admin.css
assets/css/search.css
assets/css/vetra-admin.css
assets/css/vetra-components.css
assets/css/vetra-icons.css
assets/css/vetra-print.css
assets/css/vetra-tokens.css
assets/css/vetra-utilities.css
```

### فهرست JavaScript واقعی

```text
assets/js/corporate.js
assets/js/customizer-controls.js
assets/js/service-worker.js
assets/js/vetra-admin.js
assets/js/vetra-ui.js
```

### فایل‌های PHP حساس baseline

تمام فایل‌های خواسته‌شده در baseline موجود هستند:

```text
inc/projects.php
inc/site-features.php
inc/layouts.php
inc/customizer.php
inc/plugin-manager.php
inc/integrations.php
inc/core/class-theme.php
inc/admin/class-dashboard.php
inc/core/class-updater.php
inc/vetra-design-system.php
inc/vetra-icons.php
inc/vetra-icon-library.php
```

## 3. خلاصهٔ معماری فعلی

- `functions.php` bootstrap اصلی پوسته است و helpers، icon library، settings schema، dashboard، Customizer، plugin manager، projects، site features، layouts، integrations، updater و design system را load می‌کند.
- `inc/projects.php` جدول اختصاصی `{$wpdb->prefix}vetra_projects`، CRUD، moderation، role/capability و shortcodeهای پروژه را فراهم می‌کند.
- `inc/site-features.php` محدودسازی برگه بر اساس role، REST/menu filtering، maintenance، cookie notice، نمونه‌برگه و block pattern را فراهم می‌کند.
- `inc/layouts.php` CPT داخلی `vetra_layout` برای الگوهای header/footer و meta assignment دارد.
- `inc/customizer.php` schema گسترده theme-mod، CSS پویا، PWA endpoint/manifest و controllerهای Customizer دارد؛ دسترسی مستقیم Customizer در مسیرهایی redirect می‌شود اما کد کنترل‌ها هنوز وجود دارد.
- `inc/plugin-manager.php` نصب/فعال‌سازی Elementor، ElementsKit Lite و Gravity Forms را از پنل پوسته مدیریت می‌کند.
- `inc/integrations.php` Elementor و Gravity Forms را به‌صورت optional تشخیص می‌دهد و برای shortcode Contact Form 7 نیز probe محدود دارد.
- `inc/core/class-updater.php` update checker مبتنی بر GitHub Releases و normalize پوشه ZIP است.
- `inc/vetra-design-system.php`، `assets/css/vetra-*` و `assets/js/vetra-ui.js` Design System مشترک را فراهم می‌کنند.
- `inc/vetra-icons.php` و `inc/vetra-icon-library.php` icon API و sprite داخلی VETRA را فراهم می‌کنند.

## 4. قراردادهای baseline

### Actions مهم

```text
admin_init
admin_menu
admin_notices
admin_bar_menu
admin_enqueue_scripts
admin_post_vetra_admin_action
admin_post_vetra_delete_project
admin_post_vetra_project_moderate
admin_post_vetra_role_delete
admin_post_vetra_save_project
after_setup_theme
after_switch_theme
add_meta_boxes
add_meta_boxes_page
customize_register
customize_save_after
customize_controls_enqueue_scripts
init
load-customize.php
save_post_page
save_post_vetra_layout
template_redirect
vetra_after_header
wp_enqueue_scripts
wp_footer
```

### Filters مهم

```text
body_class
excerpt_length
rest_prepare_page
pre_set_site_transient_update_themes
template_include
theme_page_templates
upgrader_source_selection
wp_nav_menu_objects
vetra_featured_projects_count
vetra_featured_project_detail_url
```

### Shortcodeها

```text
[vetra_project_detail]
[vetra_project_form]
[vetra_project_list]
[vetra_restrict]
[vetra_icon]
```

### Persistent identifiers حساس

**Options:**

```text
vetra_css_cache_version
vetra_operational_settings
vetra_portal_options
vetra_private_portal
vetra_projects_schema_version
vetra_site_options
```

**Meta:**

```text
_vetra_allowed_roles
_vetra_footer_layout
_vetra_header_layout
_vetra_layout_type
_vetra_sample_key
```

**Roles/capabilities:**

```text
vetra_project_editor
vetra_project_manager
vetra_submit_projects
vetra_create_projects
vetra_edit_own_projects
vetra_edit_all_projects
vetra_manage_projects
vetra_approve_projects
vetra_delete_projects
```

**Data:**

```text
CPT: vetra_layout
Table: {$wpdb->prefix}vetra_projects
Transient families: vetra_dynamic_css_*, vetra_portal_latest_release
Rewrite endpoints: vetra-manifest, vetra-sw
Menu locations: primary, footer
Sidebars: sidebar-1, footer-1 ... footer-4
```

این نام‌ها قرارداد migration هستند و در این فاز rename یا حذف نشده‌اند.

## 5. وابستگی‌ها و Clean-Room baseline

| مورد | وضعیت source-level |
|---|---|
| Elementor | optional؛ با `ELEMENTOR_VERSION` تشخیص داده می‌شود؛ fallback داخلی وجود دارد |
| ElementsKit Lite | در plugin manifest به‌عنوان widget مکمل Elementor معرفی شده است |
| Gravity Forms | optional؛ با `GFForms`/`gravity_form` تشخیص داده می‌شود؛ fallback متنی دارد |
| Contact Form 7 | فقط shortcode probe محدود در `inc/integrations.php`؛ در manifest نیست |
| WPBakery | هیچ reference production پیدا نشد |
| Codevz | هیچ reference production پیدا نشد |
| WooCommerce | هیچ hook/class/table/dependency production پیدا نشد؛ occurrence در تست فقط برای assert نبودن آن است |
| ionCube/obfuscation | پیدا نشد |
| `base64_decode` / `eval(` | در production پیدا نشد |
| Vetra Plus | plugin مستقل اجرایی وجود ندارد؛ فقط `plugins/README.md` وجود دارد |
| local fonts | وجود ندارد؛ سه انتخاب فونت از CDN اختیاری هستند |

## 6. مقایسه با گزارش ممیزی ایستا

1. **تعداد CSS:** گزارش قبلی عدد ۱۴ را ذکر کرده بود؛ ساختار واقعی فعلی `assets/css/` شامل **۱۲ فایل** است.
2. **تعداد JS:** گزارش قبلی شمارش ۱۰ را با احتساب تست‌ها و ابزارها بیان کرده بود؛ ساختار واقعی `assets/js/` شامل **۵ فایل اجرایی** است. `tools/build-icon-sprite.js` و `tests/*.test.js` جدا هستند.
3. **ساختار اصلی، فایل‌های حساس، integrationهای اصلی و قراردادهای ثبت‌شده** با گزارش ممیزی هم‌خوان هستند.
4. **backup-options:** همچنان در repository یا workspace وجود ندارد؛ هیچ مقدار واقعی option/theme-mod استخراج نشده است.
5. **گزارش ممیزی:** نسخهٔ مرجع خارج از repository است و به‌عنوان فایل جعلی داخل `docs/` کپی نشده است.
6. **Vetra Plus:** مطابق گزارش، هنوز ایجاد نشده است.
7. **WordPress runtime:** نه WordPress core و نه WP-CLI در workspace قابل تشخیص نیست؛ بنابراین version/runtime behavior تأیید نشده است.

## 7. آزمون‌ها و validation اجراشده

تمام build/testهای اثرگذار در copy موقت `/tmp/vetra-baseline-build` یا `/tmp/vetra-baseline-package` اجرا شدند تا repository تغییر نکند.

| بررسی | دستور/روش | نتیجه |
|---|---|---|
| Node contract tests | `npm test` | **54 passed, 0 failed** |
| PHP syntax | `find . -name '*.php' ... php -l` | **37 فایل بدون خطای syntax** |
| JSON | parse `theme.json`, `package.json`, `assets/icons/vetra-icons.json` | **هر ۳ معتبر** |
| JS syntax | `node --check` روی assets، tools و tests | **موفق** |
| Icon build | `node tools/build-icon-sprite.js` در copy موقت + مقایسه sprite | **reproducible**؛ ۷۰ symbol |
| Git whitespace | `git diff --check HEAD^ HEAD` | **بدون خطا** |
| Forbidden dependency scan | WPBakery/Codevz/WooCommerce/ionCube/base64/eval | **در production marker پیدا نشد** |
| Release ZIP build | بسته‌بندی با root دقیق `vetra-portal-theme/` | **موفق**؛ `/tmp/vetra-portal-theme-baseline.zip`، 2,092,330 bytes |
| PHP_CodeSniffer | ابزار در محیط نصب نیست | اجرا نشد |
| PHPStan | ابزار در محیط نصب نیست | اجرا نشد |
| ESLint | ابزار در محیط نصب نیست | اجرا نشد |
| ShellCheck | ابزار در محیط نصب نیست | اجرا نشد |

### CI موجود

آخرین GitHub Actions run روی tag `v3.9.1`:

- Run ID: `36933210840`
- Commit: `01860a1cfa9692b8fe0f60fcf15565bd5462af91`
- Matrix: PHP 8.1، 8.2 و 8.3
- تست‌ها، lint PHP، JSON validation، structure check و package job: **success**
- لینک: https://github.com/rostampourahmad1-ui/vetra-portal-theme/actions/runs/36933210840

CI فعلی PHPهای 8.1 تا 8.3 را matrix می‌کند؛ الزام جدید پروژه PHP 8.2+ است و alignment آن در این baseline فقط ثبت شده و تغییر داده نشده است.

## 8. موارد نیازمند runtime یا database

موارد زیر با static inspection تأیید قطعی نمی‌شوند:

- نسخه و تنظیمات واقعی WordPress production
- مقدار optionها و theme-modها
- داده و schema واقعی جدول `vetra_projects`
- roleها و capabilityهای واقعی کاربران
- attachment IDهای logo/hero/background
- اجرای Elementor editor/frontend و fallback آن
- رفتار RTL/responsive در browser واقعی
- عملکرد upload، import JSON، role management و nonce در runtime
- اجرای واقعی PWA، service worker و rewrite endpointها
- migration ایمن از پوسته به Vetra Plus
- license و compatibility واقعی فونت‌های CDN یا افزونه‌های commercial

## 9. فایل‌های حساس برای migration

قبل از هر انتقال یا بازنویسی، این فایل‌ها باید contract و migration plan داشته باشند:

```text
inc/projects.php
inc/site-features.php
inc/layouts.php
inc/customizer.php
inc/plugin-manager.php
inc/integrations.php
inc/core/settings-schema.php
inc/admin/class-dashboard.php
functions.php
header.php
footer.php
inc/helpers.php
```

اصول migration:

- table `vetra_projects` حفظ شود.
- option/meta/capability/role keyهای `vetra_` rename نشوند مگر با migration versioned و rollback.
- نبود Vetra Plus نباید پوسته را fatal کند.
- هیچ feature فعلی حذف نشود مگر baseline و migration قابل بازگشت داشته باشد.
- WooCommerce، WPBakery و Codevz وارد architecture نشوند.
- Elementor optional بماند و fallback پوسته حفظ شود.

## 10. نتیجه و تغییرات این فاز

- baseline مستند در `docs/vetra-baseline.md` ایجاد شد.
- هیچ فایل production بازنویسی نشد.
- هیچ فایل یا داده‌ای حذف نشد.
- هیچ migration یا انتقال کد انجام نشد.
- تنها تغییر repository همین فایل مستند baseline است.
- وضعیت Git پس از ایجاد baseline باید با commit مستند بعدی ثبت شود؛ پیش از ایجاد این فایل tree clean بود.
