# گزارش تست VETRA Portal Theme v3.9.0

## محیط تست
- **تاریخ:** 2026-10-02
- **محیط محلی:** Ubuntu 24.04 sandbox، Node.js 22، PHP 8.3
- **staging reachability:** `https://test.vetragroup.ir` پاسخ HTTP 200 می‌دهد؛ تست authenticated نیازمند دسترسی WordPress است.

## تست‌های خودکار

| تست | نتیجه |
|---|---|
| `npm test` | موفق؛ ۵۱ تست |
| PHP syntax check | موفق؛ تمام فایل‌های PHP |
| `node --check` روی assets/js | موفق |
| JSON validation | موفق؛ `theme.json`، `package.json`، `package-lock.json` و icon catalog |
| `git diff --check` | موفق |
| فایل‌های ضروری قالب | موفق؛ `style.css`، `functions.php`، `screenshot.png`، `index.php` |

## CI

- GitHub Actions run روی commit نهایی اجرا شد.
- jobهای PHP 8.2 و 8.3 موفق شدند؛ اجرای PHP 8.1 در آخرین snapshot در حال تکمیل بود.
- لینک: https://github.com/rostampourahmad1-ui/vetra-portal-theme/actions/workflows/ci.yml

## تست‌های نیازمند دسترسی WordPress

این موارد به‌دلیل نبود credential در sandbox علامت‌گذاری نشده‌اند و باید روی staging بررسی شوند:

- نصب و فعال‌سازی از ZIP release
- نمایش notification و دکمه update در WordPress Admin
- تست update واقعی با release package
- بررسی تب‌ها، کارت‌ها، آیکن‌ها و stateهای پنل مدیریت در مرورگر
- تست Elementor و Gravity Forms با افزونه‌های فعال
- تست responsive و RTL در Chrome/Firefox/Safari

## Release artifacts

- Tag: `v3.9.0`
- Release: https://github.com/rostampourahmad1-ui/vetra-portal-theme/releases/tag/v3.9.0
- Package: `vetra-portal-theme-3.9.0.zip`
