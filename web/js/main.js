jQuery(document).ready(function() {
	jQuery("body").tooltip({ selector: '[data-toggle=tooltip]', width: 'auto' });

	toggleAdminMenu();
});

function toggleAdminMenu()
{
	var adminNav = jQuery('#admin-nav');

	jQuery('#deploy-admin-menu').click(function() {
		adminNav.find('label').css('display', 'table-cell');
		jQuery(this).addClass('hidden');
		jQuery("#reduce-admin-menu").removeClass('hidden');
	});

	jQuery('#reduce-admin-menu').click(function() {
		adminNav.find('label').css('display', 'none');
		jQuery(this).addClass('hidden');
		jQuery("#deploy-admin-menu").removeClass('hidden');
	});
}

