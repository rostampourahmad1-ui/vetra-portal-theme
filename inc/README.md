# ساختار ماژولار

- `core/`: bootstrap، schema تنظیمات و سرویس‌های عمومی.
- `admin/`: داشبورد و عملیات مدیریتی؛ هر عملیات با capability و nonce محافظت می‌شود.
- `integrations.php`: adapterهای اختیاری افزونه‌ها؛ قالب بدون افزونه نیز اجرا می‌شود.
- `customizer.php`: رابط پیش‌نمایش تنظیمات با defaults هماهنگ با schema.
- `layouts.php`: resolver الگوهای header/footer.
- `projects.php`: داده و عملیات پروژه‌ها.
- `site-features.php`: قابلیت‌های عملیاتی مستقل سایت.
