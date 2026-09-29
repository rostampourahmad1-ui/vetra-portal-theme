# VETRA Icon Pack v1.0.0

سیستم آیکون اختصاصی و مشترک چهار محصول VETRA:

1. `vetra-portal-theme`
2. `Vetra RebarCut`
3. `Vetra Dashboard`
4. `VETRA GANTT`

## ساختار

```text
assets/
  icons/
    vetra-icons.json    ← منبع واحد حقیقت (geometry + label + alias)
    vetra-icons.svg     ← sprite تولیدشده از JSON
  css/
    vetra-icons.css     ← کلاس‌های عمومی و قرارداد هندسی
inc/
  vetra-icon-library.php  ← بارگذاری/اعتبارسنجی/جست‌وجو/sprite
  vetra-icons.php         ← vetra_icon()، شورتکد، data-uri ادمین، enqueue
tools/
  build-icon-sprite.js    ← ساخت مجدد sprite: node tools/build-icon-sprite.js
```

هیچ وابستگی به Dashicons یا FontAwesome وجود ندارد و CSS/JS خالص است.

## قرارداد هندسی

- `viewBox="0 0 24 24"`، اندازه پیش‌فرض `24`
- `stroke-width: 1.5`، `stroke-linecap: square`، `stroke-linejoin: miter`
- بدون fill، بدون سایه/گرادیان/افکت سه‌بعدی
- زوایای غالب ۴۵ و ۹۰ درجه و فرم‌های مهندسی
- رنگ با CSS کنترل می‌شود (`currentColor` + accent)

## رنگ

| نقش | کلاس | مقدار |
| --- | --- | --- |
| ساختار اصلی | `.vetra-icon-base` | `currentColor` (Basalt در Light، Chalk در Dark) |
| تأکید محدود | `.vetra-icon-accent` | `#C67D34` Architectural Ochre |
| fill پایه | `.vetra-icon-base-fill` | `currentColor` |
| fill تأکید | `.vetra-icon-accent-fill` | `#C67D34` |

قواعد:
- بخش شاخص/بحرانی/فعال/عملکردی accent می‌گیرد؛ بقیه فقط base.
- استفاده از کهربایی برای کل آیکون ممنوع است.
- رنگ ممنوع `#F97316` هرگز استفاده نمی‌شود.
- بازنویسی رنگ مجاز: `base_color` / `accent_color` یا `style="--vetra-icon-base-color:..."`.

## استفاده در PHP

```php
// آیکون تزئینی کنار متن
echo vetra_icon( 'check' );

// آیکون کاربردی با نام قابل‌دسترس
echo vetra_icon( 'warning', array( 'label' => 'هشدار', 'size' => 20 ) );

// آیکون سفارشی‌شده
echo vetra_icon( 'critical-path', array( 'base_color' => '#8A95A5', 'accent_color' => '#C67D34' ) );

// سازگاری با کد قدیمی قالب
echo vetra_inline_icon( 'arrow' );
```

## استفاده در HTML (sprite)

```html
<svg class="vetra-icon" aria-hidden="true"><use href="#vetra-icon-check"></use></svg>
```

sprite را یک‌بار در فوتر چاپ کنید:

```php
echo vetra_icon_sprite_markup();
```

## استفاده در دکمه و کنترل آیکن‌محور

```html
<button type="button" class="vetra-btn vetra-btn--secondary">
  <svg class="vetra-icon" aria-hidden="true"><use href="#vetra-icon-export"></use></svg>
  خروجی
</button>

<button type="button" class="vetra-icon-button" aria-label="جست‌وجو">
  <svg class="vetra-icon" aria-hidden="true"><use href="#vetra-icon-search"></use></svg>
</button>
```

## شورتکد

```text
[vetra_icon name="check" size="20" label="تأیید شده"]
[vetra_icon name="print" size="24"]
[vetra_icon name="warning" title="هشدار بحرانی" decorative="false"]
```

## امنیت

- نام آیکون فقط از طریق whitelist کتابخانه (و aliasها) پذیرفته می‌شود.
- نام ناشناخته → fallback مشخص (`info`)؛ خطای PHP یا SVG شکسته تولید نمی‌شود.
- هرگز نام یا attribute کاربر مستقیماً چاپ نمی‌شود؛ همه با `esc_attr`/`esc_html`/`sanitize_*` پاک می‌شوند.
- هر عنصر هندسی از `vetra_icon_validate_markup()` عبور می‌کند: فقط تگ‌های `path,circle,rect,line,polyline,polygon,g` و رد `script`, `on*`, `javascript:`, `foreignObject`, `href`, `xlink`.
- رنگ‌ها با `vetra_icon_sanitize_color()` فقط hex/rgb مجاز هستند.
- امکان تزریق SVG دلخواه از طریق `name` یا attribute وجود ندارد.

## دسترس‌پذیری

- آیکون تزئینی: `aria-hidden="true"` و `focusable="false"`.
- آیکون کاربردی: `role="img"` + `aria-label` (یا `<title>`).
- دکمه‌های آیکن‌محور: `aria-label` + tooltip اجباری (`vetra-icon-button`).
- `focus-visible` با رنگ `#C67D34`؛ آیکون هرگز تنها حامل معنا نیست.
- RTL: آیکون‌های جهت‌دار با `.vetra-icon--directional` در `[dir="rtl"]` آینه می‌شوند.

## فهرست آیکون‌ها

**عمومی (۲۳):** blueprint, building-crane, construction-site, building, safety-helmet, contract, document, folder-project, engineer, settings, search, filter, export, print, close, check, warning, info, arrow-left, arrow-right, chevron-down, menu, logout

**پیشخوان (۱۲):** dashboard-grid, id-card, user-profile, ticket-support, wallet-safe, payment-card, bank-transfer, bell-notification, message, lock-security, login, upload-receipt

**Vetra RebarCut (۱۲):** rebar-bundle, cut-blade, cut-plan, warehouse-rack, stock-material, scrap-recycling, usable-remnant, waste-material, measurement, optimization, calculation, material-report

**VETRA GANTT (۱۵):** gantt-chart, wbs-nodes, task, task-complete, milestone-diamond, critical-path, calendar, timeline-day, timeline-week, timeline-month, zoom-in, zoom-out, today, dependency, project-progress

**کمکی سازگاری (۸):** sun, moon, people, leaf, pen, cube, chart, arrow-up

جمع: **۷۰** آیکون + aliasها: `arrow, file, folder, user, lock, bell, money, date, grid, trash, recycle, gantt`.

## Dashicons و FontAwesomeهای حذف‌شده

| محل | قبل | بعد |
| --- | --- | --- |
| `inc/projects.php` (منوی پروژه‌ها) | `dashicons-building` | `vetra_icon_data_uri( 'building' )` |
| `inc/layouts.php` (CPT الگوها) | `dashicons-layout` | `vetra_icon_data_uri( 'dashboard-grid' )` |

آیکون‌های inline قبلی `vetra_inline_icon()` نیز با نسخه جدید Icon Pack جایگزین شدند (نام‌های قدیمی `arrow, file, chart, ...` با alias پشتیبانی می‌شوند).

## تست‌ها

`npm test` → `tests/icon-pack.test.js`:

- render همه آیکون‌ها (صحت تگ/attribute)
- پالت: نبود `#F97316`، base=currentColor، accent=#C67D34
- اندازه‌های ۱۶/۲۰/۲۴/۳۲ با `vetra_icon_sanitize_size()` (px/em/rem)
- RTL با کلاس directional
- keyboard focus و aria-label در CSS/رندرر
- نبود SVG injection و نام نامعتبر → fallback
- چاپ: قواعد `@media print`
- sprite: تعداد symbol برابر کتابخانه

تست‌های دستی پیشنهادی پس از انتشار: Chrome/Firefox/Safari/Edge (بازتاب `currentColor` و sprite)، Light/Dark، RTL، و چاپ مرورگر.
