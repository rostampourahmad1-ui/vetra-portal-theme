# گزارش پیاده‌سازی جامع Vetra Portal 3.5.0

## دامنه تغییرات

این شاخه با حفظ tokenهای رنگی موجود، اصلاحات زیر را اعمال می‌کند:

- **RTL/LTR:** حذف `dir="rtl"` اجباری از HTML و واگذاری جهت به `language_attributes()` وردپرس؛ فایل `rtl.css` فقط برای RTL لود می‌شود.
- **دسترس‌پذیری:** افزودن skip link، landmarkهای معنایی، `aria-label`/`aria-controls`/`aria-expanded` برای ناوبری، بستن منوی موبایل با Escape، بازگرداندن focus به کنترل آغازگر، focus قابل مشاهده و پشتیبانی از `prefers-reduced-motion`.
- **سلسله‌مراتب وردپرس:** افزودن `archive.php`، `search.php`، `single.php`، `comments.php`، `sidebar.php` و template part محتوای قابل استفاده مجدد.
- **دیدگاه‌ها:** اتصال به API رسمی وردپرس با دیدگاه‌های تودرتو، صفحه‌بندی، فرم استاندارد و رشته‌های ترجمه‌پذیر.
- **ابزارک و منو:** ثبت ناحیه ابزارک نوار کناری و منوی پابرگ.
- **ویرایشگر بلوکی:** افزودن `theme.json` و پشتیبانی‌های استاندارد responsive embeds/editor styles/block styles.
- **افزونه‌ها:** استایل bbPress فقط در صفحات bbPress بارگذاری می‌شود و نبود افزونه باعث خطا نمی‌شود.
- **عملکرد:** اسکریپت اصلی با strategy `defer` enqueue می‌شود؛ asset RTL و integration شرطی هستند.
- **ترجمه:** متن‌های fallback هدر، ۴۰۴، وضعیت خالی و پابرگ از API ترجمه وردپرس عبور می‌کنند.
- **معماری:** bootstrap اکنون سرویس‌های `Theme`، schema تنظیمات، داشبورد مدیریت و integration adapterها را به‌صورت جداگانه بارگذاری می‌کند.
- **schema مشترک:** `inc/core/settings-schema.php` نوع، default، allowlist، sanitizer، capability، preview و نگاشت CSS تنظیمات عملیاتی را تعریف و normalize می‌کند.
- **پنل مدیریت:** داشبورد امن برای وضعیت ماژول‌ها، export/import JSON اعتبارسنجی‌شده و بازسازی cache CSS اضافه شده است.
- **فرانت‌اند حرفه‌ای:** نوار بالا، جست‌وجوی overlay، انتخاب زبان WPML، widgetهای چندستونه پابرگ، بازگشت به بالا و کامپوننت‌های breadcrumb/share/related اضافه شده‌اند.
- **قالب‌های صفحه:** قالب‌های تمام‌عرض، بدون حواس‌پرتی و landing page ثبت و قابل انتخاب‌اند.
- **اتصالات:** adapterهای شرطی WPML، Gravity Forms، bbPress و WooCommerce ایجاد شده‌اند؛ منطق افزونه‌ها بازنویسی نشده است.
- **ویرایشگر بلوکی:** `theme.json` با palette، font sizes، spacing، layout و element styles تکمیل شده است.

## بررسی‌های اجراشده

| بررسی | نتیجه |
|---|---|
| `php -l` روی تمام فایل‌های PHP | موفق؛ ۳۲ فایل بدون خطای syntax |
| `npm test` | موفق؛ ۱۵ آزمون قرارداد قبول شد |
| `node --check assets/js/corporate.js` | موفق |
| `python3 -m json.tool theme.json` | موفق |
| `git diff --check` | موفق |

## بررسی دستی ایستا

- رنگ‌های پایه در `style.css` و مقادیر رنگی `customizer.php` تغییر نکرده‌اند.
- عملیات مدیریتی موجود و داشبورد جدید از nonce/capability/validation استفاده می‌کنند؛ import فقط JSON با schema مجاز را می‌پذیرد.
- قالب در نبود JavaScript مسیر محتوایی و منوی پایه را حفظ می‌کند؛ JavaScript فقط enhancement است.
- افزونه‌های اختیاری در مسیر رندر عادی شرطی و غیرالزامی باقی مانده‌اند.

## پوشش نیازمندی‌ها

| حوزه | وضعیت | پیاده‌سازی اصلی |
|---|---|---|
| معماری کلاسیک و ماژولار | پوشش داده شد | `inc/core`, `inc/admin`, `inc/integrations`, `template-parts` |
| header/footer و تعاملات | پوشش داده شد | overlay search، mobile menu، topbar، footer widgets، back-to-top |
| template hierarchy | پوشش داده شد | archive/search/single/comments/sidebar و سه page template |
| اجزای reusable | پوشش داده شد | breadcrumb، post meta، share، related، content/service card |
| Customizer و schema | پوشش داده شد | `inc/customizer.php` + `inc/core/settings-schema.php` |
| پنل مدیریت | پوشش داده شد | dashboard، module status، import/export، cache rebuild |
| RTL/LTR و ترجمه | پوشش داده شد | `language_attributes`، `rtl.css` شرطی، WPML adapter، text domain |
| امنیت | پوشش داده شد | capability، nonce، sanitize، allowlist، escape در مسیرهای جدید |
| عملکرد | پوشش داده شد | conditional assets، defer JS، transient CSS cache |
| افزونه‌ها | پوشش داده شد | bbPress/WPML/forms/WooCommerce detection بدون dependency سخت |

## محدودیت محیط

- محیط sandbox فاقد نصب وردپرس، دیتابیس، مرورگر واقعی و افزونه‌های bbPress/WPML/فرم‌ساز است؛ بنابراین آزمون end-to-end، Lighthouse، تست screen reader و ماتریس نسخه‌های WordPress در این محیط قابل اجرا نبود.
- PHP CLI برای lint در طول کار نصب شد و lint کامل روی PHP 8.3 اجرا شد. سازگاری PHP 8.1 باید در CI یا محیط staging وردپرس نیز تکرار شود.

## پیشنهاد پیش از انتشار

1. در staging با WordPress 6.x و PHP 8.1/8.2/8.3 پوسته را فعال کنید.
2. با زبان فارسی و انگلیسی، منوی چندسطحی، دیدگاه، ابزارک و حالت JavaScript خاموش تست کنید.
3. با افزونه‌های bbPress و فرم‌ساز منتخب تست بصری و keyboard-only انجام دهید.
4. پس از نصب، پیوندهای یکتا را یک‌بار ذخیره و خطاهای PHP log را بررسی کنید.
