jQuery(document).ready(
    function ()
    {
        initExpandAlbum();
    }
);

function initExpandAlbum()
{
    var context = jQuery('#albums');

    context.find('.deploy').click(
        function (event)
        {
            event.preventDefault();

            var $this = jQuery(this);
            var albumId = $this.data('album-id');
            var detailsDom = context.find('#entity-' + albumId + '-photos');

            if (detailsDom.length == 0){
                jQuery.ajax({
                    url: $this.data('action'),
                    success: function (albumDetails) {
                        var parent = $this.closest('tr');
                        parent.after(albumDetails);

                        initAddPhoto(detailsDom);
                    }
                });
            } else {
                detailsDom.remove();
            }
        }
    );
}

function initAddPhoto(detailsDom)
{
    var addPhotoForm = detailsDom.find('form');
    alert(addPhotoForm.attr('action'));

    addPhotoForm.on('submit', function(event) {
        event.preventDefault();

        alert('prevented');
    });
}