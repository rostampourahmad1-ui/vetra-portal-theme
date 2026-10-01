# پنل تنظیمات و migration فاز ۶

**وضعیت:** پیاده‌سازی فاز ۶  
**هدف PHP:** 8.2+  
**Option اصلی:** `vetra_plus_settings`

## ساختار

```text
vetra-plus/modules/settings/
├── class-settings-schema.php
├── class-settings.php
└── class-migration.php
```

## دسته‌های پنل

- هویت بصری و رنگ‌ها
- تایپوگرافی
- هدر و ناوبری
- فوتر
- تنظیمات پروژه‌ها
- CSS سفارشی

پنل از مسیر **Vetra Plus** در مدیریت WordPress قابل دسترسی است و از Settings API استفاده می‌کند. تمام keyهای جدید با `vetra_` شروع می‌شوند.

## امنیت

- capability: `manage_options`
- nonce مستقل برای export، import و reset
- sanitize callback مرکزی برای تمام fieldها
- نوع‌های محدود برای boolean، integer، color، URL، email، slug و CSS
- محدودیت حجم import برابر ۵ مگابایت
- CSS سفارشی فقط به‌صورت متن ذخیره می‌شود؛ PHP، serialized data و اجرای کد پشتیبانی نمی‌شود.

## فرمت پشتیبان JSON

```json
{
  "plugin": "vetra-plus",
  "schema_version": 1,
  "exported_at": "2026-10-02T00:00:00Z",
  "settings": {
    "vetra_brand_title": "وترا",
    "vetra_primary_color": "#C67D34"
  }
}
```

export، import، reset و نگهداری option در `class-settings.php` قرار دارند.

## migration فایل Ecopark

`class-migration.php` فقط JSON معتبر را parse می‌کند. کلیدها به‌شکل deterministic نرمال می‌شوند:

1. sanitize key
2. حذف prefixهای فنی `theme_mods_`، `option_` و `options_`
3. تبدیل `vetra_plus_foo` به `vetra_foo`
4. افزودن `vetra_` برای کلید قدیمی بدون prefix
5. حذف کلیدهایی که در schema فعلی وجود ندارند
6. sanitize مقدار طبق نوع فیلد

فایل `backup-options-01-10-2026.txt` در workspace و repository موجود نیست. بنابراین هیچ mapping اختصاصی برای کلیدهای ناشناخته یا مقدار واقعی backup حدس زده نشده است. وقتی فایل واقعی فراهم شود، ابتدا با همین importer اعتبارسنجی می‌شود و فقط کلیدهای پشتیبانی‌شده به option منتقل می‌شوند.

برای جلوگیری از اجرای کد، فایل‌های PHP، `eval` و `unserialize` عمداً پشتیبانی نمی‌شوند.

## سازگاری با پوسته

این option مستقل از `vetra_operational_settings` پوسته نگهداری می‌شود و hookهای زیر برای adapter آینده در دسترس هستند:

- `vetra_plus_settings_saved`
- `vetra_plus_settings_schema`

در این فاز هیچ option قدیمی پوسته حذف یا overwrite نشده است.
