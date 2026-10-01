# گزارش پیاده‌سازی VETRA Portal 3.9.0

## دامنه تغییرات
- integration surface به Elementor و Gravity Forms محدود شد و شاخه‌های وابستهٔ بدون استفاده حذف شدند.
- نام پوسته، پوشه، text domain و theme slug روی `vetra-portal-theme` ثابت شدند؛ نسخهٔ release برابر 3.9.0 است.
- updater استاندارد مبتنی بر GitHub Releases در `inc/core/class-updater.php` اضافه شد و update transient وردپرس و اعلان پیشخوان را پشتیبانی می‌کند.
- `assets/css/vetra-tokens.css` با neutral ramp ده‌مرحله‌ای از `#12161A` تا `#F8FAFC` و حالت‌های accent بر پایهٔ `#C67D34` تکمیل شد.
- داشبورد مدیریت به ساختار tab-based و card-based با آیکن‌های VETRA، stateهای hover/focus/active/disabled و ریتم ۸ پیکسلی ارتقا یافت.
- CI در `.github/workflows/ci.yml` برای PHP 8.1/8.2/8.3، lint، تست Node، JSON و ساختار پوسته اضافه شد.
- مستندات Design System، README و گزارش انتشار به‌روزرسانی شدند.

## بررسی‌های اجراشده
| بررسی | نتیجه |
|---|---|
| `npm test` | موفق |
| `php -l` روی تمام PHPها | در مرحلهٔ اعتبارسنجی انتشار اجرا شد |
| `node --check` روی JavaScriptها | در مرحلهٔ اعتبارسنجی انتشار اجرا شد |
| اعتبارسنجی JSON | در CI و محلی |
| `git diff --check` | در CI |
