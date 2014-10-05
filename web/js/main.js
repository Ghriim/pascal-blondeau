jQuery(document).ready(function() {
	jQuery("body").tooltip({ selector: '[data-toggle=tooltip]', width: 'auto' });

	toggleAdminMenu();

    buildSaveForm();
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
    var saveFormDOM = jQuery('#save-form');
    var form = saveFormDOM.find('form');

    form.on('submit', function(e) {
        e.preventDefault();

	    var $this = jQuery(this);
	    jQuery.ajax({
		    url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
		    type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
		    data: new FormData($this), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
		    processData: false,
		    success: function(html) {
                saveFormDOM.find('#save-form-container').html(html);
		    }
	    });
    });
}

function buildSaveForm()
{
    var openSaveFormLink = jQuery('.create-entity, .edit-entity');
    var saveFormDOM = jQuery('#save-form');

    openSaveFormLink.on('click', function(e) {
        e.preventDefault();

        var $this = jQuery(this);
        jQuery.ajax({
            url: $this.data('action'),
            type: 'GET',
            success: function(html) {
                saveFormDOM.find('.modal-title').html($this.data('title'));
                saveFormDOM.find('#save-form-container').html(html);

                saveFormDOM.modal('show');
            }
        });
    })
}