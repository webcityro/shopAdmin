<?php

class comments {
	private static $db;

	function __construct() {
		self::$db = database::init();
	}

	public static function rander($commentPlacel, $placeID) {
		/*
		<div class="commentsFrame">
				<h3><strong><?php echo language::translate('NrOfComments', '2'); ?>:</h3>

				<div class="comment">
					<img src="<?php echo config::get('site/domain'); ?>app/users/0/avatars/default_m.jpg">
					<div class="arrowLeft"></div>
					<div class="commentBody">
						<div class="commentHeader">
							<p class="commentUser"><a href="#">Andy</a> <strong><?php echo language::translate('seying'); ?>:</strong></p>
							<p class="commentDate">5.03.2014 22:30</p>
						</div>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vestibulum ac orci vitae tincidunt.
					 Maecenas venenatis convallis nisl, id fringilla felis ornare vitae. Maecenas bibendum porta tellus,
					  eget auctor velit. Etiam rhoncus diam et lacus ornare, id tincidunt lectus lobortis. Morbi lobortis
					   sapien sed pulvinar vulputate.
						Suspendisse porta enim vitae ante ultricies adipiscing. Duis pharetra egestas leo vitae vehicula.
						<div class="commentControls">
							<a href="#"><?php echo language::translate('reply'); ?></a> |
							<a href="#"><?php echo language::translate('delete'); ?></a>
						</div>
					</div>

					<div class="commentReply">
						<img src="<?php echo config::get('site/domain'); ?>app/users/0/avatars/default_m.jpg">
						<div class="arrowLeft"></div>
						<div class="commentReplyBody">
							<div class="commentReplyHeader">
								<p class="commentReplyUser"><a href="#">Andy</a> <strong><?php echo language::translate('seying'); ?>:</strong></p>
								<p class="commentReplyDate">5.03.2014 22:30</p>
							</div>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vestibulum ac orci vitae tincidunt.
						 Maecenas venenatis convallis nisl, id fringilla felis ornare vitae. Maecenas bibendum porta tellus,
						  eget auctor velit. Etiam rhoncus diam et lacus ornare, id tincidunt lectus lobortis. Morbi lobortis
						   sapien sed pulvinar vulputate.
							Suspendisse porta enim vitae ante ultricies adipiscing. Duis pharetra egestas leo vitae vehicula.
							<div class="commentReplyControls">
								<a href="#"><?php echo language::translate('reply'); ?></a> |
								<a href="#"><?php echo language::translate('delete'); ?></a>
							</div>
						</div>
					</div>
				</div>

			</div>
		*/
	}
}