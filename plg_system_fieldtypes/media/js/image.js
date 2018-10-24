/*
 * @package    System - Field Types Plugin
 * @version    1.0.0
 * @author     Nerudas  - nerudas.ru
 * @copyright  Copyright (c) 2013 - 2018 Nerudas. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link       https://nerudas.ru
 */


(function ($) {
	$(document).ready(function () {
		$('[data-input-image]').each(function () {
			let field = $(this),
				id = field.attr('id'),
				form = field.find('.form'),
				input = form.find('input[type="file"]'),
				loading = form.find('.loading'),
				image = form.find('img');

			let params = Joomla.getOptions(id, ''),
				folder_field = $('#' + params.folder_field),
				folder = $(folder_field).val(),
				ajax_url = params.ajax_url.replace(/&amp;/g, '&');

			if (params.folder_field === '' || folder_field.length === 0 || folder === '') {
				$(field).remove();
			}
			else {
				setInterval(function () {
					$(form).height(Math.round(($(field).width() / 4) * 3));
				}, 10);
				getImage();
			}

			// Upload
			input.on('change', function (e) {
				if (!form.hasClass('disable')) {
					uploadImage(e.target.files);
				}
			});

			field
				.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
					e.preventDefault();
					e.stopPropagation();
				})
				.on('dragover dragenter', function () {
					if (!form.hasClass('disable')) {
						form.addClass('dragend');
					}
				})
				.on('dragleave dragend drop', function () {
					if (!form.hasClass('disable')) {
						form.removeClass('dragend');
					}
				})
				.on('drop', function (e) {
					if (!form.hasClass('disable')) {
						uploadImage(e.originalEvent.dataTransfer.files);
					}
				});

			// Upload image function
			function uploadImage(files) {
				let ajaxData = new FormData();
				ajaxData.append('task', 'images.uploadImage');
				ajaxData.append('folder', folder);
				ajaxData.append('filename', params.filename);
				ajaxData.append('noimage', params.noimage);
				ajaxData.append('files[]', files[0]);

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: ajax_url,
					processData: false,
					contentType: false,
					cache: false,
					global: false,
					async: false,
					data: ajaxData,
					beforeSend: function () {
						$(loading).show();
					},
					complete: function () {
						$(loading).hide();
					},
					success: function (response) {
						if (response.success) {
							$(image).attr('src', params.site_root + response.data);
						}
						else {
							console.error(response.message);
						}
					},
					error: function (response) {
						console.error(response.status + ': ' + response.statusText);
					}
				});
			}

			// Get image function
			function getImage() {
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: ajax_url,
					cache: false,
					global: false,
					async: false,
					data: {
						task: 'images.getImage',
						folder: folder,
						filename: params.filename,
						noimage: params.noimage
					},
					beforeSend: function () {
						$(loading).show();
					},
					complete: function () {
						$(loading).hide();
					},
					success: function (response) {
						if (response.success) {
							$(image).attr('src', params.site_root + response.data);
						}
						else {
							console.error(response.message);
						}
					},
					error: function (response) {
						console.error(response.status + ': ' + response.statusText);
					}
				});
			}

			// Refresh image function
			$(form).find('a.refresh').on('click', function () {
				getImage();
			});

			// Remove image function
			$(form).find('a.remove').on('click', function () {
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: ajax_url,
					cache: false,
					global: false,
					async: false,
					data: {
						task: 'images.deleteImage',
						folder: folder,
						filename: params.filename,
						noimage: params.noimage
					},
					beforeSend: function () {
						$(loading).show();
					},
					complete: function () {
						$(loading).hide();
					},
					success: function (response) {
						if (response.success) {
							$(image).attr('src', params.site_root + response.data);
						}
						else {
							console.error(response.message);
						}
					},
					error: function (response) {
						console.error(response.status + ': ' + response.statusText);
					}
				});
			});
		});
	});
})(jQuery);