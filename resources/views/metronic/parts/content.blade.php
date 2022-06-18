
	<!-- begin:: Content -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		@include("$theme/parts/alerts")
		<div id="alerts-ajax" class="none-empty"></div>
		@yield('content_page')
	</div>
	<!-- end:: Content -->
</div>