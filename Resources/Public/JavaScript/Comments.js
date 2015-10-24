jQuery(document).ready(function($) {

	/**
	 * Add current timestamp for bot check
	 */
	setTimeStamp();

	/**
	 * Handle form submits and post them via ajax request
	 */
	var begin = new Date().getTime();
	$('form.commentForm').on('submit', function(event) {
		event.preventDefault();
		var $this = $(this);
		$.ajax({
			type: 'POST',
			url: $this.attr('action'),
			data: $this.serialize(),
			dataType: 'json',
			success: function(response, status, xhr) {
				if (response.status === 'success') {
					showMessage($this, response.message, 'info');
					addCommentToList($this, response.comment);
				} else {
					showMessage($this, response.message, 'error', response.errors);
				}
				setTimeStamp();
			},
			error: function(xhr, status, error) {
				showMessage($this, 'Error "' + error + '" with status "' + status + '"', 'error');
				setTimeStamp();
			}
		});
	});

	/**
	 * Returns the timestamp
	 *
	 * @return integer The timestamp
	 */
	function setTimeStamp() {
		var date = new Date();
		timestamp = parseInt(date.getTime());
		$('form.commentForm').find('input.timeStamp').attr('value', timestamp);
	}

	/**
	 * Appends the new comment
	 *
	 * @param object form The form
	 * @param object comment The comment
	 * @return void
	 */
	function addCommentToList(form, comment) {
		var $commentList = $(form).parent().parent().parent().find('.commentList');
		var $newComment = $(form).parent().parent().parent().find('.commentPrototype').first().clone();
		$newComment.find('strong').text(comment.name);
		$newComment.find('.shortString').text(comment.content);
		$newComment.find('.comment img').attr('src', 'http://www.gravatar.com/avatar/' + comment.hashedEmail);
		$commentList.append($newComment);
		$newComment.fadeIn(800);
		$(form).find("input[name='tx_ecomments_comment[comment][name]']").val('');
		$(form).find("input[name='tx_ecomments_comment[comment][email]']").val('');
		$(form).find("textarea").val('');
	}

	/**
	 * Shows the status message
	 *
	 * @param object form The form
	 * @param string message The message
	 * @param string type Type of the message
	 * @return void
	 */
	function showMessage(form, message, type, errors) {
		$(form).find('.nameError').hide();
		$(form).find('.contentError').hide();
		$(form).find('.emailError').hide();
		for (var error in errors) {
			if (error == 'nameError') {
				$(form).find('.' + error).show();
			}
			if (error == 'contentError') {
				$(form).find('.' + error).show();
			}
			if (error == 'emailError') {
				$(form).find('.' + error).show();
			}
		}
		var $errorContainer = $(form).parent().find('.commentFormError');
		var $successContainer = $(form).parent().find('.commentFormSuccess');
		if (message === null) {
			$errorContainer.hide();
			$successContainer.hide();
			return;
		}
		if (type === 'info') {
			$errorContainer.hide();
			$successContainer.text(message).show();
		} else {
			$successContainer.hide();
			$errorContainer.text(message).show();
		}
	}

	/**
	 * Expand comments in frontend
	 */
	$('.arrow, .btn').click(function() {
		var $button = $(this);
		var $shortString = $(this).parent().find('.shortString');
		var $fullString = $(this).parent().find('.fullString');
		var shortStringText = $shortString.text();
		var fullStringText = $fullString.text();
		var height = $shortString.height();
		$shortString.fadeOut(100, function() {
			$shortString.text(fullStringText);
			$fullString.text(shortStringText);
			$shortString.fadeIn(600);
			if ($button.text() == 'More') {
				$button.text('Less');
			} else {
				$button.text('More');
			}

		});
	})

});