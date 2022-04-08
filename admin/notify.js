$(document).ready(function(){
  //FEtch notification


  setInterval(function(){
    checkNotifacations();

  }, 1000);
  setInterval(function(){
    checkNotifacationsc();
    fetchNotifaction();

  }, 1000);
  fetchNotifaction();

  function fetchNotifaction(){
    $.ajax({
      url: 'script/inits.php',
      method: 'post',
      data: {action: 'fetchNotifaction'},
      success:function(response){
        $('#showNotification').html(response);
      }
    });
  }


  //CHECK NOTIFACATION
  checkNotifacations();

  setInterval(function(){
      checkNotifacations();
  }, 1000);
    function checkNotifacations(){
      $.ajax({
        url: 'script/inits.php',
        method: 'post',
        data: {action: 'getNotify'},
        success:function(response){
          // console.log(response);
          $('#getNotifys').html(response);
        }
      });
    }

  //CHECK NOTIFACATION
  checkNotifacationsc();

  function checkNotifacationsc(){
    $.ajax({
      url: 'script/inits.php',
      method: 'post',
      data: {action: 'checkNotifaction'},
      success:function(response){
        $('#getNot').html(response);
      }
    });
  }





  // remove notifiaction
  $('body').on('click', '.close', function(e){
    e.preventDefault();

    notifacation_id = $(this).attr('id');
    $.ajax({
      url: 'script/inits.php',
      method: 'post',
      data: {notifacation_id: notifacation_id},
      success:function(response){
        // checkNotifacations();
          fetchNotifaction();

      }
    });
  })



});
