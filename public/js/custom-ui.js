let savedTheme = localStorage.getItem('theme') || 'light';

function applyTheme(theme) {
	if (theme === 'dark') {
		$('body').addClass('dark-mode');
		$('#modeIcon').removeClass('bi-sun').addClass('bi-moon');
		$('#modeToggleBtn').attr('aria-label', 'Switch to Light Mode');
	} else {
		$('body').removeClass('dark-mode');
		$('#modeIcon').removeClass('bi-moon').addClass('bi-sun');
		$('#modeToggleBtn').attr('aria-label', 'Switch to Dark Mode');
	}
}

$(document).ready(function () {
	
	applyTheme(savedTheme);
	
	$('#modeToggleBtn').on('click', function () {
		const currentTheme = $('body').hasClass('dark-mode') ? 'dark' : 'light';
		const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
		localStorage.setItem('theme', newTheme);
		applyTheme(newTheme);
	});
	
	
	// Manage z-index for multiple modals
	$('.modal').on('show.bs.modal', function () {
		const zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function () {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});
	
	$('.modal').on('hidden.bs.modal', function () {
		if ($('.modal:visible').length) {
			// Adjust the backdrop z-index when closing a modal
			$('body').addClass('modal-open');
		}
	});
	
	
});


