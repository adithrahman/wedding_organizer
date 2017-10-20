$(document).ready(function() {
  var table = $('#tbl-pckg').DataTable( {
    select: true,
    dom: 'Bfrtip',
    buttons: [
      {
        text: 'New',
        action: function ( e, dt, node, config ) {
          var data = table.rows( { selected: true } ).data();
              alert( data[0] );
        }
      },
      {
        text: 'Edit',
        action: function ( e, dt, node, config ) {
          var data = table.rows( { selected: true } ).data();
            $('#myModal').modal({show: true});
            $('#myrecipient-name').text(data[0]);
        }
      },
      {
        text: 'Delete',
        action: function ( e, dt, node, config ) {
            alert( 'Delete click' );
        }
      },
      {
        text: 'Export license',
        action: function ( e, dt, node, config ) {
            alert( 'Export click' );
        }
      }
    ]
  } );
  $('#myModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-title').text('New message to ' + recipient)
    modal.find('.modal-body input').val(recipient)
  });
} );
