$(document).ready(function(){
  $('#search').click(function(event){
    event.preventDefault();
    $.ajax({
      url: 'book_info.php',
      method: 'POST',
      data: $('#search_isbn').serialize(),
      success: function(data){
        $('#response').html(data);
      }
    })

  })
  $('#add_up_stat').html("");
  $('#response').on('click', '#update_db, #add_db', function(event){
    event.preventDefault();
    $.ajax({
      url: 'add_update.php',
      method: 'POST',
      data: $('#tab_gen').serialize(),
      success: function(data){
        $('#add_up_stat').html(data);
      }
    })
  })

  $('#response').on('click', '#add_db', function(event){
    event.preventDefault();
    $('#add_db').prop('disabled', true);
    $('input[name="check"]').val('1');
    $('#update_db').prop('disabled', false);
  })
})
