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
					<div class="full col-sm-12">
						<!-- content -->
						<div class="row">
							<!-- main col left -->
							<div class="col-sm-3">
								<div class="panel panel-default">
									<div class="panel-thumbnail"><img src="assets/img/bg_5.jpg" class="img-responsive">
									</div>
									<div class="panel-body">
										<p class="lead">Blog d'Adrian</p>
										<p>420 Followers, 69 Posts</p>
										<p>
											<img src="assets/img/uFp_tsTJboUY7kue5XAsGAs28.png" height="28px" width="28px">
										</p>
									</div>
								</div>
							</div>

							<!-- main col right -->
							<div class="col-sm-9">

								<div class="well">
									<form class="form">
										<div class="input-group text-center">
											<h2>Welcome</h2>
										</div>
									</form>
								</div>
								<?php echo PostAndMediaToCarousel() ?>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /padding -->
		</div>
		<!-- /main -->
	</div>
	</div>
	</div>
	<!--post modal-->
	<form action="deletePostAndMedia.php" method="POST">
		<div id="postModal1" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						Delete post ?
					</div>
					<div class="modal-body">
						<form class="form center-block">
							<div class="form-group">
								<textarea class="form-control input-lg" autofocus="" readonly>Are you sure that you want to delete this post ?</textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<div>
						<input id="prodId" name="prodId" type="hidden" value="xm234jq">
							<button class="btn btn-primary btn-sm" data-dismiss="modal" aria-hidden="true">Yes</button>
							<button class="btn btn-primary btn-sm" data-dismiss="modal" aria-hidden="true">No</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
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