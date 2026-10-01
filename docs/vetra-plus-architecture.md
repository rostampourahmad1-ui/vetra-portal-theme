# معماری هستهٔ افزونه Vetra Plus

**نسخه:** 1.0.0  
**هدف PHP:** 8.2+  
**وضعیت:** فاز ۴، فاز ۵ و فاز ۶؛ بدون انتقال یا حذف کد پوسته

## ساختار

```text
vetra-plus/
├── vetra-plus.php
└── includes/
    ├── class-loader.php
    ├── class-plugin.php
    ├── class-capabilities.php
    ├── class-post-types.php
    ├── class-meta-boxes.php
    └── class-api.php
```

افزونه مستقل است و برای فعال‌شدن به پوسته یا Vetra Plus دیگری وابسته نیست. نبود پوسته فقط adapterهای اختیاری را بی‌اثر می‌کند؛ هیچ `require` از مسیر پوسته وجود ندارد.

## CPT و taxonomy

| نوع | slug | وضعیت |
|---|---|---|
| پروژه | `vetra_project` | public، archive، REST، thumbnail، editor، revisions |
| دسته پروژه | `vetra_project_category` | hierarchical |
| مهارت/تخصص | `vetra_project_skill` | non-hierarchical |
| ویژگی پروژه | `vetra_project_feature` | non-hierarchical |

## متادیتا

کلیدهای متا با `_vetra_project_` شروع می‌شوند:

- `client`
- `location`
- `area`
- `year`
- `status`
- `gallery`

تمام ذخیره‌سازی متا با nonce، بررسی `edit_post`، حذف autosave/revision، sanitize و allow-list وضعیت انجام می‌شود. متا از طریق REST فقط برای کاربر دارای مجوز ویرایش قابل تغییر است.

## capabilityها

افزونه نقش‌های زیر را به‌صورت مستقل نگه می‌دارد:

- `vetra_project_editor`: ایجاد و ویرایش پروژه‌های مجاز
- `vetra_project_manager`: مدیریت کامل پروژه و taxonomy
- مدیر سایت: همهٔ capabilityهای افزونه

نام‌های capability به‌صورت `vetra_` prefix شده‌اند و از capabilityهای پوستهٔ قدیمی حذف یا rename نشده‌اند.

## API و adapterها

- `GET /wp-json/vetra/v1/projects` فهرست پروژه‌های publish‌شده را با pagination برمی‌گرداند.
- `vetra_plus_project_data`: فیلتر نهایی دادهٔ سریال‌شدهٔ پروژه.
- `vetra_plus_ready`: محل extension آیندهٔ lifecycle افزونه.
- `vetra_plus_theme_adapter_ready`: پس از مشاهدهٔ `vetra_theme_components_registered` از پوسته fire می‌شود.
- قراردادهای قبلی پوسته در [`vetra-api-contracts.md`](vetra-api-contracts.md) حذف یا override نشده‌اند.

REST خروجی JSON برمی‌گرداند؛ داده‌های HTML فقط از API رسمی WordPress و `the_content` عبور می‌کنند. ویجت‌های Elementor و shortcodeهای fallback اکنون در **فاز ۵** در مسیر `modules/elementor/` قرار دارند.

جزئیات ماژول Elementor، ویجت‌ها و راهبرد بارگذاری شرطی assetها در [`vetra-plus-elementor.md`](vetra-plus-elementor.md) ثبت شده است.

پنل تنظیمات، export/reset و importer امن JSON فاز ۶ در [`vetra-plus-settings.md`](vetra-plus-settings.md) ثبت شده است.

## backup-options

فایل `backup-options-01-10-2026.txt` در repository و workspace فعلی پیدا نشد. importer فاز ۶ فقط JSON معتبر و کلیدهای موجود در schema را می‌پذیرد؛ بنابراین هیچ option یا مقدار واقعی از backup حدس زده نشده است.

## محدودیت فاز

در این فاز هیچ کد WooCommerce، WPBakery، Codevz، کد رمزگذاری‌شده یا asset تجاری اضافه نشده است. انتقال جدول legacy پوسته نیز انجام نشده؛ CPT جدید فقط لایهٔ مستقل Vetra Plus را فراهم می‌کند و migration داده نیازمند backup و تصمیم محصول جداگانه است.
