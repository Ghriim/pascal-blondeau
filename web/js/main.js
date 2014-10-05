jQuery(document).ready(function() {
	jQuery("body").tooltip({ selector: '[data-toggle=tooltip]', width: 'auto' });

	toggleAdminMenu();

	submitSaveForm();
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



function submitSaveForm()
{
    var saveFormContainer = jQuery('#save-form');
    var form = saveFormContainer.find('form');

    form.on('submit', function(e) {
        e.preventDefault();

	    var $this = jQuery(this);
	    $.ajax({
		    url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
		    type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
		    data: new FormData($this), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
		    processData: false,
		    success: function(html) {
			    saveFormContainer.find('#save-form-container').html(html);
		    }, error: function() {

            }
	    });
    });
}