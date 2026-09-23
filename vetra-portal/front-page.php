<?php
get_header();
?>
<section class="vetra-dashboard-hero">
	<div>
		<span class="vetra-eyebrow"><span class="vetra-live-indicator"></span> نمای کلی پرتال</span>
		<h1>سلام، علی محمدی</h1>
		<p>وضعیت پروژه‌ها و عملیات امروز را در یک نگاه بررسی کنید.</p>
	</div>
	<div class="vetra-project-switcher">
		<span class="vetra-project-switcher__icon"><?php echo vetra_inline_icon( 'building' ); ?></span>
		<span><small>پروژه فعال</small><strong>برج سامان</strong><em>تهران، منطقه ۲</em></span>
		<span class="vetra-chevron">⌄</span>
	</div>
</section>

<section class="vetra-stat-grid" aria-label="خلاصه وضعیت">
	<article class="vetra-stat-card">
		<span class="vetra-stat-card__icon is-gold"><?php echo vetra_inline_icon( 'calendar' ); ?></span>
		<div><small>تعداد روزهای باقی‌مانده</small><strong><?php echo esc_html( vetra_get_dashboard_stat( 'remaining_days', '۱۲۴' ) ); ?> <i>روز</i></strong><em>تا پایان برنامه پروژه</em></div>
	</article>
	<article class="vetra-stat-card">
		<span class="vetra-stat-card__icon is-blue"><?php echo vetra_inline_icon( 'building' ); ?></span>
		<div><small>پیشرفت فیزیکی</small><strong><?php echo esc_html( vetra_get_dashboard_stat( 'physical_progress', '۵۶' ) ); ?><i>%</i></strong><div class="vetra-mini-progress"><span style="width:56%"></span></div></div>
	</article>
	<article class="vetra-stat-card">
		<span class="vetra-stat-card__icon is-green"><?php echo vetra_inline_icon( 'wallet' ); ?></span>
		<div><small>مصرف منابع مالی</small><strong><?php echo esc_html( vetra_get_dashboard_stat( 'budget_used', '۴۲' ) ); ?><i>%</i></strong><em>از بودجه مصوب پروژه</em></div>
	</article>
	<article class="vetra-stat-card">
		<span class="vetra-stat-card__icon is-rose"><?php echo vetra_inline_icon( 'file' ); ?></span>
		<div><small>گزارش‌های بررسی‌نشده</small><strong><?php echo esc_html( vetra_get_dashboard_stat( 'pending_reports', '۷' ) ); ?><i> مورد</i></strong><em class="is-warning">نیازمند اقدام امروز</em></div>
	</article>
</section>

<section class="vetra-dashboard-grid">
	<article class="vetra-panel vetra-project-panel">
		<div class="vetra-panel__head"><div><span class="vetra-eyebrow">پروژه منتخب</span><h2>پروژه برج سامان</h2></div><a href="#projects" class="vetra-text-link">مشاهده جزئیات <span>←</span></a></div>
		<div class="vetra-building-art" aria-label="تصویر شماتیک پروژه برج سامان">
			<div class="vetra-building-art__sun"></div><div class="vetra-building-art__tower"><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="vetra-building-art__base"></div>
		</div>
		<div class="vetra-project-panel__meta"><span><small>مدیر پروژه</small><strong>علی محمدی</strong></span><span><small>تاریخ شروع</small><strong>۱۴۰۳/۰۲/۱۵</strong></span><span><small>وضعیت</small><strong class="is-success">در حال اجرا</strong></span></div>
	</article>

	<article class="vetra-panel vetra-progress-panel">
		<div class="vetra-panel__head"><div><span class="vetra-eyebrow">شاخص‌های اجرایی</span><h2>پیشرفت بخش‌های فیزیکی</h2></div><button class="vetra-more-button" type="button" aria-label="گزینه‌های بیشتر">•••</button></div>
		<div class="vetra-donut-row"><div class="vetra-donut"><span>۵۶<small>%</small></span></div><div class="vetra-legend"><span><i class="is-gold"></i>اسکلت و سازه <b>۷۸٪</b></span><span><i class="is-sand"></i>نازک‌کاری <b>۶۴٪</b></span><span><i class="is-blue"></i>تأسیسات برقی <b>۵۴٪</b></span><span><i class="is-dim"></i>تأسیسات مکانیکی <b>۵۲٪</b></span><span><i class="is-muted"></i>نماکاری <b>۴۱٪</b></span></div></div>
	</article>

	<article class="vetra-panel vetra-chart-panel">
		<div class="vetra-panel__head"><div><span class="vetra-eyebrow">تحلیل زمانی</span><h2>نمودار پیشرفت پروژه</h2></div><div class="vetra-chart-legend"><span><i class="is-gold"></i>برنامه</span><span><i></i>واقعی</span></div></div>
		<div class="vetra-chart"><div class="vetra-chart__lines"><i></i><i></i><i></i><i></i></div><div class="vetra-chart__bars"><b style="height:25%"></b><b style="height:36%"></b><b style="height:48%"></b><b style="height:64%"></b><b style="height:76%"></b><b style="height:88%"></b></div><div class="vetra-chart__labels"><span>فروردین</span><span>اردیبهشت</span><span>خرداد</span><span>تیر</span><span>مرداد</span><span>شهریور</span></div></div>
	</article>

	<article class="vetra-panel vetra-activity-panel">
		<div class="vetra-panel__head"><div><span class="vetra-eyebrow">آخرین فعالیت‌ها</span><h2>گزارش‌های تأیید</h2></div><a class="vetra-text-link" href="#reports">مشاهده همه</a></div>
		<ul class="vetra-activity-list"><li><span class="vetra-activity-icon is-green"><?php echo vetra_inline_icon( 'check' ); ?></span><div><strong>صورت وضعیت پیمانکار</strong><small>تأیید نهایی توسط مدیر مالی</small></div><em>امروز</em></li><li><span class="vetra-activity-icon is-gold"><?php echo vetra_inline_icon( 'file' ); ?></span><div><strong>درخواست خرید مصالح</strong><small>در انتظار بررسی مدیر پروژه</small></div><em>۲ ساعت پیش</em></li><li><span class="vetra-activity-icon is-blue"><?php echo vetra_inline_icon( 'chart' ); ?></span><div><strong>گزارش پیشرفت فیزیکی</strong><small>ثبت توسط سرپرست کارگاه</small></div><em>دیروز</em></li></ul>
	</article>
</section>

<section class="vetra-quick-actions">
	<div class="vetra-section-heading"><div><span class="vetra-eyebrow">دسترسی سریع</span><h2>فرآیندهای پرکاربرد</h2></div><a href="<?php echo esc_url( get_post_type_archive_link( 'vetra_form' ) ); ?>" class="vetra-outline-button">مشاهده همه فرم‌ها <span>←</span></a></div>
	<div class="vetra-quick-grid"><a href="<?php echo esc_url( get_post_type_archive_link( 'vetra_form' ) ); ?>#reports" class="vetra-quick-card"><span><?php echo vetra_inline_icon( 'calendar' ); ?></span><strong>گزارش روزانه کارگاه</strong><small>ثبت گزارش عملیات روزانه</small><b>←</b></a><a href="<?php echo esc_url( get_post_type_archive_link( 'vetra_form' ) ); ?>#control" class="vetra-quick-card"><span><?php echo vetra_inline_icon( 'check' ); ?></span><strong>چک‌لیست کنترل کیفی</strong><small>ثبت کنترل‌های QC پروژه</small><b>←</b></a><a href="<?php echo esc_url( get_post_type_archive_link( 'vetra_form' ) ); ?>#requests" class="vetra-quick-card"><span><?php echo vetra_inline_icon( 'file' ); ?></span><strong>درخواست خرید مصالح</strong><small>ارسال درخواست به واحد تدارکات</small><b>←</b></a></div>
</section>
<?php
get_footer();
