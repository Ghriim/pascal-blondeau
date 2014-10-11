jQuery(document).ready(function() {
	jQuery("body").tooltip({ selector: '[data-toggle=tooltip]', width: 'auto' });

	toggleAdminMenu();

    initSortableTable();

    buildSaveForm();
	submitSaveForm();
});

function initSortableTable()
{
    var sortableTable = jQuery(".table-sortable tbody");
    sortableTable.sortable({
        tolerance: "pointer",
        cursor: "move",
        items: "tr:not(.sortable-disabled)",
        update: function() {
            updateEntityPosition(sortableTable)
        }
    });
}

function updateEntityPosition(sortableTable)
{
    var sortableTableLines = sortableTable.find('tr:not(.sortable-disabled)');
    var idWithPositionList = [];

    jQuery.each(sortableTableLines, function(index) {
        var currentLine = jQuery(this);
        idWithPositionList.push({id: currentLine.data('id'), position: index + 1});
    });

    var data = {
        idWithPositionList: idWithPositionList
    };

    jQuery.ajax({
        url: sortableTable.data('action'),
        method: 'POST',
        data: data,
        success: function(json) {
            if(json.status == 'error') {
                return;
            }

            idWithPositionList.forEach(function(idWithPosition) {
                var positionArea = '#slide-' + idWithPosition.id + ' td.position';
                sortableTable.find(positionArea).html(idWithPosition.position);
            });

            var newFlashMessage = '<div class="alert alert-dismissable alert-success">'
                + json.message
                + '</div>';

            var flashMessagesContainer = jQuery('#flash-messages');
            flashMessagesContainer.append(newFlashMessage);
        }
    });
}

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
		    url: $this.attr('action'),
		    type: $this.attr('method'),
		    data: new FormData($this),
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
