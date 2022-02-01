<body>
	<div class="wrapper">
		<div class="row row-offcanvas row-offcanvas-left">
			<!-- main right col -->
			<div class="column col-sm-12 col-xs-11" id="main">
				<!-- top nav -->
				<div class="navbar navbar-blue navbar-static-top">
					<div class="navbar-header">
						<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a href="http://usebootstrap.com/theme/facebook" class="navbar-brand logo">b</a>
					</div>
					<?php
					require("nav.php");
					?>
				</div>
				<!-- /top nav -->
				<div class="padding">
					<div class="full col-sm-9">
						<!-- content -->
						<div class="row">
							<!-- main col right -->
							<div class="col-sm-7">


							</div>

						</div>
						<!--/row-->

						<div class="row">
							<div class="col-sm-6">
								<a href="#">Twitter</a> <small class="text-muted">|</small> <a href="#">Facebook</a>
								<small class="text-muted">|</small> <a href="#">Google+</a>
							</div>
						</div>

						<div class="row" id="footer">
							<div class="col-sm-6">

							</div>
							<div class="col-sm-6">
								<p>
									<a href="#" class="pull-right">�Copyright 2013</a>
								</p>
							</div>
						</div>

					</div><!-- /col-9 -->
				</div><!-- /padding -->
			</div>
			<!-- /main -->

		</div>
	</div>
	</div>

	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-toggle=offcanvas]').click(function() {
				$(this).toggleClass('visible-xs text-center');
				$(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
				$('.row-offcanvas').toggleClass('active');
				$('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
				$('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
				$('#btnShow').toggle();
			});
		});
	</script>
</body>