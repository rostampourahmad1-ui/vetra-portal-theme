# VETRA Design System v1.0.0

Design System مشترک چهار محصول:

1. `vetra-portal-theme`
2. `Vetra RebarCut`
3. `Vetra Dashboard`
4. `VETRA GANTT`

تمام فایل‌ها بدون وابستگی بیرونی (بدون React، بدون build، بدون CDN) و به‌صورت CSS/JS خالص هستند و روی وردپرس با namespace اختصاصی `vetra-` بارگذاری می‌شوند.

---

## 1. فایل‌ها

| فایل | مسئولیت |
| --- | --- |
| `assets/css/vetra-tokens.css` | پالت رسمی، توکن‌های معنایی، Light/Dark/Auto، مقیاس فاصله، شعاع، سایه |
| `assets/css/vetra-components.css` | تمام کامپوننت‌های رابط |
| `assets/css/vetra-utilities.css` | ابزارهای چیدمان، متن، دسترس‌پذیری |
| `assets/css/vetra-print.css` | استایل چاپ (همیشه روشن، خوانا برای فارسی/عدد/واحد) |
| `assets/js/vetra-ui.js` | رفتارها: تم، modal، toast، tab، tooltip، loading، sort، confirm |
| `inc/vetra-design-system.php` | بارگذاری assetها، helperهای PHP، escape/sanitize، قالب پیش‌نمایش |
| `templates/design-system.php` | صفحه پیش‌نمایش هر کامپوننت |

بارگذاری به‌صورت خودکار از `functions.php` انجام می‌شود (`vetra_ds_enqueue_assets` روی `wp_enqueue_scripts` با اولویت ۲۰ و ترتیب tokens → components → utilities → print).

محصولات دیگر می‌توانند در صورت فعال بودن قالب، `vetra_ds_enqueue_assets()` را صدا بزنند یا مستقیماً فایل‌ها را مصرف کنند.

---

## 2. توکن‌ها

### پالت رسمی (غیرقابل تغییر)

| نام | توکن | Hex | کاربرد |
| --- | --- | --- | --- |
| Basalt Black | `--vetra-basalt-black` | `#12161A` | پس‌زمینه Dark، متن اصلی Light، متن روی CTA |
| Architectural Ochre | `--vetra-architectural-ochre` | `#C67D34` | CTA اصلی، focus ring، وضعیت بحرانی |
| Raw Exposed Concrete | `--vetra-raw-concrete` | `#8A95A5` | متن ثانویه، مرزها، وضعیت اطلاعات |
| Chalk Lime White | `--vetra-chalk-white` | `#F6F7F9` | پس‌زمینه Light، متن اصلی Dark |
| Deep Brushed Bronze | `--vetra-brushed-bronze` | `#B3803B` | hover اصلی، وضعیت هشدار |
| Fluted Charcoal Aluminum | `--vetra-charcoal-aluminum` | `#222933` | سطح‌ها در Dark |

> رنگ ممنوع `#F97316` و هیچ قرمز تند/سبز نئونی/نارنجی سیگنالی در سیستم وجود ندارد.

### توکن‌های معنایی

| توکن | Light | Dark |
| --- | --- | --- |
| `--vetra-color-bg` | `#F6F7F9` | `#12161A` |
| `--vetra-color-surface` | `#FFFFFF` | `#222933` |
| `--vetra-color-surface-raised` | `#FFFFFF` | `#2A323C` |
| `--vetra-color-text` | `#12161A` | `#F6F7F9` |
| `--vetra-color-text-muted` | `#8A95A5` | `#8A95A5` |
| `--vetra-color-text-soft` | `#5A6472` | `#B9C1CC` |
| `--vetra-color-border` | `rgba(138,149,165,.42)` | `rgba(138,149,165,.32)` |
| `--vetra-color-primary` | `#C67D34` | `#C67D34` |
| `--vetra-color-primary-hover` | `#B3803B` | `#B3803B` |
| `--vetra-color-focus` | `#C67D34` | `#C67D34` |
| `--vetra-color-critical` | `#C67D34` | `#C67D34` |
| `--vetra-color-warning` | `#B3803B` | `#B3803B` |
| `--vetra-color-danger` | `#C67D34` | `#C67D34` |
| `--vetra-color-info` | `#8A95A5` | `#8A95A5` |
| `--vetra-color-success` | `#4E7A5A` ⚠️ | `#5E8C6A` ⚠️ |

⚠️ **Success یک «گسترش معنایی کنترل‌شده» است و نیازمند تأیید مالک محصول است.** پالت رسمی هیچ سبزی ندارد و بدون آن موفقیت از هشدار/بحرانی قابل تفکیک نیست. مقدار انتخابی کم‌اشباع و آرام است و همیشه همراه آیکن/برچسب استفاده می‌شود. جایگزین در صورت رد: `--vetra-brushed-bronze`.

### مقیاس‌های غیررنگی

- فاصله: `--vetra-space-1` تا `--vetra-space-10` = 4/8/12/16/24/32/48/64
- شعاع: `sm 4px`، `6px`، `md 8px` (حداکثر)، `pill` فقط برای badge/avatar
- ارتفاع کنترل: `sm 32px`، پیش‌فرض `40px`، `lg 48px`
- سایه: `xs/sm/md/lg` بسیار ملایم و کاربردی (بدون شیشه/نئون)
- حرکت: `--vetra-transition-fast 120ms` و `--vetra-transition 180ms` با احترام به `prefers-reduced-motion`

---

## 3. حالت روشن / تاریک

- پیش‌فرض `:root` = Light.
- انتخاب دستی: `<html data-vetra-theme="light|dark">`.
- حالت خودکار: `@media (prefers-color-scheme: dark)` وقتی انتخاب دستی Light نباشد.
- سازگاری با سیستم تم موجود قالب: `data-theme="dark"` هم به‌عنوان alias پذیرفته می‌شود و `MutationObserver` در JS دو attribute را همگام نگه می‌دارد.
- انتخاب کاربر در `localStorage` با کلید `vetra-theme-mode` ذخیره می‌شود (تنظیم کاربر، بدون عملیات سمت سرور؛ بنابراین nonce لازم نیست).

```html
<button type="button" data-vetra-theme-toggle="toggle" aria-pressed="false">تغییر حالت</button>
```

---

## 4. نمونه استفاده از کامپوننت‌ها

### دکمه

```html
<button type="button" class="vetra-btn vetra-btn--primary">ثبت</button>
<button type="button" class="vetra-btn vetra-btn--secondary">انصراف</button>
<button type="button" class="vetra-btn vetra-btn--ghost">جزئیات</button>
<button type="button" class="vetra-btn vetra-btn--danger" data-vetra-confirm="این مورد حذف شود؟">حذف</button>
```

```php
echo vetra_ds_button( array( 'label' => 'ثبت', 'variant' => 'primary' ) );
```

### ورودی‌ها

```html
<div class="vetra-field vetra-field--invalid">
  <label class="vetra-label" for="amount">مبلغ <span class="vetra-required">*</span></label>
  <input class="vetra-input is-invalid" id="amount" inputmode="numeric" aria-describedby="amount-error">
  <span class="vetra-error" id="amount-error">مبلغ را به عدد وارد کنید.</span>
</div>

<label class="vetra-switch">
  <input type="checkbox"><span class="vetra-switch__track"></span> فعال
</label>
```

### Badge و Alert

```php
echo vetra_ds_badge( 'تأییدشده', 'success' );
echo vetra_ds_alert( 'عملیات انجام شد.', 'success', 'موفق' );
```

### جدول داده (قابل مرتب‌سازی و چاپ)

```html
<div class="vetra-table-wrap">
  <table class="vetra-table">
    <thead><tr><th data-vetra-sort="text">پروژه</th><th data-vetra-sort="number">مبلغ</th></tr></thead>
    <tbody><tr><td>برج وترا</td><td class="vetra-table__num">۱۲٬۵۰۰٬۰۰۰</td></tr></tbody>
  </table>
</div>
```

### Modal

```html
<button data-vetra-modal-open="demo-modal">باز کردن</button>
<div class="vetra-modal" id="demo-modal" role="dialog" aria-modal="true" aria-labelledby="demo-title" hidden>
  <div class="vetra-modal__overlay" data-vetra-modal-close></div>
  <div class="vetra-modal__dialog">
    <div class="vetra-modal__header"><h2 class="vetra-modal__title" id="demo-title">عنوان</h2></div>
    <div class="vetra-modal__body">متن</div>
  </div>
</div>
```

### Toast

```html
<button data-vetra-toast="ذخیره شد." data-vetra-toast-type="success">پیام</button>
```

```js
window.VetraUI.toast('ذخیره شد.', { type: 'success' });
window.VetraUI.setTheme('dark');
```

### Tab، Pagination، Breadcrumb، Tooltip، Empty state، Loading و Skeleton

همه در `templates/design-system.php` به‌صورت زنده نمونه‌گذاری شده‌اند. برای فعال‌سازی پیش‌نمایش: یک برگه بسازید و قالب **«وترا: پیش‌نمایش دیزاین‌سیستم»** را انتخاب کنید.

---

## 5. RTL و فارسی

- همه کامپوننت‌ها با propertyهای منطقی (`padding-inline`, `margin-block`, `inset-inline`) نوشته شده‌اند؛ نیازی به استایل جداگانه RTL نیست.
- `.vetra-switch`، آیکن‌های جهت‌دار (`.vetra-icon--directional`) و تب‌ها جهت‌آگاه هستند.
- اعداد و واحدها با `.vetra-num` / `.vetra-table__num` به‌صورت `tabular-nums` و `direction: ltr` درون متن RTL خوانا می‌مانند.
- متن‌های UI فارسی‌اند و از متن انگلیسی غیرضروری پرهیز شده است.

---

## 6. دسترس‌پذیری

- `:focus-visible` با `outline` از `--vetra-color-focus` (`#C67D34`) + `--vetra-focus-ring`.
- وضعیت‌ها فقط با رنگ منتقل نمی‌شوند: badge نقطه + متن، alert نوار + عنوان، form با آیکن + متن خطا.
- دکمه‌های آیکن‌محور باید `aria-label` داشته باشند؛ tooltip جایگزین برچسب نیست.
- modal: `role="dialog"`، `aria-modal`، focus trap، ESC، بازگشت focus.
- tab: `role="tablist/tab/tabpanel`، `aria-selected`، roving `tabindex`، کلیدهای جهت‌دار.
- toast: `role="status"` (و `alert` برای بحرانی) + live region.
- ناوبری کامل با کیبورد در همه کنترل‌های تعاملی.

### هشدار کنتراست (نیازمند توجه)

`--vetra-color-text-muted: #8A95A5` روی پس‌زمینه روشن حدود ۲٫۹:۱ کنتراست دارد و برای متن بدنه AA نیست. طبق الزام برند حفظ شده است؛ برای متن طولانی/جدول از `--vetra-color-text-soft` (`#5A6472`، AA) استفاده کنید. متن ثانویه فقط برای برچسب‌های غیرحیاتی.

---

## 7. سازگاری وردپرس

- تمام کلاس‌ها با `vetra-` namespace شده‌اند و هیچ استایل عمومی وردپرس/افزونه‌ای به‌صورت سراسری override نمی‌شود؛ مصرف کامپوننت‌ها opt-in است.
- هیچ وابستگی به Elementor، Gravity Forms یا bbPress وجود ندارد؛ در نبود افزونه خطایی رخ نمی‌دهد. استایل‌های ادغام آن‌ها جدا باقی می‌مانند.
- خروجی PHP با `esc_html`/`esc_attr`/`esc_url` چاپ و ورودی‌ها با `sanitize_html_class`/`wp_parse_args` پاک‌سازی می‌شوند؛ nameهای رویدادی (`on*`) در `vetra_ds_button` مسدود می‌شوند.
- Design System عملیات تغییردهنده سمت سرور ندارد، بنابراین nonce/capability اضافه نشده است. هر endpoint آینده باید مستقل `check_admin_referer`/`wp_verify_nonce` و `current_user_can` داشته باشد.

---

## 8. تست‌ها

- قرارداد خودکار: `npm test` → `tests/design-system.test.js`
- پیش‌نمایش دستی Light/Dark: صفحه پیش‌نمایش + دکمه‌های «حالت روشن/تاریک».
- RTL: قالب `html{direction:rtl}` است؛ چیدمان و سوییچ را بررسی کنید.
- responsive: نقاط شکست 600px در toolbar/card footer و گریدهای auto-fit.
- accessibility: Tab، Shift+Tab، ESC، جهت‌ها در tab، focus visible، `prefers-reduced-motion`.

---

## 9. نسخه‌بندی

| نسخه | تاریخ | تغییرات |
| --- | --- | --- |
| 1.0.0 | 2026-09 | انتشار اولیه: توکن‌ها، ۲۶ کامپوننت، utilities، print، JS، helperهای PHP، قالب پیش‌نمایش |

**Breaking changes:** این اولین انتشار است و breaking ندارد. توکن `--vetra-radius` قالب (۱۶px) دست‌نخورده باقی مانده تا ظاهر فعلی نشکند؛ مهاجرت به `--vetra-radius-md` (۸px) یک تغییر آگاهانه در نسخه‌های بعدی است.
