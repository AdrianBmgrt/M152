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
				<form action="#" method="post" enctype="multipart/form-data">
				<!-- /top nav -->
				<div class="padding">
					<div class="full col-sm-9">
						<!-- content -->
						<div class="row">
							<!-- main col right -->
							<div class="col-sm-12">
								<div class="form-group">
									<label for="commentaire">Message :</label>
									<textarea class="form-control"  name="commentaire" id="commentaire" rows="5"></textarea>
								</div>
								<div class="form-group">
									<label for="imageFile">Image :</label>
									<input type="file" class="form-control-file" id="imageFile" name="imageFile[]" accept="image/png, image/gif, image/jpeg, video/mp4, video/x-m4v, video/*, audio/mp3, audio/* " multiple/>
								</div>
								<button type="submit" name="action" value="submit" class="btn btn-primary">Submit</button>
							</div>
							<?= $message ?>
						</div>
					</div>
				</div><!-- /padding -->
				</form>
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