# ساختار هستهٔ پوستهٔ مستقل Vetra

**نسخه:** 3.9.1
**هدف PHP:** 8.2+
**رویکرد:** Clean-Room، بدون WooCommerce، WPBakery یا Codevz

## معماری بارگذاری

`functions.php` فقط bootstrap است و به‌ترتیب زیر عمل می‌کند:

1. `ABSPATH` را guard می‌کند.
2. ثابت‌های `VETRA_PORTAL_*` را تعریف می‌کند.
3. helper، icon library، schema و `inc/core/class-loader.php` را load می‌کند.
4. کلاس‌های namespaced هسته را از طریق `Vetra\Theme\Loader` autoload می‌کند و fallback صریح `require_once` نگه می‌دارد.
5. ماژول‌های سازگار با نسخهٔ فعلی را load می‌کند: dashboard، Customizer، plugin manager، projects، site features، layouts، integrations، updater و Design System.
6. `Theme::boot()` و dashboard را فعال می‌کند.

Autoloader فقط این کلاس‌های VETRA را map می‌کند و به namespaceهای افزونه‌های خارجی دست نمی‌زند:

| کلاس | فایل |
|---|---|
| `Vetra\\Theme\\Theme` | `inc/core/class-theme.php` |
| `Vetra\\Theme\\Admin\\Dashboard` | `inc/admin/class-dashboard.php` |

اگر Vetra Plus فعال نباشد، هسته فقط با `function_exists`/`class_exists` از adapterهای اختیاری استفاده می‌کند و fatal dependency ایجاد نمی‌کند.

## ساختار دایرکتوری

```text
style.css                 # هدر استاندارد پوسته + tokenهای پایه
rtl.css                   # قواعد RTL و logical properties
theme.json                # palette و تنظیمات block editor
functions.php             # bootstrap و theme lifecycle
header.php                # document shell، skip link و header fallback
footer.php                # footer fallback، widgets و wp_footer
index.php                 # loop fallback
single.php                # post detail + comments
archive.php               # archive loop + sidebar
404.php                   # صفحهٔ خطای فارسی

inc/
├── core/
│   ├── class-loader.php   # VETRA-only namespaced autoloader
│   ├── class-theme.php    # setup و component lifecycle
│   ├── class-updater.php  # GitHub release updater
│   └── settings-schema.php
├── admin/class-dashboard.php
├── helpers.php
├── integrations.php       # adapters اختیاری Elementor/GF/form probes
├── layouts.php
├── plugin-manager.php
├── projects.php
├── site-features.php
├── customizer.php
├── vetra-design-system.php
├── vetra-icon-library.php
└── vetra-icons.php

assets/
├── css/                   # tokens، components، utilities، RTL-aware print/admin
├── js/                    # UI، corporate، service worker و admin
├── icons/                 # JSON source و SVG sprite
└── images/

template-parts/            # componentهای breadcrumb، content و service card
templates/                 # full-width، landing، distraction-free و design-system
docs/                      # قراردادها و راهنمای معماری
tests/                     # source contract tests
tools/                     # build tools
```

## هویت بصری و RTL

- Basalt Black: `#12161A`
- Warm Architectural Ochre: `#C67D34`
- Deep Brushed Bronze: `#B3803B`
- Raw Concrete: `#8A95A5`
- Chalk White: `#F6F7F9`
- همهٔ layoutها باید با `direction: rtl`، `margin-inline`، `padding-inline` و `inset-inline` سازگار بمانند.
- فونت فارسی به‌صورت اختیاری و فقط هنگام فعال‌بودن setting مربوطه enqueue می‌شود؛ فونت اجباری یا commercial در repository اضافه نشده است.
- stylesheet و JavaScript فقط در مسیرهای مرتبط enqueue می‌شوند؛ Design System با `defer` load می‌شود.

## Extension hooks اختصاصی VETRA

قرارداد کامل WordPress hookها و callbackها در [`vetra-api-contracts.md`](vetra-api-contracts.md) ثبت شده است. extension pointهای داخلی هسته:

| Hook | نوع | محل | قرارداد |
|---|---|---|---|
| `vetra_after_header` | action | `header.php` | پس از header fallback؛ callback خروجی HTML escaped تولید کند؛ بدون ورودی |
| `vetra_theme_components_registered` | action | `inc/core/class-theme.php` | پس از ثبت componentهای هسته؛ بدون ورودی |
| `vetra_ds_assets_enqueued` | action | `inc/vetra-design-system.php` | پس از enqueue/localize؛ یک آرگومان: `VETRA_DS_HANDLE` |
| `vetra_project_read` | filter | `inc/projects.php` | دو آرگومان: row پروژه یا `null` و شناسه؛ permission check قبل از filter اجرا می‌شود |
| `vetra_featured_project_detail_url` | filter | `inc/projects.php` | دو مقدار callback: URL پیش‌فرض و object پروژه؛ URL نهایی باید validate/escape شود |

`vetra_featured_projects_count` در source فعلی وجود ندارد و عمداً در این فاز ایجاد نشده است؛ وضعیت آن در قراردادهای API «تأییدنشده» باقی می‌ماند.

## Adapter policy

- Elementor فقط وقتی `ELEMENTOR_VERSION` وجود دارد فعال شناخته می‌شود؛ templateهای استاندارد پوسته fallback هستند.
- Gravity Forms فقط با `GFForms`/`gravity_form` probe می‌شود؛ فرم fallback باید بدون افزونه کار کند.
- هیچ `wc_*`، `WooCommerce`، WPBakery یا Codevz hook/class/table/dependency به هسته اضافه نمی‌شود.
- Vetra Plus در این فاز ایجاد یا لازم نشده است. قابلیت‌های stateful مانند پروژه‌ها، roleها، restriction، PWA و plugin manager در صورت انتقال آینده باید با migration versioned، rollback و compatibility shim منتقل شوند.

## استاندارد تغییرات آینده

1. قبل از rename یا حذف هر API، قرارداد `docs/vetra-api-contracts.md` و migration plan به‌روزرسانی شود.
2. عملیات state-changing باید capability، nonce، sanitize/validate و escape مناسب داشته باشد.
3. output templateها باید با APIهای escape وردپرس تولید شوند؛ SVG فقط از icon allow-list داخلی بیاید.
4. فایل جدید باید با prefix یا namespace `Vetra`/`vetra_` نام‌گذاری شود.
5. پس از هر تغییر: PHP lint، Node contract tests، بررسی hook ordering، RTL و responsive انجام شود.
