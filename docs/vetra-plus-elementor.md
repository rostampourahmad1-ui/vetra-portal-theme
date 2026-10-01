# ماژول Elementor افزونه Vetra Plus

**وضعیت:** فاز ۵  
**هدف PHP:** 8.2+  
**سازگاری:** Elementor 3.x به‌صورت optional

## ساختار

```text
vetra-plus/
├── assets/
│   ├── css/vetra-elements.css
│   └── js/vetra-elements.js
└── modules/elementor/
    ├── class-module.php
    ├── class-renderer.php
    ├── class-project-grid.php
    ├── class-project-gallery.php
    └── class-corporate-banner.php
```

## ثبت Elementor

ماژول از API رسمی Elementor استفاده می‌کند:

- `elementor/elements/categories_registered` برای category `vetra-elements` با عنوان **Vetra Elements** و icon `eicon-building`
- `elementor/widgets/register` برای ثبت ویجت‌ها
- `get_categories()` برای اتصال ویجت‌ها به category اختصاصی
- `get_style_depends()` و `get_script_depends()` برای وابستگی assetهای widget

وقتی Elementor فعال نباشد، کلاس‌هایی که از `Elementor\Widget_Base` ارث می‌برند load نمی‌شوند و افزونه فقط shortcodeها و rendererهای مستقل خود را نگه می‌دارد.

## ویجت‌های فاز ۵

| Widget | نام داخلی | کاربرد |
|---|---|---|
| Project Grid | `vetra-project-grid` | نمایش پروژه‌های publish شده با filter دسته و مهارت |
| Project Gallery | `vetra-project-gallery` | نمایش gallery بر اساس attachment ID یا متای پروژه |
| Corporate Banner | `vetra-corporate-banner` | بنر شرکتی، architectural و CTA |

تمام ویجت‌ها RTL-safe، بدون WPBakery/Codevz و با کنترلرهای رسمی Elementor هستند.

## Shortcode fallback

وقتی Elementor فعال نیست یا برای استفاده در قالب/محتوای عادی:

```text
[vetra_projects count="6" category="commercial" skill="architecture"]
[vetra_project_gallery ids="12,13,14"]
[vetra_corporate_banner title="ساختن آینده‌ای ماندگار" text="..." button_url="/contact" button_text="شروع گفتگو"]
```

shortcodeها فقط هنگام render شدن assetهای `vetra-plus-elements` را enqueue می‌کنند.

## Performance

- `wp_register_style()` و `wp_register_script()` در `wp_enqueue_scripts` انجام می‌شود.
- Elementor dependencyها را فقط در صفحاتی که widget استفاده شده enqueue می‌کند.
- افزونه خودش dependencyهای Elementor را دستی enqueue نمی‌کند.
- shortcode fallback فقط در صفحه‌ای که shortcode render شده assetها را فعال می‌کند.
- JavaScript بدون کتابخانهٔ خارجی است و با `defer` ثبت می‌شود.

## RTL و امنیت خروجی

- CSS با logical properties و breakpointهای responsive نوشته شده است.
- تمام attributeها با `esc_attr`، URLها با `esc_url` و متن‌ها با `esc_html` خروجی می‌شوند.
- query پروژه‌ها فقط `post_type=vetra_project` و `post_status=publish` را نمایش می‌دهد.
- فیلترها با `sanitize_title` و `absint` نرمال می‌شوند.

## منابع فنی

پیاده‌سازی با قرارداد رسمی Elementor برای category و dynamic widget assets انجام شده است:

- https://developers.elementor.com/docs/widgets/widget-categories/
- https://developers.elementor.com/docs/scripts-styles/widget-scripts/
- https://developers.elementor.com/docs/scripts-styles/

فاز ۶، یعنی Theme Options و migration فایل backup، هنوز اجرا نشده است.
