<?php
/**
 * Template Name: وترا: پیش‌نمایش دیزاین‌سیستم
 * Template Post Type: page
 *
 * Opt-in showcase for the VETRA Design System. Assign this template to a page to
 * visually verify Light/Dark, RTL, responsive and accessibility states.
 * Every control below is a real, copy-pasteable example.
 *
 * @package VetraPortal
 */

get_header();
?>
<main id="primary" class="vetra-ds vetra-container vetra-mt-5 vetra-mb-5">
	<div class="vetra-toolbar vetra-mb-4">
		<div class="vetra-toolbar__group">
			<strong><?php esc_html_e( 'پیش‌نمایش دیزاین‌سیستم وترا', 'vetra-portal' ); ?></strong>
			<span class="vetra-badge vetra-badge--info"><?php echo esc_html( 'v' . VETRA_DS_VERSION ); ?></span>
		</div>
		<div class="vetra-toolbar__group">
			<?php
			echo vetra_ds_button(
				array(
					'label'   => __( 'حالت روشن', 'vetra-portal' ),
					'variant' => 'secondary',
					'size'    => 'sm',
					'attrs'   => array(
						'data-vetra-theme-toggle' => 'light',
						'aria-pressed'            => 'false',
					),
				)
			);
			echo vetra_ds_button(
				array(
					'label'   => __( 'حالت تاریک', 'vetra-portal' ),
					'variant' => 'secondary',
					'size'    => 'sm',
					'attrs'   => array(
						'data-vetra-theme-toggle' => 'dark',
						'aria-pressed'            => 'false',
					),
				)
			);
			?>
		</div>
	</div>

	<section class="vetra-stack vetra-mb-5" aria-labelledby="ds-buttons">
		<h2 id="ds-buttons"><?php esc_html_e( 'دکمه‌ها', 'vetra-portal' ); ?></h2>
		<div class="vetra-cluster">
			<?php
			echo vetra_ds_button( array( 'label' => __( 'دکمه اصلی', 'vetra-portal' ) ) );
			echo vetra_ds_button( array( 'label' => __( 'دکمه ثانویه', 'vetra-portal' ), 'variant' => 'secondary' ) );
			echo vetra_ds_button( array( 'label' => __( 'دکمه شبح', 'vetra-portal' ), 'variant' => 'ghost' ) );
			echo vetra_ds_button( array( 'label' => __( 'حذف', 'vetra-portal' ), 'variant' => 'danger' ) );
			echo vetra_ds_button( array( 'label' => __( 'غیرفعال', 'vetra-portal' ), 'disabled' => true ) );
			?>
		</div>
	</section>

	<section class="vetra-stack vetra-mb-5" aria-labelledby="ds-badges">
		<h2 id="ds-badges"><?php esc_html_e( 'نشان‌ها', 'vetra-portal' ); ?></h2>
		<div class="vetra-cluster">
			<?php
			foreach ( vetra_ds_status_values() as $status ) {
				echo vetra_ds_badge( ucfirst( $status ), $status );
			}
			?>
		</div>
	</section>

	<section class="vetra-stack vetra-mb-5" aria-labelledby="ds-alerts">
		<h2 id="ds-alerts"><?php esc_html_e( 'هشدارها', 'vetra-portal' ); ?></h2>
		<?php
		echo vetra_ds_alert( __( 'این یک پیام اطلاعاتی است.', 'vetra-portal' ), 'info', __( 'اطلاع', 'vetra-portal' ) );
		echo vetra_ds_alert( __( 'عملیات با موفقیت انجام شد.', 'vetra-portal' ), 'success', __( 'موفق', 'vetra-portal' ) );
		echo vetra_ds_alert( __( 'لطفاً پیش از ادامه بررسی کنید.', 'vetra-portal' ), 'warning', __( 'هشدار', 'vetra-portal' ) );
		echo vetra_ds_alert( __( 'خطایی رخ داده است.', 'vetra-portal' ), 'critical', __( 'بحرانی', 'vetra-portal' ) );
		?>
	</section>

	<section class="vetra-grid vetra-grid--2 vetra-mb-5">
		<div class="vetra-card">
			<div class="vetra-card__header"><h2 class="vetra-card__title"><?php esc_html_e( 'فرم نمونه', 'vetra-portal' ); ?></h2></div>
			<div class="vetra-card__body vetra-stack">
				<div class="vetra-field">
					<label class="vetra-label" for="ds-name"><?php esc_html_e( 'نام پروژه', 'vetra-portal' ); ?> <span class="vetra-required">*</span></label>
					<input class="vetra-input" id="ds-name" type="text" placeholder="<?php esc_attr_e( 'مثال: برج وترا', 'vetra-portal' ); ?>">
				</div>
				<div class="vetra-field vetra-field--invalid">
					<label class="vetra-label" for="ds-amount"><?php esc_html_e( 'مبلغ (ریال)', 'vetra-portal' ); ?></label>
					<input class="vetra-input is-invalid" id="ds-amount" type="text" inputmode="numeric" aria-describedby="ds-amount-error">
					<span class="vetra-error" id="ds-amount-error"><?php esc_html_e( 'مبلغ را به عدد وارد کنید.', 'vetra-portal' ); ?></span>
				</div>
				<div class="vetra-field vetra-field--valid">
					<label class="vetra-label" for="ds-ok"><?php esc_html_e( 'شماره قرارداد', 'vetra-portal' ); ?></label>
					<input class="vetra-input is-valid" id="ds-ok" type="text" value="1402/045">
					<span class="vetra-success-text"><?php esc_html_e( 'معتبر است.', 'vetra-portal' ); ?></span>
				</div>
				<div class="vetra-field">
					<label class="vetra-label" for="ds-select"><?php esc_html_e( 'نوع قرارداد', 'vetra-portal' ); ?></label>
					<select class="vetra-select" id="ds-select">
						<option><?php esc_html_e( 'پیمان‌کاری', 'vetra-portal' ); ?></option>
						<option><?php esc_html_e( 'مشارکتی', 'vetra-portal' ); ?></option>
					</select>
				</div>
				<div class="vetra-field">
					<label class="vetra-label" for="ds-textarea"><?php esc_html_e( 'توضیحات', 'vetra-portal' ); ?></label>
					<textarea class="vetra-textarea" id="ds-textarea"></textarea>
				</div>
				<label class="vetra-check"><input type="checkbox" checked> <?php esc_html_e( 'تأیید می‌کنم', 'vetra-portal' ); ?></label>
				<label class="vetra-switch"><input type="checkbox"> <span class="vetra-switch__track"></span> <?php esc_html_e( 'نمایش در داشبورد', 'vetra-portal' ); ?></label>
				<div class="vetra-radio-group">
					<label class="vetra-check"><input type="radio" name="ds-radio" checked> <?php esc_html_e( 'گزینه اول', 'vetra-portal' ); ?></label>
					<label class="vetra-check"><input type="radio" name="ds-radio"> <?php esc_html_e( 'گزینه دوم', 'vetra-portal' ); ?></label>
				</div>
			</div>
		</div>

		<div class="vetra-stack">
			<div class="vetra-card">
				<div class="vetra-card__header"><h2 class="vetra-card__title"><?php esc_html_e( 'کارت اطلاعات', 'vetra-portal' ); ?></h2></div>
				<div class="vetra-card__body">
					<p class="vetra-text-soft vetra-mb-2"><?php esc_html_e( 'شدت پیشرفت پروژه بر اساس قرارداد.', 'vetra-portal' ); ?></p>
					<span class="vetra-badge vetra-badge--success"><?php esc_html_e( 'در حال اجرا', 'vetra-portal' ); ?></span>
				</div>
				<div class="vetra-card__footer">
					<?php echo vetra_ds_button( array( 'label' => __( 'مشاهده', 'vetra-portal' ), 'variant' => 'ghost', 'size' => 'sm' ) ); ?>
				</div>
			</div>

			<div class="vetra-card">
				<div class="vetra-card__body vetra-stack">
					<h3><?php esc_html_e( 'حالت بارگذاری', 'vetra-portal' ); ?></h3>
					<div class="vetra-cluster">
						<span class="vetra-spinner" role="status" aria-label="<?php esc_attr_e( 'در حال بارگذاری', 'vetra-portal' ); ?>"></span>
						<span class="vetra-text-sm vetra-text-soft"><?php esc_html_e( 'در حال بارگذاری…', 'vetra-portal' ); ?></span>
					</div>
					<div aria-hidden="true">
						<div class="vetra-skeleton vetra-skeleton--title"></div>
						<div class="vetra-skeleton vetra-skeleton--text"></div>
						<div class="vetra-skeleton vetra-skeleton--text" style="width:80%"></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="vetra-mb-5" aria-labelledby="ds-table">
		<h2 id="ds-table"><?php esc_html_e( 'جدول داده', 'vetra-portal' ); ?></h2>
		<div class="vetra-toolbar vetra-mb-3">
			<div class="vetra-toolbar__group">
				<input class="vetra-input" type="search" placeholder="<?php esc_attr_e( 'جست‌وجو…', 'vetra-portal' ); ?>" aria-label="<?php esc_attr_e( 'جست‌وجو در جدول', 'vetra-portal' ); ?>">
			</div>
			<div class="vetra-toolbar__group">
				<?php echo vetra_ds_button( array( 'label' => __( 'نمایش پیام', 'vetra-portal' ), 'variant' => 'secondary', 'size' => 'sm', 'attrs' => array( 'data-vetra-toast' => 'این یک پیام نمونه است.', 'data-vetra-toast-type' => 'success' ) ) ); ?>
				<?php echo vetra_ds_button( array( 'label' => __( 'باز کردن مودال', 'vetra-portal' ), 'size' => 'sm', 'attrs' => array( 'data-vetra-modal-open' => 'ds-modal' ) ) ); ?>
			</div>
		</div>
		<div class="vetra-table-wrap">
			<table class="vetra-table">
				<thead>
					<tr>
						<th scope="col" data-vetra-sort="text"><?php esc_html_e( 'پروژه', 'vetra-portal' ); ?></th>
						<th scope="col"><?php esc_html_e( 'کارفرما', 'vetra-portal' ); ?></th>
						<th scope="col" data-vetra-sort="number"><?php esc_html_e( 'مبلغ (ریال)', 'vetra-portal' ); ?></th>
						<th scope="col"><?php esc_html_e( 'وضعیت', 'vetra-portal' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>برج وترا</td>
						<td>گروه عمران</td>
						<td class="vetra-table__num">۱۲٬۵۰۰٬۰۰۰٬۰۰۰</td>
						<td><?php echo vetra_ds_badge( __( 'تأییدشده', 'vetra-portal' ), 'success' ); ?></td>
					</tr>
					<tr>
						<td>مجتمع اداری</td>
						<td>شرکت ساختمانی</td>
						<td class="vetra-table__num">۸٬۲۰۰٬۰۰۰٬۰۰۰</td>
						<td><?php echo vetra_ds_badge( __( 'در انتظار', 'vetra-portal' ), 'warning' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</section>

	<section class="vetra-stack vetra-mb-5" aria-labelledby="ds-tabs">
		<h2 id="ds-tabs"><?php esc_html_e( 'زبانه‌ها', 'vetra-portal' ); ?></h2>
		<div class="vetra-tabs">
			<div class="vetra-tabs__list" role="tablist">
				<button class="vetra-tabs__tab" role="tab" id="ds-tab-1" aria-controls="ds-panel-1" aria-selected="true"><?php esc_html_e( 'خلاصه', 'vetra-portal' ); ?></button>
				<button class="vetra-tabs__tab" role="tab" id="ds-tab-2" aria-controls="ds-panel-2" aria-selected="false" tabindex="-1"><?php esc_html_e( 'اسناد', 'vetra-portal' ); ?></button>
			</div>
			<div class="vetra-tabs__panel" id="ds-panel-1" role="tabpanel" aria-labelledby="ds-tab-1"><?php esc_html_e( 'محتوای خلاصه.', 'vetra-portal' ); ?></div>
			<div class="vetra-tabs__panel" id="ds-panel-2" role="tabpanel" aria-labelledby="ds-tab-2" hidden><?php esc_html_e( 'محتوای اسناد.', 'vetra-portal' ); ?></div>
		</div>
	</section>

	<section class="vetra-stack vetra-mb-5" aria-labelledby="ds-misc">
		<h2 id="ds-misc"><?php esc_html_e( 'مسیر، صفحه‌بندی و راهنما', 'vetra-portal' ); ?></h2>
		<nav class="vetra-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر صفحه', 'vetra-portal' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'vetra-portal' ); ?></a>
			<span class="vetra-breadcrumb__sep" aria-hidden="true">/</span>
			<span class="vetra-breadcrumb__current"><?php esc_html_e( 'پیش‌نمایش', 'vetra-portal' ); ?></span>
		</nav>
		<nav class="vetra-pagination" aria-label="<?php esc_attr_e( 'صفحه‌بندی', 'vetra-portal' ); ?>">
			<a class="vetra-pagination__item" href="#" aria-disabled="true"><?php esc_html_e( 'قبلی', 'vetra-portal' ); ?></a>
			<a class="vetra-pagination__item" href="#" aria-current="page">۱</a>
			<a class="vetra-pagination__item" href="#">۲</a>
			<a class="vetra-pagination__item" href="#"><?php esc_html_e( 'بعدی', 'vetra-portal' ); ?></a>
		</nav>
		<span class="vetra-tooltip">
			<button type="button" class="vetra-btn vetra-btn--secondary vetra-btn--sm" data-vetra-tooltip aria-describedby="ds-tip">?</button>
			<span class="vetra-tooltip__bubble" id="ds-tip" role="tooltip"><?php esc_html_e( 'راهنمای کوتاه', 'vetra-portal' ); ?></span>
		</span>
	</section>

	<section class="vetra-mb-5" aria-labelledby="ds-empty">
		<h2 id="ds-empty"><?php esc_html_e( 'حالت خالی', 'vetra-portal' ); ?></h2>
		<div class="vetra-empty">
			<svg class="vetra-empty__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 7h16v12H4zM4 7l3-3h10l3 3"/></svg>
			<p class="vetra-empty__title"><?php esc_html_e( 'موردی یافت نشد', 'vetra-portal' ); ?></p>
			<p class="vetra-empty__text"><?php esc_html_e( 'هنوز پروژه‌ای ثبت نشده است. برای شروع یک پروژه جدید بسازید.', 'vetra-portal' ); ?></p>
			<?php echo vetra_ds_button( array( 'label' => __( 'ایجاد پروژه', 'vetra-portal' ) ) ); ?>
		</div>
	</section>

	<section class="vetra-mb-5" aria-labelledby="ds-icons">
		<h2 id="ds-icons"><?php esc_html_e( 'بسته آیکون وترا', 'vetra-portal' ); ?></h2>
		<p class="vetra-text-soft"><?php esc_html_e( 'آیکون‌ها با stroke خطی ۱.۵، زوایای مهندسی و رنگ base/accent کنترل‌شده رندر می‌شوند.', 'vetra-portal' ); ?></p>
		<div class="vetra-cluster" style="gap:var(--vetra-space-4)">
			<?php foreach ( array_slice( vetra_icon_names(), 0, 24 ) as $icon_name ) : ?>
				<span class="vetra-tooltip" style="display:inline-flex;flex-direction:column;align-items:center;gap:var(--vetra-space-1)">
					<?php echo vetra_icon( $icon_name, array( 'size' => 24, 'title' => $icon_name, 'decorative' => true ) ); ?>
					<small class="vetra-text-xs vetra-text-soft"><?php echo esc_html( $icon_name ); ?></small>
				</span>
			<?php endforeach; ?>
		</div>
		<p class="vetra-mt-3">
			<?php esc_html_e( 'نمونه دکمه آیکن‌محور:', 'vetra-portal' ); ?>
			<button type="button" class="vetra-icon-button" aria-label="<?php esc_attr_e( 'جست‌وجو', 'vetra-portal' ); ?>"><?php echo vetra_icon( 'search', array( 'size' => 20, 'decorative' => true ) ); ?></button>
			<button type="button" class="vetra-btn vetra-btn--secondary"><?php echo vetra_icon( 'export', array( 'size' => 18, 'decorative' => true ) ); ?> <?php esc_html_e( 'خروجی', 'vetra-portal' ); ?></button>
		</p>
		<pre class="vetra-text-xs" dir="ltr">[vetra_icon name="check" size="20" label="تأیید شده"]</pre>
	</section>

	<div class="vetra-modal" id="ds-modal" role="dialog" aria-modal="true" aria-labelledby="ds-modal-title" hidden aria-hidden="true">
		<div class="vetra-modal__overlay" data-vetra-modal-close></div>
		<div class="vetra-modal__dialog">
			<div class="vetra-modal__header">
				<h2 class="vetra-modal__title" id="ds-modal-title"><?php esc_html_e( 'تأیید عملیات', 'vetra-portal' ); ?></h2>
				<button type="button" class="vetra-toast__close" data-vetra-modal-close aria-label="<?php esc_attr_e( 'بستن', 'vetra-portal' ); ?>">&times;</button>
			</div>
			<div class="vetra-modal__body"><?php esc_html_e( 'آیا از انجام این عملیات مطمئن هستید؟', 'vetra-portal' ); ?></div>
			<div class="vetra-modal__footer">
				<?php echo vetra_ds_button( array( 'label' => __( 'انصراف', 'vetra-portal' ), 'variant' => 'ghost', 'attrs' => array( 'data-vetra-modal-close' => '' ) ) ); ?>
				<?php echo vetra_ds_button( array( 'label' => __( 'تأیید', 'vetra-portal' ), 'variant' => 'primary', 'attrs' => array( 'data-vetra-modal-close' => '' ) ) ); ?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
