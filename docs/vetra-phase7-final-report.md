# گزارش نهایی ممیزی کیفیت VETRA — فاز ۷

**تاریخ:** 2026-10-02  
**نوع فاز:** آزمون پایداری، امنیت، سازگاری PHP 8.2+، بهینه‌سازی asset و آماده‌سازی release  
**رویکرد:** Clean-Room؛ بدون افزودن WooCommerce، WPBakery یا Codevz

## ۱. نتیجهٔ اجرایی

فاز ۷ با موفقیت تکمیل شد. پوسته و افزونهٔ مستقل Vetra Plus با PHP 8.2/8.3، تست‌های قراردادی پروژه، کنترل‌های امنیتی، بسته‌بندی جداگانه و release اختصاصی Vetra Plus آمادهٔ استقرار آزمایشی هستند.

> وضعیت مهم: WordPress Core و محیط کامل staging در Sandbox نصب نیست؛ بنابراین تست مرورگر/Database/Elementor واقعی در محیط WordPress انجام نشده و این موارد باید طبق checklist روی `test.vetragroup.ir` اجرا شوند.

## ۲. نتایج validation

| کنترل | نتیجه |
|---|---|
| Node contract tests | **70 passed, 0 failed** |
| PHP syntax lint با PHP 8.3.6 و `E_ALL` | موفق؛ بدون خطای syntax |
| JavaScript `node --check` | موفق |
| JSON validation برای `theme.json`، `package.json` و icon manifest | موفق |
| GitHub Actions YAML parsing | موفق |
| `git diff --check` | موفق |
| scan برای WooCommerce/WPBakery/Codevz در production source | موردی پیدا نشد |
| scan برای `eval`، `unserialize`، `extract` و الگوهای قدیمی PHP | موردی پیدا نشد |
| staging HEAD request | `HTTP/2 200` از `https://test.vetragroup.ir` |

## ۳. Fail-safe افزونه

پوسته بدون فعال‌بودن Vetra Plus نباید crash کند. شواهد static این فاز:

- هیچ `require` یا `include` از مسیر `vetra-plus` در پوسته وجود ندارد.
- هیچ reference به namespace یا constantهای `Vetra\\Plus` در production theme وجود ندارد.
- Vetra Plus فقط کلاس‌ها و فایل‌های خودش را load می‌کند.
- کلاس‌های Elementor در افزونه فقط از طریق hookهای رسمی Elementor استفاده می‌شوند.
- تمام فایل‌های افزونه و ماژول‌ها guard مربوط به `ABSPATH` دارند.
- بستهٔ پوسته، پوشهٔ `vetra-plus` را شامل نمی‌شود.

تست runtime کامل نیازمند نصب WordPress Core است و در staging باید با دو حالت انجام شود:

1. فعال: فقط پوسته فعال، Vetra Plus غیرفعال.
2. فعال: پوسته و Vetra Plus هر دو فعال، Elementor غیرفعال و سپس فعال.

## ۴. اصلاحات PHP 8.2+ و Type-Safety

- PHP target افزونه و پوسته روی 8.2 تنظیم شده است.
- workflow CI فقط matrixهای PHP 8.2 و 8.3 را اجرا می‌کند.
- الگوهای dynamic property assignment در source production پیدا نشد.
- کلاس‌های جدید دارای propertyهای صریح یا state محدود هستند.
- ورودی‌های enum در project و site settings قبل از allow-list با `wp_unslash` و `sanitize_key` پاک‌سازی می‌شوند.
- migration تنظیمات فقط JSON allow-list است و serialized/PHP code را اجرا نمی‌کند.
- updater پوسته asset جدید `vetra.zip` را ترجیح می‌دهد و با assetهای versioned قبلی نیز backward-compatible است.

## ۵. امنیت

کنترل‌های بررسی‌شده:

- `current_user_can()` برای عملیات مدیریتی و business actionها
- nonce برای فرم‌های پوسته، پروژه، متاباکس، import/export/reset و plugin manager
- sanitize برای text، textarea، key، URL، integer، enum و file name
- validation و allow-list برای status، role، layout، mode و setting key
- escape خروجی با `esc_html`، `esc_attr`، `esc_url`، `esc_textarea` و `wp_kses_post`
- استفاده از `$wpdb->prepare()` برای queryهای دارای ورودی
- callback مجوز برای REST و post meta
- محدودیت حجم فایل import تنظیمات به ۵ مگابایت
- عدم استفاده از `eval()`، `unserialize()` یا include پویا از ورودی کاربر

## ۶. Asset و Performance

دارایی‌های اصلی frontend اکنون در production از نسخهٔ minified استفاده می‌کنند و با `SCRIPT_DEBUG` نسخهٔ readable فعال می‌شود.

| Asset | قبل | بعد | کاهش تقریبی |
|---|---:|---:|---:|
| `corporate.js` | 6,972 B | 4,661 B | 33.1% |
| `corporate.css` | 33,215 B | 29,267 B | 11.9% |
| `vetra-elements.js` | 561 B | 389 B | 30.7% |
| `vetra-elements.css` | 2,091 B | 2,089 B | کمتر از 1% |

راهبرد بارگذاری:

- اسکریپت اصلی پوسته با `defer` بارگذاری می‌شود.
- اسکریپت Elementor افزونه با `defer` ثبت می‌شود.
- assetهای Vetra Plus ابتدا register و فقط هنگام render widget/shortcode enqueue می‌شوند.
- استایل جست‌وجو فقط در صورت فعال‌بودن قابلیت جست‌وجو enqueue می‌شود.
- فونت‌ها فقط طبق setting انتخاب‌شده load می‌شوند.

## ۷. ساختار packageهای نهایی

### `vetra.zip`

```text
vetra-portal-theme/
├── style.css
├── functions.php
├── templates/
├── template-parts/
├── inc/
├── assets/
├── rtl.css
├── theme.json
└── screenshot.png
```

شامل افزونهٔ `vetra-plus`، testها، docs و ابزارهای توسعه نیست.

### `vetra-plus.zip`

```text
vetra-plus/
├── vetra-plus.php
├── includes/
├── modules/
│   ├── elementor/
│   └── settings/
└── assets/
```

## ۸. چک‌لیست استقرار روی staging

1. از WordPress staging و دیتابیس backup بگیرید.
2. مطمئن شوید PHP حداقل 8.2 و WordPress حداقل 6.3 است.
3. از بخش Themes، فایل `vetra.zip` را نصب و فعال کنید.
4. از بخش Plugins، فایل `vetra-plus.zip` را نصب کنید؛ در مرحلهٔ اول آن را فعال نکنید.
5. با Vetra Plus غیرفعال، صفحهٔ اصلی، آرشیو، single، 404، search و RTL را بررسی کنید.
6. Vetra Plus را فعال کنید و flush rewrite را فقط یک‌بار انجام دهید.
7. وجود CPT `vetra_project`، taxonomyها، capabilities و REST endpoint را بررسی کنید.
8. Elementor را غیرفعال نگه دارید و fallback shortcodeها را تست کنید.
9. Elementor را فعال کنید و category `Vetra Elements` و سه widget را تست کنید.
10. import/export/reset تنظیمات را با JSON معتبر و فایل نامعتبر تست کنید.
11. درخواست‌های غیرمجاز، nonce قدیمی، فایل بزرگ و کلیدهای ناشناخته را تست کنید.
12. در DevTools بررسی کنید assetهای `.min.js` و `.min.css` در production و assetهای readable با `SCRIPT_DEBUG` load می‌شوند.
13. در موبایل، RTL، tablet و desktop تست responsive انجام دهید.
14. فرم‌های پروژه، upload تصویر، moderation و نقش‌های پروژه را با حساب‌های مجزا آزمایش کنید.
15. logهای PHP و browser console را بررسی کنید و سپس cache/CDN را purge کنید.

## ۹. نصب گام‌به‌گام production

1. ابتدا پوستهٔ فعلی و دیتابیس را backup کنید.
2. `vetra.zip` را در **Appearance → Themes → Add New → Upload Theme** نصب کنید.
3. پوسته را فعال کنید.
4. `vetra-plus.zip` را در **Plugins → Add New → Upload Plugin** نصب و فعال کنید.
5. در صورت نیاز Elementor را جداگانه نصب و فعال کنید؛ Vetra Plus بدون Elementor نیز fatal نمی‌دهد.
6. در تنظیمات Vetra Plus، گزینه‌های branding، typography، header، footer، projects و custom CSS را تنظیم کنید.
7. برای migration فقط JSON معتبر و شناخته‌شده import کنید. فایل backup Ecopark تا زمان فراهم‌شدن artifact اصلی نباید حدس زده یا import شود.
8. permalinkها را یک‌بار save کنید.
9. صفحات اصلی و templateهای هدر/فوتر را بررسی کنید.
10. پس از تأیید staging، همین دو ZIP را به production منتقل کنید.

## ۱۰. محدودیت‌ها و ریسک‌های باقی‌مانده

- WordPress runtime، دیتابیس و Elementor واقعی در Sandbox موجود نبود؛ تست واقعی staging الزامی است.
- فایل `backup-options-01-10-2026.txt` در workspace پیدا نشد؛ migration واقعی Ecopark انجام نشده است.
- assetهای غیر اصلی مانند search، RTL و print نسخهٔ جداگانهٔ minified ندارند؛ assetهای پرمصرف اصلی و Elementor minified شده‌اند.
- تغییر release/tag خارجی پس از commit و push باید با لینک GitHub و assetهای uploadشده verify شود.

## ۱۱. Release هدف

- عنوان release: **VETRA PLUS WP Them v1.0.0**
- tag فنی Git: `vetra-plus-wp-them-v1.0.0`
- assetها: `vetra.zip` و `vetra-plus.zip`
