# گزارش کامل وضعیت پروژه VETRA Portal

**تاریخ گزارش:** ۲ اکتبر ۲۰۲۶

**Repository:** `rostampourahmad1-ui/vetra-portal-theme`

**شاخه:** `main`

**آخرین commit:** `3795a3d refactor: remove projects and restore customizer`

**وضعیت Git:** working tree پاک است؛ شاخه محلی یک commit جلوتر از `origin/main` قرار دارد.

---

## ۱. خلاصه مدیریتی

در این مرحله، محدوده پروژه از نظر کد و معماری به وضعیت جدید موردنظر نزدیک شده است:

- ماژول **Projects** از قالب و افزونه Vetra Plus حذف شده است.
- صفحه اصلی، داشبورد مدیریت و تنظیمات Customizer دیگر رابط یا داده‌ای برای Projects ندارند.
- قابلیت **Native WordPress Customizer** دوباره فعال شده است.
- مشکل redirect در `customize.php` اصلاح شده است.
- Vetra Plus دیگر منوی سطح بالای مستقل ندارد و تنظیمات آن به زیرمنوی Appearance منتقل شده است.
- وابستگی‌های runtime مربوط به WooCommerce و commerce builderها در ممیزی نهایی پیدا نشدند.
- تست‌های خودکار repository با موفقیت کامل اجرا شدند.
- یک نسخه پشتیبان از وضعیت قبل از تغییرات ساخته شده است.

**جمع‌بندی:** پروژه از نظر تست‌های قراردادی و پاک‌سازی محدوده آماده ورود به مرحله نصب روی محیط staging و آزمون واقعی WordPress است؛ اما هنوز قبل از بهره‌برداری production باید نصب واقعی، فعال‌سازی قالب/افزونه، آزمون نقش‌ها، آزمون Customizer در مرورگر و بررسی backup/restore در محیط WordPress انجام شود.

---

## ۲. وضعیت فعلی Git و نسخه‌ها

| مورد | وضعیت |
|---|---|
| شاخه فعال | `main` |
| آخرین commit | `3795a3d` |
| commit مبنا | `6093e18` |
| وضعیت working tree | پاک |
| فاصله با remote | یک commit جلوتر از `origin/main` |
| push به GitHub | انجام نشده |
| package version | `3.9.0` |
| تست اجراشده | `npm test` |
| نتیجه تست | ۳۸ موفق، ۰ خطا |

### نکته مهم درباره remote

تغییرات فقط در repository محلی commit شده‌اند. برای انتقال به GitHub باید در مرحله بعد یک push آگاهانه انجام شود. قبل از push، بهتر است تغییرات روی staging نصب و smoke test شوند.

---

## ۳. موارد انجام‌شده

### ۳.۱ حذف کامل Projects از قالب

موارد زیر از runtime قالب حذف یا پاک‌سازی شده‌اند:

- فایل اصلی ماژول:
  - `inc/projects.php`
- فایل CSS مدیریتی پروژه:
  - `assets/css/project-admin.css`
- بخش Projects از `front-page.php`
- لینک Projects از fallback navigation
- تنظیمات Projects از Customizer defaults
- کنترل‌های Projects از Customizer registration
- dependency mapping مربوط به Projects در `assets/js/customizer-controls.js`
- تب و کارت Projects از داشبورد VETRA Portal
- selectorها و styleهای مربوط به grid، single و form پروژه از assetهای CSS

### ۳.۲ حذف Projects از Vetra Plus

ماژول‌های زیر حذف شده‌اند:

- `vetra-plus/includes/class-post-types.php`
- `vetra-plus/includes/class-api.php`
- `vetra-plus/includes/class-capabilities.php`
- `vetra-plus/includes/class-meta-boxes.php`
- `vetra-plus/modules/elementor/class-project-grid.php`
- `vetra-plus/modules/elementor/class-project-gallery.php`

همچنین موارد زیر اصلاح شده‌اند:

- loader افزونه دیگر کلاس‌های Projects را register نمی‌کند.
- lifecycle افزونه دیگر CPT، taxonomy، capability یا API پروژه را فعال نمی‌کند.
- Elementor فقط Corporate Banner را نگه می‌دارد.
- تنظیمات Projects از schema Vetra Plus حذف شده است.
- assetهای Vetra Plus به CSS مربوط به Corporate Banner محدود شده‌اند.

### ۳.۳ بازگردانی Native WordPress Customizer

- ثبت Customizer دوباره فعال است.
- پنل با عنوان `سفارشی‌سازی VETRA Portal` نمایش داده می‌شود.
- بخش‌های معتبر موجود شامل هویت برند، اجزای قالب، ظاهر و رنگ، پس‌زمینه، header، mobile menu، user bar، hero، محتوای شرکتی، تماس/CTA، PWA و CSS سفارشی هستند.
- کنترل‌های Projects از درخت Customizer حذف شده‌اند.
- محدودیت قبلی که Customizer را با `load-customize.php` به داشبورد redirect می‌کرد حذف شده است.

### ۳.۴ اصلاح مشکل redirect

تابع `vetra_restrict_dashboard()` اصلاح شده تا:

- مسیر `customize.php` را redirect نکند.
- کاربران فاقد `manage_options` اما دارای `edit_theme_options` بتوانند Customizer را باز کنند.
- AJAX، Cron و REST همچنان از منطق redirect عمومی مستثنا باشند.

### ۳.۵ اصلاح جایگاه تنظیمات Vetra Plus

Vetra Plus دیگر top-level menu مستقل ایجاد نمی‌کند و از مسیر زیر register می‌شود:

```text
Appearance → Vetra Plus
```

صفحه اصلی VETRA Portal نیز در Appearance باقی مانده است.

### ۳.۶ حذف commerce runtime references

ممیزی نهایی روی فایل‌های PHP، JS و CSS runtime برای موارد زیر انجام شد و موردی پیدا نشد:

- `WooCommerce`
- `is_product`
- `is_woocommerce`
- `wc_`
- `vetra_project`
- `vetra_projects`
- `home_projects`
- `project-admin`
- `project-grid`
- `project-gallery`
- `project-single`
- `project-form`

---

## ۴. تست‌ها و اعتبارسنجی انجام‌شده

### ۴.۱ تست خودکار

فرمان اجراشده:

```bash
npm test
```

نتیجه:

```text
# tests 38
# pass 38
# fail 0
```

گروه‌های اصلی تست شامل موارد زیر هستند:

- قراردادهای core theme
- نسخه assetها
- design system و palette
- RTL و logical properties
- icon pack و sprite
- loader و bootstrap
- حذف Projects
- فعال‌بودن Customizer
- مسیر redirect صحیح
- منوی Vetra Plus
- schema و settings API
- عدم وجود commerce runtime references

### ۴.۲ بررسی Git

فرمان زیر بدون خطا اجرا شد:

```bash
git diff --check
```

### ۴.۳ بررسی scope runtime

ممیزی repository برای Projects و WooCommerce در فایل‌های runtime بدون نتیجه باقی‌مانده انجام شد.

### ۴.۴ محدودیت اعتبارسنجی فعلی

در sandbox فعلی executable مربوط به PHP نصب نبود؛ بنابراین این بررسی اجرا نشد:

```bash
php -l
```

در نتیجه، syntax و behavior واقعی PHP باید در محیطی که WordPress و PHP 8.2 یا بالاتر نصب است، دوباره بررسی شود.

همچنین PDF مرجع طراحی در محیط موجود نبود؛ بنابراین بررسی تطبیق بصری بر اساس آن PDF انجام نشده است.

---

## ۵. فایل‌ها و artifactهای مهم

### کد پروژه

- [VETRA Portal Theme](/home/ubuntu/vetra-portal-theme)
- [گزارش حاضر](/home/ubuntu/vetra-portal-theme/docs/PROJECT-STATUS-REPORT-2026-10-02.md)
- [تست scope و Customizer](/home/ubuntu/vetra-portal-theme/tests/scope-customizer.test.js)
- [Bootstrap قالب](/home/ubuntu/vetra-portal-theme/functions.php)
- [Customizer](/home/ubuntu/vetra-portal-theme/inc/customizer.php)
- [Front Page](/home/ubuntu/vetra-portal-theme/front-page.php)
- [Vetra Plus settings](/home/ubuntu/vetra-portal-theme/vetra-plus/modules/settings/class-settings.php)

### نسخه پشتیبان

- [Backup قبل از تغییرات](/home/ubuntu/vetra-backups/vetra-portal-theme-before-scope-change-2026-10-02.tgz)

این backup مربوط به وضعیت قبل از حذف Projects و بازگردانی Customizer است و برای rollback محلی قابل استفاده است.

---

## ۶. معماری فعلی پروژه

### قالب VETRA Portal

مسئولیت‌های اصلی قالب در وضعیت فعلی:

- rendering صفحات عمومی
- طراحی Corporate / RTL
- header و footer
- Hero، Services، About و CTA
- Customizer
- PWA manifest و service worker endpoint
- تنظیمات private portal
- icon library و design system
- admin dashboard اصلی
- integrations اختیاری مانند Elementor و Gravity Forms

### افزونه Vetra Plus

مسئولیت‌های باقی‌مانده:

- تنظیمات Vetra Plus
- migration و import/export تنظیمات
- Corporate Banner برای Elementor
- assetهای مربوط به Banner

ماژول‌های CPT، API، taxonomy، capability و metadata پروژه دیگر در معماری فعال نیستند.

---

## ۷. وضعیت آمادگی بهره‌برداری

| حوزه | وضعیت | توضیح |
|---|---|---|
| پاک‌سازی Projects | تکمیل‌شده | runtime و UIهای اصلی پاک‌سازی شده‌اند |
| Native Customizer | تکمیل‌شده در کد | نیازمند آزمون در WordPress واقعی |
| redirect fix | تکمیل‌شده در کد | باید با نقش‌های واقعی تست شود |
| Vetra Plus settings | تکمیل‌شده در کد | مسیر Appearance بررسی شود |
| تست Node | تکمیل‌شده | ۳۸ از ۳۸ موفق |
| syntax PHP | نیازمند staging | PHP CLI در sandbox موجود نبود |
| نصب WordPress واقعی | انجام نشده | گام بعدی ضروری |
| آزمون مرورگر | انجام نشده | برای Customizer و admin menu ضروری |
| آزمون موبایل/RTL | انجام نشده | باید روی مرورگر واقعی انجام شود |
| backup/restore واقعی | انجام نشده | فقط archive محلی ساخته شده است |
| push به GitHub | انجام نشده | repository یک commit جلوتر است |
| deploy production | انجام نشده | پس از staging و تأیید نهایی |

---

## ۸. مراحل بعدی برای بهره‌برداری کامل

### مرحله اول: آماده‌سازی محیط staging

1. یک WordPress staging با PHP 8.2 یا بالاتر آماده شود.
2. نسخه PHP، WordPress و MySQL/MariaDB ثبت شود.
3. قالب و Vetra Plus در staging نصب شوند.
4. افزونه‌های لازم مانند Elementor و در صورت نیاز Gravity Forms فعال شوند.
5. اگر نسخه قبلی Projects روی سایت نصب بوده، قبل از فعال‌سازی نسخه جدید از database و uploads backup گرفته شود.

### مرحله دوم: بررسی نصب و activation

1. قالب VETRA فعال شود.
2. Vetra Plus فعال شود.
3. صفحه اصلی و page templates بررسی شوند.
4. rewrite rules با یک بار بازدید از Settings → Permalinks refresh شوند.
5. لاگ PHP و `debug.log` بررسی شود.
6. خطاهای `class not found` یا `include failed` بررسی شوند.

### مرحله سوم: آزمون Customizer

با یک کاربر administrator و یک کاربر دارای `edit_theme_options` بررسی شود:

1. ورود به `Appearance → Customize`.
2. اطمینان از بازشدن مستقیم `customize.php` بدون redirect.
3. بررسی نمایش پنل `سفارشی‌سازی VETRA Portal`.
4. تغییر موقت موارد زیر:
   - لوگو
   - رنگ روشن و تیره
   - فونت
   - متن Hero
   - CTA
   - footer
   - PWA
5. مشاهده live preview.
6. ذخیره تغییرات.
7. refresh صفحه عمومی و بررسی persistence تنظیمات.
8. بررسی عدم وجود گزینه یا تب Projects.

### مرحله چهارم: آزمون نقش‌ها و امنیت

1. با administrator مسیرهای VETRA Portal و Vetra Plus باز شوند.
2. با کاربر دارای `edit_theme_options`، Customizer باز شود.
3. با کاربر subscriber، ورود به صفحات غیرمجاز wp-admin بررسی شود.
4. AJAX و فرم‌های frontend بررسی شوند.
5. REST endpointهای موجود بررسی شوند.
6. nonce و capabilityهای settings form تست شوند.
7. import/export JSON با فایل معتبر و نامعتبر تست شود.

### مرحله پنجم: آزمون UI و responsive

در دسکتاپ و موبایل بررسی شود:

- header و navigation
- mobile menu و bottom bar
- Hero
- Services
- About
- CTA
- Contact
- dark mode و light mode
- RTL/LTR
- focus states و keyboard navigation
- reduced motion
- print stylesheet
- 404 و صفحات داخلی

### مرحله ششم: بررسی Vetra Plus و Elementor

1. وجود Vetra Plus در Appearance بررسی شود.
2. تنظیمات هویت، typography، header، footer و custom بررسی شوند.
3. Corporate Banner در Elementor درج شود.
4. widget بدون error render شود.
5. assetهای Banner در حالت `SCRIPT_DEBUG` و production بررسی شوند.
6. اطمینان حاصل شود widget یا shortcode پروژه در UI نمایش داده نمی‌شود.

### مرحله هفتم: بهینه‌سازی release

1. نسخه release را مشخص کنید؛ مثلاً `3.9.1` یا نسخه موردنظر تیم.
2. `CHANGELOG.md` به‌روزرسانی شود.
3. نسخه قالب و افزونه هماهنگ شوند.
4. zip نصب‌پذیر قالب و plugin ساخته شود.
5. نصب clean و upgrade از نسخه قبلی هر دو تست شوند.
6. backup نهایی database، uploads، theme و plugin گرفته شود.
7. checksum فایل‌های release ثبت شود.

### مرحله هشتم: انتشار به GitHub

پس از تأیید staging:

```bash
git push origin main
```

قبل از push بهتر است:

- commit نهایی review شود.
- release note نوشته شود.
- در صورت استفاده از CI، تست `npm test` در pipeline اجرا شود.
- tag نسخه release ایجاد شود.

### مرحله نهم: استقرار production

1. maintenance window تعیین شود.
2. database و uploads backup شوند.
3. نسخه جدید ابتدا در یک release directory یا staging clone آماده شود.
4. قالب و افزونه deploy شوند.
5. cache و object cache پاک‌سازی شوند.
6. rewrite rules refresh شوند.
7. smoke test عمومی انجام شود.
8. لاگ‌ها حداقل ۳۰ تا ۶۰ دقیقه پایش شوند.
9. در صورت خطا rollback به backup و commit قبلی انجام شود.

---

## ۹. پیشنهادهای فنی برای تکمیل کیفیت

### پیشنهاد ۱: اضافه‌کردن PHP lint به CI

چون تست فعلی فقط Node-based است، یک pipeline با PHP 8.2 و WordPress coding checks اضافه شود:

```bash
find . -name '*.php' -print0 | xargs -0 -n1 php -l
```

در مرحله بعد می‌توان PHP_CodeSniffer با WordPress Coding Standards را نیز اضافه کرد.

### پیشنهاد ۲: تست integration واقعی WordPress

تست‌های فعلی قراردادهای source را بررسی می‌کنند. برای اطمینان بیشتر، یک محیط WordPress test با موارد زیر لازم است:

- `WP_UnitTestCase`
- administrator و editor roles
- request واقعی به `customize.php`
- Settings API submission
- nonce validation
- activation/deactivation hook

### پیشنهاد ۳: تست مرورگر خودکار

برای جلوگیری از بازگشت مشکل redirect، یک تست Playwright یا browser smoke test اضافه شود که:

- `Appearance → Customize` را باز کند.
- status code و URL نهایی را بررسی کند.
- نبودن Projects در admin UI را assert کند.
- تنظیم یک option و preview را بررسی کند.

### پیشنهاد ۴: تصمیم درباره داده‌های قدیمی Projects

کد Projects حذف شده است؛ اما اگر در database قبلی داده‌های پروژه وجود دارد، باید تصمیم اجرایی گرفته شود:

- archive و نگهداری برای rollback
- export به JSON/CSV قبل از حذف
- پاک‌سازی database در یک migration جداگانه

این گزارش هیچ حذف databaseای انجام نداده است.

### پیشنهاد ۵: بازبینی مستندات قدیمی

برخی docs و readmeهای موجود ممکن است هنوز توضیحات تاریخی درباره Projects داشته باشند. قبل از انتشار عمومی، documentation sweep انجام شود تا مشخص شود کدام موارد باید:

- حذف شوند
- به‌عنوان legacy علامت‌گذاری شوند
- با معماری جدید همسان شوند

---

## ۱۰. برنامه rollback

در صورت مشاهده خطای جدی در staging یا production:

1. deployment را متوقف کنید.
2. به commit قبلی برگردید:

```bash
git checkout 6093e18
```

3. یا backup archive را در مسیر امن extract کنید.
4. database را فقط در صورتی restore کنید که migration داده‌ای انجام شده باشد.
5. cache و rewrite rules را refresh کنید.
6. لاگ و علت خطا ثبت شود.

**توجه:** commit جدید فقط در local repository ایجاد شده و هنوز به remote push نشده است؛ بنابراین rollback Git در محیط محلی ساده و کم‌ریسک است.

---

## ۱۱. نتیجه نهایی

کد پروژه در محدوده تغییرات درخواست‌شده آماده تحویل به مرحله staging است. مهم‌ترین کار باقی‌مانده، اجرای آزمون روی WordPress واقعی و مرورگر واقعی است؛ به‌خصوص برای Customizer، نقش `edit_theme_options`، مسیرهای admin، Vetra Plus settings و سازگاری Elementor.

تا قبل از تکمیل این آزمون‌ها، وضعیت پروژه را می‌توان این‌گونه توصیف کرد:

> **کد و تست‌های قراردادی آماده؛ نصب واقعی، تست UI، تست امنیتی و انتشار production هنوز pending.**
