// In Page Ajax Form Submission
$(document).on('submit' , '.in_page_ajax_form' , function (e){
    e.preventDefault();
    blockUI();

    var url = $(this).attr('data-href');
    $.ajax({
        url: url,
        type: 'POST',
        data:  new FormData(this),
        contentType: false,
        processData:false,
        success: function (response){
            $('.in_page_ajax_form')[0].reset();
            $('#dataTable').DataTable().ajax.reload();
			$('.modal').modal('hide');
			
			unblockUI();
			showToast('success', response?.message || 'Data saved successfully');
		}, error: function (xhr) {
			unblockUI();
			var response = xhr.responseJSON;
			if (xhr.status === 422) {
				$('.responseError').remove();
				$.each(response?.errors || [], function (i, error) {
					var el = $(document).find('[name="' + i + '"]');
					el.after($('<span class="responseError" style="color: red;">' + error[0] + '</span>'));
				});

				return;
			}

			showToast('error', response?.message || 'Something went wrong');
		}
    })
});

// Ajax Delete
$(document).on('click' , '.deleteRecord' , function (e){
    e.preventDefault();
    var url = $(this).attr('data-href');
    $.ajax({
        url: url,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response){
            showToast('success', response?.message || 'Data deleted successfully');
            $('.dataTable').DataTable().ajax.reload();
        },
        error: function (xhr) {
			var response = xhr.responseJSON;
            showToast('error', response?.message || 'Something went wrong');
        }
    })
});

// Remove error messages when modals are opened
$(document).on('show.bs.modal', '.modal', function () {
	$('.responseError').remove();
});